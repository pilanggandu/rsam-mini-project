<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // belum login → biar middleware auth yang handle (auth middleware tetap dipasang di route)
        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('login');
        }

        $userRole = $user->role ?? null;

        // cek apakah role user termasuk di parameter $roles
        if (! $userRole || ! in_array($userRole, $roles, true)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Role tidak diizinkan.'], 403);
            }

            abort(403, 'Anda tidak berhak mengakses halaman ini.');
        }

        return $next($request);
    }
}
