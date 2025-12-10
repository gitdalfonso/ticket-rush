<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Concert>
 */
class ConcertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->catchPhrase() . ' Tour',
            'location' => fake()->city() . ' Arena',
            'date' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'total_tickets' => 100, // Un valor por defecto
            'price' => fake()->randomFloat(2, 20, 150),
        ];
    }
}
