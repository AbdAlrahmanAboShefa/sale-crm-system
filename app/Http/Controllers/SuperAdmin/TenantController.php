<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function index(): View
    {
        $tenants = Tenant::withCount(['users', 'contacts', 'deals'])
            ->latest()
            ->paginate(20);

        return view('super_admin.tenants.index', compact('tenants'));
    }

    public function create(): View
    {
        return view('super_admin.tenants.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'required|string|max:255|unique:tenants,subdomain',
            'plan' => 'required|string|in:free,basic,pro,enterprise',
            'trial_ends_at' => 'nullable|date',
        ]);

        Tenant::create([
            ...$validated,
            'is_active' => true,
        ]);

        return redirect()->route('super_admin.tenants.index')
            ->with('success', __('messages.tenants.created'));
    }

    public function show(Tenant $tenant): View
    {
        $tenant->load(['users', 'contacts', 'deals', 'activities']);

        return view('super_admin.tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant): View
    {
        return view('super_admin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'required|string|max:255|unique:tenants,subdomain,'.$tenant->id,
            'plan' => 'required|string|in:free,basic,pro,enterprise',
            'is_active' => 'boolean',
            'trial_ends_at' => 'nullable|date',
        ]);

        $tenant->update($validated);

        return redirect()->route('super_admin.tenants.show', $tenant)
            ->with('success', __('messages.tenants.updated'));
    }

    public function destroy(Tenant $tenant): RedirectResponse
    {
        $tenant->delete();

        return redirect()->route('super_admin.tenants.index')
            ->with('success', __('messages.tenants.deleted'));
    }

    public function toggleActive(Tenant $tenant): RedirectResponse
    {
        $tenant->update(['is_active' => ! $tenant->is_active]);

        $status = $tenant->is_active ? __('messages.tenants.activated') : __('messages.tenants.deactivated');

        return back()->with('success', $status);
    }
}
