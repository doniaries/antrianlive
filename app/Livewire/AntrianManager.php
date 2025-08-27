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

    public function getServicesProperty()
    {
        return Service::where('is_active', true)->orderBy('name')->get();
    }

    public function callNext($antrianId, $serviceId, $counterId = null)
    {
        // Update status antrian yang sedang diproses
        $antrian = Antrian::findOrFail($antrianId);
        $antrian->update([
            'status' => 'called',
            'counter_id' => $counterId,
            'called_at' => now()
        ]);

        // Kirim event ke frontend dengan data antrian
        $this->dispatch('antrian-called', [
            'number' => $antrian->formatted_number,
            'service' => $antrian->service->name,
            'counter' => $antrian->counter ? $antrian->counter->name : 'Umum'
        ]);

        session()->flash('message', 'Antrian ' . $antrian->formatted_number . ' dipanggil');
    }

    public function recall($antrianId)
    {
        $antrian = Antrian::with(['service', 'counter'])->findOrFail($antrianId);
        
        $this->dispatch('antrian-called', [
            'number' => $antrian->formatted_number,
            'service' => $antrian->service->name,
            'counter' => $antrian->counter ? $antrian->counter->name : 'Umum'
        ]);

        session()->flash('message', 'Antrian ' . $antrian->formatted_number . ' dipanggil ulang');
    }

    public function skip($antrianId)
    {
        $antrian = Antrian::findOrFail($antrianId);
        $antrian->update([
            'status' => 'skipped',
            'finished_at' => now()
        ]);

        session()->flash('message', 'Antrian ' . $antrian->formatted_number . ' dilewati');
    }

    public function finish($antrianId)
    {
        $antrian = Antrian::findOrFail($antrianId);
        $antrian->update([
            'status' => 'finished',
            'finished_at' => now()
        ]);

        session()->flash('message', 'Antrian ' . $antrian->formatted_number . ' selesai diproses');
    }

    public function getWaitingCountProperty()
    {
        return Antrian::where('status', 'waiting')
            ->when($this->filterDate, function($q) {
                $q->whereDate('created_at', Carbon::parse($this->filterDate));
            })
            ->count();
    }

    public function getCalledCountProperty()
    {
        return Antrian::where('status', 'called')
            ->when($this->filterDate, function($q) {
                $q->whereDate('created_at', Carbon::parse($this->filterDate));
            })
            ->count();
    }

    public function getFinishedCountProperty()
    {
        return Antrian::whereIn('status', ['finished', 'skipped'])
            ->when($this->filterDate, function($q) {
                $q->whereDate('created_at', Carbon::parse($this->filterDate));
            })
            ->count();
    }

    public function render()
    {
        $query = Antrian::with(['service', 'counter'])
            ->when($this->filterDate, function($q) {
                $q->whereDate('created_at', Carbon::parse($this->filterDate));
            })
            ->when($this->filterService, function($q) {
                $q->where('service_id', $this->filterService);
            })
            ->when($this->filterStatus, function($q) {
                if ($this->filterStatus === 'finished') {
                    $q->whereIn('status', ['finished', 'skipped']);
                } else {
                    $q->where('status', $this->filterStatus);
                }
            })
            ->latest();

        return view('livewire.antrian-manager', [
            'antrians' => $query->paginate(10),
            'services' => Service::where('is_active', true)->get(),
            'counters' => Counter::all(),
        ]);
    }

    #[\Livewire\Attributes\On('callNext')]
    public function handleCallNext($antrianId, $serviceId = null, $counterId = null)
    {
        try {
            $this->callNext($antrianId, $serviceId, $counterId);
        } catch (\Exception $e) {
            $this->dispatch('error', message: $e->getMessage());
        }
    }

    #[\Livewire\Attributes\On('recall')]
    public function handleRecall($antrianId)
    {
        try {
            $this->recall($antrianId);
        } catch (\Exception $e) {
            $this->dispatch('error', message: $e->getMessage());
        }
    }

    #[\Livewire\Attributes\On('skip')]
    public function handleSkip($antrianId)
    {
        try {
            $this->skip($antrianId);
        } catch (\Exception $e) {
            $this->dispatch('error', message: $e->getMessage());
        }
    }

    #[\Livewire\Attributes\On('finish')]
    public function handleFinish($antrianId)
    {
        try {
            $this->finish($antrianId);
        } catch (\Exception $e) {
            $this->dispatch('error', message: $e->getMessage());
        }
    }

    #[\Livewire\Attributes\On('delete')]
    public function handleDelete($antrianId)
    {
        try {
            $this->delete($antrianId);
        } catch (\Exception $e) {
            $this->dispatch('error', message: $e->getMessage());
        }
    }
}
