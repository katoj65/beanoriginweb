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
    public function handle(Request $request, Closure $next, $role): Response
    {
        $allowedRoles = array_map('trim', explode(',', (string) $role));

        if (!$request->user() || !in_array($request->user()->role, $allowedRoles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
