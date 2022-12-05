<?php

namespace Tests\Unit\services\projects;

use App\Enums\ProjectSort;
use App\Enums\Status;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectServiceSearchTest extends TestCase
{

    use RefreshDatabase;

    public function test_searchById()
    {

        $project = Project::first();
        $service = new ProjectService();
        $this->assertSame( $project->id, $service->searchById( $project->id )->id );

    }

    public function test_search_only_open()
    {

        $projects = Project::where( 'status', Status::OPEN );
        $service = new ProjectService();

        $searchParams = [
            'sortBy' => ProjectSort::ALPHA_ASC,
            'withClosed' => false,
            'onlyClosed' => false,
            'perPage' => $projects->count(),
        ];

        $this->assertSame( $projects->count(), $service->search( $searchParams )->count() );

    }

    public function test_search_only_closed()
    {

        $projects = Project::where( 'status', Status::CLOSE );
        $service = new ProjectService();

        $searchParams = [
            'sortBy' => ProjectSort::ALPHA_ASC,
            'withClosed' => false,
            'onlyClosed' => true,
            'perPage' => $projects->count(),
        ];

        $this->assertSame( $projects->count(), $service->search( $searchParams )->count() );

    }

    public function test_search_with_closed()
    {

        $projects = Project::all();
        $service = new ProjectService();

        $searchParams = [
            'sortBy' => ProjectSort::ALPHA_ASC,
            'withClosed' => true,
            'onlyClosed' => false,
            'perPage' => $projects->count(),
        ];

        $this->assertSame( $projects->count(), $service->search( $searchParams )->count() );

    }


}
