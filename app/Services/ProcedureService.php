<?php

namespace App\Services;

use App\Models\Procedure;
use App\Models\ProcedureApprovals;
use Illuminate\Support\Facades\Auth;
use App\Services\PaginationService;
use Illuminate\Support\Facades\DB;
use App\Models\ProcedureDocument;

class ProcedureService
{
    // 新規登録に関する処理
    public function createProcedure($name, $task_id, $is_visible, $documents)
    {
        try {
            DB::beginTransaction();

            $approver_id = (Auth::user()->role !== 9) ? Auth::user()->employee_number : null;

            $procedure = Procedure::create([
                'name' => $name,
                'task_id' => $task_id,
                'previous_procedure_id' => null,
                'next_procedure_id' => null,
                'is_visible' => $is_visible,
                'approver_id' => $approver_id,
                'creator_id' => Auth::user()->employee_number,
            ]);

            if (Auth::user()->role !== 9) {
                foreach ($documents as $document) {
                    ProcedureDocument::create([
                        'procedure_id' => $procedure->id,
                        'document_id' => $document,
                    ]);
                }
                session()->flash('status', '登録完了');
            } else {
                $documents_id = implode(',', $documents);
                ProcedureApprovals::create([
                    'procedure_id' => $procedure->id,
                    'creator_id' => Auth::user()->employee_number,
                    'approved' => 0, // 0：申請中、1：承認、2：否認、3：取り下げ
                    'document_id' => $documents_id,
                    'approval_at' => null,
                ]);
                session()->flash('status', '登録が完了しました。承認までお待ちください。');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e); // エラーログに記録する
            session()->flash('alert', '更新エラー');
        }
    }

    // ドキュメントをチェックする処理
    public function checkDocuments($documents)
    {
        // 重複した値を除外して再格納
        $documents = array_values(array_unique($documents));

        // 配列から null の値を除外して再格納
        $documents = array_filter($documents, function ($document) {
            return $document !== null;
        });

        if (empty($documents)) {
            return false;
        }

        return $documents;
    }

    // 手順の順番を取得する処理
    public function getOrders($procedures)
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

                if (empty($nextProcedureIds[0])) { // もしnextProcedureIdsが空またはnullの場合
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

    // 手順の順番を取得する関数
    public function getProcedureOrder($procedures, $perPage, $page)
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

                if (empty($nextProcedureIds[0])) { // もしnextProcedureIdsが空またはnullの場合
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

        $paginationSV = new PaginationService;
        $paginatedProcedures = $paginationSV->paginateResults($sortedProcedures, $perPage, $page);

        return $paginatedProcedures;
    }

    // 手順を分割する処理
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
