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
    public $selectedServices = [];
    public $counterId = null;
    public $isEditMode = false;
    public $showModal = false;
    
    protected $listeners = [
        'showCounterModal' => 'openModal',
        'hideCounterModal' => 'closeModal'
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
        'selectedServices' => 'array',
        'selectedServices.*' => 'exists:services,id',
    ];

    protected $validationAttributes = [
        'name' => 'Nama Loket',
        'description' => 'Deskripsi',
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
        $this->resetValidation();
        $counter = Counter::with('services')->findOrFail($id);
        $this->counterId = $id;
        $this->name = $counter->name;
        $this->description = $counter->description;
        $this->selectedServices = $counter->services->pluck('id')->toArray();
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        $counter = Counter::find($this->counterId);
        $counter->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        // Sync services
        $counter->services()->sync($this->selectedServices);

        session()->flash('message', 'Loket berhasil diperbarui.');
        $this->closeModal();
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
