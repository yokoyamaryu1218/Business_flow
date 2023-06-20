<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Document;
use App\Models\Routine;
use App\Models\Procedure;
use Illuminate\Http\Request;
use App\Services\ProcedureService;
use App\Services\DocumentService;
use App\Services\PaginationService;
use App\Services\RoutineService;
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
        $task = Task::where('is_visible', 1)->paginate(20);
        $title = "作業一覧";
        return view('dashboard.task.index', compact('task', 'title'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function task_details(Request $request, $id)
    {
        $proceduresPagination = 10;  // Procedure のページネーション数
        $routinesPagination = 5;     // Routine のページネーション数

        $routinePage = $request->query('routine_page', 1); // リクエストパラメータからRoutineのページ番号を取得

        $task = Task::findOrFail($id);

        // Procedure のページネーションを取得
        $procedures = Procedure::where('task_id', $task->id)
            ->where('is_visible', 1)
            ->paginate($proceduresPagination);

        $routines = Routine::where('task_id', $id)
            ->where('is_visible', 1)
            ->paginate($routinesPagination, ['*'], 'routine_page', $routinePage);

        $routineSV = new RoutineService;
        $sortedProcedures = $routineSV->sortProcedures($routines);

        $title = $task->name . "手順一覧";

        return view('dashboard.task.show', compact('title', 'task', 'procedures', 'routines', 'sortedProcedures', 'routinePage'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function procedures($id1, $id2)
    {
        // 本文中に載せるマニュアル
        $documents = Document::join('procedure_documents', 'documents.id', '=', 'procedure_documents.document_id')
            ->where('procedure_documents.procedure_id', $id1)
            ->where('procedure_documents.document_id', $id2)
            ->where('is_visible', 1)
            ->get();

        if ($documents->isEmpty()) {
            abort(404); // URLからアクセス不可にする
        }

        // 関連マニュアルを取得
        $manuals = Document::join('procedure_documents', 'documents.id', '=', 'procedure_documents.document_id')
            ->where('procedure_documents.procedure_id', $id1)
            ->where('procedure_documents.document_id', '!=', $id2)
            ->where('documents.is_visible', 1)
            ->get();

        // 手順の情報を取得
        $procedure = Procedure::select('procedures.name', 'procedures.task_id', 'procedures.previous_procedure_id', 'procedures.next_procedure_ids', 'tasks.name AS task_name', 'procedures.is_visible')
            ->join('tasks', 'procedures.task_id', '=', 'tasks.id')
            ->where('procedures.id', $id1)
            ->first();

        $previousProcedureIds = [];
        $nextProcedureIds = [];

        // 前後の手順を取得
        if ($procedure && $procedure->is_visible === 1) {
            $procedureSV = new ProcedureService;
            $previousProcedureIds = $procedureSV->separateCharacters($procedure->previous_procedure_id);
            $nextProcedureIds = $procedureSV->separateCharacters($procedure->next_procedure_ids);
        }

        // テキストファイルの内容を取得する
        $documentSV = new DocumentService;
        $fileContents = $documentSV->getContents($documents->first()->file_name);

        $title = $procedure ? $procedure->name . "・" . $documents->first()->title : '';

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
        $documents = Document::where('is_visible', 1)->paginate(10);
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
     */
    public function search(Request $request)
    {
        $title = "検索結果";
        $search = $request->input('search');
        $search_target = $request->input('search_target');

        $search_list = [];

        $taskPage = $request->query('task_page', 1);
        $procedurePage = $request->query('procedure_page', 1);
        $documentPage = $request->query('document_page', 1);

        if (!empty($search)) {
            $query = Document::where('title', 'like', '%' . $search . '%')
                ->where('is_visible', 1);

            if (is_array($search_target) && count($search_target) > 0) {
                if (in_array('task', $search_target)) {
                    $task = Task::where('name', 'like', '%' . $search . '%')
                        ->where('is_visible', 1)
                        ->paginate(10, ['*'], 'task_page', $taskPage);
                    $search_list['task'] = $task;
                }
                if (in_array('procedure', $search_target)) {
                    $procedure = Procedure::where('name', 'like', '%' . $search . '%')
                        ->where('is_visible', 1)
                        ->paginate(10, ['*'], 'procedure_page', $procedurePage);
                    $search_list['procedure'] = $procedure;
                }

                $document = $query->paginate(10, ['*'], 'document_page', $documentPage);
                $search_list['document'] = $document;
            } else {
                // 検索対象が指定されていない場合、全ての対象で検索
                $task = Task::where('name', 'like', '%' . $search . '%')
                    ->where('is_visible', 1)
                    ->paginate(10, ['*'], 'task_page', $taskPage);
                $procedure = Procedure::where('name', 'like', '%' . $search . '%')
                    ->where('is_visible', 1)
                    ->paginate(10, ['*'], 'procedure_page', $procedurePage);
                $document = $query->paginate(10, ['*'], 'document_page', $documentPage);

                $search_list['task'] = $task;
                $search_list['procedure'] = $procedure;
                $search_list['document'] = $document;
            }
        }

        return view('dashboard.search', compact('title', 'search', 'search_target', 'search_list'));
    }
}
