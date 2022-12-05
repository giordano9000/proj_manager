<?php

namespace Tests\Unit\services\tasks;

use App\Enums\Status;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskServiceUpdateTest extends TestCase
{

    use RefreshDatabase;

    public function test_update_task()
    {

        $task = Task::factory()
            ->set('status', TaskStatus::OPEN)
            ->for( Project::factory()
                ->set( 'status', Status::OPEN ) )
            ->create();
        $oldData = $task->getAttributes();
        $newData = Task::factory()
            ->makeOne()
            ->only('title', 'description', 'assignee', 'difficulty', 'priority');

        $service = new TaskService();
        $service->update( $task->id, $newData );

        $this->assertDatabaseHas( 'tasks', $newData );
        $this->assertDatabaseMissing( 'tasks', $oldData );

    }

}
