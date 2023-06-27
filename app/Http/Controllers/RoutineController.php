<?php

namespace App\Http\Controllers;

use App\Models\Routine;
use App\Models\Procedure;
use App\Models\RoutineApprovals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreRoutineRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\RoutineService;

class RoutineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id1, $id2)
    {
        $title = 'ルーティン詳細';
        $task_id = $id1;

        // 手順リストの取得
        $procedure_list = Procedure::whereNotNull('approver_id')->get();

        // ルーティンの取得
        $routines = Routine::where('id', $id2)->get();
        $procedures = [];

        foreach ($routines as $routine) {
            $procedure = [];
            $procedure[] = Procedure::find($routine->previous_procedure_id)->toArray();

            if ($routine->next_procedure_ids !== null) {
                $nextProcedureIds = explode(',', $routine->next_procedure_ids);

                foreach ($nextProcedureIds as $nextProcedureId) {
                    $procedure[] = Procedure::find($nextProcedureId)->toArray();
                }
            }

            $procedure[] = Procedure::find($routine->next_procedure_id)->toArray();

            $procedures = $procedure;
        }

        $previous_procedures_list = Procedure::where('task_id', $id1)
            ->whereNull('previous_procedure_id')
            ->whereNotNull('approver_id')
            ->get();
        $next_procedures_list = Procedure::where('task_id', $id1)
            ->whereNull('next_procedure_ids')
            ->whereNotNull('approver_id')
            ->get();


        session(['procedure_data' => $procedures]);
        session(['routines' => $routines[0]]);
        return view('routine.edit', compact('title', 'routines', 'task_id', 'procedures', 'procedure_list', 'previous_procedures_list', 'next_procedures_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $title = "ルーティン新規登録";
        $task_id = $id;
        $procedure_list = Procedure::where('task_id', $id)->whereNotNull('approver_id')->get();
        $previous_procedures_list = Procedure::where('task_id', $id)
            ->whereNull('previous_procedure_id')
            ->whereNotNull('approver_id')
            ->get();
        $next_procedures_list = Procedure::where('task_id', $id)
            ->whereNull('next_procedure_ids')
            ->whereNotNull('approver_id')
            ->get();

        return view('routine.create', compact('title', 'task_id', 'procedure_list', 'previous_procedures_list', 'next_procedures_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRoutineRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoutineRequest $request)
    {
        $routineSV = new RoutineService;
        $result = $routineSV->numbering($request->input('procedure_id'));

        if ($result === false) {
            return redirect()->back()->withErrors(['previous_procedure_id' => '手順の選択に誤りがあります。']);
        }

        $procedureIds = $result;
        $previous_procedure_id = reset($procedureIds);
        $next_procedure_ids = null;
        $next_procedure_id = null;

        if (count($procedureIds) > 1) {
            $next_procedure_id = end($procedureIds);

            if (count($procedureIds) > 2) {
                $next_procedure_ids = implode(',', array_slice($procedureIds, 1, -1));
            }
        }

        DB::beginTransaction();

        try {
            $approver_id = (Auth::user()->role !== 9) ? Auth::user()->employee_number : null;

            $routine = Routine::create([
                'task_id' => $request['task_id'],
                'previous_procedure_id' => $previous_procedure_id,
                'next_procedure_ids' => $next_procedure_ids,
                'next_procedure_id' => $next_procedure_id,
                'is_visible' => $request['is_visible'],
                'approver_id' => $approver_id,
                'creator_id' => Auth::user()->employee_number,
            ]);

            if (Auth::user()->role !== 9) {
                $routineSV = new RoutineService;
                $result = $routineSV->createRoutine($routine);

                if (!$result) {
                    throw new \Exception('登録エラー');
                }
                session()->flash('status', '登録完了');
            } else {
                RoutineApprovals::create([
                    'routine_id' => $routine->id,
                    'creator_id' => Auth::user()->employee_number,
                    'approved' => 0, // 0：申請中、1：承認、2：否認、3：取り下げ
                    'approval_at' => null,
                ]);

                session()->flash('status', '登録が完了しました。承認までお待ちください。');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('alert', '登録エラー');
        }

        return (Auth::user()->role !== 9)
            ? redirect()->route('task.edit', ['task' => $routine->task_id])
            : redirect()->route('approval.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Routine  $routine
     * @return \Illuminate\Http\Response
     */
    public function show(Routine $routine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Routine  $routine
     * @return \Illuminate\Http\Response
     */
    public function edit(Routine $routine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRoutineRequest  $request
     * @param  \App\Models\Routine  $routine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $routineSV = new RoutineService;
        $result = $routineSV->numbering($request->input('previous_procedure_id'));

        if ($result === false) {
            return redirect()->back()->withErrors(['previous_procedure_id' => '手順を重複して選択しています。']);
        }

        $previousProcedureIds = $result;

        try {
            DB::beginTransaction();

            // セッションからルーティン情報を取得
            $routines = session('routines');
            $routine = Routine::findOrFail($routines->id);

            // リクエストから一番最初と最後の値を除いた配列を作成
            $newIds = array_slice($previousProcedureIds, 1, -1);

            // 既存のテーブルから配列を作成
            $existingIds = explode(',', $routine->next_procedure_ids);

            // カンマ区切りの文字列に変換して、DBに保存する
            if (!empty($newIds)) {
                $updatedIds = implode(',', $newIds);
                $routine->next_procedure_ids = $updatedIds;
                $routine->save();
            } else {
                $updatedIds = null;
            }

            // データベースに更新
            $routine->next_procedure_ids = $updatedIds;
            $routine->is_visible = $request->input('is_visible');
            $routine->save();

            // Routineテーブルからレコードを取得
            $routines = Routine::where('id', $routine->id)->get();

            // ルーティンに含まれるプロシージャの情報を取得
            foreach ($routines as $routine) {
                $procedureIds = [];
                $procedureIds[] = $routine->previous_procedure_id;

                if ($routine->next_procedure_ids !== null) {
                    $nextProcedureIds = explode(',', $routine->next_procedure_ids);
                    $procedureIds = array_merge($procedureIds, $nextProcedureIds);
                }

                $procedureIds[] = $routine->next_procedure_id;

                $procedureRecords = Procedure::whereIn('id', $procedureIds)->get()->toArray();
                $procedures = $procedureRecords;
            }

            // ルーチンのnext_procedure_idsがnullかどうかをチェックし、それに応じてProcedureを更新します
            if ($routine->next_procedure_ids === null) {
                // ルーチンからprevious_procedure_idとnext_procedure_idを取得します
                $prevProcIdInRoutine = $routine->previous_procedure_id;
                $nextProcIdInRoutine = $routine->next_procedure_id;
                // 対応するIDを持つProcedureを検索します
                $prevProcedure = Procedure::find($prevProcIdInRoutine);
                $nextProcedure = Procedure::find($nextProcIdInRoutine);

                if ($prevProcedure && $nextProcedure) {
                    // 前の手順のnext_procedure_idsを更新します
                    $nextProcedureIdsForPrevProc = explode(',', $prevProcedure->next_procedure_ids);
                    if (!in_array($nextProcIdInRoutine, $nextProcedureIdsForPrevProc)) {
                        $nextProcedureIdsForPrevProc[] = $nextProcIdInRoutine;
                        $prevProcedure->next_procedure_ids = implode(',', $nextProcedureIdsForPrevProc);
                        $prevProcedure->save();
                    }

                    // 次の手順のprevious_procedure_idを更新します
                    $prevProcedureIdsForNextProc = explode(',', $nextProcedure->previous_procedure_id);
                    if (!in_array($prevProcIdInRoutine, $prevProcedureIdsForNextProc)) {
                        $prevProcedureIdsForNextProc[] = $prevProcIdInRoutine;
                        $nextProcedure->previous_procedure_id = implode(',', $prevProcedureIdsForNextProc);
                        $nextProcedure->save();
                    }
                }
            } else {
                // プロシージャIDリストを初期化
                $procedureIds = [];

                // ルーチン配列からプロシージャIDを取り出す
                foreach ($routines as $routine) {
                    $procedureIds[] = $routine['previous_procedure_id'];
                    $procedureIds = array_merge($procedureIds, explode(',', $routine['next_procedure_ids']));
                    $procedureIds[] = $routine['next_procedure_id'];
                }

                // プロシージャIDリストを配列に変換
                $procedureIds = array_unique($procedureIds);

                // プロシージャテーブルを更新するための準備
                $updatedProcedures = [];

                foreach ($procedureIds as $index => $procedureId) {
                    // プロシージャを見つける
                    foreach ($procedures as $procedure) {
                        if ($procedure['id'] == $procedureId) {
                            // 最初のプロシージャでない場合、前のプロシージャIDを設定する
                            if ($index != 0) {
                                // 次のプロシージャIDを追加
                                $previousId = $procedureIds[$index - 1];
                                $previousIds = explode(',', $procedure['previous_procedure_id']);
                                // 次のプロシージャIDが既に存在する場合は追加しない
                                if (!in_array($previousId, $previousIds)) {
                                    $previousIds[] = $previousId;
                                    $procedure['previous_procedure_id'] = $procedureIds[$index - 1];
                                }
                                $procedure['previous_procedure_id'] = implode(',', $previousIds);
                            }

                            // 最後のプロシージャでない場合、次のプロシージャIDを設定する
                            if ($index != count($procedureIds) - 1) {
                                // 次のプロシージャIDを追加
                                $nextProcedureId = $procedureIds[$index + 1];
                                if (!empty($procedure['next_procedure_ids'])) {
                                    $nextProcedureIds = explode(',', $procedure['next_procedure_ids']);
                                    // 次のプロシージャIDが既に存在する場合は追加しない
                                    if (!in_array($nextProcedureId, $nextProcedureIds)) {
                                        $nextProcedureIds[] = $nextProcedureId;
                                    }
                                } else {
                                    $nextProcedureIds = [$nextProcedureId];
                                }
                                $procedure['next_procedure_ids'] = implode(',', $nextProcedureIds);
                            }

                            // 更新したプロシージャを保存
                            $updatedProcedures[] = $procedure;
                        }
                    }
                }

                // 更新処理
                foreach ($updatedProcedures as $updatedProcedure) {
                    // Eloquentモデルを使用してデータベースを更新
                    $procedure = Procedure::find($updatedProcedure['id']);
                    if ($procedure) {
                        $procedure->previous_procedure_id = $updatedProcedure['previous_procedure_id'];
                        $procedure->next_procedure_ids = $updatedProcedure['next_procedure_ids'];
                        $procedure->save();
                    } else {
                        // IDが見つからない場合のエラーハンドリング
                        dd("Procedure with ID {$updatedProcedure['id']} not found.\n");
                    }
                }
            }

            // 他の行に存在しない既存のIDがあるかどうかをチェックします。
            $otherRoutines = Routine::where('id', '!=', $routine->id)->get();
            $foundInOtherRoutines = array_fill_keys($existingIds, false);

            foreach ($otherRoutines as $otherRoutine) {
                $otherNextProcedureIds = explode(',', $otherRoutine->next_procedure_ids);
                foreach ($existingIds as $existingId) {
                    if (in_array($existingId, $otherNextProcedureIds)) {
                        $foundInOtherRoutines[$existingId] = true;
                    }
                }
            }

            // 他の手順で見つからなかったIDをチェックし、対応する手順の値をnullに設定します。
            foreach ($foundInOtherRoutines as $key => $found) {
                if (!$found) {
                    // 他の手順で見つからない。対応する手順の値をnullに設定します。
                    $procedure = Procedure::find($key);
                    if ($procedure) {
                        $procedure->previous_procedure_id = null;
                        $procedure->next_procedure_ids = null;
                        $procedure->save();
                    }
                }
            }

            DB::commit();

            session()->flash('status', '更新完了');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('alert', '更新エラー');
            // return response()->json(['error' => $e->getMessage()], 500);
        }
        return redirect()->route('task.edit', ['task' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Routine  $routine
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $routines = session('routines');

        try {
            DB::beginTransaction();

            // 削除するRoutineエントリを取得
            $routineToDelete = Routine::find($routines->id);

            // 削除する前の値を$existingIdsに保存
            $existingIds = array_merge([$routineToDelete->previous_procedure_id], explode(',', $routineToDelete->next_procedure_ids), [$routineToDelete->next_procedure_id]);

            // 他の行に存在しない既存のIDがあるかどうかをチェックします。
            $otherRoutines = Routine::where('id', '!=', $routines->id)->get();
            $foundInOtherRoutines = array_fill_keys($existingIds, false);

            $nofonds = [];
            foreach ($otherRoutines as $otherRoutine) {
                $otherNextProcedureIds = array_merge([$otherRoutine->previous_procedure_id], explode(',', $otherRoutine->next_procedure_ids), [$otherRoutine->next_procedure_id]);
                foreach ($existingIds as $existingId) {
                    if (in_array($existingId, $otherNextProcedureIds)) {
                        $foundInOtherRoutines[$existingId] = true;
                    } else {
                        $nofonds[] = $existingId;
                    }
                }
            }

            foreach ($foundInOtherRoutines as $key => $found) {
                if (!$found) {
                    $procedure = Procedure::find($key);
                    if ($procedure) {
                        $procedure->previous_procedure_id = null;
                        $procedure->next_procedure_ids = null;
                        $procedure->save();
                    }
                } else {
                    $procedure = Procedure::find($key);
                    if ($procedure) {
                        $previousProcedureIds = explode(',', $procedure->previous_procedure_id);

                        $previousKey = $key - 1;
                        $previousProcedureIds = array_filter($previousProcedureIds, function ($value) use ($previousKey, $nofonds) {
                            return $value != $previousKey && !in_array($value, $nofonds);
                        });

                        $procedure->previous_procedure_id = implode(',', $previousProcedureIds);

                        if ($procedure->previous_procedure_id === '') {
                            $procedure->previous_procedure_id = null;
                        }

                        $nextProcedureIds = explode(',', $procedure->next_procedure_ids);

                        $nextKey = $key + 1;
                        $nextProcedureIds = array_filter($nextProcedureIds, function ($value) use ($nextKey, $nofonds) {
                            return $value != $nextKey && !in_array($value, $nofonds);
                        });

                        $procedure->next_procedure_ids = implode(',', $nextProcedureIds);

                        if ($procedure->next_procedure_ids === '') {
                            $procedure->next_procedure_ids = null;
                        }

                        $procedure->save();
                    }
                }
            }

            RoutineApprovals::where('routine_id', $routines->id)->delete();
            // 最後に、指定されたRoutineエントリを削除
            $routineToDelete->delete();

            DB::commit();
            session()->flash('status', '削除完了');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('alert', '更新エラー');
            // return response()->json(['error' => $e->getMessage()], 500);
        }

        $request->session()->forget('routines');

        return redirect()->route('task.edit', ['task' => $id]);
    }
}
