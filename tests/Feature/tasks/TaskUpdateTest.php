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
use NunoMaduro\Collision\Adapters\Phpunit\State;
use Tests\TestCase;

class TaskUpdateTest extends TestCase
{

    use RefreshDatabase;

    public function test_task_update()
    {

        $token = $this->get_token();
        $task = Task::factory()
            ->for( Project::factory()->set('status', Status::OPEN))
            ->create();

        $newData = Task::factory()
            ->makeOne()
            ->only('title', 'description', 'assignee', 'difficulty', 'priority');

        $response = $this->patchJson( 'api/projects/' . $task->project_id . '/tasks/' . $task->id, $newData, $this->get_auth_header( $token ) );

        $updatedtask = Task::find( $task->id );

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
        $project = Project::factory()
            ->set( 'status', Status::OPEN )
            ->create();
        $tasks = Task::factory(2)
            ->for($project)
            ->create();

        $newData = Task::factory()
            ->set('title', $tasks->get(0)->title)
            ->makeOne()
            ->only('title', 'description', 'assignee', 'difficulty', 'priority');


        $response = $this->patchJson( 'api/projects/' . $project->id . '/tasks/' . $tasks->get(1)->id, $newData, $this->get_auth_header( $token ) );

        $response->assertStatus( 422 );

    }

    public function test_invalid_request()
    {

        $token = $this->get_token();

        $task = Task::factory()
            ->set('status', TaskStatus::CLOSE)
            ->for( Project::factory()
                ->set( 'status', Status::CLOSE ) )
            ->create();

        $newData = Task::factory()
            ->makeOne()
            ->only('assignee', 'difficulty', 'priority');

        $response = $this->patchJson( 'api/projects/' . $task->project_id . '/tasks/' . $task->id, $newData, $this->get_auth_header( $token ) );
        $response->assertStatus( 422 );

    }

    public function test_need_token()
    {

        $task = Task::factory()
            ->for( Project::factory())
            ->create();
        $response = $this->getJson( 'api/projects/' . $task->project_id . '/tasks/' . $task->id );
        $response->assertStatus( 401 );

    }

}
