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
                'approver_id' => 1000,
                'creator_id' => 1000,
            ],
            [
                'name' => 'おむつを替える',
                'task_id' => 1,
                'previous_procedure_id' => null,
                'next_procedure_ids' => '3,5',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
            ],
            [
                'name' => '離乳食を作る',
                'task_id' => 1,
                'previous_procedure_id' => "2",
                'next_procedure_ids' => '1',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
            ],
            [
                'name' => '寝かしつける',
                'task_id' => 1,
                'previous_procedure_id' => "1,5",
                'next_procedure_ids' => null,
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
            ],
            [
                'name' => '搾母乳を作る',
                'task_id' => 1,
                'previous_procedure_id' => "2",
                'next_procedure_ids' => '4',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
            ],
            [
                'name' => '教材を準備する',
                'task_id' => 2,
                'previous_procedure_id' => "8",
                'next_procedure_ids' => '9',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
            ],
            [
                'name' => '宿題をチェックする',
                'task_id' => 2,
                'previous_procedure_id' => null,
                'next_procedure_ids' => '8,10',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
            ],
            [
                'name' => '授業を行う',
                'task_id' => 2,
                'previous_procedure_id' => "7",
                'next_procedure_ids' => '6',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
            ],
            [
                'name' => 'レポートを提出する',
                'task_id' => 2,
                'previous_procedure_id' => "6,10",
                'next_procedure_ids' => null,
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
            ],
            [
                'name' => '試験を実施する',
                'task_id' => 2,
                'previous_procedure_id' => "8",
                'next_procedure_ids' => '9',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
            ],
        ];

        foreach ($procedures as $procedure) {
            DB::table('procedures')->insert($procedure);
        }
    }
}
