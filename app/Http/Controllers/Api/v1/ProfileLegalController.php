<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileLegalRequest;
use App\Http\Requests\User\ProfileLegalUpdateRequest;
use App\Models\ProfileLagal;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProfileLegalController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $data = ProfileLagal::with('user')->get();
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

    public function store(ProfileLegalRequest $request): JsonResponse
    {
        try {
            $data = new ProfileLagal();
            $user = Auth::user();
            $data->user_id = $user->id;
            $data->type_legal = $request->type_legal;
            $data->place_registration = $request->place_registration;
            $data->establishment = $request->establishment;
            $data->signed_right = $request->signed_right;
            $data->initial_investment = $request->initial_investment;
            $data->fund = $request->fund;
            $data->subject_activity = $request->subject_activity;
            $data->name_representative = $request->name_representative;
            $data->landline_phone = $request->landline_phone;
            $data->phone = $request->phone;
            $data->email = $request->email;
            $data->site = $request->site;
            $data->save();


            return response()->json([
                'success' => true,
                'last_id' => $data->id,
            ], 201);
        }catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function update(ProfileLegalUpdateRequest $request, $id): JsonResponse
    {
        try {
            $data = ProfileLagal::find($id);
            if ($data){
                $data->type_legal = $request->type_legal ?? $data->type_legal;
                $data->place_registration = $request->place_registration ?? $data->place_registration;
                $data->establishment = $request->establishment ?? $data->establishment;
                $data->signed_right = $request->signed_right ?? $data->signed_right;
                $data->initial_investment = $request->initial_investment ?? $data->initial_investment;
                $data->fund = $request->fund ?? $data->fund;
                $data->subject_activity = $request->subject_activity ?? $data->subject_activity;
                $data->name_representative = $request->name_representative ?? $data->name_representative;
                $data->landline_phone = $request->landline_phone ?? $data->landline_phone;
                $data->phone = $request->phone ?? $data->phone;
                $data->email = $request->email ?? $data->email;
                $data->site = $request->site ?? $data->site;
                $data->save();
                return response()->json([
                    'success' => true,
                ], 202);
            } else
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
            $data = ProfileLagal::find($id);
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
            $data = ProfileLagal::with('user')->find($id);
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

    public function is_profile_legal(): JsonResponse
    {
        try {
            $user = Auth::user();
            $exists = ProfileLagal::where('user_id', $user->id)->exists();
            if ($exists)
                return response()->json(
                    true,
                    200
                );
            else
                return response()->json(
                    false,
                    200
                );

        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}

