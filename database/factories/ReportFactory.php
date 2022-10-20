<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'thema' => $this->faker->text,
            'start_time' => $this->faker->date(),
            'end_time' => $this->faker->date(),
            'description' => $this->faker->text,
            'presentation' => $this->faker->text,
            'duration' => $this->faker->randomDigit(),
            'category_id' => $this->faker->randomDigit(),
            'conference_id' => $this->faker->randomDigit(),
            'user_id' => $this->faker->randomDigit(),
            'zoom_meeting_id' => null
        ];
    }
}
