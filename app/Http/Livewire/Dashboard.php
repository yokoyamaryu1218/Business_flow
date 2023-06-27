<?php

namespace App\Http\Livewire;

use App\Models\Task;
use App\Models\Document;
use Livewire\Component;

class Dashboard extends Component
{
    public $work_list = []; // 作業リスト
    public $document_list = []; // ドキュメントリスト

    public function mount()
    {
        $this->work_list = Task::where('is_visible', 1)->get(); // 表示可能なタスクを取得

        $this->document_list = Document::where('is_visible', 1)
            ->whereNotNull('approver_id')
            ->get(); // 承認者が存在し、表示可能なドキュメントを取得
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
