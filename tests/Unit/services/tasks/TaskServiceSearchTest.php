<?php

namespace Tests\Unit\services\tasks;

use App\Enums\ProjectSort;
use App\Enums\Status;
use App\Models\Task;
use App\Models\Project;
use App\Services\TaskService;
use Tests\TestCase;

class TaskServiceSearchTest extends TestCase
{

    public function test_searchById()
    {

        $task = Task::first();
        $service = new TaskService();
        $this->assertSame( $task->id, $service->searchById( $task->id )['id'] );

    }

    public function test_search_only_open()
    {

        $project = Project::whereHas('closedTasks')->whereHas('unclosedTasks')->first();
        $tasks = Task::where( 'status', Status::OPEN )->where('project_id', $project->id);
        $service = new TaskService();

        $searchParams = [
            'sortBy' => ProjectSort::ALPHA_ASC,
            'withClosed' => false,
            'onlyClosed' => false,
            'perPage' => $tasks->count(),
        ];

        $this->assertSame( $tasks->count(), $service->search( $project->id, $searchParams )->count() );

    }

    public function test_search_only_closed()
    {

        $project = Project::whereHas('closedTasks')->whereHas('unclosedTasks')->first();
        $tasks = Task::where( 'status', Status::CLOSE )->where('project_id', $project->id);
        $service = new TaskService();

        $searchParams = [
            'sortBy' => ProjectSort::ALPHA_ASC,
            'withClosed' => false,
            'onlyClosed' => true,
            'perPage' => $tasks->count(),
        ];

        $this->assertSame( $tasks->count(), $service->search( $project->id, $searchParams )->count() );

    }

    public function test_search_with_closed()
    {

        $project = Project::whereHas('closedTasks')->whereHas('unclosedTasks')->first();
        $tasks = Task::where('project_id', $project->id);
        $service = new TaskService();

        $searchParams = [
            'sortBy' => ProjectSort::ALPHA_ASC,
            'withClosed' => true,
            'onlyClosed' => false,
            'perPage' => $tasks->count(),
        ];

        $this->assertSame( $tasks->count(), $service->search( $project->id, $searchParams )->count() );

    }

}
