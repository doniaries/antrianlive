<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('patient.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');
        $remember = $request->filled('remember');

        // Determine if the login is an email or BPJS number
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'bpjs_number';

        // Attempt to authenticate the user
        if (Auth::guard('patient')->attempt([$field => $login, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('patient.dashboard'));
        }

        // If authentication failed
        throw ValidationException::withMessages([
            'login' => __('auth.failed'),
        ]);
    }

    public function showRegistrationForm()
    {
        return view('patient.register');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:patients,email',
            'phone' => 'required|string|max:20',
            'bpjs_number' => 'required|string|max:20|unique:patients,bpjs_number',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $patient = Patient::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'bpjs_number' => $validatedData['bpjs_number'],
            'password' => Hash::make($validatedData['password']),
        ]);

        Auth::guard('patient')->login($patient);

        return redirect()->route('patient.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('patient')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
