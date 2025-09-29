<?php

namespace App\Livewire\Auth\Patient;

use Livewire\Component;
use App\Livewire\WithLayout;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Providers\RouteServiceProvider;

class Login extends Component
{
    use WithLayout;

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
        $this->validate();

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::guard('patient')->attempt($credentials, $this->remember)) {
            session()->regenerate();
            return redirect()->intended(route('patient.dashboard'));
        }

        $this->addError('email', 'Email atau password salah.');
    }

    public function mount()
    {
        $this->layout = 'components.layouts.guest';
        $this->title = 'Masuk Sebagai Pasien';
    }

    public function render()
    {
        return view('livewire.auth.patient.login');
    }
}
