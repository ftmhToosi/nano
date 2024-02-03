<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requests;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class CountController extends Controller
{
    public function count_users(): JsonResponse
    {
        try {
        $data = User::where('type', 'genuine')->orWhere('type', 'legal')->count();
            return response()->json(
                $data,
                200
            );
        }catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function count_experts(): JsonResponse
    {
        try {
            $data = User::where('type', 'expert')->count();
            return response()->json(
                $data,
                200
            );
        }catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function count_requests(): JsonResponse
    {
        try {
            $data = Requests::count();
            return response()->json(
                $data,
                200
            );
        }catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
