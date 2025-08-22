<?php

namespace App\Livewire;

use App\Models\Service;
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
        $this->name = '';
        $this->code = '';
        $this->is_active = true;
        $this->serviceId = null;
        $this->isEditMode = false;
        $this->resetValidation();
    }

    public function store()
    {
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
            session()->flash('message', 'Layanan berhasil diperbarui.');
        } else {
            Service::create($data);
            session()->flash('message', 'Layanan berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function edit($id)
    {
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
        Service::find($id)->delete();
        session()->flash('message', 'Layanan berhasil dihapus.');
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
