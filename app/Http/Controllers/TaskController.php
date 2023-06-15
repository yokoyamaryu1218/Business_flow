<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Procedure;
use App\Models\ProcedureDocument;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\ProcedureService;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "作業管理";
        $tasks = Task::paginate(10);

        return view('tasks.index', compact('title', 'tasks'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "作業新規登録";
        return view('tasks.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTaskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request)
    { {
            DB::beginTransaction();

            try {
                Task::create([
                    'name' => $request['task_name'],
                    'is_visible' => $request['is_visible'],
                ]);

                DB::commit();

                session()->flash('status', '登録完了');
            } catch (\Exception $e) {
                DB::rollback();
                session()->flash('alert', '登録エラー');
            }

            return redirect()->route('task.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $title = "作業詳細";
        $task = Task::findOrFail($task->id);
        $procedures = Procedure::where('task_id', $task->id)->get();
        $procedureSV = new ProcedureService;
        $sortedProcedures = $procedureSV->getProcedureOrder($procedures);

        return view('tasks.edit', compact('title', 'task', 'procedures', 'sortedProcedures'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTaskRequest  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        DB::beginTransaction();

        try {
            $task_name = $request->input('task');
            $task = Task::findOrFail($task->id);
            $task->name = $task_name;
            $task->is_visible = $request['is_visible'];
            $task->updated_at = Carbon::now();
            $task->save();

            DB::commit();

            session()->flash('status', '更新完了');
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('alert', '更新エラー');
        }

        return redirect()->route('task.edit', ['task' => $task->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        DB::beginTransaction();

        try {
            $task = Task::findOrFail($task->id);

            // 関連するprocedure_idsを取得
            $procedureIds = $task->procedures()->pluck('id');

            // procedure_documentsテーブルから関連するレコードを削除
            ProcedureDocument::whereIn('procedure_id', $procedureIds)->delete();

            // proceduresテーブルから関連するレコードを削除
            $task->procedures()->delete();

            // tasksテーブルからタスクを削除
            $task->delete();

            DB::commit();

            session()->flash('status', '削除完了');
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('alert', '削除エラー');
        }

        return redirect()->route('task.index');
    }
}
