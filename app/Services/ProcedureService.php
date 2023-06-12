<?php

namespace App\Services;

use App\Models\Procedure;
class ProcedureService
{
    // 手順の順番を取得する関数
    public function getProcedureOrder($procedures)
    {
        $startProcedures = $procedures->where('previous_procedure_id', null);

        $sortedProcedures = [];

        foreach ($startProcedures as $startProcedure) {
            $procedureStack = [[$startProcedure, []]]; // [procedure, visitedProcedures]

            while (!empty($procedureStack)) {
                list($currentProcedure, $visitedProcedures) = array_pop($procedureStack);

                // 無限ループを回避するために既に訪れた手順かどうかをチェック
                if (in_array($currentProcedure->id, $visitedProcedures)) {
                    continue;
                }

                $visitedProcedures[] = $currentProcedure->id;
                $procedureArray = $visitedProcedures;

                $nextProcedureIds = explode(',', $currentProcedure->next_procedure_ids);

                if (empty($nextProcedureIds[0])) { // if nextProcedureIds is empty or null
                    $sortedProcedures[] = array_map(function ($id) use ($procedures) {
                        return $procedures->firstWhere('id', $id);
                    }, $procedureArray);
                    continue;
                }

                $nextProcedureFound = false;
                foreach ($nextProcedureIds as $nextProcedureId) {
                    $nextProcedure = $procedures->firstWhere('id', $nextProcedureId);

                    if ($nextProcedure) {
                        $procedureStack[] = [$nextProcedure, $visitedProcedures];
                        $nextProcedureFound = true;
                    }
                }

                if (!$nextProcedureFound) {
                    $sortedProcedures[] = array_map(function ($id) use ($procedures) {
                        return $procedures->firstWhere('id', $id);
                    }, $procedureArray);
                }
            }
        }

        return $sortedProcedures;
    }

    public function separateCharacters($procedureId)
    {
        $procedures = []; // 変数を初期化

        $nextProcedureIds = null;
        if ($procedureId) {
            $nextProcedureIds = explode(',', $procedureId);
        }

        if ($nextProcedureIds) {
            foreach ($nextProcedureIds as $nextProcedureId) { // $nextProcedureIdsをループ処理
                $nextProcedure = Procedure::find($nextProcedureId);
                if ($nextProcedure) {
                    $procedures[] = $nextProcedure;
                }
            }
        }

        return $procedures;
    }
}
