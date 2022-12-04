<?php

namespace Tests\Feature\projects;

use App\Enums\Status;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class ChangeStatusTest extends TestCase
{

    public function test_valid_request()
    {

        $token = $this->get_token();
        $project = Project::where('status', Status::OPEN)
            ->doesntHave('unclosedTasks')
            ->first();

        $response = $this->patchJson( 'api/projects/' . $project->id . '/close', [], $this->get_auth_header( $token ) );
        $response->assertStatus( 204 );

    }

    public function test_invalid_change()
    {

        $token = $this->get_token();
        $project = Project::where( 'status', Status::CLOSE )->inRandomOrder()->first();

        $response = $this->patchJson( 'api/projects/' . $project->id . '/open', [], $this->get_auth_header( $token ) );

        $response->assertStatus( 400 );

    }

    public function test_invalid_request()
    {

        $token = $this->get_token();
        $project = Project::inRandomOrder()->first();

        $response = $this->patchJson( 'api/projects/' . $project->id . '/invalid', [], $this->get_auth_header( $token ) );

        $response->assertStatus( 405 );

    }

    public function test_need_token()
    {

        $project = Project::inRandomOrder()->first();
        $response = $this->patchJson( 'api/projects/' . $project->id . '/close', [] );
        $response->assertStatus( 401 );

    }

}
