<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $isAdminOrManager = $user->hasRole(['Admin', 'Manager']);
        $routePrefix = $this->getRoutePrefix();

        $query = Activity::forTenant()->with(['contact', 'deal', 'user']);

        if (! $isAdminOrManager) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('is_done')) {
            $query->where('is_done', $request->is_done === '1');
        }

        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        $activities = $query->orderBy('due_date', 'desc')->orderBy('created_at', 'desc')->paginate(20);

        return view('activities.index', compact('activities', 'routePrefix'));
    }

    public function create(): View
    {
        $routePrefix = $this->getRoutePrefix();
        $contacts = \App\Models\Contact::forTenant()->get();
        $deals = \App\Models\Deal::forTenant()->where('stage', '!=', 'Lost')->where('stage', '!=', 'Won')->get();

        return view('activities.create', compact('contacts', 'deals', 'routePrefix'));
    }

    public function store(ActivityRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['tenant_id'] = auth()->user()->tenant_id;
        $data['is_done'] = $request->has('is_done');

        Activity::create($data);

        return redirect()->route($this->getRoutePrefix().'.activities.index')
            ->with('success', 'Activity logged successfully.');
    }

    public function show(Activity $activity): View
    {
        $this->authorizeActivity($activity);

        return view('activities.show', compact('activity'));
    }

    public function edit(Activity $activity): View
    {
        $this->authorizeActivity($activity);
        $routePrefix = $this->getRoutePrefix();

        $contacts = \App\Models\Contact::forTenant()->get();
        $deals = \App\Models\Deal::forTenant()->where('stage', '!=', 'Lost')->where('stage', '!=', 'Won')->get();

        return view('activities.edit', compact('activity', 'contacts', 'deals', 'routePrefix'));
    }

    public function update(ActivityRequest $request, Activity $activity): RedirectResponse
    {
        $this->authorizeActivity($activity);

        $data = $request->validated();
        $data['is_done'] = $request->has('is_done');

        $activity->update($data);

        return redirect()->route($this->getRoutePrefix().'.activities.show', $activity)
            ->with('success', 'Activity updated successfully.');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $this->authorizeActivity($activity);

        $activity->delete();

        return redirect()->route($this->getRoutePrefix().'.activities.index')
            ->with('success', 'Activity deleted successfully.');
    }

    public function markDone(Activity $activity): JsonResponse
    {
        $this->authorizeActivity($activity);

        $activity->update(['is_done' => true]);

        return response()->json(['success' => true]);
    }

    private function authorizeActivity(Activity $activity): void
    {
        $user = auth()->user();
        $isAdminOrManager = $user->hasRole(['Admin', 'Manager']);

        if (! $isAdminOrManager && $activity->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    private function getRoutePrefix(): string
    {
        $user = auth()->user();
        if ($user->hasRole('Admin')) {
            return 'admin';
        }
        if ($user->hasRole('Manager')) {
            return 'manager';
        }

        return 'agent';
    }
}
