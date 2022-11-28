<?php

namespace Database\Seeders;

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

        Task::create([
            'title' => 'autenticazione',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam mollis imperdiet felis in volutpat.',
            'slug' => '',
            'difficulty' => 3,
            'status' => 'open',
            'priority' => 'medium',
            'assignee' => '96bb3e08-d7a9-48ae-a31b-82654eed35bb',
            'project_id' => 1,
            'created_at' => '2021-10-26 17:14:58',
            'updated_at' => '2022-03-21 13:14:58'
        ]);
//        Task::create([
//            'title' => 'fare sito',
//            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam mollis imperdiet felis in volutpat.',
//            'difficulty' => 5,
//            'status' => 'closed',
//            'priority' => 'high',
//            'assignee' => ,
//            'project_id' => 1,
//            'created_at' => '2021-10-26 17:14:58',
//            'updated_at' => '2022-03-21 13:14:58'
//        ]);
//        Task::create([
//            'title' => 'autenticazione',
//            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam mollis imperdiet felis in volutpat.',
//            'difficulty' => 3,
//            'status' => 'open',
//            'priority' => 'medium',
//            'assignee' => 1,
//            'project_id' => 1,
//            'created_at' => '2021-10-26 17:14:58',
//            'updated_at' => '2022-03-21 13:14:58'
//        ]);
//        Task::create([
//            'title' => 'autenticazione',
//            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam mollis imperdiet felis in volutpat.',
//            'difficulty' => 3,
//            'status' => 'open',
//            'priority' => 'medium',
//            'assignee' => 1,
//            'project_id' => 1,
//            'created_at' => '2021-10-26 17:14:58',
//            'updated_at' => '2022-03-21 13:14:58'
//        ]);
//        Task::create([
//            'title' => 'autenticazione',
//            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam mollis imperdiet felis in volutpat.',
//            'difficulty' => 3,
//            'status' => 'open',
//            'priority' => 'medium',
//            'assignee' => 1,
//            'project_id' => 1,
//            'created_at' => '2021-10-26 17:14:58',
//            'updated_at' => '2022-03-21 13:14:58'
//        ]);
//        Task::create([
//            'title' => 'autenticazione',
//            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam mollis imperdiet felis in volutpat.',
//            'difficulty' => 3,
//            'status' => 'open',
//            'priority' => 'medium',
//            'assignee' => 1,
//            'project_id' => 1,
//            'created_at' => '2021-10-26 17:14:58',
//            'updated_at' => '2022-03-21 13:14:58'
//        ]);

        DB::table('tasks')->update([
            'slug' => DB::raw("CONCAT(id, '-', title)")
        ]);

        $this->command->info('Tasks table seeded!');
    }
}
