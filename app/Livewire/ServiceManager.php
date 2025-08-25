<?php

namespace App\Livewire;

use App\Models\Service;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceManager extends Component
{
    use WithPagination;

    public $name = '';
    public $code = '';
    public $is_active = true;
    public $serviceId = null;
    public $isEditMode = false;
    public $showModal = false;
    
    protected $listeners = [
        'showServiceModal' => 'openModal',
        'hideServiceModal' => 'closeModal'
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:10|unique:services,code',
        'is_active' => 'boolean',
    ];

    protected $validationAttributes = [
        'name' => 'Nama Layanan',
        'code' => 'Kode Layanan',
    ];

    public function updated($propertyName)
    {
        if ($this->isEditMode && $propertyName === 'code') {
            $this->rules['code'] = 'required|string|max:10|unique:services,code,' . $this->serviceId;
        }
    }

    public function openModal()
    {
        \Illuminate\Support\Facades\Log::info('ServiceManager: openModal called');
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->showModal = true;
        \Illuminate\Support\Facades\Log::info('ServiceManager: showModal set to true');
    }

    public function closeModal()
    {
        \Illuminate\Support\Facades\Log::info('ServiceManager: closeModal called');
        $this->showModal = false;
        $this->resetInputFields();
        \Illuminate\Support\Facades\Log::info('ServiceManager: showModal set to false');
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->code = '';
        $this->is_active = true;
        $this->serviceId = null;
        $this->isEditMode = false;
        $this->resetValidation();
    }

    public function store()
    {
        try {
            if ($this->isEditMode) {
                $this->rules['code'] = 'required|string|max:10|unique:services,code,' . $this->serviceId;
            }

            $this->validate();

            $data = [
                'name' => $this->name,
                'code' => strtoupper($this->code),
                'is_active' => $this->is_active,
            ];

            if ($this->isEditMode) {
                Service::find($this->serviceId)->update($data);
                $message = 'Layanan berhasil diperbarui.';
            } else {
                Service::create($data);
                $message = 'Layanan berhasil ditambahkan.';
            }

            $this->showModal = false;
            $this->resetInputFields();
            session()->flash('message', $message);
            
            // Dispatch browser event to close the modal
            $this->dispatch('modal-closed');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->resetValidation();
        $service = Service::findOrFail($id);
        $this->serviceId = $id;
        $this->name = $service->name;
        $this->code = $service->code;
        $this->is_active = $service->is_active;
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        try {
            $service = Service::findOrFail($id);
            
            // Detach any relationships if needed
            $service->counters()->detach();
            
            $service->delete();
            
            session()->flash('message', 'Layanan berhasil dihapus.');
            return true;
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus layanan: ' . $e->getMessage());
            return false;
        }
    }

    public function toggleStatus($id)
    {
        $service = Service::find($id);
        $service->is_active = !$service->is_active;
        $service->save();
        session()->flash('message', 'Status layanan berhasil diubah.');
    }

    public function render()
    {
        $services = Service::orderBy('created_at', 'desc')->paginate(10);
        return view('livewire.service-manager', compact('services'));
    }
}
