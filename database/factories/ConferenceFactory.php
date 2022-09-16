<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ConferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => Str::random(20),
            'date' => $this->faker->text,
            'time' => $this->faker->text,
            'country' => $this->faker->text,
            'latitude' => $this->faker->text,
            'longitude' => $this->faker->text,
        ];
    }
}