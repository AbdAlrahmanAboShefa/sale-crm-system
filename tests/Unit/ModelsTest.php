<?php

namespace Tests\Unit;

use App\Models\Contact;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $contact = Contact::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $contact->user);
        $this->assertEquals($user->id, $contact->user->id);
    }

    public function test_deal_belongs_to_contact(): void
    {
        $contact = Contact::factory()->create();
        $deal = Deal::factory()->create(['contact_id' => $contact->id]);

        $this->assertInstanceOf(Contact::class, $deal->contact);
        $this->assertEquals($contact->id, $deal->contact->id);
    }

    public function test_contact_can_be_soft_deleted(): void
    {
        $contact = Contact::factory()->create();
        $contact->delete();

        $this->assertSoftDeleted('contacts', ['id' => $contact->id]);
        $this->assertNull(Contact::find($contact->id));
        $this->assertNotNull(Contact::withTrashed()->find($contact->id));
    }

    public function test_deal_can_be_soft_deleted(): void
    {
        $contact = Contact::factory()->create();
        $deal = Deal::factory()->create(['contact_id' => $contact->id]);
        $deal->delete();

        $this->assertSoftDeleted('deals', ['id' => $deal->id]);
    }

    public function test_user_has_roles(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Admin');

        $this->assertTrue($user->hasRole('Admin'));
        $this->assertFalse($user->hasRole('Agent'));
    }

    public function test_contact_has_tags_as_array(): void
    {
        $contact = Contact::factory()->create([
            'tags' => ['vip', 'hot-lead'],
        ]);

        $this->assertIsArray($contact->tags);
        $this->assertContains('vip', $contact->tags);
    }
}
