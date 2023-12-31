<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Document;
use App\Models\ProcedureDocument;
use App\Models\ProcedureApprovals;
use App\Models\Routine;
use App\Models\Procedure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProcedureRequest;
use App\Http\Requests\UpdateProcedureRequest;
use App\Services\ProcedureService;
use App\Services\RoutineService;
use App\Services\PaginationService;

class ProcedureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $title = "手順新規登録";
        $task = Task::findOrFail($id);
        $procedure_list = Procedure::whereNotNull('approver_id')->get();
        $documents_list = Document::whereNotNull('approver_id')->get();

        return view('procedures.create', compact('title', 'task', 'procedure_list', 'documents_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function procedure_create()
    {
        $title = "手順新規登録";
        $task_list = Task::all();
        $procedure_list = Procedure::whereNotNull('approver_id')->get();
        $documents_list = Document::whereNotNull('approver_id')->get();

        return view('tasks.procedures.create', compact('title', 'task_list', 'procedure_list', 'documents_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProcedureRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $task_id = $request->input('task_id');

        $procedureSV = new ProcedureService;
        $documents = $procedureSV->checkDocuments($request->input('document_id'));

        if ($documents === false) {
            return redirect()->back()->withErrors(['document' => '関連するマニュアルが選択されていません。']);
        }

        $procedureSV->createProcedure(
            $request->input('name'),
            $task_id,
            $request->input('is_visible'),
            $documents
        );

        return (Auth::user()->role !== 9)
            ? redirect()->route('task.edit', ['task' => $task_id])
            : redirect()->route('approval.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProcedureRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function procedure_store(StoreProcedureRequest $request)
    {
        $procedureSV = new ProcedureService;
        $documents = $procedureSV->checkDocuments($request->input('document_id'));

        if ($documents === false) {
            return redirect()->back()->withErrors(['document' => '関連するマニュアルが選択されていません。']);
        }

        $procedureSV->createProcedure(
            $request->input('name'),
            $request->input('task_id'),
            $request->input('is_visible'),
            $documents
        );

        return (Auth::user()->role !== 9)
            ? redirect()->route('task.index')
            : redirect()->route('approval.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $title = "検索結果";
        $search = $request->input('procedure_search');

        $search_list = [];
        $procedurePage = $request->query('procedure_page', 1);

        if (!empty($search)) {
            $procedures = Procedure::leftJoin('tasks', 'procedures.task_id', '=', 'tasks.id')
                ->select('procedures.id', 'procedures.name', 'procedures.is_visible', 'tasks.name as task_name', 'procedures.task_id')
                ->where(function ($query) use ($search) {
                    $query->where('procedures.name', 'like', '%' . $search . '%');
                })
                ->groupBy('procedures.id', 'procedures.name', 'procedures.is_visible', 'tasks.name', 'procedures.task_id')
                ->paginate(10, ['*'], 'procedure_page', $procedurePage);
            $procedures->appends(['procedure_search' => $search]); // 検索条件をページネーションリンクに追加
            $search_list = $procedures;
        }

        return view('tasks.procedures.search', compact('title', 'search', 'search_list'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function procedure_search(Request $request, $id)
    {
        $title = "検索結果";
        $task_id = $id;
        $search = $request->input('procedure_search');

        $search_list = [];
        $procedurePage = $request->query('procedure_page', 1);

        if (!empty($search)) {
            $procedures = Procedure::leftJoin('tasks', 'procedures.task_id', '=', 'tasks.id')
                ->select('procedures.id', 'procedures.name', 'procedures.is_visible', 'procedures.task_id')
                ->where(function ($query) use ($search, $id) {
                    $query->where('procedures.name', 'like', '%' . $search . '%');
                    $query->where('tasks.id', '=', $id);
                })
                ->groupBy('procedures.id', 'procedures.name', 'procedures.is_visible', 'tasks.name', 'procedures.task_id')
                ->paginate(10, ['*'], 'procedure_page', $procedurePage);
            $procedures->appends(['procedure_search' => $search]); // 検索条件をページネーションリンクに追加
            $search_list = $procedures;
        }

        return view('procedures.search', compact('title', 'search', 'task_id', 'search_list'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Procedure  $procedure
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id1, $id2)
    {
        $title = "手順詳細";
        $task = Task::findOrFail($id1);
        $procedures = Procedure::findOrFail($id2);

        $pagination = 5;
        $routinePage = $request->query('page', 1);

        if (!$procedures) {
            abort(404);
        };

        $procedureSV = new ProcedureService;
        $previousProcedureIds = $procedureSV->separateCharacters($procedures->previous_procedure_id); // 前の手順
        $nextProcedureIds = $procedureSV->separateCharacters($procedures->next_procedure_ids); // 次の手順

        $procedure_list = Procedure::whereNotNull('approver_id')->get();
        $documents_list = Document::whereNotNull('approver_id')->get();

        $my_documents = Document::join('procedure_documents', 'documents.id', '=', 'procedure_documents.document_id')
            ->where('procedure_documents.procedure_id', $procedures->id)
            ->get();

        // Routineの取得
        $routines = Routine::where('task_id', $task->id)->whereNotNull('approver_id')->get();

        $matchingRoutines = [];
        $targetProcedureId = $procedures->id;

        foreach ($routines as $routine) {
            // next_procedure_idsを配列に変換
            $previousIds = explode(',', $routine->previous_procedure_id);
            $nextIdsOption1 = explode(',', $routine->next_procedure_ids);
            $nextIdsOption2 =  explode(',', $routine->next_procedure_id);

            // previous_procedure_idまたはnext_procedure_idに一致するIDが含まれているかチェック
            if (in_array($targetProcedureId, $previousIds) || in_array($targetProcedureId, $nextIdsOption1) || in_array($targetProcedureId, $nextIdsOption2)) {
                $matchingRoutines[] = $routine;
            }
        }

        $routineSV = new RoutineService;
        $sortedProcedures = $routineSV->sortProcedures($matchingRoutines);

        $paginationSV = new PaginationService;
        $sortedProcedures = $paginationSV->paginateResults($sortedProcedures, $pagination, $routinePage);
        $sortedProcedures->appends(['page' => $routinePage]);

        return view('procedures.edit', compact(
            'title',
            'task',
            'procedures',
            'previousProcedureIds',
            'nextProcedureIds',
            'procedure_list',
            'documents_list',
            'my_documents',
            'sortedProcedures',
            'matchingRoutines',
            'routinePage',
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProcedureRequest  $request
     * @param  \App\Models\Procedure  $procedure
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProcedureRequest $request, $id1, $id2)
    {
        $name = $request->input('name');

        $previousProcedureId = $request->input('previous_procedure_id');
        $previousProcedureIds = implode(',', $previousProcedureId);

        $nextProcedureId =  $request->input('next_procedure_id');
        $nextProcedureIds = implode(',', $nextProcedureId);

        $is_visible = $request->input('is_visible');

        $procedureSV = new ProcedureService;
        $documents = $procedureSV->checkDocuments($request->input('document_id'));

        if ($documents === false) {
            return redirect()->back()->withErrors(['documents' => '関連マニュアルの選択に誤りがあります。']);
        }

        try {
            DB::beginTransaction();

            // 手順に関連付けられた文書の取得
            $procedureDocuments = ProcedureDocument::where('procedure_id', $id2)->get();

            // 渡された文書IDを1つずつ処理
            foreach ($documents as $documentId) {
                // 指定された文書IDに対応するProcedureDocumentを取得
                $procedureDocument = $procedureDocuments->where('document_id', $documentId)->first();

                // 文書が見つかった場合は既に手順に関連付けられているため、$procedureDocumentsから削除
                if ($procedureDocument) {
                    $procedureDocuments = $procedureDocuments->reject(function ($item) use ($documentId) {
                        return $item->document_id == $documentId;
                    });
                }
                // 文書が見つからなかった場合は手順に新しい文書を関連付ける
                else {
                    ProcedureDocument::create([
                        'procedure_id' => $id2,
                        'document_id' => $documentId,
                    ]);
                }
            }

            // 手順から削除された文書に対応するProcedureDocumentを削除
            foreach ($procedureDocuments as $procedureDocument) {
                $procedureDocument->delete();
            }

            $procedures = Procedure::findOrFail($id2);
            $procedures->name = $name;
            $procedures->previous_procedure_id  = $previousProcedureIds;
            $procedures->next_procedure_ids  = $nextProcedureIds;
            $procedures->is_visible  = $is_visible;
            $procedures->updated_at = Carbon::now();
            $procedures->save();

            DB::commit();

            session()->flash('status', '更新完了');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
            // session()->flash('status', '更新エラー');
        }
        return redirect()->route('task.edit', ['task' => $id1]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Procedure  $procedure
     * @return \Illuminate\Http\Response
     */
    public function destroy($id1, $id2)
    {
        $procedure = Procedure::findOrFail($id2);

        DB::beginTransaction();

        try {
            // 関連する routines レコードを削除
            $procedure->procedureDocument()->delete();
            ProcedureApprovals::where('procedure_id', $procedure->id)->delete();

            // Procedure の削除
            $procedure->delete();

            DB::commit();
            session()->flash('status', '削除完了');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('alert', '削除エラー');
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return redirect()->route('task.edit', ['task' => $id1]);
    }
}
