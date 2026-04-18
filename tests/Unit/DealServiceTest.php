<?php

namespace Tests\Unit;

use App\Models\Contact;
use App\Models\Deal;
use App\Models\Tenant;
use App\Models\User;
use App\Services\DealService;

test('returns all stages in kanban data', function () {
    $dealService = new DealService;
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'New',
        'value' => 1000,
    ]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Won',
        'value' => 5000,
    ]);

    $kanban = $dealService->getKanbanData(isAdmin: true);

    expect($kanban)->toHaveKeys(['New', 'Won'])
        ->and($kanban['New']['deals'])->toHaveCount(1)
        ->and($kanban['Won']['deals'])->toHaveCount(1);
});

test('filters kanban by user when not admin', function () {
    $dealService = new DealService;
    $tenant = Tenant::factory()->create();
    $user1 = User::factory()->create(['tenant_id' => $tenant->id]);
    $user2 = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user1->id]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user1->id,
        'contact_id' => $contact->id,
        'stage' => 'New',
    ]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user2->id,
        'contact_id' => $contact->id,
        'stage' => 'New',
    ]);

    $kanban = $dealService->getKanbanData(isAdmin: false, userId: $user1->id);

    expect($kanban['New']['deals'])->toHaveCount(1);
});

test('calculates weighted forecast value', function () {
    $dealService = new DealService;
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Negotiation',
        'value' => 10000,
        'probability' => 75,
    ]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Qualified',
        'value' => 5000,
        'probability' => 25,
    ]);

    $forecast = $dealService->getForecastValue(isAdmin: true);

    expect($forecast)->toBe(8750.00);
});

test('excludes won and lost deals from forecast', function () {
    $dealService = new DealService;
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Won',
        'value' => 100000,
        'probability' => 100,
    ]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Lost',
        'value' => 50000,
        'probability' => 0,
    ]);

    $forecast = $dealService->getForecastValue(isAdmin: true);

    expect($forecast)->toBe(0.0);
});

test('filters deals by search term', function () {
    $dealService = new DealService;
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact1 = Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'name' => 'John Doe',
    ]);
    $contact2 = Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'name' => 'Jane Smith',
    ]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact1->id,
        'title' => 'Enterprise Deal',
    ]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact2->id,
        'title' => 'Small Deal',
    ]);

    $results = $dealService->getFilteredDeals(
        ['search' => 'John'],
        isAdmin: true
    );

    expect($results)->toHaveCount(1)
        ->and($results->first()->title)->toBe('Enterprise Deal');
});

test('filters deals by stage', function () {
    $dealService = new DealService;
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'New',
    ]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Won',
    ]);

    $results = $dealService->getFilteredDeals(
        ['stage' => 'New'],
        isAdmin: true
    );

    expect($results)->toHaveCount(1);
});

test('filters deals by date range', function () {
    $dealService = new DealService;
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'expected_close_date' => now()->addDays(5),
    ]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'expected_close_date' => now()->addDays(30),
    ]);

    $start = now()->addDays(1)->format('Y-m-d');
    $end = now()->addDays(10)->format('Y-m-d');

    $results = $dealService->getFilteredDeals(
        ['date_range' => "{$start} - {$end}"],
        isAdmin: true
    );

    expect($results)->toHaveCount(1);
});

test('returns paginated filtered deals', function () {
    $dealService = new DealService;
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    Deal::factory()->count(20)->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
    ]);

    $results = $dealService->getFilteredDeals([], isAdmin: true);

    expect($results->count())->toBe(15)
        ->and($results->total())->toBe(20)
        ->and($results->lastPage())->toBe(2);
});
