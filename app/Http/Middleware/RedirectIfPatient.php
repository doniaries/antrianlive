<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfPatient
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (Auth::guard('patient')->check()) {
            return redirect()->route('patient.dashboard');
        }

        return $next($request);
    }
}
