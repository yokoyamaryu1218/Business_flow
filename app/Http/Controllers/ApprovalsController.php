
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Document;
use App\Models\Procedure;
use App\Models\Routine;
use App\Models\ProcedureDocument;
use App\Models\DocumentApprovals;
use App\Models\ProcedureApprovals;
use App\Models\RoutineApprovals;
use App\Http\Requests\UpdateDocumentApprovalsRequest;
use App\Http\Requests\UpdateProcedureApprovalsRequest;
use App\Http\Requests\UpdateRoutineApprovalsRequest;
use App\Services\DocumentService;
use App\Services\RoutineService;

class ApprovalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documentPage = request()->query('document_page', 1);
        $routinePage = request()->query('routine_page', 1);
        $procedurePage = request()->query('procedure_page', 1);

        $isApprover = Auth::user()->role !== 9;

        $title = $isApprover ? "承認管理" : "申請一覧";

        if ($isApprover) {
            $documentQuery = DocumentApprovals::select('document_approvals.*', 'documents.title', 'users.name as user_name')
                ->leftJoin('documents', 'document_approvals.document_id', '=', 'documents.id')
                ->leftJoin('users', 'document_approvals.creator_id', '=', 'users.employee_number')
                ->where('document_approvals.approved', 0);

            $routineQuery = RoutineApprovals::select('routine_approvals.*', 'users.name as user_name')
                ->leftJoin('users', 'routine_approvals.creator_id', '=', 'users.employee_number')
                ->where('routine_approvals.approved', 0);

            $procedureQuery = ProcedureApprovals::select('procedure_approvals.*', 'procedures.name', 'users.name as user_name')
                ->leftJoin('users', 'procedure_approvals.creator_id', '=', 'users.employee_number')
                ->leftJoin('procedures', 'procedure_approvals.procedure_id', '=', 'procedures.id')
                ->where('procedure_approvals.approved', 0);
        } else {
            $documentQuery = DocumentApprovals::select('document_approvals.*', 'documents.title', 'users.name as user_name')
                ->leftJoin('documents', 'document_approvals.document_id', '=', 'documents.id')
                ->leftJoin('users', 'document_approvals.creator_id', '=', 'users.employee_number')
                ->where('document_approvals.creator_id', Auth::user()->employee_number);

            $routineQuery = RoutineApprovals::select('routine_approvals.*', 'users.name as user_name')
                ->leftJoin('users', 'routine_approvals.creator_id', '=', 'users.employee_number')
                ->where('routine_approvals.creator_id', Auth::user()->employee_number);

            $procedureQuery = ProcedureApprovals::select('procedure_approvals.*', 'procedures.name', 'users.name as user_name')
                ->leftJoin('users', 'procedure_approvals.creator_id', '=', 'users.employee_number')
                ->leftJoin('procedures', 'procedure_approvals.procedure_id', '=', 'procedures.id')
                ->where('procedure_approvals.creator_id', Auth::user()->employee_number);
        }

        $documents = $documentQuery->paginate(10, ['*'], 'document_page', $documentPage);
        $routines = $routineQuery->paginate(10, ['*'], 'routine_page', $routinePage);
        $procedures = $procedureQuery->paginate(10, ['*'], 'procedure_page', $procedurePage);

        return view('approval.index', compact('title', 'documents', 'routines', 'procedures', 'documentPage', 'routinePage', 'procedurePage'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ public function approved()
    {
        if (Auth::user()->role === 9) {
            abort(404);
        }

        $documentPage = request()->query('document_page', 1);
        $routinePage = request()->query('routine_page', 1);
        $procedurePage = request()->query('procedure_page', 1);

        $title = "承認済み一覧";

        $documentQuery = DocumentApprovals::select('document_approvals.*', 'documents.title', 'users.name as user_name', 'users_approver.name as approver_name')
            ->leftJoin('documents', 'document_approvals.document_id', '=', 'documents.id')
            ->leftJoin('users', 'document_approvals.creator_id', '=', 'users.employee_number')
            ->leftJoin('users as users_approver', 'document_approvals.approver_id', '=', 'users_approver.employee_number')
            ->where('document_approvals.approved', 1);

        $routineQuery = RoutineApprovals::select('routine_approvals.*', 'users.name as user_name', 'users_approver.name as approver_name')
            ->leftJoin('users', 'routine_approvals.creator_id', '=', 'users.employee_number')
            ->leftJoin('users as users_approver', 'routine_approvals.approver_id', '=', 'users_approver.employee_number')
            ->where('routine_approvals.approved', 1);

        $procedureQuery = ProcedureApprovals::select('procedure_approvals.*', 'procedures.name', 'users.name as user_name', 'users_approver.name as approver_name')
            ->leftJoin('users', 'procedure_approvals.creator_id', '=', 'users.employee_number')
            ->leftJoin('procedures', 'procedure_approvals.procedure_id', '=', 'procedures.id')
            ->leftJoin('users as users_approver', 'procedure_approvals.approver_id', '=', 'users_approver.employee_number')
            ->where('procedure_approvals.approved', 1);

            $documents = $documentQuery->paginate(10, ['*'], 'document_page', $documentPage);
            $routines = $routineQuery->paginate(10, ['*'], 'routine_page', $routinePage);
            $procedures = $procedureQuery->paginate(10, ['*'], 'procedure_page', $procedurePage);

            return view('approval.approved', compact('title', 'documents', 'routines', 'procedures', 'documentPage', 'routinePage', 'procedurePage'));
        }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentApprovals  $documentApprovals
     * @return \Illuminate\Http\Response
     */
    public function routine_edit(RoutineApprovals $routines)
    {
        if (Auth::user()->role === 9 && $routines->approved === 1) {
            abort(404);
        }

        $getRoutines = Routine::where('id', $routines->routine_id)->get();

        $procedureIds = [];
        $procedureIds[] = $getRoutines[0]->previous_procedure_id;

        if ($getRoutines[0]->next_procedure_ids !== null) {
            $nextProcedureIds = explode(',', $getRoutines[0]->next_procedure_ids);
            $procedureIds = array_merge($procedureIds, $nextProcedureIds);
        }

        $procedureIds[] = $getRoutines[0]->next_procedure_id;

        $procedures = Procedure::whereIn('id', $procedureIds)->get()->toArray();

        $title = "詳細画面";
        session(['routines' => $getRoutines[0]]);

        return view('approval.routine_edit', compact('title', 'routines', 'procedures', 'getRoutines'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentApprovals  $documentApprovals
     * @return \Illuminate\Http\Response
     */
    public function document_edit(DocumentApprovals $documents)
    {
        if (Auth::user()->role === 9 && $documents->approved === 1) {
            abort(404);
        }

        $title = "詳細画面";
        $document = DocumentApprovals::select('document_approvals.*', 'documents.title', 'documents.file_name', 'users.name')
            ->leftJoin('documents', 'document_approvals.document_id', '=', 'documents.id')
            ->leftJoin('users', 'document_approvals.creator_id', '=', 'users.employee_number')
            ->where('document_approvals.id', $documents->id)
            ->firstOrFail();

        $documentSV = new DocumentService;
        $fileContents = $documentSV->getContents($document->file_name);

        return view('approval.edit', compact('title', 'document', 'fileContents'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentApprovals  $documentApprovals
     * @return \Illuminate\Http\Response
     */
    public function procedure_edit(ProcedureApprovals $procedures)
    {
        if (Auth::user()->role === 9 && $procedures->approved === 1) {
            abort(404);
        }

        $title = "詳細画面";
        $procedure = Procedure::select('procedures.id', 'procedures.name', 'procedures.task_id', 'procedures.previous_procedure_id', 'procedures.next_procedure_ids', 'tasks.name AS task_name', 'procedures.is_visible')
            ->join('tasks', 'procedures.task_id', '=', 'tasks.id')
            ->findOrFail($procedures->procedure_id);

        $documents = Document::join('procedure_documents', 'documents.id', '=', 'procedure_documents.document_id')
            ->whereIn('procedure_documents.procedure_id', explode(',', $procedures->document_id))
            ->get();

        return view('approval.procedure_edit', compact('title', 'procedures', 'procedure', 'documents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDocumentApprovalsRequest  $request
     * @param  \App\Models\DocumentApprovals  $documentApprovals
     * @return \Illuminate\Http\Response
     */
    public function document_update(UpdateDocumentApprovalsRequest $request, DocumentApprovals $documents)
    {
        DB::beginTransaction();

        try {
            // 承認を更新
            $approved = $request->input('approved') === "1";
            $employeeNumber = Auth::user()->employee_number;

            $documents->update([
                'approved' => $approved,
                'approver_id' => $employeeNumber,
                'approval_at' => $approved ? Carbon::now() : null,
            ]);

            $document = Document::findOrFail($documents->document_id);
            $document->update([
                'is_visible' => $approved ? 1 : 0,
                'approver_id' => $approved ? $employeeNumber : null,
            ]);

            DB::commit();
            session()->flash('status', '更新完了');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('alert', '更新エラー');
        }

        return redirect()->route('approval.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRoutineApprovalsRequest  $request
     * @param  \App\Models\RoutineApprovals  $routineApprovals
     * @return \Illuminate\Http\Response
     */
    public function routine_update(UpdateRoutineApprovalsRequest $request, RoutineApprovals $routines)
    {
        DB::beginTransaction();

        try {
            $approved = $request->input('approved');

            $routines->approved = $approved;
            $routines->approver_id = Auth::user()->employee_number;
            $routines->approval_at = ($approved === "1") ? Carbon::now() : null;
            $routines->save();

            $routine = Routine::findOrFail($routines->routine_id);

            if ($approved === "1") {
                $routine->is_visible = 1;
                $routine->approver_id = Auth::user()->employee_number;
                $routine->save();

                $routineSV = new RoutineService;
                $result = $routineSV->createRoutine($routine);

                if (!$result) {
                    throw new \Exception('更新エラー');
                }

                DB::commit();
                session()->flash('status', '更新完了');
            } else {
                $routine->is_visible = 0;
                $routine->approver_id = null;
                $routine->save();

                $routineSV = new RoutineService;
                $result = $routineSV->updateProcedure($routine);

                if (!$result) {
                    throw new \Exception('更新エラー');
                }

                DB::commit();
                session()->flash('status', '更新完了');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('alert', '更新エラー');
        }

        return redirect()->route('approval.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProcedureApprovalsRequest  $request
     * @param  \App\Models\ProcedureApprovals  $procedureApprovals
     * @return \Illuminate\Http\Response
     */
    public function procedure_update(UpdateProcedureApprovalsRequest $request, ProcedureApprovals $procedures)
    {
        DB::beginTransaction();

        try {
            // 承認を更新
            $procedures->approved = $request->input('approved');
            $procedures->approver_id = Auth::user()->employee_number;
            $procedures->approval_at = $request->input('approved') === "1" ? Carbon::now() : null;
            $procedures->save();

            $procedure = Procedure::findOrFail($procedures->procedure_id);
            $procedure->is_visible = $request->input('approved') === "1" ? 1 : 0;
            $procedure->approver_id = $request->input('approved') === "1" ? Auth::user()->employee_number : null;
            $procedure->save();

            if ($request->input('approved') === "1") {
                $documents = explode(',', $procedures->document_id);
                foreach ($documents as $document) {
                    ProcedureDocument::create([
                        'procedure_id' => $procedure->id,
                        'document_id' => $document,
                    ]);
                }
            } else {
                ProcedureDocument::where('procedure_id', $procedure->id)->delete();
            }

            DB::commit();
            session()->flash('status', '更新完了');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            session()->flash('alert', '更新エラー');
        }

        return redirect()->route('approval.index');
    }
}
