<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

class UserController extends BaseController
{
    public function info()
    {
        $user = auth()->user();
        if (!$user) {
            return ApiResponse::unauthorized('User not authenticated');
        }
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
        return ApiResponse::success($userData, 'User info retrieved successfully');
    }
}
