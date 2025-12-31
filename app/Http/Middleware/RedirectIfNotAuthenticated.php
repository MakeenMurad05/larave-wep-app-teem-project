<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // اسم الـ route لصفحة login
        if (Auth::guest() && $request->routeIs('filament.auth.login') === false) {
            return redirect()->route('filament.admin.auth.login');
        }
        return $next($request);
    }
}
