<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If user is not logged in, let auth middleware handle it
        if (!$user) {
            return $next($request);
        }

        // Admin users don't need a profile
        if ($user->isAdmin()) {
            return $next($request);
        }

        // If user doesn't have a profile and is not on the setup page, redirect
        if (!$user->hasProfile() && !$request->routeIs('profile.setup.*')) {
            return redirect()->route('profile.setup.index');
        }

        return $next($request);
    }
}
