<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
   public function handle(Request $request, Closure $next, ...$roles)
{
    if (in_array($request->user()->role, $roles)) {
        return $next($request);
    }

    return redirect('dashboard')->with('error', 'Kamu tidak punya akses ke halaman ini.');
}
}
