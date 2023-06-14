<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Document;
use App\Models\Procedure;
use Illuminate\Http\Request;
use App\Services\ProcedureService;
use App\Services\DocumentService;
use App\Services\PaginationService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tasks()
    {
        $task = Task::paginate(20);
        $title = "作業一覧";
        return view('dashboard.task.index', compact('task', 'title'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function task_details($id)
    {
        $task = Task::findOrFail($id);
        $procedures = Procedure::where('task_id', $id)->get();

        $procedureSV = new ProcedureService;
        $sortedProcedures = $procedureSV->getProcedureOrder($procedures);

        $title = $task->name . "手順一覧";

        return view('dashboard.task.show', compact('title', 'task', 'procedures', 'sortedProcedures'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function procedures($id1, $id2)
    {
        $documents = Document::join('procedure_documents', 'documents.id', '=', 'procedure_documents.document_id')
            ->where('procedure_documents.procedure_id', $id1)
            ->where('procedure_documents.document_id', $id2)
            ->get();

        if (count($documents) === 0) {
            abort(404); // URLからアクセス不可にする
        };

        $manuals = Document::join('procedure_documents', 'documents.id', '=', 'procedure_documents.document_id')
            ->where('procedure_documents.procedure_id', $id1)
            ->where('procedure_documents.document_id', '!=', $id2)
            ->get();

        $procedure = Procedure::select('procedures.name', 'procedures.task_id', 'procedures.previous_procedure_id', 'procedures.next_procedure_ids', 'tasks.name AS task_name')
            ->join('tasks', 'procedures.task_id', '=', 'tasks.id')
            ->where('procedures.id', $id1)
            ->first();

        // 前後の手順を取得
        $procedureSV = new ProcedureService;
        $previousProcedureIds = $procedureSV->separateCharacters($procedure->previous_procedure_id);
        $nextProcedureIds = $procedureSV->separateCharacters($procedure->next_procedure_ids);

        // テキストファイルの内容を取得する
        $documentSV = new DocumentService;
        $fileContents = $documentSV->getContents($documents[0]->file_name);

        $title = $procedure->name . "・" . $documents[0]->title;

        return view('dashboard.procedures.show', compact('title', 'documents', 'manuals', 'procedure', 'previousProcedureIds', 'nextProcedureIds', 'fileContents'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function documents()
    {
        $documents = Document::paginate(20);
        $title = "マニュアル一覧";
        return view('dashboard.documents.index', compact('documents', 'title'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function documents_details($id)
    {
        $documents = Document::join('procedure_documents', 'documents.id', '=', 'procedure_documents.document_id')
            ->where('procedure_documents.document_id', $id)
            ->get();

        if (count($documents) === 0) {
            abort(404); // URLからアクセス不可にする
        };

        // マニュアルに紐づいている手順・作業を取得する
        $procedures = DB::table('procedures')
            ->select('procedures.id', 'procedures.name', 'tasks.name AS task_name', 'procedures.task_id', 'procedure_documents.document_id')
            ->leftJoin('procedure_documents', 'procedures.id', '=', 'procedure_documents.procedure_id')
            ->leftJoin('tasks', 'procedures.task_id', '=', 'tasks.id')
            ->where('procedure_documents.document_id', $id)
            ->get();

        // テキストファイルの内容を取得する
        $documentSV = new DocumentService;
        $fileContents = $documentSV->getContents($documents[0]->file_name);

        $title = $documents[0]->title;

        return view('dashboard.documents.show', compact('documents', 'fileContents', 'procedures', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */ public function search(Request $request)
    {
        $title = "検索結果";
        $search = $request->input('search');

        $search_list = [];
        $search_target = null;
        $pagenationSV = new PaginationService;

        if ($search) {
            $search_target = $request->input('search_target', 'all');
            $perPage = 20; // 1ページあたりの項目数

            if ($search_target === 'task') {
                $query = Task::where('name', 'like', '%' . $search . '%');
                $search_list['task'] = $pagenationSV->paginateResults($query, $perPage, $request->page);
            } else if ($search_target === 'procedure') {
                $query = Procedure::where('name', 'like', '%' . $search . '%');
                $search_list['procedure'] = $pagenationSV->paginateResults($query, $perPage, $request->page);
            } else if ($search_target === 'document') {
                $query = Document::where('title', 'like', '%' . $search . '%');
                $search_list['document'] = $pagenationSV->paginateResults($query, $perPage, $request->page);
            } else {
                $taskQuery = Task::where('name', 'like', '%' . $search . '%');
                $procedureQuery = Procedure::where('name', 'like', '%' . $search . '%');
                $documentQuery = Document::where('title', 'like', '%' . $search . '%');

                $search_list['task'] = $pagenationSV->paginateResults($taskQuery, $perPage, $request->page);
                $search_list['procedure'] = $pagenationSV->paginateResults($procedureQuery, $perPage, $request->page);
                $search_list['document'] = $pagenationSV->paginateResults($documentQuery, $perPage, $request->page);
            }
        }

        return view('dashboard.search', compact('title', 'search', 'search_target', 'search_list'));
    }
}
