<?php

namespace App\Livewire\Patient;

use Livewire\Component;
use App\Livewire\WithLayout;
use App\Models\Service;
use App\Models\Antrian;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Ticket extends Component
{
    use WithLayout;

    public $patient_type = 'umum';
    public $bpjs_number = '';
    public $service_id = '';
    public $services = [];
    public $tickets = [];
    public $patient = null;
    public $patientData = null;
    public $isLoading = false;
    public $showPatientInfo = false;

    protected $listeners = ['ticketCreated' => 'loadTickets'];

    public function mount()
    {
        $this->layout = 'components.layouts.patient';
        $this->title = 'Ambil Tiket';
        $this->services = Service::active()->get();
        $this->loadTickets();
        
        // Load patient data if authenticated
        if (auth('patient')->check()) {
            $this->patient = auth('patient')->user();
            if ($this->patient->bpjs_number) {
                $this->patient_type = 'bpjs';
                $this->bpjs_number = $this->patient->bpjs_number;
            }
        }
    }

    public function loadTickets()
    {
        if (auth('patient')->check()) {
            $this->tickets = Antrian::where('patient_id', auth('patient')->id())
                ->with('service')
                ->latest()
                ->take(5)
                ->get();
        }
    }

    public function checkBpjs()
    {
        $this->validate([
            'bpjs_number' => ['required', 'string', 'size:13', 'regex:/^[0-9]+$/'],
        ], [
            'bpjs_number.required' => 'Nomor BPJS wajib diisi',
            'bpjs_number.size' => 'Nomor BPJS harus 13 digit',
            'bpjs_number.regex' => 'Nomor BPJS hanya boleh berisi angka',
        ]);

        $this->isLoading = true;
        $this->patientData = null;
        $this->showPatientInfo = false;

        try {
            // Simulate API call delay
            sleep(1);
            
            // Check if BPJS number exists in the system
            $patient = Patient::where('bpjs_number', $this->bpjs_number)->first();
            
            if ($patient) {
                $this->patient = $patient;
                $this->patientData = [
                    'name' => $patient->name,
                    'bpjs_number' => $patient->bpjs_number,
                    'nik' => $patient->nik,
                    'date_of_birth' => $patient->date_of_birth->format('d/m/Y'),
                    'gender' => $patient->gender == 'L' ? 'Laki-laki' : 'Perempuan',
                    'phone' => $patient->phone,
                    'address' => $patient->address,
                ];
                $this->showPatientInfo = true;
                $this->dispatch('notify', type: 'success', message: 'Data pasien ditemukan');
            } else {
                $this->addError('bpjs_number', 'Nomor BPJS tidak ditemukan');
                $this->dispatch('notify', type: 'error', message: 'Data pasien tidak ditemukan');
            }
        } catch (\Exception $e) {
            $this->addError('bpjs_number', 'Terjadi kesalahan saat memeriksa BPJS');
            $this->dispatch('notify', type: 'error', message: 'Terjadi kesalahan saat memeriksa BPJS');
        } finally {
            $this->isLoading = false;
        }
    }

    public function createTicket()
    {
        try {
            // Validate input
            $rules = [
                'service_id' => ['required', 'exists:services,id'],
            ];

            if ($this->patient_type === 'bpjs') {
                $rules['bpjs_number'] = ['required', 'string', 'size:13', 'regex:/^[0-9]+$/'];
            }

            $this->validate($rules, [
                'service_id.required' => 'Pilih layanan terlebih dahulu',
                'service_id.exists' => 'Layanan tidak valid',
                'bpjs_number.required' => 'Nomor BPJS wajib diisi',
                'bpjs_number.size' => 'Nomor BPJS harus 13 digit',
                'bpjs_number.regex' => 'Nomor BPJS hanya boleh berisi angka',
            ]);

            // Start database transaction
            return DB::transaction(function () {
                // If BPJS type but patient not found, create new patient
                if ($this->patient_type === 'bpjs' && !$this->patient) {
                    $this->patient = Patient::create([
                        'bpjs_number' => $this->bpjs_number,
                        'name' => 'Pasien BPJS ' . substr($this->bpjs_number, -4),
                        'password' => bcrypt($this->bpjs_number),
                        'nik' => $this->bpjs_number,
                        'date_of_birth' => now()->subYears(30),
                        'gender' => 'L',
                        'phone' => '000000000000', // Default phone
                        'address' => 'Alamat tidak diketahui', // Default address
                    ]);
                }

                // Get service details
                $service = Service::findOrFail($this->service_id);
                
                // Get the latest queue number for the selected service today
                $lastAntrian = Antrian::where('service_id', $this->service_id)
                    ->whereDate('created_at', today())
                    ->orderBy('queue_number', 'desc')
                    ->first();

                $queueNumber = $lastAntrian ? $lastAntrian->queue_number + 1 : 1;
                $formattedNumber = $service->prefix . '-' . str_pad($queueNumber, 3, '0', STR_PAD_LEFT);

                // Create new antrian
                $antrian = Antrian::create([
                    'service_id' => $this->service_id,
                    'patient_id' => $this->patient ? $this->patient->id : null,
                    'queue_number' => $queueNumber,
                    'formatted_number' => $formattedNumber,
                    'patient_type' => $this->patient_type,
                    'bpjs_number' => $this->patient_type === 'bpjs' ? $this->bpjs_number : null,
                    'status' => 'menunggu',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Load relationships
                $antrian->load('service');

                // Reset form
                $this->reset(['service_id', 'bpjs_number']);
                $this->showPatientInfo = false;
                $this->patientData = null;

                // Dispatch event to show success message
                $this->dispatchBrowserEvent('ticket-created', [
                    'success' => true,
                    'ticket_number' => $formattedNumber,
                    'service_name' => $service->name,
                    'message' => 'Tiket berhasil dibuat',
                    'redirect' => route('patient.tickets')
                ]);

                // Also return the response for Livewire
                return [
                    'success' => true,
                    'ticket_number' => $formattedNumber,
                    'service_name' => $service->name,
                    'message' => 'Tiket berhasil dibuat',
                    'redirect' => route('patient.tickets')
                ];
            });
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return [
                'success' => false,
                'message' => $e->validator->errors()->first(),
                'errors' => $e->errors()
            ];
        } catch (\Exception $e) {
            Log::error('Error creating ticket: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi.'
            ];
        }
    }

    public function render()
    {
        return view('livewire.patient.ticket', [
            'services' => $this->services,
        ]);
    }
}
