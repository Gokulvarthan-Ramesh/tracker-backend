<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\Paginator;
use App\Models\StudyLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class StudyController extends Controller
{
    /**
     * Helper to get the authenticated user or return error response.
     */
    private function getAuthenticatedUser()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return ApiResponse::notFound('User not found');
            }
            return $user;
        } catch (JWTException $e) {
            return ApiResponse::unauthorized('Token error: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) return $user;

        $query = StudyLog::where('user_id', $user->id);

        return Paginator::paginateQuery(
            $query,
            $request,
            ['subject', 'description', 'notes'],
            ['subject', 'date', 'hours']
        );
    }


    public function store(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) return $user;

        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'hours' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        $validated = $validator->validated();

        $studyLog = StudyLog::create([
            'user_id' => $user->id,
            'subject' => $validated['subject'],
            'description' => $validated['description'] ?? null,
            'date' => $validated['date'],
            'hours' => $validated['hours'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return ApiResponse::success($studyLog, 'Study log created successfully', 201);
    }

    public function update(Request $request, $id)
    {
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) return $user;

        $studyLog = StudyLog::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$studyLog) {
            return ApiResponse::notFound('Study log not found');
        }

        $validator = Validator::make($request->all(), [
            'subject' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'sometimes|required|date',
            'hours' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        $studyLog->update($validator->validated());

        return ApiResponse::success($studyLog, 'Study log updated successfully');
    }

    public function destroy($id)
    {
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) return $user;

        $studyLog = StudyLog::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$studyLog) {
            return ApiResponse::notFound('Study log not found');
        }

        $studyLog->delete();

        return ApiResponse::success(null, 'Study log deleted successfully');
    }
}
