<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TenantRegistrationController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'subdomain' => 'required|string|max:50|unique:tenants,subdomain|alpha_dash',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($request) {
            $tenant = Tenant::create([
                'name' => $request->company_name,
                'subdomain' => $request->subdomain,
                'plan' => 'free',
                'is_active' => true,
                'trial_ends_at' => now()->addDays(14),
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'tenant_id' => $tenant->id,
                'is_active' => true,
            ]);

            $user->assignRole('Admin');

            Auth::login($user);
        });

        return redirect()->route('admin.dashboard')
            ->with('success', 'Welcome! Your 14-day trial has started.');
    }
}
