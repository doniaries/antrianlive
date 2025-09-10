<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role = null): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Jika superadmin, beri akses ke semua halaman
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Jika petugas, cek akses berdasarkan layanan
        if ($role === 'petugas' && $user->isPetugas()) {
            // Cek akses ke layanan tertentu jika ada parameter service_id
            if ($request->has('service_id')) {
                $serviceId = $request->get('service_id');
                if (!$user->hasServiceAccess($serviceId)) {
                    abort(403, 'Anda tidak memiliki akses ke layanan ini.');
                }
            }

            return $next($request);
        }

        // Jika role spesifik tidak cocok
        if ($role && $user->role !== $role) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
