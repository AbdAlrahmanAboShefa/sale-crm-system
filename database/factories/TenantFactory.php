<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'subdomain' => fake()->unique()->domainWord(),
            'plan' => 'pro',
            'is_active' => true,
            'trial_ends_at' => now()->addDays(30),
        ];
    }

    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'plan' => 'free',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
