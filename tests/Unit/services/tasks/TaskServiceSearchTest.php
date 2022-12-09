<?php

namespace Tests\Unit\services\tasks;

use App\Enums\ProjectSort;
use App\Enums\Status;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\Project;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskServiceSearchTest extends TestCase
{

    use RefreshDatabase;

    public function test_searchById()
    {

        $task = Task::first();
        $service = new TaskService();
        $this->assertSame( $task->id, $service->searchById( $task->id )->id );

    }

    public function test_search_only_open()
    {

        $tasks = Task::factory( 10 )
            ->for( Project::factory()
                ->set( 'status', Status::OPEN ) )
            ->create();

        $openTasks = $tasks->whereIn('status', [ TaskStatus::OPEN, TaskStatus::BLOCK ] );
        $service = new TaskService();
        $searchParams = [
            'sortBy' => ProjectSort::ALPHA_ASC,
            'withClosed' => false,
            'onlyClosed' => false,
            'perPage' => $openTasks->count(),
        ];

        $this->assertSame( $openTasks->count(), $service->search( $tasks->first()->project_id, $searchParams )->count() );

    }

    public function test_search_only_closed()
    {

        $tasks = Task::factory( 10 )
            ->for( Project::factory()
                ->set( 'status', Status::OPEN ) )
            ->create();
        $closedTasks = $tasks->where('status', TaskStatus::CLOSE);
        $service = new TaskService();

        $searchParams = [
            'sortBy' => ProjectSort::ALPHA_ASC,
            'withClosed' => false,
            'onlyClosed' => true,
            'perPage' => $closedTasks->count(),
        ];

        $this->assertSame( $closedTasks->count(), $service->search( $tasks->first()->project_id, $searchParams )->count() );

    }

    public function test_search_with_closed()
    {

        $tasks = Task::factory( 10 )
            ->for( Project::factory()
                ->set( 'status', Status::OPEN ) )
            ->create();
        $service = new TaskService();

        $searchParams = [
            'sortBy' => ProjectSort::ALPHA_ASC,
            'withClosed' => true,
            'onlyClosed' => false,
            'perPage' => $tasks->count(),
        ];

        $this->assertSame( $tasks->count(), $service->search( $tasks->first()->project_id, $searchParams )->count() );

    }

}
