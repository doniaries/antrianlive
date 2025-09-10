<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Service;
use App\Models\Antrian;
use Carbon\Carbon;

class AmbilTiket extends Component
{
    public $services = [];
    public $selectedService = '';
    public $nomorAntrian = '';
    public $showModal = false;

    public function mount()
    {
        $this->services = Service::where('is_active', true)->get();
    }

    public function generateAntrian()
    {
        $this->validate([
            'selectedService' => 'required|exists:services,id',
        ]);

        $service = Service::find($this->selectedService);
        
        // Generate nomor antrian
        $today = Carbon::today();
        $lastAntrian = Antrian::whereDate('created_at', $today)
                            ->where('service_id', $this->selectedService)
                            ->latest()
                            ->first();

        $nextNumber = $lastAntrian ? 
            intval(substr($lastAntrian->nomor_antrian, -3)) + 1 : 1;
        
        $nomorAntrian = $service->kode . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $antrian = Antrian::create([
            'nomor_antrian' => $nomorAntrian,
            'service_id' => $this->selectedService,
            'status' => 'pending',
            'tanggal' => Carbon::today(),
        ]);

        $this->nomorAntrian = $nomorAntrian;
        $this->showModal = true;
        
        $this->dispatch('antrian-created');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->nomorAntrian = '';
        $this->selectedService = '';
    }

    public function render()
    {
        return view('livewire.ambil-tiket');
    }
}