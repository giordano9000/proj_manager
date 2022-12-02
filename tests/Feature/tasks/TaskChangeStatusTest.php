<?php

namespace Tests\Feature;

use App\Enums\Status;
use App\Enums\TaskPriority;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskChangeStatusTest extends TestCase
{

    public function test_valid_request()
    {

        $token = $this->get_token();
        $project = Project::inRandomOrder()->where( 'status', Status::OPEN )->first();
        $difficulties = [ 1, 2, 3, 5, 8, 13, 21 ];

        $data = [
            "title" => fake()->city(),
            "description" => fake()->text,
            "assignee" => User::first()->id,
            "difficulty" => $difficulties[ array_rand( $difficulties ) ],
            "priority" => TaskPriority::getRandomValue()
        ];
        $response = $this->postJson( 'api/projects/' . $project->id . '/tasks', $data, $this->get_auth_header( $token ) );

        $task = Task::find( $response->json('id') )->first();
        $response = $this->patchJson( 'api/projects/' . $project->id . '/tasks/' . $task->id . '/close', [], $this->get_auth_header( $token ) );

        $response->assertStatus( 204 );

    }

    public function test_invalid_change()
    {

        $token = $this->get_token();
        $project = Project::where( 'status', Status::CLOSE )
            ->whereHas('tasks')
            ->with('tasks')
            ->inRandomOrder()
            ->first();

        $response = $this->patchJson( 'api/projects/' . $project->id . '/tasks/' . $project->tasks->first()->id . '/open', [], $this->get_auth_header( $token ) );

        $response->assertStatus( 400 );

    }

    public function test_invalid_request()
    {

        $token = $this->get_token();
        $task = Task::inRandomOrder()
            ->with('project')
            ->first();

        $response = $this->patchJson( 'api/projects/' . $task->project->id . '/tasks/' . $task->id . '/invalid-status', [], $this->get_auth_header( $token ) );

        $response->assertStatus( 405 );

    }

    public function test_need_token()
    {

        $task = Task::inRandomOrder()
            ->with('project')
            ->first();
        $response = $this->patchJson( 'api/projects/' . $task->project->id . '/tasks/' . $task->id . '/close', [] );
        $response->assertStatus( 401 );

    }

}
