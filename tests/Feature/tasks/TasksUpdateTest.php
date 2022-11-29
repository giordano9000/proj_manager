<?php

namespace Tests\Feature\projects;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TasksUpdateTest extends TestCase
{

    public function test_project_update()
    {

        $token = $this->get_token();
        $project = Project::inRandomOrder()->first();

        $projectId = $project->id;
        $beforeTitle = $project->title;
        $beforeDescription = $project->description;

        $data = [
            'title' => fake()->city,
            'description' => fake()->text,
        ];

        $response = $this->patchJson( 'api/projects/' . $projectId, $data, $this->get_auth_header( $token ) );

        $updatedProject = Project::find($projectId)->first();
        $afterTitle = $updatedProject->title;
        $afterDescription = $updatedProject->description;

        $response->assertJsonStructure( [
            'id',
            'title',
            'description',
            'slug',
            'status',
            'open_tasks',
            'closed_tasks',
        ] );
        $response->assertStatus( 200 );
        $this->assertNotEquals( $beforeTitle, $afterTitle );
        $this->assertNotEquals( $beforeDescription, $afterDescription );

    }

    public function test_duplicated_name()
    {

        $token = $this->get_token();
        $project = Project::inRandomOrder()->first();
        $project2 = Project::inRandomOrder()->latest()->first();

        $data = [
            'title' => $project->title,
            'description' => fake()->text,
        ];

        $response = $this->patchJson( 'api/projects/' . $project2->id, $data, $this->get_auth_header( $token ) );

        $response->assertStatus( 422 );

    }

    public function test_invalid_request()
    {

        $token = $this->get_token();
        $project = Project::inRandomOrder()->first();

        $data = [
            'title' => null,
            'description' => fake()->text,
        ];

        $response = $this->patchJson( 'api/projects/' . $project->id, $data, $this->get_auth_header( $token ) );
        $response->assertStatus( 422 );

    }

    public function test_need_token()
    {

        $project = Project::inRandomOrder()->first();
        $response = $this->getJson( 'api/projects/' . $project->id );
        $response->assertStatus( 401 );

    }

}
