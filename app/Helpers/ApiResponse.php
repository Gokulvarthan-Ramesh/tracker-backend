<?php

namespace App\Helpers;

class ApiResponse
{
    /**
     * Success response
     *
     * @param array|object $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data = [], $message = 'Success', $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'code' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Paginated success response
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $paginator
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function paginate($paginator, $message = 'Success')
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => $message,
            'data' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
            ]
        ], 200);
    }

    /**
     * General error response
     *
     * @param array|string $errors
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($errors = [], $message = 'Error', $code = 400)
    {
        return response()->json([
            'status' => 'error',
            'code' => $code,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * Validation error response (422)
     *
     * @param array $errors
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function validationError($errors, $message = 'Validation failed')
    {
        return self::error($errors, $message, 422);
    }

    /**
     * Not found error (404)
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function notFound($message = 'Resource not found')
    {
        return self::error([], $message, 404);
    }

    /**
     * Unauthorized error (401)
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function unauthorized($message = 'Unauthorized')
    {
        return self::error([], $message, 401);
    }

    /**
     * Forbidden error (403)
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function forbidden($message = 'Forbidden')
    {
        return self::error([], $message, 403);
    }

    /**
     * Custom response
     *
     * @param string $status
     * @param int $code
     * @param string $message
     * @param array|object $data
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public static function custom($status, $code, $message, $data = [], $errors = [])
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'errors' => $errors
        ], $code);
    }
}