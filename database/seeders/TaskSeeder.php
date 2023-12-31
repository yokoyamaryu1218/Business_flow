<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tasks')->insert(
            [
                [
                    'id' => 1,
                    'name' => '育児',
                    'is_visible' => 1,
                ],
                [
                    'id' => 2,
                    'name' => '教育',
                    'is_visible' => 1,
                ],
            ],
        );
    }
}