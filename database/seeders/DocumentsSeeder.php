<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DocumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currentDate = Carbon::now();

        $documents = [
            [
                'document_number' => 'B-100-1',
                'title' => '寝かしつけマニュアル',
                'file_name' => 'B-100-1.txt',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'document_number' => 'B-200-1',
                'title' => 'おススメのオムツはこれだ',
                'file_name' => 'B-200-1.txt',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'document_number' => 'B-200-2',
                'title' => 'オムツの変え方',
                'file_name' => 'B-200-2.txt',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'document_number' => 'B-300-1',
                'title' => 'ミルクの上げ方',
                'file_name' => 'B-300-1.txt',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'document_number' => 'B-300-2',
                'title' => '外出時におススメのミルク・離乳食',
                'file_name' => 'B-300-2.txt',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'document_number' => 'B-400-1',
                'title' => '離乳食の上げ方',
                'file_name' => 'B-400-1.txt',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'document_number' => 'B-500-1',
                'title' => '母乳をあげるお手伝いの方法',
                'file_name' => 'B-500-1.txt',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'document_number' => 'C-100-1',
                'title' => '教材を準備する重要性',
                'file_name' => 'C-100-1.txt',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'document_number' => 'C-200-1',
                'title' => '宿題のチェックのコツ',
                'file_name' => 'C-200-1.txt',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'document_number' => 'C-200-2',
                'title' => '授業を行う意義',
                'file_name' => 'C-200-2.txt',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'document_number' => 'C-300-1',
                'title' => 'レポートチェックの進め方',
                'file_name' => 'C-300-1.txt',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
            [
                'document_number' => 'C-400-1',
                'title' => '試験に向けての準備',
                'file_name' => 'C-400-1.txt',
                'is_visible' => 1,
                'approver_id' => 1000,
                'creator_id' => 1000,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ],
        ];

        foreach ($documents as $document) {
            DB::table('documents')->insert($document);
        }
    }
}
