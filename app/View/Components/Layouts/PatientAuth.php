<?php

namespace App\View\Components\Layouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PatientAuth extends Component
{
    /**
     * The page title.
     *
     * @var string
     */
    public $title;

    /**
     * Create a new component instance.
     *
     * @param  string  $title
     * @return void
     */
    public function __construct($title = null)
    {
        $this->title = $title ?? 'Pasien';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.patient-auth', [
            'title' => $this->title,
        ]);
    }
}
