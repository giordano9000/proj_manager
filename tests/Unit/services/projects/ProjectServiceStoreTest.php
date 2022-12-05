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

        $data = [
            'title' => fake()->address,
            'description' => fake()->city,
        ];
        $service = new ProjectService();
        $service->store( $data );
        $this->assertDatabaseHas( 'projects', $data );

    }

}
