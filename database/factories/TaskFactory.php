<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Enums\TaskPriority;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

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
    public function definition()
    {

        $difficulties = [ 1, 2, 3, 5, 8, 13, 21 ];

        return [
            'id' => fake()->uuid,
            'title' => fake()->unique()->city,
            'description' => fake()->text,
            'difficulty' => $difficulties[ array_rand( $difficulties ) ],
            'status' => TaskStatus::getRandomValue(),
            'priority' => TaskPriority::getRandomValue(),
            'assignee' => User::select('id')->inRandomOrder()->first(),
            'project_id' => Project::select('id')->inRandomOrder()->first(),
            'slug' => null,
        ];
    }
}
