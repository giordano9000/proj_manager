<?php

namespace Tests\Unit\services\tasks;

use App\Enums\Status;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Services\TaskService;
use Tests\TestCase;

class TaskServiceChangeStatusTest extends TestCase
{

    public function test_task()
    {

        $task = Task::where( 'status', Status::OPEN )->first();
        $service = new TaskService();
        $service->changeStatus( $task->id, Status::CLOSE );

        $this->assertDatabaseHas( 'tasks', [ 'id' => $task->id, 'status' => TaskStatus::CLOSE ] );

    }

}
