<?php

namespace App\Livewire;

use App\Models\RunningTeks;
use Livewire\Component;
use Livewire\WithPagination;

class RunningTeksManager extends Component
{
    use WithPagination;

    public $text = '';
    public $editId = null;
    public $isOpen = false;

    protected $rules = [
        'text' => 'required|string|max:500',
    ];

    public function render()
    {
        $teksList = RunningTeks::latest()->paginate(10);
        return view('livewire.running-teks-manager', [
            'teksList' => $teksList
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function store()
    {
        $this->validate();

        RunningTeks::updateOrCreate(['id' => $this->editId], [
            'text' => $this->text,
        ]);

        session()->flash('message', 
            $this->editId ? 'Running teks berhasil diperbarui.' : 'Running teks berhasil ditambahkan.'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $teks = RunningTeks::findOrFail($id);
        $this->editId = $id;
        $this->text = $teks->text;
        $this->openModal();
    }

    public function delete($id)
    {
        RunningTeks::find($id)->delete();
        session()->flash('message', 'Running teks berhasil dihapus.');
    }

    private function resetInputFields()
    {
        $this->text = '';
        $this->editId = null;
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }
}