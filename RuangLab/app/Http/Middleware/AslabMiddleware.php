<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AslabMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !$user->isAslab()) {
            abort(403, 'Anda tidak memiliki akses ke halaman Aslab.');
        }

        return $next($request);
    }
}
