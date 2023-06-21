<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Document;

class Documents extends Component
{
    public $documents = [];
    public $taskId;
    public $procedureId;
    protected $listeners = ['fetchDocuments'];

    public function fetchDocuments($procedureId)
    {
        $documents = Document::join('procedure_documents', 'documents.id', '=', 'procedure_documents.document_id')
            ->where('procedure_documents.procedure_id', $procedureId)
            ->where('documents.is_visible', 1)
            ->whereNotNull('documents.approver_id')
            ->get();

        $this->documents = $documents;
        $this->procedureId = $procedureId;
    }

    public function closeModal()
    {
        $this->documents = [];
        $this->emit('closeModal');
    }


    public function render()
    {
        return view('livewire.documents');
    }
}
