<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (auth()->check()) {
            $role = auth()->user()->role;
            $hasAccess = in_array($role, $roles);

            if (!$hasAccess) {
                abort(403);
            }
        }

        // Other than that proceed
        return $next($request);
    }
}