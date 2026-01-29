<?php

namespace App\Helpers;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Paginator
{
    /**
     * Paginate a query with search and sorting features.
     *
     * @param Builder $query
     * @param Request $request
     * @param array $searchFields
     * @param array $sortableFields
     * @return \Illuminate\Http\JsonResponse
     */
    public static function paginateQuery(Builder $query, Request $request, array $searchFields = [], array $sortableFields = ['id', 'date'])
    {
        // Search
        if ($request->has('search') && !empty($request->search) && !empty($searchFields)) {
            $search = $request->search;
            $query->where(function ($q) use ($search, $searchFields) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', $sortableFields[0]);
        $order = $request->get('order', 'desc');

        if (!in_array($sortBy, $sortableFields)) $sortBy = $sortableFields[0];
        if (!in_array($order, ['asc', 'desc'])) $order = 'desc';

        $query->orderBy($sortBy, $order);

        // Pagination
        $perPage = (int) $request->get('per_page', 10);
        $paginated = $query->paginate($perPage);

        return ApiResponse::paginate($paginated);
    }
}
