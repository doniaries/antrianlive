<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Antrian;
use App\Models\Service;
use App\Models\Counter;
use Carbon\Carbon;

class AntrianIndex extends Component
{
    public $services = [];
    public $selectedService = '';
    public $nomorAntrian = '';
    public $antrians = [];
    public $statusFilter = 'all';

    public function mount()
    {
        $this->services = Service::all();
        $this->loadAntrians();
    }

    public function loadAntrians()
    {
        $today = Carbon::today();
        
        $query = Antrian::with(['service', 'counter'])
                       ->whereDate('created_at', $today)
                       ->latest();

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $this->antrians = $query->get();
    }

    public function createAntrian()
    {
        $this->validate([
            'selectedService' => 'required|exists:services,id',
        ]);

        $service = Service::find($this->selectedService);
        
        // Generate nomor antrian
        $lastAntrian = Antrian::whereDate('created_at', Carbon::today())
                            ->where('service_id', $this->selectedService)
                            ->latest()
                            ->first();

        $nextNumber = $lastAntrian ? 
            intval(substr($lastAntrian->nomor_antrian, -3)) + 1 : 1;
        
        $nomorAntrian = $service->kode . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        Antrian::create([
            'nomor_antrian' => $nomorAntrian,
            'service_id' => $this->selectedService,
            'status' => 'pending',
            'tanggal' => Carbon::today(),
        ]);

        $this->nomorAntrian = $nomorAntrian;
        $this->loadAntrians();
        
        session()->flash('success', 'Antrian berhasil dibuat: ' . $nomorAntrian);
    }

    public function render()
    {
        return view('livewire.antrian-index');
    }
}