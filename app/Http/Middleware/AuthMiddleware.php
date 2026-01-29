<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'code' => 401,
                    'message' => 'Unauthorized'
                ], 401);
            }
        } catch (Exception $e) {
            return response()->json([
                'code' => 401,
                'message' => 'Token is invalid or missing',
            ], 401);
        }

        return $next($request);
    }
}
