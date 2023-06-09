<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documents = [
            [
                'document_number' => 'B-100-1',
                'title' => '寝かしつけマニュアル',
                'file_name' => 'B-100-1.txt',
            ],
            [
                'document_number' => 'B-200-1',
                'title' => 'おススメのオムツはこれだ',
                'file_name' => 'B-200-1.txt',
            ],
            [
                'document_number' => 'B-200-2',
                'title' => 'オムツの変え方',
                'file_name' => 'B-200-2.txt',
            ],
            [
                'document_number' => 'B-300-1',
                'title' => 'ミルクの上げ方',
                'file_name' => 'B-300-1.txt',
            ],
            [
                'document_number' => 'B-300-2',
                'title' => '外出時におススメのミルク・離乳食',
                'file_name' => 'B-300-2.txt',
            ],
            [
                'document_number' => 'B-400-1',
                'title' => '離乳食の上げ方',
                'file_name' => 'B-400-1.txt',
            ],
            [
                'document_number' => 'B-500-1',
                'title' => '母乳をあげるお手伝いの方法',
                'file_name' => 'B-500-1.txt',
            ],
        ];

        foreach ($documents as $document) {
            DB::table('documents')->insert($document);
        }
    }
}
