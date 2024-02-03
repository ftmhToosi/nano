<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ReceiptRequest;
use App\Models\Receipt;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReceiptController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $data = Receipt::with(['user'])->get();
            return response()->json(
                $data,
                200
            );
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function store(ReceiptRequest $request): JsonResponse
    {
        try {
            $data = new Receipt();
            $user = Auth::user();
            $data->user_id = $user->id;
            $deposit = $request->file('deposit');
            $filename = time() . Str::random(10) . '.' . $deposit->getClientOriginalExtension();
            $folder = 'Receipt';
            $deposit->move($folder, $filename);
            $data->deposit = $folder . '/' . $filename;
            return response()->json([
                'success' => true,
                'last_id' => $data->id,
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $data = Receipt::find($id);
            if ($data) {
                $data->delete();
                return response()->json([
                    'success' => true,
                ], 204);
            } else
                return response()->json([], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $data = Receipt::with(['user'])->find($id);
            if ($data) {
                return response()->json(
                    $data,
                    200
                );
            } else
                return response()->json([], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function get_with_user(): JsonResponse
    {
        try {
            $user = Auth::user();
            $data = Receipt::with(['user'])->where('user_id', '=', $user->id)->get();
            if ($data) {
                return response()->json(
                    $data,
                    200
                );
            } else
                return response()->json([], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }

    }
}
