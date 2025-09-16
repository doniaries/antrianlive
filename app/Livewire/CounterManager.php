<?php

namespace App\Livewire;

use App\Models\Counter;
use App\Models\Service;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class CounterManager extends Component
{
    use WithPagination;

    public $name = '';
    public $description = '';
    public $status = 'buka';
    public $open_time = '08:00';
    public $close_time = '17:00';
    public $selectedServices = [];
    public $counterId = null;
    public $isEditMode = false;
    public $showModal = false;
    
    protected $listeners = [
        'showCounterModal' => 'openModal',
        'hideCounterModal' => 'closeModal',
        'toggleCounterStatus' => 'update'
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
        'status' => 'required|in:buka,tutup,istirahat',
        'open_time' => 'required|date_format:H:i',
        'close_time' => 'required|date_format:H:i|after:open_time',
        'selectedServices' => 'array',
        'selectedServices.*' => 'exists:services,id',
    ];

    protected $validationAttributes = [
        'name' => 'Nama Loket',
        'description' => 'Deskripsi',
        'status' => 'Status',
        'open_time' => 'Jam Buka',
        'close_time' => 'Jam Tutup',
        'selectedServices' => 'Layanan',
    ];

    public function openModal()
    {
        \Illuminate\Support\Facades\Log::info('openModal called');
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->showModal = true;
        \Illuminate\Support\Facades\Log::info('showModal set to true');
    }

    public function closeModal()
    {
        \Illuminate\Support\Facades\Log::info('closeModal called');
        $this->showModal = false;
        $this->resetInputFields();
        \Illuminate\Support\Facades\Log::info('showModal set to false');
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->description = '';
        $this->status = 'buka';
        $this->open_time = '08:00';
        $this->close_time = '17:00';
        $this->selectedServices = [];
        $this->counterId = null;
        $this->isEditMode = false;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        $counter = Counter::create([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'open_time' => $this->open_time,
            'close_time' => $this->close_time,
        ]);

        // Attach services
        if (!empty($this->selectedServices)) {
            $counter->services()->sync($this->selectedServices);
        }

        session()->flash('message', 'Loket berhasil ' . ($this->isEditMode ? 'diperbarui' : 'ditambahkan') . '.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $counter = Counter::findOrFail($id);
        $this->counterId = $id;
        $this->name = $counter->name;
        $this->description = $counter->description;
        $this->status = $counter->status;
        $this->open_time = $counter->open_time ? $counter->open_time->format('H:i') : '08:00';
        $this->close_time = $counter->close_time ? $counter->close_time->format('H:i') : '17:00';
        $this->selectedServices = $counter->services->pluck('id')->toArray();
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function update($id = null)
    {
        if ($id) {
            $counter = Counter::findOrFail($id);
            // Toggle between 'buka' and 'tutup' when called from toggle
            $newStatus = $counter->status === 'buka' ? 'tutup' : 'buka';
            $counter->update(['status' => $newStatus]);
            
            // Broadcast the status update
            event(new \App\Events\CounterStatusUpdated($counter->fresh()));
            
            session()->flash('message', 'Status loket berhasil diperbarui.');
            return;
        }

        // Regular update from form
        $this->validate();

        $counter = Counter::find($this->counterId);
        $counter->update([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'open_time' => $this->open_time,
            'close_time' => $this->close_time,
        ]);

        // Sync services
        $counter->services()->sync($this->selectedServices);
        
        // Broadcast the status update
        event(new \App\Events\CounterStatusUpdated($counter->fresh()));

        session()->flash('message', 'Loket berhasil diperbarui.');
    }

    public function delete($id)
    {
        $counter = Counter::find($id);
        $counter->services()->detach();
        $counter->delete();
        session()->flash('message', 'Loket berhasil dihapus.');
    }

    public function render()
    {
        $counters = Counter::with('services')->orderBy('created_at', 'desc')->paginate(10);
        $services = Service::where('is_active', true)->orderBy('name')->get();
        
        return view('livewire.counter-manager', compact('counters', 'services'));
    }
}
