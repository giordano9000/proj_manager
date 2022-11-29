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

    public function test_project_creation()
    {

        $token = $this->get_token();
        $project = Project::inRandomOrder()->where( 'status', Status::OPEN )->first();
        $difficulties = [ 1, 2, 3, 5, 8, 13, 21 ];

        $data = [
            "title" => fake()->country,
            "description" => fake()->text,
            "assignee" => User::inRandomOrder()->first()->id,
            "difficulty" => $difficulties[ array_rand( $difficulties ) ],
            "priority" => TaskPriority::getRandomValue()
        ];
        $response = $this->postJson( 'api/projects/' . $project->id . '/tasks', $data, $this->get_auth_header( $token ) );

        $response->assertJsonStructure( [
            'id',
            'title',
            'description',
            'assignee',
            'difficulty',
            'priority',
            'slug'
        ] );
        $this->assertDatabaseHas( 'tasks', [ 'title' => $data[ 'title' ] ] );

        Task::where( 'title', $data[ 'title' ] )->delete();

    }

    public function test_duplicated_name()
    {

        $token = $this->get_token();
        $project = Project::inRandomOrder()->where( 'status', Status::OPEN )->first();
        $difficulties = [ 1, 2, 3, 5, 8, 13, 21 ];

        $data = [
            "title" => fake()->country,
            "description" => fake()->text,
            "assignee" => User::inRandomOrder()->first()->id,
            "difficulty" => $difficulties[ array_rand( $difficulties ) ],
            "priority" => TaskPriority::getRandomValue()
        ];
        $this->postJson( 'api/projects/' . $project->id . '/tasks', $data, $this->get_auth_header( $token ) );
        $response = $this->postJson( 'api/projects/' . $project->id . '/tasks', $data, $this->get_auth_header( $token ) );

        $response->assertStatus( 422 );

    }

    public function test_invalid_request()
    {

        $token = $this->get_token();
        $project = Project::inRandomOrder()->where( 'status', Status::OPEN )->first();

        $data = [
            'title' => null,
            'description' => '',
        ];

        $response = $this->postJson( 'api/projects/' . $project->id . '/tasks', $data, $this->get_auth_header( $token ) );

        $response->assertStatus( 422 );

    }

    public function test_need_token()
    {

        $project = Project::inRandomOrder()->where( 'status', Status::OPEN )->first();

        $response = $this->getJson( 'api/projects/' . $project->id . '/tasks' );
        $response->assertStatus( 401 );

    }

}
