<?php

namespace Tests\Unit;

use App\Models\Contact;
use App\Models\Tenant;
use App\Models\User;
use App\Services\ContactService;

test('returns all contacts for admin', function () {
    $contactService = new ContactService;
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    Contact::factory()->count(3)->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    $results = $contactService->getFilteredContacts([], isAdmin: true);

    expect($results)->toHaveCount(3);
});

test('filters contacts by user when not admin', function () {
    $contactService = new ContactService;
    $tenant = Tenant::factory()->create();
    $user1 = User::factory()->create(['tenant_id' => $tenant->id]);
    $user2 = User::factory()->create(['tenant_id' => $tenant->id]);

    Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user1->id,
        'name' => 'Contact 1',
    ]);

    Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user2->id,
        'name' => 'Contact 2',
    ]);

    $results = $contactService->getFilteredContacts([], userId: $user1->id, isAdmin: false);

    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('Contact 1');
});

test('searches contacts by name', function () {
    $contactService = new ContactService;
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
    ]);

    $results = $contactService->getFilteredContacts(['search' => 'John'], isAdmin: true);

    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('John Doe');
});

test('searches contacts by email', function () {
    $contactService = new ContactService;
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
    ]);

    $results = $contactService->getFilteredContacts(['search' => 'jane@example.com'], isAdmin: true);

    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('Jane Smith');
});

test('searches contacts by company', function () {
    $contactService = new ContactService;
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'name' => 'John Doe',
        'company' => 'Acme Corp',
    ]);

    Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'name' => 'Jane Smith',
        'company' => 'Beta Inc',
    ]);

    $results = $contactService->getFilteredContacts(['search' => 'Acme'], isAdmin: true);

    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('John Doe');
});

test('filters contacts by status', function () {
    $contactService = new ContactService;
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'status' => 'Active',
    ]);

    Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'status' => 'Inactive',
    ]);

    $results = $contactService->getFilteredContacts(['status' => 'Active'], isAdmin: true);

    expect($results)->toHaveCount(1)
        ->and($results->pluck('status')->unique()->toArray())->toBe(['Active']);
});

test('filters contacts by source', function () {
    $contactService = new ContactService;
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'source' => 'Website',
    ]);

    Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'source' => 'Referral',
    ]);

    $results = $contactService->getFilteredContacts(['source' => 'Website'], isAdmin: true);

    expect($results)->toHaveCount(1);
});

test('filters contacts by date range', function () {
    $contactService = new ContactService;
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'created_at' => now()->subDays(5),
    ]);

    Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'created_at' => now()->subDays(30),
    ]);

    $start = now()->subDays(10)->format('Y-m-d');
    $end = now()->format('Y-m-d');

    $results = $contactService->getFilteredContacts(
        ['date_range' => "{$start} - {$end}"],
        isAdmin: true
    );

    expect($results)->toHaveCount(1);
});

test('returns paginated contacts', function () {
    $contactService = new ContactService;
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    Contact::factory()->count(20)->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    $results = $contactService->getFilteredContacts([], isAdmin: true);

    expect($results->count())->toBe(15)
        ->and($results->total())->toBe(20)
        ->and($results->lastPage())->toBe(2);
});

test('orders contacts by created_at desc', function () {
    $contactService = new ContactService;
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'name' => 'Old Contact',
        'created_at' => now()->subDays(10),
    ]);

    Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'name' => 'New Contact',
        'created_at' => now(),
    ]);

    $results = $contactService->getFilteredContacts([], isAdmin: true);

    expect($results->first()->name)->toBe('New Contact');
});
