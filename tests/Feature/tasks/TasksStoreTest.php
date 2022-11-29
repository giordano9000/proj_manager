<?php

namespace Tests\Feature\projects;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TasksStoreTest extends TestCase
{

    public function test_project_creation()
    {

        $token = $this->get_token();

        $data = [
            'title' => fake()->city,
            'description' => fake()->text,
        ];

        $response = $this->postJson( 'api/projects', $data, $this->get_auth_header( $token ) );

        $response->assertJsonStructure( [
            'slug',
            'status',
            'title',
            'description',
            'id',
        ] );
        $this->assertDatabaseHas( 'projects', [ 'title' => $data[ 'title' ] ] );

        Project::where( 'title', $data[ 'title' ] )->delete();

    }

    public function test_duplicated_name()
    {

        $token = $this->get_token();

        $data = [
            'title' => fake()->city,
            'description' => fake()->text,
        ];

        $this->postJson( 'api/projects', $data, $this->get_auth_header( $token ) );
        $response = $this->postJson( 'api/projects', $data, $this->get_auth_header( $token ) );

        $response->assertStatus(422);

    }

    public function test_invalid_request()
    {

        $token = $this->get_token();

        $data = [
            'title' => null,
            'description' => '',
        ];

        $response = $this->postJson( 'api/projects', $data, $this->get_auth_header( $token ) );

        $response->assertStatus( 422 );

    }

    public function test_need_token()
    {

        $response = $this->getJson( 'api/projects');
        $response->assertStatus( 401 );

    }

}
