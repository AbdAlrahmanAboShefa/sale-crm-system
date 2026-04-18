<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $tenant = $user->tenant;

        $users = User::with('roles')
            ->where('tenant_id', $tenant->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $userLimit = $tenant->getUserLimit();
        $userCount = $tenant->getUserCount();
        $canAddUser = $tenant->canAddUser();

        return view('admin.users.index', compact('users', 'userLimit', 'userCount', 'canAddUser'));
    }

    public function create(): View
    {
        $tenant = auth()->user()->tenant;

        if (! $tenant->canAddUser()) {
            return view('admin.users.index', [
                'users' => User::with('roles')->where('tenant_id', $tenant->id)->paginate(15),
                'userLimit' => $tenant->getUserLimit(),
                'userCount' => $tenant->getUserCount(),
                'canAddUser' => false,
            ])->with('error', "User limit reached. Your plan ({$tenant->plan}) allows {$tenant->getUserLimit()} users.");
        }

        return view('admin.users.create', [
            'userLimit' => $tenant->getUserLimit(),
            'userCount' => $tenant->getUserCount(),
        ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $tenant = auth()->user()->tenant;

        if (! $tenant->canAddUser()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'User limit reached. Upgrade your plan to add more users.');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'tenant_id' => $tenant->id,
            'is_active' => true,
        ])->assignRole($request->role);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $tenant = auth()->user()->tenant;

        if ($user->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.users.edit', [
            'user' => $user,
            'userLimit' => $tenant->getUserLimit(),
            'userCount' => $tenant->getUserCount(),
        ]);
    }

    public function show(User $user): View
    {
        return $this->edit($user);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $tenant = auth()->user()->tenant;

        if ($user->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized action.');
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $tenant = auth()->user()->tenant;

        if ($user->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot deactivate your own account.');
        }

        $user->is_active = false;
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deactivated successfully.');
    }

    public function activate(User $user): RedirectResponse
    {
        $tenant = auth()->user()->tenant;

        if ($user->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot activate your own account.');
        }

        $user->is_active = true;
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User activated successfully.');
    }
}
