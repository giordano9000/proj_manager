<?php

namespace Tests\Unit\services\projects;

use App\Enums\Status;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectServiceCheckTest extends TestCase
{

    use RefreshDatabase;

    public function test_valid_and_modifiable_project_id()
    {

        $project = Project::factory()
            ->set( 'status', Status::OPEN )
            ->create();
        $service = new ProjectService();
        $valid = $service->isValid( $project->id );
        $modifiable = $service->isModifiable( $project->id );

        $this->assertNotSame( $valid, FALSE );
        $this->assertNotSame( $modifiable, FALSE );


    }

    public function test_valid_and_unmodifiable_project_id()
    {

        $project = Project::factory()
            ->set( 'status', Status::CLOSE )
            ->create();
        $service = new ProjectService();
        $valid = $service->isValid( $project->id );
        $modifiable = $service->isModifiable( $project->id );

        $this->assertNotSame( $valid, FALSE );
        $this->assertSame( $modifiable, FALSE );

    }

}
