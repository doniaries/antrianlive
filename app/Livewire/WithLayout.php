<?php

namespace App\Livewire;

trait WithLayout
{
    public $layout = 'components.layouts.app';
    public $title = 'Sistem Antrian';

    public function layout()
    {
        return $this->layout;
    }
}
