<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

class PaginationService
{
    public function getProcedureOrder($procedures)
    {
        // $procedures をコレクションに変換
        $procedures = collect($procedures);

        // ページネーション用に手順をチャンク化
        $chunkedProcedures = $procedures->chunk(10);

        // チャンク化した手順の配列を取得
        $sortedProcedures = $chunkedProcedures->toArray();

        return $sortedProcedures;
    }

    public function paginateResults($procedures, $perPage, $page)
    {
        $collection = new Collection($procedures);
        $total = $collection->count();
        $slicedProcedures = $collection->slice(($page - 1) * $perPage, $perPage);

        // ページネーションされた手順を作成
        $paginatedProcedures = new LengthAwarePaginator(
            $slicedProcedures,
            $total,
            $perPage,
            $page,
            ['path' => Request::url()]
        );

        return $paginatedProcedures;
    }
}
