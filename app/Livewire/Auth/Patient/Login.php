<?php

namespace App\Livewire\Auth\Patient;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use Illuminate\Validation\ValidationException;

#[Layout('patient-auth')]
class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    public $showPassword = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $credentials = $this->validate();

        if (Auth::guard('patient')->attempt([
            'email' => $this->email,
            'password' => $this->password
        ], $this->remember)) {
            return redirect()->intended(route('patient.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function render()
    {
        return view('livewire.auth.patient.login');
    }
}
