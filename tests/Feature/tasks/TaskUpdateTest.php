<?php

namespace Tests\Feature\projects;

use App\Enums\Status;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use Tests\TestCase;

class TaskUpdateTest extends TestCase
{

    public function test_task_update()
    {

        $token = $this->get_token();
        $difficulties = [ 1, 2, 3, 5, 8, 13, 21 ];
        $task = Task::inRandomOrder()
            ->with('project')
            ->whereHas( 'project', function ( $query ) {
                $query->whereNot( 'status', Status::CLOSE );
            } )
            ->first();

        $data = [
            'title' => fake()->city,
            'description' => fake()->text,
            'status' => TaskStatus::getRandomValue(),
            'assignee' => User::inRandomOrder()->first()->id,
            'difficulty' => $difficulties[ array_rand( $difficulties ) ],
            'priority' => TaskPriority::getRandomValue()
        ];

        $response = $this->patchJson( 'api/projects/' . $task->project->id . '/tasks/' . $task->id, $data, $this->get_auth_header( $token ) );

        $updatedtask = Task::find( $task->id )->first();

        $response->assertJsonStructure( [
            'id',
            'title',
            'description',
            'slug',
            'assignee',
            'difficulty',
            'priority',
            'status'
        ] );
        $response->assertStatus( 200 );
        $this->assertNotEquals( $task->getAttributes(), $updatedtask->getAttributes() );

    }

    public function test_duplicated_name()
    {

        $token = $this->get_token();
        $difficulties = [ 1, 2, 3, 5, 8, 13, 21 ];
        $tasks = Task::inRandomOrder()
            ->with('project')
            ->whereHas('project', function( $query ) {
                $query->whereNot('status', Status::CLOSE);
            });
        $task = $tasks->first();
        $task2 = $tasks->latest()->first();

        $data = [
            'title' => $task->title,
            'description' => fake()->text,
            'status' => TaskStatus::getRandomValue(),
            'assignee' => User::inRandomOrder()->first()->id,
            'difficulty' => $difficulties[ array_rand( $difficulties ) ],
            'priority' => TaskPriority::getRandomValue()
        ];

        $response = $this->patchJson( 'api/projects/' . $task->project->id . '/tasks/' . $task2->id, $data, $this->get_auth_header( $token ) );

        $response->assertStatus( 422 );

    }

    public function test_invalid_request()
    {

        $token = $this->get_token();
        $task = Task::inRandomOrder()
            ->with('project')
            ->whereHas('project', function( $query ) {
                $query->whereNot('status', Status::CLOSE);
            })
            ->first();

        $data = [
            'title' => null,
            'description' => fake()->text,
            'status' => TaskStatus::getRandomValue(),
            'assignee' => User::inRandomOrder()->first()->id,
            'difficulty' => 4,
            'priority' => TaskPriority::getRandomValue()
        ];

        $response = $this->patchJson( 'api/projects/' . $task->project->id . '/tasks/' . $task->id, $data, $this->get_auth_header( $token ) );
        $response->assertStatus( 422 );

    }

    public function test_need_token()
    {

        $task = Task::with('project')->first();
        $response = $this->getJson( 'api/projects/' . $task->project->id . '/tasks/' . $task->id );
        $response->assertStatus( 401 );

    }

}
