<?php

namespace Tests\Unit\services\projects;

use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectServiceStoreTest extends TestCase
{

    use RefreshDatabase;

    public function test_store_new_project()
    {

        $project = new Project( [
            'title' => fake()->address,
            'description' => fake()->city,
        ] );

        $this->assertDatabaseMissing( 'projects', $project->getAttributes() );
        $service = new ProjectService();
        $storedProject = $service->store( $project );

        $this->assertSame( $storedProject->only( [ 'title', 'description' ] ), $project->only( [ 'title', 'description' ] ) );
        $this->assertModelExists( $storedProject );

    }

}
