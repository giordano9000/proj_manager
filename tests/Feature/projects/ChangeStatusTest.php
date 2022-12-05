<?php

namespace Tests\Feature\projects;

use App\Enums\Status;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChangeStatusTest extends TestCase
{

    use RefreshDatabase;

    public function test_valid_request()
    {

        $token = $this->get_token();

        $project = Project::factory()
            ->set( 'status', Status::OPEN )
            ->has( Task::factory( 3 )
                ->set( 'status', TaskStatus::CLOSE ) )
            ->create();

        $response = $this->patchJson( 'api/projects/' . $project->id . '/close', [], $this->get_auth_header( $token ) );
        $response->assertStatus( 204 );

    }

    public function test_invalid_change()
    {

        $token = $this->get_token();

        $project = Project::factory()
            ->set( 'status', Status::CLOSE )
            ->create();

        $response = $this->patchJson( 'api/projects/' . $project->id . '/open', [], $this->get_auth_header( $token ) );

        $response->assertStatus( 400 );

    }

    public function test_invalid_request()
    {

        $token = $this->get_token();
        $project = Project::factory()->create();

        $response = $this->patchJson( 'api/projects/' . $project->id . '/invalid', [], $this->get_auth_header( $token ) );

        $response->assertStatus( 405 );

    }

    public function test_need_token()
    {

        $project = Project::factory()->create();
        $response = $this->patchJson( 'api/projects/' . $project->id . '/close', [] );
        $response->assertStatus( 401 );

    }

}
