<?php

namespace Tests\Unit\services\tasks;

use App\Enums\Status;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskServiceChangeStatusTest extends TestCase
{

    use RefreshDatabase;

    public function test_task()
    {

        $task = Task::factory()
            ->set( 'status', Status::OPEN )
            ->create();
        $service = new TaskService();
        $service->changeStatus( $task->id, Status::CLOSE );

        $this->assertDatabaseHas( 'tasks', [ 'id' => $task->id, 'status' => TaskStatus::CLOSE ] );

    }

}
