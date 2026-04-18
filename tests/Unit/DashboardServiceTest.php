<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Tenant;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Cache;

test('returns sum of active deals for pipeline value', function () {
    $dashboardService = new DashboardService;
    Cache::flush();

    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Negotiation',
        'value' => 10000,
    ]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Proposal',
        'value' => 5000,
    ]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Won',
        'value' => 20000,
    ]);

    $pipelineValue = $dashboardService->getTotalPipelineValue(isAdminOrManager: true);

    expect($pipelineValue)->toBe(15000.00);
});

test('excludes lost deals from pipeline value', function () {
    $dashboardService = new DashboardService;
    Cache::flush();

    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'New',
        'value' => 10000,
    ]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Lost',
        'value' => 50000,
    ]);

    $pipelineValue = $dashboardService->getTotalPipelineValue(isAdminOrManager: true);

    expect($pipelineValue)->toBe(10000.00);
});

test('returns count and value for won this month', function () {
    $dashboardService = new DashboardService;
    Cache::flush();

    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Won',
        'value' => 10000,
        'updated_at' => now(),
    ]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Won',
        'value' => 5000,
        'updated_at' => now(),
    ]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Won',
        'value' => 15000,
        'updated_at' => now()->subMonths(2),
    ]);

    $result = $dashboardService->getWonThisMonth(isAdminOrManager: true);

    expect($result)->toHaveKeys(['count', 'value'])
        ->and($result['count'])->toBe(2)
        ->and($result['value'])->toBe(15000.00);
});

test('calculates conversion rate percentage', function () {
    $dashboardService = new DashboardService;
    Cache::flush();

    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    Deal::factory()->count(4)->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'New',
    ]);

    Deal::factory()->count(2)->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Won',
    ]);

    $rate = $dashboardService->getConversionRate(isAdminOrManager: true);

    expect($rate)->toBe(33.3);
});

test('returns zero when no deals exist for conversion rate', function () {
    $dashboardService = new DashboardService;
    Cache::flush();

    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    $rate = $dashboardService->getConversionRate(isAdminOrManager: true);

    expect($rate)->toBe(0.0);
});

test('counts incomplete past due activities as overdue', function () {
    $dashboardService = new DashboardService;
    Cache::flush();

    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    Activity::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'is_done' => false,
        'due_date' => now()->subDays(1),
    ]);

    Activity::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'is_done' => false,
        'due_date' => now()->addDays(1),
    ]);

    Activity::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'is_done' => true,
        'due_date' => now()->subDays(1),
    ]);

    $count = $dashboardService->getOverdueActivities(isAdminOrManager: true);

    expect($count)->toBe(1);
});

test('returns 12 months data for monthly revenue', function () {
    $dashboardService = new DashboardService;
    Cache::flush();

    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Won',
        'value' => 10000,
        'updated_at' => now(),
    ]);

    $result = $dashboardService->getMonthlyRevenue(isAdminOrManager: true);

    expect($result)->toHaveKeys(['labels', 'values'])
        ->and($result['labels'])->toHaveCount(12)
        ->and($result['values'])->toHaveCount(12);
});

test('returns stage counts for pipeline funnel', function () {
    $dashboardService = new DashboardService;
    Cache::flush();

    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    foreach (['New', 'Contacted', 'Qualified'] as $stage) {
        Deal::factory()->count(2)->create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'contact_id' => $contact->id,
            'stage' => $stage,
        ]);
    }

    $funnel = $dashboardService->getPipelineFunnel(isAdminOrManager: true);

    expect($funnel)->toHaveKeys(['labels', 'values'])
        ->and($funnel['labels'])->toContain('New')
        ->and($funnel['labels'])->toContain('Won')
        ->and($funnel['values'][0])->toBe(2);
});

test('returns top users in leaderboard', function () {
    $dashboardService = new DashboardService;
    Cache::flush();

    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    Deal::factory()->count(3)->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Won',
        'value' => 10000,
    ]);

    auth()->login($user);

    $leaderboard = $dashboardService->getLeaderboard();

    expect($leaderboard)->toBeArray()
        ->and($leaderboard)->toHaveCount(1);
});

test('returns limited results for recent activities', function () {
    $dashboardService = new DashboardService;
    Cache::flush();

    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    Activity::factory()->count(10)->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'type' => 'Call',
    ]);

    $activities = $dashboardService->getRecentActivities(isAdminOrManager: true);

    expect($activities)->toBeArray()
        ->and($activities)->toHaveCount(5);
});

test('formats activity titles correctly', function () {
    $dashboardService = new DashboardService;
    Cache::flush();

    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'name' => 'Test Contact',
    ]);

    Activity::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'type' => 'Meeting',
        'note' => 'Initial meeting',
    ]);

    $activities = $dashboardService->getRecentActivities(isAdminOrManager: true);

    expect($activities[0]['title'])->toContain('Meeting with Test Contact')
        ->and($activities[0]['type'])->toBe('meeting');
});

test('returns highest value deals excluding lost', function () {
    $dashboardService = new DashboardService;
    Cache::flush();

    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'title' => 'Low Value Deal',
        'value' => 1000,
        'stage' => 'New',
    ]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'title' => 'High Value Deal',
        'value' => 50000,
        'stage' => 'Negotiation',
    ]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'title' => 'Lost Deal',
        'value' => 100000,
        'stage' => 'Lost',
    ]);

    $topDeals = $dashboardService->getTopDeals(isAdminOrManager: true);

    expect($topDeals)->toHaveCount(2)
        ->and($topDeals[0]['name'])->toBe('High Value Deal');
});

test('includes probability based on stage', function () {
    $dashboardService = new DashboardService;
    Cache::flush();

    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Negotiation',
    ]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'stage' => 'Won',
    ]);

    $topDeals = $dashboardService->getTopDeals(isAdminOrManager: true);

    $wonDeal = collect($topDeals)->firstWhere('stage', 'Won');
    $negotiationDeal = collect($topDeals)->firstWhere('stage', 'Negotiation');

    expect($wonDeal['probability'])->toBe(100)
        ->and($negotiationDeal['probability'])->toBe(75);
});

test('respects user filter for all methods', function () {
    $dashboardService = new DashboardService;
    Cache::flush();

    $tenant = Tenant::factory()->create();
    $user1 = User::factory()->create(['tenant_id' => $tenant->id]);
    $user2 = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user1->id]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user1->id,
        'contact_id' => $contact->id,
        'stage' => 'New',
        'value' => 1000,
    ]);

    Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user2->id,
        'contact_id' => $contact->id,
        'stage' => 'New',
        'value' => 5000,
    ]);

    $pipelineValue = $dashboardService->getTotalPipelineValue(
        isAdminOrManager: false,
        userId: $user1->id
    );

    expect($pipelineValue)->toBe(1000.00);
});
