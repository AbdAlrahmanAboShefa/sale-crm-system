<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        return [
            'contact_id' => Contact::factory(),
            'deal_id' => null,
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['Call', 'Meeting', 'Email', 'Task', 'Demo']),
            'note' => fake()->paragraph(),
            'outcome' => fake()->randomElement(['Positive', 'Neutral', 'Negative', null]),
            'due_date' => fake()->optional()->dateTimeBetween('now', '+7 days'),
            'duration_minutes' => fake()->optional()->numberBetween(15, 120),
            'is_done' => false,
        ];
    }

    public function done(): static
    {
        return $this->state(fn (array $attributes) => ['is_done' => true]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'due_date' => now()->subDay(),
            'is_done' => false,
        ]);
    }
}
