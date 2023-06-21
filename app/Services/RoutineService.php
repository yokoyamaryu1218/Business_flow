<?php

namespace App\Services;

use App\Models\Procedure;
use App\Models\Routine;
use Illuminate\Support\Facades\DB;

class RoutineService
{
    // ルーティンの中身を書き換える
    public function sortProcedures($routines)
    {
        $sortedProcedures = [];

        foreach ($routines as $routine) {
            $sortedProcedure = [];

            // 前の手順のIDから手順を取得
            $sortedProcedure[] = Procedure::find($routine->previous_procedure_id);

            // 次の手順のIDが存在する場合は、カンマで分割してそれぞれの手順を取得
            if ($routine->next_procedure_ids !== null) {
                $nextIds = explode(',', $routine->next_procedure_ids);

                foreach ($nextIds as $nextId) {
                    $sortedProcedure[] = Procedure::find($nextId);
                }
            }

            // 次の手順のIDから手順を取得
            $sortedProcedure[] = Procedure::find($routine->next_procedure_id);

            $sortedProcedures[] = $sortedProcedure;
        }

        return $sortedProcedures;
    }

    public function createRoutine($routine)
    {
        try {
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
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateProcedure($routine)
    {
        try {
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
                    $nextProcedureIdsForPrevProc = array_diff($nextProcedureIdsForPrevProc, [$nextProcIdInRoutine]);
                    $prevProcedure->next_procedure_ids = implode(',', $nextProcedureIdsForPrevProc);
                    $prevProcedure->save();

                    // 次の手順のprevious_procedure_idを更新します
                    $prevProcedureIdsForNextProc = explode(',', $nextProcedure->previous_procedure_id);
                    $prevProcedureIdsForNextProc = array_diff($prevProcedureIdsForNextProc, [$prevProcIdInRoutine]);
                    $nextProcedure->previous_procedure_id = implode(',', $prevProcedureIdsForNextProc);
                    $nextProcedure->save();
                }
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    // 手順の順番を取得する関数
    public function numbering($procedureIds)
    {
        array_pop($procedureIds); // 最後の値を外す
        array_pop($procedureIds); // 次の最後の値を外す

        $procedureIds = array_values($procedureIds);

        // 番号で取得した後、手順の情報へ配列を置き換える
        foreach ($procedureIds as $key => $value) {
            if (!is_numeric($value)) {
                // 数値以外の要素の処理
                $procedure = Procedure::where('name', $value)->first();
                if ($procedure) {
                    $procedureIds[$key] = $procedure->id;
                }
            }
        }

        // 重複チェック
        if ($this->hasDuplicates($procedureIds)) {
            return false;
        }
        return $procedureIds;
    }

    private function hasDuplicates(array $array): bool
    {
        $uniqueValues = array_unique($array);
        return count($array) !== count($uniqueValues);
    }
}
