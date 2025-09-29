<?php

namespace App\Livewire\Patient;

use Livewire\Component;
use App\Livewire\WithLayout;

class Ticket extends Component
{
    use WithLayout;

    public function mount()
    {
        $this->layout = 'components.layouts.patient';
        $this->title = 'Ambil Tiket';
    }

    public function render()
    {
        return view('livewire.patient.ticket');
    }
}
