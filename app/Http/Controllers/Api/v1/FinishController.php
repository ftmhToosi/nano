<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\FinishRequest;
use App\Http\Requests\User\FinishUpdateRequest;
use App\Models\Finish;
use App\Models\Facilities;
use App\Models\Requests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FinishController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $data = Finish::with('facilities')->get();
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

    public function store(FinishRequest $request): JsonResponse
    {
        try {
            $data = new Finish();
            $data->facilities_id = $request->facilities_id;
            $data->name = $request->name;
            $data->amount = $request->amount;
            $data->title = $request->title;
            $data->supply = $request->supply;
            $file = $request->file('signature');
            $filename = time().Str::random(10) .'.'.$file->getClientOriginalExtension();
            $folder = 'Facilities';
            $file->move( $folder, $filename );
            $data->signature = $folder .'/'. $filename;
            $data->save();
            return response()->json([
                'success' => true,
            ], 201);
        }catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function update(FinishUpdateRequest $request, $id)
    {
        try {
            $data = Requests::find($id);
            if ($data){
                $facilities = Facilities::query()->where('request_id', '=', $data->id)->first();
                $finish = Finish::query()->where('facilities_id', '=', $facilities->id)->first();
                $finish->name = $request->name ?? $finish->name;
                $finish->amount = $request->amount ?? $finish->amount;
                $finish->title = $request->title ?? $finish->title;
                $finish->supply = $request->supply ?? $finish->supply;
                if ($request->hasFile('signature')){
                    $file = $request->file('signature');
                    $filename = time().Str::random(10) .'.'.$file->getClientOriginalExtension();
                    $folder = 'Facilities';
                    $file->move( $folder, $filename );
                    $finish->signature = $folder .'/'. $filename;
                }
                $finish->save();
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
            $data = Finish::find($id);
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
            $data = Finish::with('facilities')->find($id);
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
}
