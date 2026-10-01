<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperadmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('superadmin')->check()) {
            abort(403, 'Halaman ini khusus Superadmin.');
        }

        return $next($request);
    }
}
