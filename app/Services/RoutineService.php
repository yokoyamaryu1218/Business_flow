<?php

namespace App\Services;

use App\Models\Procedure;

class RoutineService
{
    // ルーティンの中身を書き換える
    function sortProcedures($routines)
    {
        $sortedProcedures = [];

        foreach ($routines as $routine) {
            $sortedProcedure = [];

            $sortedProcedure[] = Procedure::find($routine->previous_procedure_id);

            if ($routine->next_procedure_ids !== null) {
                $nextIds = explode(',', $routine->next_procedure_ids);

                foreach ($nextIds as $nextId) {
                    $sortedProcedure[] = Procedure::find($nextId);
                }
            }

            $sortedProcedure[] = Procedure::find($routine->next_procedure_id);

            $sortedProcedures[] = $sortedProcedure;
        }

        return $sortedProcedures;
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
