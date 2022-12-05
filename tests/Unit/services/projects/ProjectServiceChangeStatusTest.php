<?php

namespace Tests\Unit\services\projects;

use App\Enums\Status;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectServiceChangeStatusTest extends TestCase
{

    use RefreshDatabase;

    public function test_close_open_project()
    {

        $project = Project::factory()
            ->set( 'status', Status::OPEN )
            ->has( Task::factory( 3 )
                ->set( 'status', TaskStatus::CLOSE ) )
            ->create();
        $service = new ProjectService();
        $result = $service->changeStatus( $project->id, Status::CLOSE );

        $this->assertSame( $result, TRUE );

    }

    public function test_close_project_with_unclosed_tasks()
    {

        $project = Project::factory()
            ->set( 'status', Status::OPEN )
            ->has( Task::factory( 3 )
                ->set( 'status', TaskStatus::OPEN ) )
            ->create();
        $service = new ProjectService();
        $result = $service->changeStatus( $project->id, Status::CLOSE );

        $this->assertSame( $result, FALSE );

    }

    public function test_open_a_closed_project()
    {

        $project = Project::factory()
            ->set( 'status', Status::CLOSE )
            ->has( Task::factory( 3 )
                ->set( 'status', TaskStatus::CLOSE ) )
            ->create();
        $service = new ProjectService();
        $result = $service->changeStatus( $project->id, Status::OPEN );

        $this->assertSame( $result, FALSE );

    }

}
