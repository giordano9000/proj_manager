<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // USERS
        DB::table('users')->delete();
         \App\Models\User::factory()->create([
             'id' => '96bb3e08-d7a9-48ae-a31b-82654eed35bb',
             'name' => 'user',
             'email' => 'user@example.com',
         ]);
        \App\Models\User::factory(5)->create();
        DB::table('users')->update( [ 'password' => Hash::make('password') ] );


        $this->call('Database\Seeders\ProjectsSeeder');
        $this->call('Database\Seeders\TasksSeeder');

    }
}
