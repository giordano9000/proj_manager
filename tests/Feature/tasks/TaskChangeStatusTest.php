<?php

namespace Tests\Feature;

use App\Enums\Status;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskChangeStatusTest extends TestCase
{

    use RefreshDatabase;

    public function test_valid_request()
    {

        $token = $this->get_token();
        $task = Task::factory()
            ->set( 'status', Status::OPEN )
            ->for( Project::factory()
                ->set( 'status', TaskStatus::OPEN ) )
            ->create();

        $response = $this->patchJson( 'api/projects/' . $task->project_id . '/tasks/' . $task->id . '/close', [], $this->get_auth_header( $token ) );

        $response->assertStatus( 204 );

    }

    public function test_invalid_change()
    {

        $token = $this->get_token();
        $project = Project::factory()
            ->set( 'status', Status::CLOSE)
            ->has( Task::factory( 3 )
                ->set( 'status', TaskStatus::CLOSE ) )
            ->create();

        $response = $this->patchJson( 'api/projects/' . $project->id . '/tasks/' . $project->tasks()->first()->id . '/open', [], $this->get_auth_header( $token ) );

        $response->assertStatus( 400 );

    }

    public function test_invalid_request()
    {

        $token = $this->get_token();
        $task = Task::inRandomOrder()
            ->with('project')
            ->first();

        $response = $this->patchJson( 'api/projects/' . $task->project->id . '/tasks/' . $task->id . '/invalid-status', [], $this->get_auth_header( $token ) );

        $response->assertStatus( 405 );

    }

    public function test_need_token()
    {

        $task = Task::inRandomOrder()
            ->with('project')
            ->first();
        $response = $this->patchJson( 'api/projects/' . $task->project->id . '/tasks/' . $task->id . '/close', [] );
        $response->assertStatus( 401 );

    }

}
