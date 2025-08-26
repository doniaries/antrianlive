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

    public function finish($id)
    {
        try {
            $antrian = Antrian::findOrFail($id);
            $antrian->update([
                'status' => 'finished',
                'finished_at' => now()
            ]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Antrian ' . $antrian->formatted_number . ' telah selesai!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal menyelesaikan antrian: ' . $e->getMessage()
            ]);
        }
    }

    public function skip($id)
    {
        try {
            $antrian = Antrian::findOrFail($id);
            $antrian->update([
                'status' => 'skipped',
                'skipped_at' => now()
            ]);

            $this->dispatch('notify', [
                'type' => 'warning',
                'message' => 'Antrian ' . $antrian->formatted_number . ' dilewati!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal melewati antrian: ' . $e->getMessage()
            ]);
        }
    }


    public function callNext($antrianId, $serviceId, $counterId = null)
    {
        try {
            // Update status antrian yang sedang diproses
            $antrian = Antrian::with(['service', 'counter'])->findOrFail($antrianId);

            // Update antrian
            $antrian->update([
                'status' => 'called',
                'counter_id' => $counterId,
                'called_at' => now()
            ]);

            // Get counter name or use default
            $counter = $antrian->counter ? $antrian->counter->name : 'Umum';

            // Dispatch event for sound and notification
            $this->dispatch('antrian-called', [
                'number' => $antrian->formatted_number,
                'service' => $antrian->service->name,
                'counter' => $counter
            ]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Antrian ' . $antrian->formatted_number . ' berhasil dipanggil!'
            ]);

            // Dispatch the call-queue event
            $this->dispatch('call-queue', [
                'number' => $antrian->formatted_number,
                'service' => $antrian->service->name,
                'counter' => $counter,
                'counterId' => $counterId,
                'serviceCode' => $antrian->service->code
            ]);

            session()->flash('message', 'Antrian ' . $antrian->formatted_number . ' dipanggil di loket ' . $counter);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal memanggil antrian: ' . $e->getMessage()
            ]);
        }
    }



    public function getWaitingCountProperty()
    {
        return Antrian::where('status', 'waiting')
            ->when($this->filterDate, function ($q) {
                $q->whereDate('created_at', Carbon::parse($this->filterDate));
            })
            ->count();
    }

    public function getCalledCountProperty()
    {
        return Antrian::where('status', 'called')
            ->when($this->filterDate, function ($q) {
                $q->whereDate('created_at', Carbon::parse($this->filterDate));
            })
            ->count();
    }

    public function getFinishedCountProperty()
    {
        return Antrian::whereIn('status', ['finished', 'skipped'])
            ->when($this->filterDate, function ($q) {
                $q->whereDate('created_at', Carbon::parse($this->filterDate));
            })
            ->count();
    }

    public function render()
    {
        $query = Antrian::with(['service', 'counter'])
            ->when($this->filterDate, function ($q) {
                $q->whereDate('created_at', Carbon::parse($this->filterDate));
            })
            ->when($this->filterService, function ($q) {
                $q->where('service_id', $this->filterService);
            })
            ->when($this->filterStatus, function ($q) {
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
        ])->layout('layouts.app');
    }
}
