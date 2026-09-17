<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Contoh pemakaian di routes: ->middleware('role:admin')
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!auth()->check() || auth()->user()->role !== $role) {
            abort(403, 'Anda tidak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}
