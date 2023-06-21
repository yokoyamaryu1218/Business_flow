<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoutineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $routines = [
            [
                'task_id' => 1,
                'previous_procedure_id' => 2,
                'next_procedure_ids' => "3,1",
                'next_procedure_id' => 4,
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
            ],
            [
                'task_id' => 1,
                'previous_procedure_id' => 2,
                'next_procedure_ids' => "5",
                'next_procedure_id' => 4,
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
            ],
            [
                'task_id' => 2,
                'previous_procedure_id' => 7,
                'next_procedure_ids' => "8,6",
                'next_procedure_id' => 9,
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
            ],
            [
                'task_id' => 2,
                'previous_procedure_id' => 7,
                'next_procedure_ids' => "10",
                'next_procedure_id' => 9,
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
            ],
        ];

            foreach ($routines as $routine) {
                DB::table('routines')->insert($routine);
            }
    }
}
