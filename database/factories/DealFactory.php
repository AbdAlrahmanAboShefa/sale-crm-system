<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Deal;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DealFactory extends Factory
{
    protected $model = Deal::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'contact_id' => Contact::factory(),
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'value' => fake()->randomFloat(2, 1000, 100000),
            'currency' => 'USD',
            'stage' => fake()->randomElement(['New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won', 'Lost']),
            'probability' => fake()->numberBetween(0, 100),
            'expected_close_date' => fake()->dateTimeBetween('now', '+30 days'),
            'lost_reason' => null,
        ];
    }

    public function won(): static
    {
        return $this->state(fn (array $attributes) => ['stage' => 'Won']);
    }

    public function lost(): static
    {
        return $this->state(fn (array $attributes) => ['stage' => 'Lost', 'lost_reason' => fake()->sentence()]);
    }
}
