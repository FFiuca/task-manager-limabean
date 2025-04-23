<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Master\Status;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Status::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $statuses = [
            [
                'name' => 'To Do',
            ],
            [
                'name' => 'Selected for Development',
            ],
            [
                'name' => 'In Progress',
            ],
            [
                'name' => 'In Review',
            ],
            [
                'name' => 'Testing',
            ],
            [
                'name' => 'Completed',
            ],
        ];

        Status::insert($statuses);
    }
}
