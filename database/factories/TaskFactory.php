<?php

namespace Database\Factories;

use App\Models\Epic;
use App\Models\Master\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'description' => fake()->paragraphs(random_int(1, 3), true),
            'status_id' => Status::all()->random(1)->first()->id,
            'epic_id' => Epic::all()->random(1)->first()->id,
            'created_by' => User::all()->random(1)->first()->id,
            'assign_user_id' => random_int(0, 1) ? User::all()->random(1)->first()->id : null,
            'report_to_user_id' => User::all()->random(1)->first()->id,
            'due_date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
        ];
    }
}
