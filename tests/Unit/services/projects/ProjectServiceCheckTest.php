<?php

namespace Tests\Unit\services\projects;

use App\Enums\Status;
use App\Models\Project;
use App\Services\ProjectService;
use Tests\TestCase;

class ProjectServiceCheckTest extends TestCase
{

    public function test_valid_and_modifiable_project_id()
    {

        $project = Project::where('status', Status::OPEN)->first();
        $service = new ProjectService();
        $valid = $service->isValid( $project->id );
        $modifiable = $service->isModifiable( $project->id );

        $this->assertNotSame( $valid, FALSE );
        $this->assertNotSame( $modifiable, FALSE );


    }

    public function test_valid_and_unmodifiable_project_id()
    {

        $project = Project::where('status', Status::CLOSE)->first();
        $service = new ProjectService();
        $valid = $service->isValid( $project->id );
        $modifiable = $service->isModifiable( $project->id );

        $this->assertNotSame( $valid, FALSE );
        $this->assertSame( $modifiable, FALSE );

    }

}
