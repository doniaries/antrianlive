<?php

namespace App\Livewire\Patient;

use Livewire\Component;
use App\Livewire\WithLayout;
use App\Models\Service;
use App\Models\Antrian;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        // Check if BPJS number exists in the system
        $patient = Patient::where('bpjs_number', $this->bpjs_number)->first();
        
        if ($patient) {
            $this->patient = $patient;
            session()->flash('message', 'Data BPJS ditemukan');
        } else {
            $this->addError('bpjs_number', 'Nomor BPJS tidak ditemukan');
        }
    }

    public function createTicket()
    {
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

        try {
            // If BPJS type but patient not found, create new patient
            if ($this->patient_type === 'bpjs' && !$this->patient) {
                $this->patient = Patient::create([
                    'bpjs_number' => $this->bpjs_number,
                    'name' => 'Pasien BPJS ' . substr($this->bpjs_number, -4),
                    'password' => bcrypt($this->bpjs_number),
                    'nik' => $this->bpjs_number, // Using BPJS as NIK for simplicity
                    'date_of_birth' => now()->subYears(30), // Default DOB
                    'gender' => 'L', // Default gender
                ]);
            }

            // Get the latest queue number for the selected service
            $lastAntrian = Antrian::where('service_id', $this->service_id)
                ->whereDate('created_at', today())
                ->orderBy('queue_number', 'desc')
                ->first();

            $queueNumber = $lastAntrian ? $lastAntrian->queue_number + 1 : 1;
            $formattedNumber = 'A-' . str_pad($queueNumber, 3, '0', STR_PAD_LEFT);

            // Create new antrian
            $antrian = Antrian::create([
                'service_id' => $this->service_id,
                'patient_id' => $this->patient ? $this->patient->id : null,
                'queue_number' => $queueNumber,
                'formatted_number' => $formattedNumber,
                'patient_type' => $this->patient_type,
                'bpjs_number' => $this->patient_type === 'bpjs' ? $this->bpjs_number : null,
                'status' => 'menunggu',
            ]);

            $this->dispatch('ticketCreated');
            session()->flash('success', 'Tiket berhasil dibuat. Nomor antrian Anda: ' . $formattedNumber);
            
            // Reset form
            $this->reset(['service_id']);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan. Silakan coba lagi.');
            Log::error('Error creating ticket: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.patient.ticket', [
            'services' => $this->services,
        ]);
    }
}
