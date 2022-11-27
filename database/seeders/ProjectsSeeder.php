<?php

namespace Database\Seeders;

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

        Project::create([
            'title' => 'progetto 1',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam mollis imperdiet felis in volutpat.',
            'status' => 'aperto',
            'slug' => '',
            'created_at' => '2021-10-26 17:14:58',
            'updated_at' => '2022-03-21 13:14:58'
        ]);

        Project::create([
            'title' => 'web design',
            'description' => 'progetto web',
            'status' => 'aperto',
            'slug' => '',
            'created_at' => '2019-02-12 17:14:58',
            'updated_at' => '2019-04-26 17:14:58'
        ]);
        Project::create([
            'title' => 'fotovoltaico',
            'description' => 'test fotovoltaico',
            'status' => 'aperto',
            'slug' => '',
            'created_at' => '2022-01-01 17:14:58',
            'updated_at' => '2022-07-04 17:14:58'
        ]);
        Project::create([
            'title' => 'edilizia',
            'description' => 'Dolor sit in volutpat',
            'status' => 'aperto',
            'slug' => '',
            'created_at' => '2010-03-10 17:14:58',
            'updated_at' => '2015-04-01 17:14:58'
        ]);
        Project::create([
            'title' => 'gestore casa',
            'description' => 'asdasdasd',
            'status' => 'aperto',
            'slug' => '',
            'created_at' => '2018-01-26 17:14:58',
            'updated_at' => '2018-01-26 17:14:58'
        ]);
        Project::create([
            'title' => 'urban style',
            'description' => 'descrizione stile urbano',
            'status' => 'aperto',
            'slug' => '',
            'created_at' => '2019-08-21 17:14:58',
            'updated_at' => '2019-08-21 17:14:58'
        ]);

        DB::table('projects')->update([
            'slug' => DB::raw("CONCAT(id, '-', title)")
        ]);

        $this->command->info('Projects table seeded!');

    }
}
