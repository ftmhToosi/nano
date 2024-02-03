<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssessmentRequest;
use App\Http\Requests\Admin\AssessmentUpdateRequest;
use App\Models\Assessment;
use App\Models\Requests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $data = Assessment::with(['request', 'request.user'])->get();
            return response()->json(
                $data,
                200
            );
        }catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function store(AssessmentRequest $request): JsonResponse
    {
        try {
            $data = new Assessment();
            $data->request_id = $request->request_id;
            $data->is_accepted = $request->is_accepted;
            $data->save();
            return response()->json([
                'success' => true,
                'last_id' => $data->id,
            ], 201);
        }catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function update(AssessmentUpdateRequest $request, $id): JsonResponse
    {
        try {
            $data = Assessment::find($id);
            if ($data){
                $data->is_accepted = $request->is_accepted;
                $data->save();
                return response()->json([
                    'success' => true,
                ], 202);
            } else
                return response()->json([], 404);
        }catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $data = Assessment::find($id);
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
            $data = Assessment::with(['request', 'request.user'])->find($id);
            if ($data){
                return response()->json(
                    $data,
                    200
                );
            }else
                return response()->json([], 404);
        }catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function get_assessment($id): JsonResponse
    {
        try {
            $data = Assessment::with(['request', 'request.user'])->where('request_id', '=', $id)->first();
            if ($data)
                return response()->json(
                    $data,
                    200
                );
            else
                return response()->json([
                    'message' => 'You do not have an Start the assessment !!'
                ], 404);
        }catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
