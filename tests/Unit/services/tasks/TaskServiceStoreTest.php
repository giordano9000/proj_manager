<?php

namespace Tests\Unit\services\tasks;

use App\Enums\Status;
use App\Enums\TaskPriority;
use App\Models\User;
use App\Services\TaskService;
use Tests\TestCase;
use App\Models\Project;

class TaskServiceStoreTest extends TestCase
{

    public function test_store_new_project()
    {

        $project = Project::where('status', Status::OPEN)->first();
        $difficulties = [ 1, 2, 3, 5, 8, 13, 21 ];
        $data = [
            "title" => fake()->password,
            "description" => fake()->text,
            "assignee" => User::inRandomOrder()->first()->id,
            "difficulty" => $difficulties[ array_rand( $difficulties ) ],
            "priority" => TaskPriority::getRandomValue()
        ];
        $service = new TaskService();
        $service->store( $project->id, $data );

        $this->assertDatabaseHas( 'tasks', $data );

    }

}
