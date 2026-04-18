<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'company' => fake()->company(),
            'website' => fake()->url(),
            'source' => fake()->randomElement(['website', 'referral', 'social', 'cold']),
            'status' => fake()->randomElement(['Lead', 'Prospect', 'Client', 'Lost', 'Inactive']),
            'tags' => fake()->randomElements(['vip', 'hot-lead', 'follow-up', 'cold'], rand(0, 2)),
            'custom_fields' => null,
        ];
    }
}
