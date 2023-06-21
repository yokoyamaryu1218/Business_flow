<?php

namespace App\Http\Livewire;

use App\Models\Procedure;
use App\Models\Document;
use Livewire\Component;

class Procedures extends Component
{
    public $procedures = [];
    public $documents = [];
    public $taskId;
    public $procedureId;
    public $documentId;
    protected $listeners = ['fetchProcedures'];
    
    public function fetchProcedures($procedureId)
    {
        $this->procedures = []; // 変数を初期化
        $this->documents = [];

        $nextProcedureIds = null;
        if ($procedureId) {
            $nextProcedureIds = explode(',', $procedureId);
        }

        if ($nextProcedureIds) {
            foreach ($nextProcedureIds as $nextProcedureId) { // $nextProcedureIdsをループ処理
                $nextProcedure = Procedure::find($nextProcedureId);
                if ($nextProcedure) {
                    $this->procedures[] = $nextProcedure;
                }
            }
        }

        $this->procedureId = $procedureId;
    }

    public function fetchDocuments($documentId)
    {
        $documents = Document::join('procedure_documents', 'documents.id', '=', 'procedure_documents.document_id')
            ->where('procedure_documents.procedure_id', $documentId)
            ->whereNotNull('documents.approver_id')
            ->get();

        $this->documents = $documents;
        $this->documentId = $documentId;
    }

    public function render()
    {
        return view('livewire.procedures');
    }
}
