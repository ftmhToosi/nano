<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UsersController extends Controller
{
    public function index(): JsonResponse
    {
        try {
//            $data = User::query()->with(['request', 'request.expert_assignment'])->where('type', '=', 'genuine')
//                ->orWhere('type', '=', 'legal');
            $data = User::with(['request', 'request.expert_assignment'])
                ->where(function ($q) {
                    $q->where('type', 'genuine')
                        ->orWhere('type', 'legal');
                });

            if($keyword = request('search')) {
                $data->where('name' , 'LIKE' , "%{$keyword}%")
                    ->orWhere('family' , 'LIKE' , "%{$keyword}%")
                    ->orWhere('national_code', '=', $keyword)
                    ->orWhere('phone', '=', $keyword)
                    ->orWhere('company_name', 'LIKE' , "%{$keyword}%")
                    ->orWhere('national_company', '=' , $keyword)
                    ->orWhere('email', 'LIKE' , "%{$keyword}%");
            }
            $data = $data->get();
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

    public function get_legal(): JsonResponse
    {
        try {
            $data = User::with(['request', 'request.expert_assignment'])
                ->where('type', '=', 'legal')->get();
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

    public function get_genuine(): JsonResponse
    {
        try {
            $data = User::with(['request', 'request.expert_assignment'])->where('type', '=', 'genuine')
                ->get();
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

    public function update(UserUpdateRequest $request, $id): JsonResponse
    {
        try {
            $data = User::find($id);
            if ($data){
                $data->name = $request->name;
                $data->family = $request->family;
                $data->email = $request->email;
                $data->save();
                return response()->json([
                    'success' => true,
                ], 202);
            }
            else
                return response()->json([], 404);
        }catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $data = User::find($id);
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
            $data = User::with(['profilegenuine.address', 'profilelagal','request', 'request.expert_assignment'])->find($id);
            if ($data){
                return response()->json(
                    $data,
                    200
                );
            }else
                return response()->json([], 404);
        }catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
