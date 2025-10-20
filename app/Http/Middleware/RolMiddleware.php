<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RolMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
            $user = Auth::user();
            $rol = $request->route()->getAction('rol');

            if ($rol && (!$user || $user->rol !== $rol)) {
                abort(403, 'No tienes permiso para acceder a esta sección.');
            }
            return $next($request);
    }
}
