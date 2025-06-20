<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => 'STU' . $this->faker->unique()->numberBetween(1000, 9999),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'course' => $this->faker->randomElement([
                'Computer Science',
                'Mathematics',
                'Physics',
                'Chemistry',
                'Biology',
                'Engineering',
                'Business Administration',
                'Economics',
                'Psychology',
                'Sociology'
            ]),
            'year_level' => $this->faker->numberBetween(1, 5),
        ];
    }
}
