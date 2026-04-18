<?php

use App\Models\Activity;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Tenant;
use App\Models\User;

beforeEach(function () {
    $this->seed(\Database\Seeders\RoleSeeder::class);

    $this->tenantA = Tenant::factory()->create();
    $this->tenantB = Tenant::factory()->create();

    $this->userA = User::factory()->create(['tenant_id' => $this->tenantA->id]);
    $this->userA->assignRole('Agent');

    $this->contactA = Contact::factory()->create([
        'tenant_id' => $this->tenantA->id,
        'user_id' => $this->userA->id,
    ]);

    $this->contactB = Contact::factory()->create([
        'tenant_id' => $this->tenantB->id,
        'user_id' => $this->userA->id,
    ]);
});

test('user cannot create activity for contact in different tenant', function () {
    $response = $this->actingAs($this->userA)->post('/agent/activities', [
        'contact_id' => $this->contactB->id,
        'type' => 'Call',
        'note' => 'Test cross-tenant activity',
    ]);

    $response->assertSessionHasErrors('contact_id');
    expect(Activity::where('note', 'Test cross-tenant activity')->exists())->toBeFalse();
});

test('user cannot create activity for deal in different tenant', function () {
    $dealA = Deal::factory()->create([
        'tenant_id' => $this->tenantA->id,
        'user_id' => $this->userA->id,
        'contact_id' => $this->contactA->id,
    ]);

    $dealB = Deal::factory()->create([
        'tenant_id' => $this->tenantB->id,
        'user_id' => $this->userA->id,
        'contact_id' => $this->contactB->id,
    ]);

    $response = $this->actingAs($this->userA)->post('/agent/activities', [
        'contact_id' => $this->contactA->id,
        'deal_id' => $dealB->id,
        'type' => 'Call',
        'note' => 'Test cross-tenant deal',
    ]);

    $response->assertSessionHasErrors('deal_id');
    expect(Activity::where('note', 'Test cross-tenant deal')->exists())->toBeFalse();
});

test('user can create activity for contact in same tenant', function () {
    $response = $this->actingAs($this->userA)->post('/agent/activities', [
        'contact_id' => $this->contactA->id,
        'type' => 'Call',
        'note' => 'Valid same-tenant activity',
    ]);

    expect(Activity::where('note', 'Valid same-tenant activity')->exists())->toBeTrue();
});

test('user can create activity for deal in same tenant', function () {
    $dealA = Deal::factory()->create([
        'tenant_id' => $this->tenantA->id,
        'user_id' => $this->userA->id,
        'contact_id' => $this->contactA->id,
    ]);

    $response = $this->actingAs($this->userA)->post('/agent/activities', [
        'contact_id' => $this->contactA->id,
        'deal_id' => $dealA->id,
        'type' => 'Call',
        'note' => 'Valid same-tenant deal activity',
    ]);

    expect(Activity::where('note', 'Valid same-tenant deal activity')->exists())->toBeTrue();
});
