<?php

namespace Database\Seeders;

use App\Enums\TaskPriority;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use App\Models\Task;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('tasks')->delete();
        \App\Models\Task::factory(70)->create();

        $this->command->info('Tasks table seeded!');
    }
}
