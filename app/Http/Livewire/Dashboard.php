<?php

namespace App\Http\Livewire;

use App\Models\Task;
use App\Models\Document;
use Livewire\Component;

class Dashboard extends Component
{
    public $work_list = [];
    public $document_list = [];

    public function mount()
    {
        $work_list = [];
        $works = Task::where('is_visible', 1)->get();
        foreach ($works as $work) {
            array_push($work_list, $work);
        }

        $document_list = [];
        $documents = Document::where('is_visible', 1)->whereNotNull('approver_id')->get();
        foreach ($documents as $document) {
            array_push($document_list, $document);
        }

        $this->work_list = $work_list;
        $this->document_list = $document_list;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
