<?php

use App\Models\Activity;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Tenant;
use App\Models\User;

test('contact belongs to user', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    expect($contact->user)->toBeInstanceOf(User::class)
        ->and($contact->user->id)->toBe($user->id);
});

test('deal belongs to contact', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id, 'contact_id' => $contact->id]);

    expect($deal->contact)->toBeInstanceOf(Contact::class)
        ->and($deal->contact->id)->toBe($contact->id);
});

test('contact can be soft deleted', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $contact->delete();

    expect(Contact::find($contact->id))->toBeNull()
        ->and(Contact::withTrashed()->find($contact->id))->not->toBeNull();
});

test('deal can be soft deleted', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id, 'contact_id' => $contact->id]);
    $deal->delete();

    expect(Deal::find($deal->id))->toBeNull()
        ->and(Deal::withTrashed()->find($deal->id))->not->toBeNull();
});

test('user has roles', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $user->assignRole('Admin');

    expect($user->hasRole('Admin'))->toBeTrue()
        ->and($user->hasRole('Agent'))->toBeFalse();
});

test('contact has tags as array', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'tags' => ['vip', 'hot-lead'],
    ]);

    expect($contact->tags)->toBeArray()
        ->and(in_array('vip', $contact->tags))->toBeTrue();
});

test('deal has contact and user relationships', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
    ]);

    expect($deal->contact)->toBeInstanceOf(Contact::class)
        ->and($deal->user)->toBeInstanceOf(User::class)
        ->and($deal->tenant)->toBeInstanceOf(Tenant::class);
});

test('contact has activities and deals relationships', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    expect($contact->activities())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class)
        ->and($contact->deals())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('deal days in stage calculation', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'updated_at' => now()->subDays(5),
    ]);

    expect($deal->daysInStage())->toBeGreaterThanOrEqual(5);
});

test('deal stages constant has all stages', function () {
    expect(Deal::STAGES)->toBe([
        'New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won', 'Lost',
    ]);
});

test('activity types constant has all types', function () {
    expect(Activity::TYPES)->toBe(['Call', 'Meeting', 'Email', 'Task', 'Demo']);
});

test('activity outcomes constant has all outcomes', function () {
    expect(Activity::OUTCOMES)->toBe(['Positive', 'Neutral', 'Negative']);
});

test('tenant has users contacts deals activities relationships', function () {
    $tenant = Tenant::factory()->create();

    expect($tenant->users())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class)
        ->and($tenant->contacts())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class)
        ->and($tenant->deals())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class)
        ->and($tenant->activities())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('tenant plan limits has correct values', function () {
    expect(Tenant::PLAN_LIMITS['free'])->toBe(3)
        ->and(Tenant::PLAN_LIMITS['basic'])->toBe(10)
        ->and(Tenant::PLAN_LIMITS['pro'])->toBe(25)
        ->and(Tenant::PLAN_LIMITS['enterprise'])->toBe(PHP_INT_MAX);
});

test('tenant contact limits constant has all plans', function () {
    expect(Tenant::CONTACT_LIMITS)->toHaveKey('free', 50)
        ->toHaveKey('basic', 500)
        ->toHaveKey('pro', PHP_INT_MAX)
        ->toHaveKey('enterprise', PHP_INT_MAX);
});

test('tenant is on trial when trial date is in future', function () {
    $tenant = Tenant::factory()->create([
        'trial_ends_at' => now()->addDays(10),
    ]);

    expect($tenant->isOnTrial())->toBeTrue();
});

test('tenant is not on trial when trial date is in past', function () {
    $tenant = Tenant::factory()->create([
        'trial_ends_at' => now()->subDays(10),
    ]);

    expect($tenant->isOnTrial())->toBeFalse();
});

test('tenant is not on trial when trial date is null', function () {
    $tenant = Tenant::factory()->create([
        'trial_ends_at' => null,
    ]);

    expect($tenant->isOnTrial())->toBeFalse();
});

test('tenant get user limit returns correct limit for plan', function () {
    $free = Tenant::factory()->create(['plan' => 'free']);
    $basic = Tenant::factory()->create(['plan' => 'basic']);
    $pro = Tenant::factory()->create(['plan' => 'pro']);
    $enterprise = Tenant::factory()->create(['plan' => 'enterprise']);
    $unknown = Tenant::factory()->create(['plan' => 'unknown']);

    expect($free->getUserLimit())->toBe(3)
        ->and($basic->getUserLimit())->toBe(10)
        ->and($pro->getUserLimit())->toBe(25)
        ->and($enterprise->getUserLimit())->toBe(PHP_INT_MAX)
        ->and($unknown->getUserLimit())->toBe(3);
});

test('tenant get contact limit returns correct limit for plan', function () {
    $free = Tenant::factory()->create(['plan' => 'free']);
    $basic = Tenant::factory()->create(['plan' => 'basic']);
    $pro = Tenant::factory()->create(['plan' => 'pro']);
    $enterprise = Tenant::factory()->create(['plan' => 'enterprise']);
    $unknown = Tenant::factory()->create(['plan' => 'unknown']);

    expect($free->getContactLimit())->toBe(50)
        ->and($basic->getContactLimit())->toBe(500)
        ->and($pro->getContactLimit())->toBe(PHP_INT_MAX)
        ->and($enterprise->getContactLimit())->toBe(PHP_INT_MAX)
        ->and($unknown->getContactLimit())->toBe(50);
});

test('tenant get user count returns correct count', function () {
    $tenant = Tenant::factory()->create();
    User::factory()->count(3)->create(['tenant_id' => $tenant->id]);

    expect($tenant->getUserCount())->toBe(3);
});

test('tenant can add user returns true when under limit', function () {
    $tenant = Tenant::factory()->create(['plan' => 'free']);
    User::factory()->count(2)->create(['tenant_id' => $tenant->id]);

    expect($tenant->canAddUser())->toBeTrue();
});

test('tenant can add user returns false when at limit', function () {
    $tenant = Tenant::factory()->create(['plan' => 'free']);
    User::factory()->count(3)->create(['tenant_id' => $tenant->id]);

    expect($tenant->canAddUser())->toBeFalse();
});

test('tenant get remaining user slots returns correct count', function () {
    $tenant = Tenant::factory()->create(['plan' => 'free']);
    User::factory()->count(1)->create(['tenant_id' => $tenant->id]);

    expect($tenant->getRemainingUserSlots())->toBe(2);
});

test('tenant get remaining user slots returns zero when at limit', function () {
    $tenant = Tenant::factory()->create(['plan' => 'free']);
    User::factory()->count(3)->create(['tenant_id' => $tenant->id]);

    expect($tenant->getRemainingUserSlots())->toBe(0);
});

test('tenant get remaining user slots returns zero when over limit', function () {
    $tenant = Tenant::factory()->create(['plan' => 'free']);
    User::factory()->count(5)->create(['tenant_id' => $tenant->id]);

    expect($tenant->getRemainingUserSlots())->toBe(0);
});

test('tenant get contact count returns correct count', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    Contact::factory()->count(5)->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    expect($tenant->getContactCount())->toBe(5);
});

test('tenant can add contact returns true when under limit', function () {
    $tenant = Tenant::factory()->create(['plan' => 'free']);
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    Contact::factory()->count(49)->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    expect($tenant->canAddContact())->toBeTrue();
});

test('tenant can add contact returns false when at limit', function () {
    $tenant = Tenant::factory()->create(['plan' => 'free']);
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    Contact::factory()->count(50)->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    expect($tenant->canAddContact())->toBeFalse();
});

test('deal casts value as decimal', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'value' => '12345.67',
    ]);

    expect((float) $deal->value)->toBe(12345.67);
});

test('deal casts probability as integer', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'probability' => '75',
    ]);

    expect($deal->probability)->toBe(75);
});

test('deal casts expected close date as date', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'expected_close_date' => '2026-12-31',
    ]);

    expect($deal->expected_close_date)->toBeInstanceOf(\Carbon\CarbonInterface::class);
});

test('deal days in stage is zero for today', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'updated_at' => now(),
    ]);

    expect($deal->daysInStage())->toBe(0);
});

test('deal days in stage is positive for past dates', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'updated_at' => now()->subDays(10),
    ]);

    expect($deal->daysInStage())->toBe(10);
});

test('activity belongs to deal and user', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id, 'contact_id' => $contact->id]);
    $activity = Activity::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'deal_id' => $deal->id,
    ]);

    expect($activity->deal)->toBeInstanceOf(Deal::class)
        ->and($activity->user)->toBeInstanceOf(User::class)
        ->and($activity->contact)->toBeInstanceOf(Contact::class);
});

test('activity is overdue returns true when not done and due date in past', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $activity = Activity::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'due_date' => now()->subDays(1),
        'is_done' => false,
    ]);

    expect($activity->isOverdue())->toBeTrue();
});

test('activity is overdue returns false when done', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $activity = Activity::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'due_date' => now()->subDays(1),
        'is_done' => true,
    ]);

    expect($activity->isOverdue())->toBeFalse();
});

test('activity is overdue returns false when due date in future', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $activity = Activity::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'due_date' => now()->addDays(1),
        'is_done' => false,
    ]);

    expect($activity->isOverdue())->toBeFalse();
});

test('activity is overdue returns false when due date is null', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $activity = Activity::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'due_date' => null,
        'is_done' => false,
    ]);

    expect($activity->isOverdue())->toBeFalse();
});

test('activity casts is done as boolean', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $activity = Activity::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'is_done' => 1,
    ]);

    expect($activity->is_done)->toBeTrue();
});

test('activity casts duration minutes as integer', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $activity = Activity::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'duration_minutes' => '45',
    ]);

    expect($activity->duration_minutes)->toBe(45);
});

test('contact casts tags as array', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'tags' => ['vip', 'hot'],
    ]);

    expect($contact->tags)->toBe(['vip', 'hot']);
});

test('contact accepts empty tags array', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'tags' => [],
    ]);

    expect($contact->tags)->toBe([]);
});

test('contact casts custom fields as array', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'custom_fields' => ['birthday' => '1990-01-01', 'favorite_color' => 'blue'],
    ]);

    expect($contact->custom_fields)->toBe(['birthday' => '1990-01-01', 'favorite_color' => 'blue']);
});

test('contact accepts null for optional fields', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'phone' => null,
        'company' => null,
        'website' => null,
    ]);

    expect($contact->phone)->toBeNull()
        ->and($contact->company)->toBeNull()
        ->and($contact->website)->toBeNull();
});

test('contact scope owned by returns correct contacts', function () {
    $tenant = Tenant::factory()->create();
    $user1 = User::factory()->create(['tenant_id' => $tenant->id]);
    $user2 = User::factory()->create(['tenant_id' => $tenant->id]);
    Contact::factory()->count(2)->create(['tenant_id' => $tenant->id, 'user_id' => $user1->id]);
    Contact::factory()->count(3)->create(['tenant_id' => $tenant->id, 'user_id' => $user2->id]);

    expect(Contact::ownedBy($user1->id)->count())->toBe(2)
        ->and(Contact::ownedBy($user2->id)->count())->toBe(3);
});

test('deal scope owned by returns correct deals', function () {
    $tenant = Tenant::factory()->create();
    $user1 = User::factory()->create(['tenant_id' => $tenant->id]);
    $user2 = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user1->id]);
    Deal::factory()->count(2)->create(['tenant_id' => $tenant->id, 'user_id' => $user1->id, 'contact_id' => $contact->id]);
    Deal::factory()->count(3)->create(['tenant_id' => $tenant->id, 'user_id' => $user2->id, 'contact_id' => $contact->id]);

    expect(Deal::ownedBy($user1->id)->count())->toBe(2)
        ->and(Deal::ownedBy($user2->id)->count())->toBe(3);
});

test('user belongs to tenant', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    expect($user->tenant)->toBeInstanceOf(Tenant::class)
        ->and($user->tenant->id)->toBe($tenant->id);
});

test('user can have null tenant', function () {
    $user = User::factory()->create(['tenant_id' => null]);

    expect($user->tenant)->toBeNull();
});

test('user has posts relationship', function () {
    $user = User::factory()->create();

    expect($user->posts())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('user has image generations relationship', function () {
    $user = User::factory()->create();

    expect($user->imageGenerations())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('activity can have null deal', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $activity = Activity::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'deal_id' => null,
    ]);

    expect($activity->deal)->toBeNull();
});

test('deal requires contact due to foreign key constraint', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
    ]);

    expect($deal->contact)->toBeInstanceOf(Contact::class);
});

test('deal can have null lost reason', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'lost_reason' => null,
    ]);

    expect($deal->lost_reason)->toBeNull();
});

test('deal can have null expected close date', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'expected_close_date' => null,
    ]);

    expect($deal->expected_close_date)->toBeNull();
});

test('deal value is required and cannot be null', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'value' => 5000,
    ]);

    expect($deal->value)->not->toBeNull();
});

test('deal can have zero value', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'value' => 0,
    ]);

    expect((float) $deal->value)->toBe(0.0);
});

test('deal can have zero probability', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'probability' => 0,
    ]);

    expect($deal->probability)->toBe(0);
});

test('deal can have hundred probability', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'probability' => 100,
    ]);

    expect($deal->probability)->toBe(100);
});

test('contact can have many activities', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    Activity::factory()->count(3)->create(['tenant_id' => $tenant->id, 'user_id' => $user->id, 'contact_id' => $contact->id]);

    expect($contact->activities()->count())->toBe(3);
});

test('contact can have many deals', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    Deal::factory()->count(4)->create(['tenant_id' => $tenant->id, 'user_id' => $user->id, 'contact_id' => $contact->id]);

    expect($contact->deals()->count())->toBe(4);
});

test('tenant can have many users', function () {
    $tenant = Tenant::factory()->create();
    User::factory()->count(5)->create(['tenant_id' => $tenant->id]);

    expect($tenant->users()->count())->toBe(5);
});

test('tenant can have many contacts', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    Contact::factory()->count(6)->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);

    expect($tenant->contacts()->count())->toBe(6);
});

test('tenant can have many deals', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    Deal::factory()->count(7)->create(['tenant_id' => $tenant->id, 'user_id' => $user->id, 'contact_id' => $contact->id]);

    expect($tenant->deals()->count())->toBe(7);
});

test('tenant can have many activities', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    Activity::factory()->count(8)->create(['tenant_id' => $tenant->id, 'user_id' => $user->id, 'contact_id' => $contact->id]);

    expect($tenant->activities()->count())->toBe(8);
});

test('user can have multiple roles', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $user->assignRole('Admin');
    $user->assignRole('Manager');

    expect($user->hasRole('Admin'))->toBeTrue()
        ->and($user->hasRole('Manager'))->toBeTrue()
        ->and($user->hasRole('Agent'))->toBeFalse();
});

test('activity is done casts to boolean true', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $activity = Activity::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'is_done' => true,
    ]);

    expect($activity->is_done)->toBeBool()->toBeTrue();
});

test('activity is done casts to boolean false', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $activity = Activity::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'is_done' => false,
    ]);

    expect($activity->is_done)->toBeBool()->toBeFalse();
});

test('tenant is active casts to boolean true', function () {
    $tenant = Tenant::factory()->create(['is_active' => true]);

    expect($tenant->is_active)->toBeBool()->toBeTrue();
});

test('tenant is active casts to boolean false', function () {
    $tenant = Tenant::factory()->create(['is_active' => false]);

    expect($tenant->is_active)->toBeBool()->toBeFalse();
});

test('deal uses belonngs to tenant trait', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $deal = Deal::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
    ]);

    expect($deal->tenant)->toBeInstanceOf(Tenant::class);
});

test('contact uses belongs to tenant trait', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
    ]);

    expect($contact->tenant)->toBeInstanceOf(Tenant::class);
});

test('activity uses belongs to tenant trait', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $contact = Contact::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id]);
    $activity = Activity::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'contact_id' => $contact->id,
    ]);

    expect($activity->tenant)->toBeInstanceOf(Tenant::class);
});
