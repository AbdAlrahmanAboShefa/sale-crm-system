<?php

use App\Models\Activity;
use App\Models\Contact;
use App\Models\Tenant;
use App\Models\User;

beforeEach(function () {
    $this->tenant = Tenant::factory()->create();

    $this->admin = User::factory()->create(['tenant_id' => $this->tenant->id]);
    $this->admin->assignRole('Admin');

    $this->agent = User::factory()->create(['tenant_id' => $this->tenant->id]);
    $this->agent->assignRole('Agent');

    $this->contact = Contact::factory()->create([
        'user_id' => $this->admin->id,
        'tenant_id' => $this->tenant->id,
    ]);
});

test('admin can view activities', function () {
    $response = $this->actingAs($this->admin)->get('/admin/activities');

    $response->assertStatus(200);
});

test('agent can create activity', function () {
    $contact = Contact::factory()->create([
        'user_id' => $this->agent->id,
        'tenant_id' => $this->tenant->id,
    ]);

    $response = $this->actingAs($this->agent)->post('/agent/activities', [
        'contact_id' => $contact->id,
        'type' => 'Call',
        'note' => 'Discussed project requirements',
    ]);

    expect(Activity::where('type', 'Call')->exists())->toBeTrue();
});

test('activity requires type and note', function () {
    $response = $this->actingAs($this->agent)->post('/agent/activities', [
        'contact_id' => $this->contact->id,
    ]);

    $response->assertSessionHasErrors(['type', 'note']);
});

test('activity can be marked done', function () {
    $activity = Activity::factory()->create([
        'contact_id' => $this->contact->id,
        'user_id' => $this->admin->id,
        'tenant_id' => $this->tenant->id,
        'is_done' => false,
    ]);

    $response = $this->actingAs($this->admin)
        ->patchJson("/admin/activities/{$activity->id}/done");

    $response->assertJson(['success' => true]);
    expect($activity->fresh()->is_done)->toBeTrue();
});

test('overdue activities are highlighted', function () {
    Activity::factory()->create([
        'contact_id' => $this->contact->id,
        'user_id' => $this->admin->id,
        'tenant_id' => $this->tenant->id,
        'due_date' => now()->subDay(),
        'is_done' => false,
    ]);

    $response = $this->actingAs($this->admin)->get('/admin/activities');

    $response->assertStatus(200);
});
