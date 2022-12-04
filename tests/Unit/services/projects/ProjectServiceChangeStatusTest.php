<?php

namespace Tests\Unit\services\projects;

use App\Enums\Status;
use App\Models\Project;
use App\Services\ProjectService;
use Tests\TestCase;

class ProjectServiceChangeStatusTest extends TestCase
{

//    public function test_close_open_project()
//    {
//
//        $project = Project::where('status', Status::OPEN)
//            ->doesntHave('unclosedTasks')
//            ->first();
//        $service = new ProjectService();
//        $result = $service->changeStatus( $project->id, Status::CLOSE );
//
//        $this->assertSame( $result, TRUE );
//
//    }

    public function test_close_project_with_unclosed_tasks()
    {

        $project = Project::where('status', Status::OPEN)
            ->whereHas('unclosedTasks')
            ->first();
        $service = new ProjectService();
        $result = $service->changeStatus( $project->id, Status::CLOSE );

        $this->assertSame( $result, FALSE );

    }

    public function test_open_a_closed_project()
    {

        $project = Project::where('status', Status::CLOSE)->first();
        $service = new ProjectService();
        $result = $service->changeStatus( $project->id, Status::OPEN );

        $this->assertSame( $result, FALSE );

    }

}
