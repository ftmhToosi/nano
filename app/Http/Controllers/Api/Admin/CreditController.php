<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreditRequest;
use App\Http\Requests\Admin\CreditUpdateRequest;
use App\Models\Credit;
use App\Models\Requests;
use App\Models\User;
use App\Notifications\Notificate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Str;
use Kavenegar;

class CreditController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $data = Credit::with(['request', 'request.user'])->get();
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

    public function store(CreditRequest $request): JsonResponse
    {
        try {
            
            $data = new Credit();
            $data->request_id = $request->request_id;
            $data->is_accepted = true;
             $file = $request->file('file');
            $filename = time().Str::random(10) .'.'.$file->getClientOriginalExtension();
            $folder = 'Credit';
            $file->move( $folder, $filename );
//            $path = Storage::put('public/Credit', $file);
            $data->file_name = $file->getClientOriginalName();
            $data->path = $folder .'/'. $filename;
            $data->save();

            $requests = Requests::find($request->request_id);
            $requests->is_finished = true;
            $requests->save();

            $sender = Auth::user();
            $user = User::find($requests->user_id);
            $message = 'حد اعتباری شما برای درخواست ' .$requests->shenaseh. ' اعلام گردید.';
            $user->notify(new Notificate($message, $sender->family, $request->request_id));

            $receptor = $user->phone;
            $token = $requests->shenaseh;
            $token2 = null;
            $token3 = null;
            $template = "result";
            Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);

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

    public function update(CreditUpdateRequest $request, $id): JsonResponse
    {
        try {
            $data = Credit::find($id);
            if ($data){
                $file = $request->file('file');
                $filename = time().Str::random(10) .'.'.$file->getClientOriginalExtension();
                $folder = 'Credit';
                $file->move( $folder, $filename );
//            $path = Storage::put('public/Credit', $file);
                $data->file_name = $file->getClientOriginalName();
                $data->path = $folder .'/'. $filename;
                $data->save();

                $sender = Auth::user();
                $requests = Requests::find($request->request_id);
                $user = User::find($requests->user_id);
                $message = 'حد اعتباری شما برای درخواست ' .$requests->shenaseh. ' ویرایش یافت.';
                $user->notify(new Notificate($message, $sender->family, $request->request_id));

                $receptor = $user->phone;
                $token = $requests->shenaseh;
                $token2 = null;
                $token3 = null;
                $template = "result";
                Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);
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
            $data = Credit::find($id);
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
            $data = Credit::with(['request', 'request.user'])->find($id);
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

    public function get_credit($id): JsonResponse
    {
        try {
            $data = Credit::with(['request', 'request.user'])->where('request_id', '=', $id)->first();
            if ($data)
                return response()->json(
                    $data,
                    200
                );
            else
                return response()->json([
                    'message' => 'You do not have an credit limit !!'
                ], 404);
        }catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
