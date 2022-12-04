<?php

namespace Tests\Unit\services\tasks;

use App\Enums\Status;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Tests\TestCase;

class TaskServiceUpdateTest extends TestCase
{

    public function test_update_task()
    {

        $difficulties = [ 1, 2, 3, 5, 8, 13, 21 ];
        $task = Task::inRandomOrder()->first();
        $service = new TaskService();
        $oldData = $task->only( 'title', 'description', 'assignee', 'difficulty', 'priority' );
        $newData = [
            'title' => fake()->password,
            'description' => fake()->city,
            'assignee' => User::first()->id,
            'difficulty' => $difficulties[ array_rand( $difficulties ) ],
            'priority' => TaskPriority::getRandomValue()

        ];
        $service->update( $task->id, $newData );

        $this->assertDatabaseHas( 'tasks', $newData );
        $this->assertDatabaseMissing( 'tasks', $oldData );

    }

}
