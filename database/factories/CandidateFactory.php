<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Services\Uid;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Candidate> */
class CandidateFactory extends Factory
{
    protected $model = Candidate::class;

    public function definition(): array
    {
        return [
            // Uses the real generator so factory records and records created
            // through the application never collide on a reference.
            'uid' => app(Uid::class)->next('candidate'),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+8801'.fake()->numerify('#########'),
            'date_of_birth' => fake()->dateTimeBetween('-35 years', '-18 years'),
            'gender' => fake()->randomElement(['male', 'female']),
            'nationality' => 'Bangladeshi',
            'country_of_residence' => 'Bangladesh',
            'passport_number' => strtoupper(fake()->bothify('??######')),
            'passport_expiry' => fake()->dateTimeBetween('+1 year', '+8 years'),
            'preferred_destination' => fake()->randomElement(['United Kingdom', 'Canada', 'Australia', 'USA']),
            'preferred_level' => fake()->randomElement(['Undergraduate', 'Masters', 'Foundation']),
            'preferred_subject' => fake()->randomElement(['Business', 'Computer Science', 'Nursing', 'Engineering']),
            'preferred_intake' => fake()->randomElement(['January 2026', 'May 2026', 'September 2026']),
            'status' => 'new',
            'source' => fake()->randomElement(['Facebook', 'Website', 'Referral', 'Walk-in']),
        ];
    }

    public function archived(): static
    {
        return $this->state(fn () => ['archived_at' => now()]);
    }
}
