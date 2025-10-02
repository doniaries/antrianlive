<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PatientAuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('patient.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Determine if the login is an email or BPJS number
        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'bpjs_number';
        
        // Store login in cookie if remember me is checked
        if ($request->has('remember')) {
            $minutes = 60 * 24 * 30; // 30 days
            cookie()->queue('patient_login_remember', $request->login, $minutes);
        } else {
            cookie()->queue(cookie()->forget('patient_login_remember'));
        }
        
        // Attempt to authenticate the patient
        if (Auth::guard('patient')->attempt(
            [$fieldType => $request->login, 'password' => $request->password],
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();

            return redirect()->intended(route('patient.dashboard'));
        }

        // If authentication failed, return with error
        throw ValidationException::withMessages([
            'login' => __('auth.failed'),
        ]);
    }

    /**
     * Log the patient out of the application.
     */
    public function logout(Request $request)
    {
        Auth::guard('patient')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
