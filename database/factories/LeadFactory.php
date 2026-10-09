<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Services\Uid;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        return [
            'uid' => fn () => app(Uid::class)->next('lead'),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'country' => fake()->country(),
            'preferred_destination' => fake()->randomElement(['United Kingdom', 'USA', 'Canada', 'Australia', 'Europe', 'Malaysia']),
            'preferred_level' => fake()->randomElement(['Foundation', 'Undergraduate', 'Pre-Masters', 'Masters', 'PhD']),
            'preferred_subject' => fake()->randomElement(['Business', 'Computer Science', 'Engineering', 'Nursing', 'Law']),
            'status' => 'new',
        ];
    }

    public function contacted(): static
    {
        return $this->state(['status' => 'contacted', 'contacted_at' => now()]);
    }

    public function qualified(): static
    {
        return $this->state(['status' => 'qualified']);
    }

    public function converted(): static
    {
        return $this->state(['status' => 'converted', 'converted_at' => now()]);
    }

    public function lost(): static
    {
        return $this->state(['status' => 'lost']);
    }
}
