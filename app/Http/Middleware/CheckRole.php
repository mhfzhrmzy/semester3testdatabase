<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Contoh pemakaian di routes: ->middleware('role:admin')
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = Auth::guard('admin')->user();

        if (! $user || $user->role !== $role) {
            abort(403, 'Anda tidak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}
