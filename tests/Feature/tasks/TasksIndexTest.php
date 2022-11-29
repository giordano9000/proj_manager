<?php

namespace Tests\Feature\projects;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TasksIndexTest extends TestCase
{

    public function test_valid_request()
    {

        $token = $this->get_token();
        $project = Project::inRandomOrder()->whereHas('tasks')->first();

        $response = $this->getJson( 'api/projects/' . $project->id . '/tasks?page=1&perPage=8&sortBy=alpha_desc', $this->get_auth_header($token) );
        $response->assertStatus( 200 );
        $response->assertJsonStructure( [
            '*' => [
                'id',
                'title',
                'description',
                'slug',
                'assignee',
                'difficulty',
                'priority',
                'status'
            ]
        ] );

    }

    public function test_invalid_request()
    {

        $token = $this->get_token();
        $project = Project::inRandomOrder()->whereHas('tasks')->first();
        $response = $this->getJson( 'api/projects/' . $project->id . '/tasks', $this->get_auth_header($token) );
        $response->assertStatus( 422 );

    }

    public function test_need_token()
    {

        $project = Project::inRandomOrder()->whereHas('tasks')->first();
        $response = $this->getJson( 'api/projects/' . $project->id . '/tasks?page=1&perPage=8&sortBy=alpha_desc');
        $response->assertStatus( 401 );

    }

}
