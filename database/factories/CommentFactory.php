<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomDigit(),
            'conference_id' => $this->faker->randomDigit(),
            'report_id' => $this->faker->randomDigit(),
            'comment' => $this->faker->text
        ];
    }
}
