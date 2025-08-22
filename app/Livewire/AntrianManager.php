<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Antrian;
use App\Models\Service;
use App\Models\Counter;
use Illuminate\Support\Carbon;

class AntrianManager extends Component
{
    use WithPagination;

    public $selectedService = null;
    public $selectedCounter = null;
    public $queueNumber = '';
    public $formattedNumber = '';
    public $status = 'waiting';
    
    public $showModal = false;
    public $isEditMode = false;
    public $antrianId = null;

    public $filterService = '';
    public $filterStatus = '';
    public $filterDate = '';

    protected $rules = [
        'selectedService' => 'required|exists:services,id',
        'selectedCounter' => 'nullable|exists:counters,id',
        'status' => 'required|in:waiting,called,finished,skipped',
    ];

    protected $messages = [
        'selectedService.required' => 'Pilih layanan terlebih dahulu',
        'selectedService.exists' => 'Layanan tidak valid',
        'selectedCounter.exists' => 'Loket tidak valid',
        'status.required' => 'Status harus dipilih',
        'status.in' => 'Status tidak valid',
    ];

    public function mount()
    {
        $this->filterDate = now()->format('Y-m-d');
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->selectedService = null;
        $this->selectedCounter = null;
        $this->status = 'waiting';
        $this->antrianId = null;
        $this->isEditMode = false;
    }

    public function create()
    {
        $this->validate();

        $service = Service::findOrFail($this->selectedService);
        
        // Generate queue number
        $today = now()->format('Y-m-d');
        $lastAntrian = Antrian::where('service_id', $this->selectedService)
            ->whereDate('created_at', $today)
            ->orderBy('queue_number', 'desc')
            ->first();

        $nextNumber = $lastAntrian ? $lastAntrian->queue_number + 1 : 1;
        $this->queueNumber = $nextNumber;
        $this->formattedNumber = $service->code . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        Antrian::create([
            'service_id' => $this->selectedService,
            'counter_id' => $this->selectedCounter,
            'queue_number' => $this->queueNumber,
            'formatted_number' => $this->formattedNumber,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Antrian berhasil ditambahkan: ' . $this->formattedNumber);
        $this->closeModal();
    }

    public function edit($id)
    {
        $antrian = Antrian::findOrFail($id);
        $this->antrianId = $id;
        $this->selectedService = $antrian->service_id;
        $this->selectedCounter = $antrian->counter_id;
        $this->status = $antrian->status;
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        $antrian = Antrian::findOrFail($this->antrianId);
        
        $antrian->update([
            'service_id' => $this->selectedService,
            'counter_id' => $this->selectedCounter,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Antrian berhasil diperbarui');
        $this->closeModal();
    }

    public function delete($id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->delete();
        session()->flash('message', 'Antrian berhasil dihapus');
    }

    public function callNext($serviceId, $counterId)
    {
        $nextAntrian = Antrian::where('service_id', $serviceId)
            ->where('status', 'waiting')
            ->orderBy('queue_number')
            ->first();

        if ($nextAntrian) {
            $nextAntrian->update([
                'counter_id' => $counterId,
                'status' => 'called',
                'called_at' => now(),
            ]);
            
            session()->flash('message', 'Memanggil antrian: ' . $nextAntrian->formatted_number);
        } else {
            session()->flash('message', 'Tidak ada antrian yang menunggu');
        }
    }

    public function finish($id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->update([
            'status' => 'finished',
            'finished_at' => now(),
        ]);
        
        session()->flash('message', 'Antrian selesai: ' . $antrian->formatted_number);
    }

    public function skip($id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->update([
            'status' => 'skipped',
        ]);
        
        session()->flash('message', 'Antrian dilewati: ' . $antrian->formatted_number);
    }

    public function getWaitingCountProperty()
    {
        return Antrian::where('status', 'waiting')
            ->when($this->filterService, fn($q) => $q->where('service_id', $this->filterService))
            ->whereDate('created_at', $this->filterDate)
            ->count();
    }

    public function getCalledCountProperty()
    {
        return Antrian::where('status', 'called')
            ->when($this->filterService, fn($q) => $q->where('service_id', $this->filterService))
            ->whereDate('created_at', $this->filterDate)
            ->count();
    }

    public function getFinishedCountProperty()
    {
        return Antrian::where('status', 'finished')
            ->when($this->filterService, fn($q) => $q->where('service_id', $this->filterService))
            ->whereDate('created_at', $this->filterDate)
            ->count();
    }

    public function getServicesProperty()
    {
        return Service::where('is_active', true)->orderBy('name')->get();
    }

    public function getCountersProperty()
    {
        return Counter::orderBy('name')->get();
    }

    public function render()
    {
        $antrians = Antrian::query()
            ->with(['service', 'counter'])
            ->when($this->filterService, fn($q) => $q->where('service_id', $this->filterService))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->whereDate('created_at', $this->filterDate)
            ->orderBy('queue_number')
            ->paginate(10);

        return view('livewire.antrian-manager', [
            'antrians' => $antrians,
            'services' => $this->services,
            'counters' => $this->counters,
        ]);
    }
}
