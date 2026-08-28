<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanLimits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $resource): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        $plan = $user->currentPlan();

        if (!$plan) {
            return $next($request);
        }

        // Check resource limits
        switch ($resource) {
            case 'products':
                if (!$user->canAddProduct()) {
                    if ($request->wantsJson()) {
                        return response()->json([
                            'message' => 'You have reached your product limit. Please upgrade your plan to add more products.',
                            'upgrade_url' => route('billing.upgrade'),
                        ], 403);
                    }

                    return redirect()->route('billing.upgrade')
                        ->with('error', 'You have reached your product limit. Please upgrade your plan to add more products.');
                }
                break;

            case 'links':
                if (!$user->canAddLink()) {
                    if ($request->wantsJson()) {
                        return response()->json([
                            'message' => 'You have reached your link limit. Please upgrade your plan to add more links.',
                            'upgrade_url' => route('billing.upgrade'),
                        ], 403);
                    }

                    return redirect()->route('billing.upgrade')
                        ->with('error', 'You have reached your link limit. Please upgrade your plan to add more links.');
                }
                break;
        }

        return $next($request);
    }
}
