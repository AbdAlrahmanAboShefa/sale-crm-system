<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(
        private ContactService $contactService
    ) {}

    public function index(Request $request): View
    {
        $user = auth()->user();
        $isAdminOrManager = $user->hasRole(['Admin', 'Manager']);
        $routePrefix = $this->getRoutePrefix();

        $contacts = $this->contactService->getFilteredContacts(
            $request->only(['search', 'status', 'source', 'date_range']),
            $isAdminOrManager ? null : $user->id,
            $isAdminOrManager
        );

        return view('contacts.index', compact('contacts', 'routePrefix'));
    }

    public function create(): View
    {
        return view('contacts.create');
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['tags'] = $request->tags ?? [];
        $data['custom_fields'] = $request->custom_fields ?? [];

        Contact::create($data);

        return redirect()->route($this->getRoutePrefix() . '.contacts.index')->with('success', 'Contact created successfully.');
    }

    public function show(Contact $contact): View
    {
        $this->authorizeContact($contact);

        return view('contacts.show', compact('contact'));
    }

    public function edit(Contact $contact): View
    {
        $this->authorizeContact($contact);

        return view('contacts.edit', compact('contact'));
    }

    public function update(ContactRequest $request, Contact $contact): RedirectResponse
    {
        $this->authorizeContact($contact);

        $data = $request->validated();
        $data['tags'] = $request->tags ?? [];
        $data['custom_fields'] = $request->custom_fields ?? [];

        $contact->update($data);

        return redirect()->route($this->getRoutePrefix() . '.contacts.show', $contact)->with('success', 'Contact updated successfully.');
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $this->authorizeContact($contact);

        $contact->delete();

        return redirect()->route($this->getRoutePrefix() . '.contacts.index')->with('success', 'Contact deleted successfully.');
    }

    private function getRoutePrefix(): string
    {
        $user = auth()->user();
        if ($user->hasRole('Admin')) return 'admin';
        if ($user->hasRole('Manager')) return 'manager';
        return 'agent';
    }

    private function authorizeContact(Contact $contact): void
    {
        $user = auth()->user();
        $isAdminOrManager = $user->hasRole(['Admin', 'Manager']);

        if (! $isAdminOrManager && $contact->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
    }
}
