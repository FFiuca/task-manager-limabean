<?php

namespace Database\Seeders;

use App\Models\Epic;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        if(User::where('email', 'test@example.com')->exists()===false){
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        $this->call([
            StatusSeeder::class,
        ]);
        Epic::factory(1)->create();
        Task::factory(1)->create();
        TaskComment::factory(1)->create();

    }
}
