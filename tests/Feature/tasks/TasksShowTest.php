<?php

namespace Tests\Feature\projects;

use App\Enums\Status;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TasksShowTest extends TestCase
{

    use RefreshDatabase;

    public function test_valid_request()
    {

        $token = $this->get_token();
        $project = Project::factory()
            ->has( Task::factory( 3 ))
            ->create();
        $task = $project->tasks()->first();

        $response = $this->getJson( 'api/projects/' . $project->id . '/tasks/' . $task->id, $this->get_auth_header($token) );
        $response->assertStatus( 200 );
        $response->assertJsonStructure( [
            'id',
            'title',
            'description',
            'slug',
            'assignee',
            'difficulty',
            'priority',
            'status'
        ] );

    }

    public function test_slug_resolution()
    {

        $token = $this->get_token();
        $project = $project = Project::factory()
            ->has( Task::factory( 3 ))
            ->create();
        $task = $project->tasks()->first();
        $response = $this->getJson( 'api/projects/' . $project->slug . '/tasks/' . $task->slug, $this->get_auth_header($token) );
        $response->assertStatus( 200 );

    }

    public function test_invalid_request()
    {

        $token = $this->get_token();
        $project = Project::factory()->create();

        $response = $this->getJson( 'api/projects/' . $project->id . '/tasks/invalid_task_id', $this->get_auth_header($token) );
        $response->assertStatus( 404 );

    }

    public function test_need_token()
    {

        $project = Project::factory()
            ->has( Task::factory( 3 ))
            ->create();

        $response = $this->getJson( 'api/projects/' . $project->id . '/tasks/' . $project->tasks()->first()->id);
        $response->assertStatus( 401 );

    }

}
