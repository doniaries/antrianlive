<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Service;
use App\Models\Antrian;
use App\Models\Counter;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AmbilTiket extends Component
{
    public $services = [];
    public $nomorAntrian = '';

    public function mount()
    {
        $this->services = Service::where('is_active', true)->get();
    }

    public function takeTicket($serviceId, $counterId = null)
    {
        try {
            // Ensure patient is authenticated
            if (!auth('patient')->check()) {
                return $this->dispatch('ticket-error', [
                    'message' => 'Anda harus login terlebih dahulu untuk mengambil tiket.'
                ]);
            }

            $patient = auth('patient')->user();
            $service = Service::findOrFail($serviceId);
            $today = Carbon::today();

            // Check if patient already has an active queue
            $hasActiveQueue = Antrian::where('patient_id', $patient->id)
                ->whereIn('status', ['waiting', 'processing'])
                ->whereDate('created_at', $today)
                ->exists();

            if ($hasActiveQueue) {
                return $this->dispatch('ticket-error', [
                    'message' => 'Anda sudah memiliki tiket antrian yang aktif. Silakan selesaikan antrian sebelumnya terlebih dahulu.'
                ]);
            }

            // Get the latest queue number for this service today
            $lastAntrian = Antrian::where('service_id', $serviceId)
                ->whereDate('created_at', $today)
                ->latest()
                ->first();

            $nextNumber = $lastAntrian ? 
                intval(preg_replace('/[^0-9]/', '', $lastAntrian->nomor_antrian)) + 1 : 1;
            
            $formattedNumber = $service->kode . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // Create the queue
            $antrian = Antrian::create([
                'nomor_antrian' => $formattedNumber,
                'service_id' => $serviceId,
                'counter_id' => $counterId,
                'patient_id' => $patient->id,
                'status' => 'waiting',
                'tanggal' => $today,
            ]);

            // Dispatch success event with ticket details
            $this->dispatch('ticket-created', [
                'ticket_number' => $formattedNumber,
                'service_name' => $service->name,
                'counter_name' => $counterId ? Counter::find($counterId)->name : null
            ]);

        } catch (\Exception $e) {
            Log::error('Error taking ticket: ' . $e->getMessage());
            $this->dispatch('ticket-error', [
                'message' => 'Terjadi kesalahan saat membuat tiket antrian. Silakan coba lagi.'
            ]);
        }
    }

    public function closeModal()
    {
        $this->nomorAntrian = '';
    }

    public function render()
    {
        return view('livewire.ambil-tiket');
    }
}