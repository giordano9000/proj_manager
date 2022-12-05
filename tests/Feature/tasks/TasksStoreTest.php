<?php

namespace Tests\Feature\projects;

use App\Enums\Status;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TasksStoreTest extends TestCase
{

    use RefreshDatabase;

    public function test_project_creation()
    {

        $token = $this->get_token();
        $project = Project::factory()
            ->set( 'status', Status::OPEN )
            ->create();
        $taskData = Task::factory()
            ->for($project)
            ->makeOne()
            ->only('title', 'description', 'assignee', 'difficulty', 'priority');

        $response = $this->postJson( 'api/projects/' . $project->id . '/tasks', $taskData, $this->get_auth_header( $token ) );

        $response->assertJsonStructure( [
            'id',
            'title',
            'description',
            'assignee',
            'difficulty',
            'priority',
            'slug'
        ] );
        $this->assertDatabaseHas( 'tasks', [ 'title' => $taskData[ 'title' ] ] );

    }

    public function test_duplicated_name()
    {

        $token = $this->get_token();
        $project = Project::factory()
            ->set( 'status', Status::OPEN )
            ->create();
        $taskData = Task::factory()
            ->for($project)
            ->create()
            ->only('title', 'description', 'assignee', 'difficulty', 'priority');

        $response = $this->postJson( 'api/projects/' . $project->id . '/tasks', $taskData, $this->get_auth_header( $token ) );

        $response->assertStatus( 422 );

    }

    public function test_invalid_request()
    {

        $token = $this->get_token();
        $project = Project::factory()->create();

        $data = [
            'title' => null,
            'description' => '',
        ];

        $response = $this->postJson( 'api/projects/' . $project->id . '/tasks', $data, $this->get_auth_header( $token ) );

        $response->assertStatus( 422 );

    }

    public function test_need_token()
    {

        $project = Project::factory()->create();
        $response = $this->getJson( 'api/projects/' . $project->id . '/tasks' );
        $response->assertStatus( 401 );

    }

}
