<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $agent;

    private Contact $contact;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->admin->assignRole('Admin');

        $this->agent = User::factory()->create();
        $this->agent->assignRole('Agent');

        $this->contact = Contact::factory()->create(['user_id' => $this->admin->id]);
    }

    public function test_admin_can_view_activities(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/activities');
        $response->assertStatus(200);
    }

    public function test_agent_can_create_activity(): void
    {
        $contact = Contact::factory()->create(['user_id' => $this->agent->id]);

        $response = $this->actingAs($this->agent)->post('/agent/activities', [
            'contact_id' => $contact->id,
            'type' => 'Call',
            'note' => 'Discussed project requirements',
        ]);

        $this->assertDatabaseHas('activities', [
            'type' => 'Call',
            'note' => 'Discussed project requirements',
            'user_id' => $this->agent->id,
        ]);
    }

    public function test_activity_requires_type_and_note(): void
    {
        $response = $this->actingAs($this->agent)->post('/agent/activities', [
            'contact_id' => $this->contact->id,
        ]);

        $response->assertSessionHasErrors(['type', 'note']);
    }

    public function test_activity_can_be_marked_done(): void
    {
        $activity = Activity::factory()->create([
            'contact_id' => $this->contact->id,
            'user_id' => $this->admin->id,
            'is_done' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->patchJson("/admin/activities/{$activity->id}/done");

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('activities', ['id' => $activity->id, 'is_done' => true]);
    }

    public function test_overdue_activities_are_highlighted(): void
    {
        Activity::factory()->create([
            'contact_id' => $this->contact->id,
            'user_id' => $this->admin->id,
            'due_date' => now()->subDay(),
            'is_done' => false,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/activities');
        $response->assertStatus(200);
    }
}
