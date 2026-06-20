<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(404);
        }

        $allowed = match ($role) {
            'admin' => $user->isAdmin(),
            'user' => $user->isUser(),
            default => false,
        };

        if (! $allowed) {
            abort(404);
        }

        return $next($request);
    }
}