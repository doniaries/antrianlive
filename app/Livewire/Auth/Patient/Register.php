<?php

namespace App\Livewire\Auth\Patient;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

#[Layout('patient-auth')]
class Register extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $bpjs_number = '';
    public $password = '';
    public $password_confirmation = '';
    public $showPassword = false;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:patients,email',
        'phone' => 'required|string|max:20',
        'bpjs_number' => 'required|string|max:20|unique:patients,bpjs_number',
        'password' => 'required|string|min:8|confirmed',
    ];
    
    public function register()
    {
        $validatedData = $this->validate();
        
        $patient = Patient::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'bpjs_number' => $this->bpjs_number,
            'password' => Hash::make($this->password),
        ]);
        
        // Log in the patient after registration
        Auth::guard('patient')->login($patient);
        
        return redirect()->route('patient.dashboard');
    }
    
    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }
    
    public function render()
    {
        return view('livewire.auth.patient.register');
    }
}
