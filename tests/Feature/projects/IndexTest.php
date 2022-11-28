<?php

namespace Tests\Feature\projects;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{

    public function test_valid_request()
    {

        $token = $this->get_token();
        $response = $this->getJson( 'api/projects?page=1&perPage=8&sortBy=alpha_desc', $this->get_auth_header($token) );
        $response->assertStatus( 200 );

    }

    public function test_invalid_request()
    {

        $token = $this->get_token();
        $response = $this->getJson( 'api/projects', $this->get_auth_header($token) );
        $response->assertStatus( 422 );

    }

    public function test_need_token()
    {

        $response = $this->getJson( 'api/projects');
        $response->assertStatus( 401 );

    }

}
