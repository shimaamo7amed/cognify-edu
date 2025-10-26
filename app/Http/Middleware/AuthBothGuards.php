<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Symfony\Component\HttpFoundation\Response;

class AuthBothGuards
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
   {
        if ($request->routeIs('filament.cognify.auth.login')) {
            return $next($request);
        }
        if (!auth('admin')->check() && !auth('employee')->check()) {
            return redirect()->route('filament.cognify.auth.login');
        }

        return $next($request);
    }


}