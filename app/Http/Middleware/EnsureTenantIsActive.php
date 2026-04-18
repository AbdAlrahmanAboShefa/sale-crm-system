<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            return $next($request);
        }

        if ($user->hasRole('Super Admin')) {
            return $next($request);
        }

        $tenant = $user->tenant;

        if (! $tenant) {
            abort(403, 'No tenant assigned to your account.');
        }

        if (! $tenant->is_active) {
            auth()->logout();

            return redirect()->route('login')
                ->with('error', 'Your account has been suspended. Please contact support.');
        }

        if ($tenant->trial_ends_at && $tenant->trial_ends_at->isPast() && $tenant->plan === 'free') {
            return redirect()->route('billing.upgrade')
                ->with('error', 'Your trial has expired. Please upgrade to continue.');
        }

        return $next($request);
    }
}
