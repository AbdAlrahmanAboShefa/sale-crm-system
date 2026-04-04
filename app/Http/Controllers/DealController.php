<?php

namespace App\Http\Controllers;

use App\Http\Requests\DealRequest;
use App\Models\Deal;
use App\Services\DealService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DealController extends Controller
{
    public function __construct(
        private DealService $dealService
    ) {}

    public function index(Request $request): View
    {
        $user = auth()->user();
        $isAdminOrManager = $user->hasRole(['Admin', 'Manager']);
        $routePrefix = $this->getRoutePrefix();

        $deals = $this->dealService->getFilteredDeals(
            $request->only(['search', 'stage', 'user_id', 'date_range']),
            $isAdminOrManager,
            $user->id
        );

        $users = \App\Models\User::all();

        return view('deals.index', compact('deals', 'users', 'routePrefix'));
    }

    public function kanban(): View
    {
        $user = auth()->user();
        $isAdminOrManager = $user->hasRole(['Admin', 'Manager']);
        $routePrefix = $this->getRoutePrefix();

        $kanban = $this->dealService->getKanbanData($isAdminOrManager, $user->id);
        $forecast = ['total' => $this->dealService->getForecastValue($isAdminOrManager, $user->id), 'currency' => 'USD'];
        $users = $isAdminOrManager ? \App\Models\User::all() : collect();

        return view('deals.kanban', compact('kanban', 'forecast', 'users', 'isAdminOrManager', 'routePrefix'));
    }

    public function create(): View
    {
        $user = auth()->user();
        $isAdminOrManager = $user->hasRole(['Admin', 'Manager']);
        $routePrefix = $this->getRoutePrefix();

        $contacts = \App\Models\Contact::all();
        $users = $isAdminOrManager ? \App\Models\User::all() : collect([$user]);

        return view('deals.create', compact('contacts', 'users', 'routePrefix'));
    }

    public function store(DealRequest $request): RedirectResponse
    {
        Deal::create($request->validated());

        return redirect()->route($this->getRoutePrefix().'.deals.index')
            ->with('success', 'Deal created successfully.');
    }

    public function show(Deal $deal): View
    {
        $this->authorizeDeal($deal);
        $routePrefix = $this->getRoutePrefix();

        return view('deals.show', compact('deal', 'routePrefix'));
    }

    public function edit(Deal $deal): View
    {
        $this->authorizeDeal($deal);
        $routePrefix = $this->getRoutePrefix();

        $user = auth()->user();
        $isAdminOrManager = $user->hasRole(['Admin', 'Manager']);

        $contacts = \App\Models\Contact::all();
        $users = $isAdminOrManager ? \App\Models\User::all() : collect([$user]);

        return view('deals.edit', compact('deal', 'contacts', 'users', 'routePrefix'));
    }

    public function update(DealRequest $request, Deal $deal): RedirectResponse
    {
        $this->authorizeDeal($deal);

        $deal->update($request->validated());

        return redirect()->route($this->getRoutePrefix().'.deals.show', $deal)
            ->with('success', 'Deal updated successfully.');
    }

    public function updateStage(Request $request, Deal $deal): JsonResponse
    {
        $this->authorizeDeal($deal);

        $request->validate([
            'stage' => ['required', 'in:New,Contacted,Qualified,Proposal,Negotiation,Won,Lost'],
        ]);

        $deal->update(['stage' => $request->stage]);

        return response()->json(['success' => true]);
    }

    public function destroy(Deal $deal): RedirectResponse
    {
        $this->authorizeDeal($deal);

        $deal->delete();

        return redirect()->route($this->getRoutePrefix().'.deals.index')
            ->with('success', 'Deal deleted successfully.');
    }

    private function authorizeDeal(Deal $deal): void
    {
        $user = auth()->user();
        $isAdminOrManager = $user->hasRole(['Admin', 'Manager']);

        if (! $isAdminOrManager && $deal->user_id !== $user->id) {
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
