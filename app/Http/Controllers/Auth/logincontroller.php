<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        $role = $user->getRoleNames()->first() ?? 'Agent';

        $redirectRoute = match ($role) {
            'Super Admin' => 'super_admin.tenants.index',
            'Admin' => 'admin.dashboard',
            'Manager' => 'manager.dashboard',
            'Agent' => 'agent.dashboard',
            default => 'agent.dashboard',
        };

        return redirect()->route($redirectRoute);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}
