<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginationService
{
    public function paginateResults($query, $perPage, $page)
    {
        $offset = ($page - 1) * $perPage;
        $results = $query->skip($offset)->take($perPage + 1)->get();

        $paginator = new LengthAwarePaginator(
            $results->forPage($page, $perPage),
            $results->count(),
            $perPage,
            $page,
            ['path' => url()->current()]
        );

        return $paginator;
    }
}
