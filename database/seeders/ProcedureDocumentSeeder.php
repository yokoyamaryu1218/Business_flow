<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcedureDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $procedureDocuments = [
            [
                'procedure_id' => 1,
                'document_id' => 4,
            ],
            [
                'procedure_id' => 1,
                'document_id' => 5,
            ],
            [
                'procedure_id' => 2,
                'document_id' => 2,
            ],
            [
                'procedure_id' => 2,
                'document_id' => 3,
            ],
            [
                'procedure_id' => 3,
                'document_id' => 5,
            ],
            [
                'procedure_id' => 3,
                'document_id' => 6,
            ],
            [
                'procedure_id' => 4,
                'document_id' => 1,
            ],
            [
                'procedure_id' => 5,
                'document_id' => 4,
            ],
            [
                'procedure_id' => 5,
                'document_id' => 7,
            ],
        ];

        foreach ($procedureDocuments as $procedureDocument) {
            DB::table('procedure_documents')->insert($procedureDocument);
        }
    }
}