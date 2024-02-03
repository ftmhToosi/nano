<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileGenuineRequest;
use App\Http\Requests\User\ProfileGenuineUpdateRequest;
use App\Models\Address;
use App\Models\ProfileGenuine;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileGenuineController extends Controller
{
    public function index(): JsonResponse
    {
        try {
//            $this->authorize('');
            $data = ProfileGenuine::with(['user', 'address'])->get();
            return response()->json(
                $data,
                200
            );
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function store(ProfileGenuineRequest $request): JsonResponse
    {
        try {
            $data = new ProfileGenuine();
            $user = Auth::user();
            $data->user_id = $user->id;
            if ($request->hasFile('image')) {
        
                $file = $request->file('image');
                $filename = time().Str::random(10) .'.'.$file->getClientOriginalExtension();
                $folder = 'Profile';
                $file->move( $folder, $filename );
                $data->image = 'Profile/' . $filename;
            }

            $data->father_name = $request->father_name;
            $data->number_certificate = $request->number_certificate;
            $data->birth_day = $request->birth_day;
            $data->place_issue = $request->place_issue;
            $data->series_certificate = $request->series_certificate;
            $data->nationality = $request->nationality;
            $data->gender = $request->gender;
            $data->marital = $request->marital;
            $data->residential = $request->residential;
            $data->study = $request->study;
            $data->education = $request->education;
            $data->job = $request->job;
            $data->save();

            $add = new Address();
            $add->profile_genuine_id = $data->id;
            $add->address = $request->address;
            $add->postal_code = $request->postal_code;
            $add->home_number = $request->home_number;
            $add->namabar = $request->namabar;
            $add->work_address = $request->work_address;
            $add->work_postal_code = $request->work_postal_code;
            $add->work_phone = $request->work_phone;
            $add->work_namabar = $request->work_namabar;
            $add->save();
            return response()->json([
                'success' => true,
                'last_id' => $data->id,
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function update(ProfileGenuineUpdateRequest $request, $id): JsonResponse
    {
        try {
            $data = ProfileGenuine::find($id);
            if ($data) {
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $filename = time().Str::random(10) .'.'.$file->getClientOriginalExtension();
                    $folder = 'Profile';
                    $file->move( $folder, $filename );
                    $data->image = 'Profile/' . $filename;
                }
                $data->father_name = $request->father_name ?? $data->father_name;
                $data->number_certificate = $request->number_certificate ?? $data->number_certificate;
                $data->birth_day = $request->birth_day ?? $data->birth_day;
                $data->place_issue = $request->place_issue ?? $data->place_issue;
                $data->series_certificate = $request->series_certificate ?? $data->series_certificate;
                $data->nationality = $request->nationality ?? $data->nationality;
                $data->gender = $request->gender ?? $data->gender;
                $data->marital = $request->marital ?? $data->marital;
                $data->residential = $request->residential ?? $data->residential;
                $data->study = $request->study ?? $data->study;
                $data->education = $request->education ?? $data->education;
                $data->job = $request->job ?? $data->job;
                $data->save();

                $add = Address::where('profile_genuine_id', '=', $id)->first();
                $add->address = $request->address ?? $add->address;
                $add->postal_code = $request->postal_code ?? $add->postal_code;
                $add->home_number = $request->home_number ?? $add->home_number;
                $add->namabar = $request->namabar ?? $add->namabar;
                $add->work_address = $request->work_address ?? $add->work_address;
                $add->work_postal_code = $request->work_postal_code ?? $add->work_postal_code;
                $add->work_phone = $request->work_phone ?? $add->work_phone;
                $add->work_namabar = $request->work_namabar ?? $add->work_namabar;
                $add->save();
                return response()->json([
                    'success' => true,
                ], 202);
            } else
                return response()->json([], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $data = ProfileGenuine::find($id);
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
            $data = ProfileGenuine::with(['user', 'address'])->find($id);
            return response()->json(
                $data,
                200
            );
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function is_profile_genuine(): JsonResponse
    {
        try {
            $user = Auth::user();
            $exists = ProfileGenuine::where('user_id', $user->id)->exists();
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


