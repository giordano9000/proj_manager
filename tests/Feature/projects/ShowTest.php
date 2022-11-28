<?php

namespace Tests\Feature\projects;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowTest extends TestCase
{

    public function test_valid_request()
    {

        $token = $this->get_token();
        $projectId = Project::inRandomOrder()->first()->id;

        $response = $this->getJson( 'api/projects/' . $projectId, $this->get_auth_header($token) );
        $response->assertStatus( 200 );

    }

    public function test_slug_resolution()
    {

        $token = $this->get_token();
        $projectId = Project::inRandomOrder()->first();

        $slug = Project::find($projectId)->first()->slug;
        $response = $this->getJson( 'api/projects/' . $slug, $this->get_auth_header($token) );
        $response->assertStatus( 200 );

    }

    public function test_invalid_request()
    {

        $token = $this->get_token();
        $projectId = Project::inRandomOrder()->first();

        $response = $this->getJson( 'api/projects', $this->get_auth_header($token) );
        $response->assertStatus( 422 );

    }

    public function test_need_token()
    {

        $response = $this->getJson( 'api/projects');
        $response->assertStatus( 401 );

    }

}
