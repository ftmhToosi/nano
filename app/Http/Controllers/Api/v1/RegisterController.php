<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangPassRequest;
use App\Http\Requests\Auth\ConfirmRequest;
use App\Http\Requests\Auth\ForgetPassRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\VerifyRequest;
use App\Models\Confirmation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Kavenegar;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = new User();
            if ($request->type == 'legal') {
                $user->national_company = $request->national_company;
                $user->company_name = $request->company_name;
            }
            else
                $user->national_code = $request->national_code;
            $user->name = $request->name;
            $user->family = $request->family;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->password_confirmation = Hash::make($request->password_confirmation);
            $user->type = $request->type;
            $user->is_confirmed = $request->is_confirmed;
            // $user->assignRole('user');
            $user->save();


            $token = Auth::login($user);
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function verify(VerifyRequest $request)
    {
        try{
            $data = new Confirmation();
            $user = User::where('phone', '=', $request->phone)->first();
            $data->user_id = $user->id;
            $receptor = $user->phone;
            $token = mt_rand(100000, 999999);
            $data->verification_code = $token;
            $data->save();
            $token2 = null;
            $token3 = null;
            $template="verify";
            Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);
        }
        catch(\Kavenegar\Exceptions\ApiException $e){
            echo $e->errorMessage();
        }
        catch(\Kavenegar\Exceptions\HttpException $e){
            echo $e->errorMessage();
        }
    }

    public function confirmation(ConfirmRequest $request): JsonResponse
    {
        try {
            $user = User::where('phone', '=', $request->phone)->first();
            $data = Confirmation::where('user_id', '=', $user->id)->latest()->first();
//
            if ($data && $data->verification_code == $request->code){
                return response()->json([
                    'status' => 'success',
                    'message' => 'User verified successfully',
                    'user' => $user,
                ], 200);
            }
            else
                return response()->json([
                    'status' => 'false',
                    'message' => 'User not verified',
                ], 403);
        }catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $input = $request->all();
        $fieldType = $this->getFieldType($input['username']);


        if ($fieldType == 'national_code') {
            if (!$token = Auth::attempt(['national_code' => $input['username'], 'password' => $input['password']])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'رمزعبور اشتباه است',
                ], 403);
            }
        } elseif ($fieldType == 'national_company') {
            if (!$token = Auth::attempt(['national_company' => $input['username'], 'password' => $input['password']])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'رمزعبور اشتباه است',
                ], 403);
            }
        } else {
            return response()->json([
                'status' => 'error',
            ], 404);
        }

        $user = Auth::user();

        $roles = $user->getRoleNames();
        $permissions = $user->getAllPermissions()->pluck('name');
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    private function getFieldType($input): string
    {
        if (preg_match('/^[0-9]{10}$/',$input)) {
            return 'national_code';
        } elseif (preg_match('/^[0-9]{11}$/',$input)) {
            return 'national_company';
        } else {
            return 'unknown';
        }
    }

    public function logout(): JsonResponse
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function change_pass(ChangPassRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            if ($user) {
                if (Hash::check($request->old_password , $user->password))
                {
                    $user->password = Hash::make($request->password);
                    $user->password_confirmation = Hash::make($request->password_confirmation);
                    $user->save();
                    return response()->json([
                        'success' => true,
                    ], 202);
                }
                else {
                    return response()->json(
                        ['message' => 'password not correct'],
                        401
                    );
                }
            } else {
                return response()->json(
                    ['message' => 'user not found'],
                    404
                );
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }


    public function forget_pass(ForgetPassRequest $request): JsonResponse
    {
        try {
            $user = User::where('phone', '=', $request->phone)->first();
            if ($user) {
                $new_pass = Str::random(8, 'alnum|punct');
//                dd($new_pass);
                $user->password = Hash::make($new_pass);
                $user->password_confirmation = Hash::make($new_pass);
                $user->save();
                $receptor = $request->phone;
                $token = $new_pass;
                $token2 = null;
                $token3 = null;
                $template="forgetPass";
                Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);
                return response()->json(
                    ['message' => 'success'],
                    200
                );
            }
            else
                return response()->json(
                    ['message' => 'user not found'],
                    404
                );
        }catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }


}
