<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExpertRequest;
use App\Http\Requests\Admin\ExpertUpdateRequest;
use App\Models\Address;
use App\Models\ExpertAssignment;
use App\Models\ProfileGenuine;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Kavenegar;

class ExpertController extends Controller
{


    public function index(): JsonResponse
    {
        try {
//            $data = User::with(['profilegenuine', 'profilegenuine.address'])->where('type', '=', 'expert')->get();
            $data = User::with(['profilegenuine', 'profilegenuine.address'])
                ->where(function ($q) {
                    $q->where('type', 'expert');
                });

            if ($keyword = request('search')) {
                $data->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('family', 'LIKE', "%{$keyword}%")
                    ->orWhere('national_code', '=', $keyword)
                    ->orWhere('phone', '=', $keyword)
                    ->orWhere('email', '=', $keyword);
            }

            $data = $data->get();

            $data->each(function ($user) {
                $count = ExpertAssignment::where('user2_id', $user->id)->count();
                $user->project_count = $count;
            });
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

    public function store(ExpertRequest $request): JsonResponse
    {
        try {
            $user = new User();
            $user->national_code = $request->national_code;
            $user->name = $request->name;
            $user->family = $request->family;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->password_confirmation = Hash::make($request->password_confirmation);
            $user->type = 'expert';
            $user->is_confirmed = true;
            // $user->assignRole('expert');
            $user->save();

            $profile = new ProfileGenuine();
            $profile->user_id = $user->id;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time().Str::random(10) .'.'.$file->getClientOriginalExtension();
                $folder = 'Profile';
                $file->move( $folder, $filename );
                $profile->image = $folder .'/'. $filename;
            }
            $profile->father_name = $request->father_name;
            $profile->number_certificate = $request->number_certificate;
            $profile->birth_day = $request->birth_day;
            $profile->place_issue = $request->place_issue;
            $profile->series_certificate = $request->series_certificate; //
            $profile->nationality = $request->nationality; //
            $profile->gender = $request->gender;
            $profile->marital = $request->marital;
            $profile->residential = $request->residential;
            $profile->study = $request->study;
            $profile->education = $request->education;
            $profile->job = $request->job;
            $profile->save();

            $add = new Address();
            $add->profile_genuine_id = $profile->id;
            $add->address = $request->address;
            $add->postal_code = $request->postal_code;
            $add->home_number = $request->home_number;
            $add->namabar = $request->namabar;
            $add->work_address = $request->work_address; //
            $add->work_postal_code = $request->work_postal_code;
            $add->work_phone = $request->work_phone;
            $add->work_namabar = $request->work_namabar;
            $add->save();


            $receptor = $user->phone;
            $token = $user->national_code;
            $token2 = $request->password;
            $token3 = null;
            $template="addExpert";
            Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);

            $token = Auth::login($user);
            return response()->json([
                'status' => 'success',
                'message' => 'Expert created successfully',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function update(ExpertUpdateRequest $request, $id): JsonResponse
    {
        try {
            $user = User::find($id);
            if ($user){
                $user->national_code = $request->national_code ?? $user->national_code;
                $user->name = $request->name ?? $user->name;
                $user->family = $request->family ?? $user->family;
                $user->phone = $request->phone ?? $user->phone;
                $user->email = $request->email ?? $user->email;
                if ($request->password) {
                    $user->password = Hash::make($request->password);
                    $user->password_confirmation = Hash::make($request->password_confirmation);
                }

                if ($request->password or $request->national_code){
                    $receptor = $user->phone;
                    $token = $user->national_code;
                    $token2 = $request->password;
                    $token3 = null;
                    $template="editExpert";
                    Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);
                }
                $user->save();

                $profile = ProfileGenuine::where('user_id', '=', $user->id)->first();
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $filename = time().Str::random(10) .'.'.$file->getClientOriginalExtension();
                    $folder = 'Profile';
                    $file->move( $folder, $filename );
                    $profile->image = $folder .'/'. $filename;
                }
                $profile->father_name = $request->father_name ??$profile->father_name;
                $profile->number_certificate = $request->number_certificate ??$profile->number_certificate;
                $profile->birth_day = $request->birth_day ??$profile->birth_day;
                $profile->place_issue = $request->place_issue ??$profile->place_issue;
                $profile->series_certificate = $request->series_certificate ??$profile->series_certificate;
                $profile->nationality = $request->nationality ??$profile->nationality;
                $profile->gender = $request->gender ??$profile->gender;
                $profile->marital = $request->marital ??$profile->marital;
                $profile->residential = $request->residential ??$profile->residential;
                $profile->study = $request->study ?? $profile->study;
                $profile->education = $request->education ??$profile->education;
                $profile->job = $request->job ??$profile->job;
                $profile->save();

                $add = Address::where('profile_genuine_id', '=', $profile->id)->first();
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
            $data = User::with(['profilegenuine', 'profilegenuine.address'])->find($id);
            if ($data->type == 'expert') {
                return response()->json(
                    $data,
                    200
                );
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
}


