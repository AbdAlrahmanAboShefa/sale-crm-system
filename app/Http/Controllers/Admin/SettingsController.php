<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $tenant = auth()->user()->tenant;

        return view('admin.settings', compact('tenant'));
    }

    public function update(Request $request): RedirectResponse
    {
        $tenant = auth()->user()->tenant;

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tenant->update(['name' => $request->name]);

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully.');
    }
}
