<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $procedures = [
            [
                'name' => 'ミルクを作る',
                'task_id' => 1,
                'previous_procedure_id' => "3",
                'next_procedure_ids' => '4',
                'is_visible' => 1,
            ],
            [
                'name' => 'おむつを替える',
                'task_id' => 1,
                'previous_procedure_id' => null,
                'next_procedure_ids' => '3,5',
                'is_visible' => 1,
            ],
            [
                'name' => '離乳食を作る',
                'task_id' => 1,
                'previous_procedure_id' => "2",
                'next_procedure_ids' => '1',
                'is_visible' => 1,
            ],
            [
                'name' => '寝かしつける',
                'task_id' => 1,
                'previous_procedure_id' => "1,5",
                'next_procedure_ids' => null,
                'is_visible' => 1,
            ],
            [
                'name' => '搾母乳を作る',
                'task_id' => 1,
                'previous_procedure_id' => "2",
                'next_procedure_ids' => '4',
                'is_visible' => 1,
            ],
        ];

        foreach ($procedures as $procedure) {
            DB::table('procedures')->insert($procedure);
        }
    }
}
