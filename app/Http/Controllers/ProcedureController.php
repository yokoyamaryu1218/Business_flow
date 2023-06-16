<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Procedure;
use App\Models\Document;
use App\Models\ProcedureDocument;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProcedureRequest;
use App\Http\Requests\UpdateProcedureRequest;
use App\Services\ProcedureService;

class ProcedureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $procedure_list = Procedure::all();
        $documents_list = Document::all();

        return view('procedures.create', compact('title', 'task', 'procedure_list', 'documents_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProcedureRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProcedureRequest $request)
    {
        try {
            DB::beginTransaction();

            // トランザクション内での処理
            $latestProcedure = Procedure::latest('id')->first();
            $latestProcedureId = $latestProcedure->id + 1;

            $task_id = $request->input('id');
            $name = $request->input('name');

            $previousProcedureId = $request->input('previous_procedure_id');
            $previousProcedureIds = implode(',', $previousProcedureId);

            $nextProcedureId =  $request->input('next_procedure_id');
            $nextProcedureIds = implode(',', $nextProcedureId);

            $documentIds = $request->input('document_id');

            Procedure::create([
                'id' => $latestProcedureId,
                'name' => $name,
                'task_id' => $task_id,
                'previous_procedure_id' => $previousProcedureIds,
                'next_procedure_id' => $nextProcedureIds,
            ]);

            foreach ($documentIds as $documentId) {
                ProcedureDocument::create([
                    'procedure_id' => $latestProcedureId,
                    'document_id' => $documentId,
                ]);
            }

            DB::commit();

            session()->flash('status', '登録完了');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            session()->flash('alert', '更新エラー');
        }
        return redirect()->route('task.edit', ['task' => $task_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Procedure  $procedure
     * @return \Illuminate\Http\Response
     */
    public function show(Procedure $procedure)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Procedure  $procedure
     * @return \Illuminate\Http\Response
     */
    public function edit(Procedure $procedure)
    {
        $title = "手順詳細";
        $procedures = Procedure::findOrFail($procedure->id);

        $procedureSV = new ProcedureService;
        $previousProcedureIds = $procedureSV->separateCharacters($procedures->previous_procedure_id);
        $nextProcedureIds = $procedureSV->separateCharacters($procedures->next_procedure_ids);

        $procedure_list = Procedure::all();
        $documents_list = Document::all();

        $my_documents = Document::join('procedure_documents', 'documents.id', '=', 'procedure_documents.document_id')
            ->where('procedure_documents.procedure_id', $procedures->id)
            ->get();

        return view('procedures.edit', compact('title', 'procedures', 'previousProcedureIds', 'nextProcedureIds', 'procedure_list', 'documents_list', 'my_documents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProcedureRequest  $request
     * @param  \App\Models\Procedure  $procedure
     * @return \Illuminate\Http\Response
     */
     public function update(UpdateProcedureRequest $request, Procedure $procedure)
    {
        try {
            DB::beginTransaction();

            $name = $request->input('name');

            $previousProcedureId = $request->input('previous_procedure_id');
            $previousProcedureIds = implode(',', $previousProcedureId);

            $nextProcedureId =  $request->input('next_procedure_id');
            $nextProcedureIds = implode(',', $nextProcedureId);

            $documentIds = $request->input('document_id');
            $procedureDocuments = ProcedureDocument::where('procedure_id', $procedure->id)->get();

            foreach ($documentIds as $documentId) {
                $procedureDocument = $procedureDocuments->where('document_id', $documentId)->first();
                if ($procedureDocument) {
                    $procedureDocuments = $procedureDocuments->reject(function ($item) use ($documentId) {
                        return $item->document_id == $documentId;
                    });
                } else {
                    ProcedureDocument::create([
                        'procedure_id' => $procedure->id,
                        'document_id' => $documentId,
                    ]);
                }
            }

            // $procedureDocumentsに残った要素を削除する
            foreach ($procedureDocuments as $procedureDocument) {
                $procedureDocument->delete();
            }

            $procedures = Procedure::findOrFail($procedure->id);
            $procedures->name = $name;
            $procedures->previous_procedure_id  = $previousProcedureIds;
            $procedures->next_procedure_ids  = $nextProcedureIds;
            $procedures->updated_at = Carbon::now();
            $procedures->save();

            DB::commit();

            session()->flash('status', '更新完了');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('status', '更新エラー');
        }
        return redirect()->route('task.edit', ['task' => $procedure->task_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Procedure  $procedure
     * @return \Illuminate\Http\Response
     */
    public function destroy(Procedure $procedure)
    {
        //
    }
}
