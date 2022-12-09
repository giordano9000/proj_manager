<?php

namespace Tests\Unit\services\tasks;

use App\Enums\Status;
use App\Enums\TaskPriority;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Project;
use App\Models\Task;

class TaskServiceStoreTest extends TestCase
{

    use RefreshDatabase;

    public function test_store_new_task()
    {

        $project = Project::factory()
            ->set( 'status', Status::OPEN )
            ->create();
        $data = Task::factory()
            ->makeOne()
            ->only( 'title', 'description', 'assignee', 'difficulty', 'priority' );

        $this->assertDatabaseMissing( 'tasks', $data );
        $service = new TaskService();
        $task = $service->store( $project->id, $data );
        $this->assertDatabaseHas( 'tasks', $data );
        $this->assertModelExists( $task );

    }

}
