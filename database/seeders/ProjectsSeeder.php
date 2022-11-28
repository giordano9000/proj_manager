<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('projects')->delete();

        \App\Models\Project::factory(10)->create();

        DB::table('projects')->update([
            'slug' => DB::raw("CONCAT(id, '-', title)")
        ]);

        $this->command->info('Projects table seeded!');

    }
}
