<?php

namespace Tests\Unit\services\tasks;

use App\Enums\Status;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskServiceCheckTest extends TestCase
{

    use RefreshDatabase;

    public function test_valid_task_id()
    {

        $task = Task::first();
        $service = new TaskService();
        $valid = $service->isValid( $task->id, $task->project_id );

        $this->assertNotSame( $valid, FALSE );
        $this->assertSame( $task->id, $valid->id );

    }

}
