<?php

namespace Tests\Unit\services\projects;

use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Project;

class ProjectServiceUpdateTest extends TestCase
{

    use RefreshDatabase;

    public function test_update_project()
    {

        $project = Project::inRandomOrder()->first();
        $service = new ProjectService();
        $oldData = $project->only('title', 'description');
        $newData = [
            'title' => fake()->password,
            'description' => fake()->city,
        ];
        $service->update( $project->id, $newData );

        $this->assertDatabaseHas( 'projects', $newData );
        $this->assertDatabaseMissing( 'projects', $oldData );

    }

}
