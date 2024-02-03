<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CheckDocRequest;
use App\Http\Requests\Admin\CheckDocUpdateRequest;
use App\Models\CheckDoc;
use App\Models\Requests;
use App\Models\User;
use App\Notifications\Notificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Kavenegar;

class CheckDocController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $data = CheckDoc::with(['request', 'request.user'])->get();
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

    public function store(CheckDocRequest $request): JsonResponse
    {
        try {
            $sender = Auth::user();
            $data = new CheckDoc();
            $data->request_id = $request->request_id;
            $data->is_accepted = $request->is_accepted;
            $data->is_failed = $request->is_failed;
            $data->save();
            $user_id = Requests::find($request->request_id)->user_id;
            $user = User::find($user_id);
            $requests = Requests::find($request->request_id);
            if ($data->is_accepted == false or $data->is_failed == true) {
                $user->notify(new Notificate($request->message, $sender->family, $request->request_id));
            } else {
                $message = 'مدارک شما برای درخواست ' . $requests->shenaseh . ' با موفقیت تایید شد.';
                $user->notify(new Notificate($message, $sender->family, $request->request_id));
            }
            $receptor = $user->phone;
            $token = $requests->shenaseh;
            $token2 = null;
            $token3 = null;
            $template = "notices";
            Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);
            return response()->json([
                'success' => true,
                'message_notif' => $request->message,
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function update(CheckDocUpdateRequest $request, $id): JsonResponse
    {
        try {
            $sender = Auth::user();
            $data = CheckDoc::find($id);
            if ($data) {
                $data->is_accepted = $request->is_accepted ?? $data->is_accepted;
                $data->is_failed = $request->is_failed ?? $data->is_failed;
                $data->save();
                $user_id = Requests::find($data->request_id)->user_id;
                $user = User::find($user_id);
                $requests = Requests::find($data->request_id);
                if ($data->is_accepted == false or $data->is_failed == true) {
                    $user->notify(new Notificate($request->message, $sender->family, $data->request_id));
                } else {
                    $message = 'مدارک شما برای درخواست ' . $requests->shenaseh . ' با موفقیت تایید شد.';
                    $user->notify(new Notificate($message, $sender->family, $data->request_id));
                }
                $receptor = $user->phone;
                $token = $requests->shenaseh;
                $token2 = null;
                $token3 = null;
                $template = "notices";
                Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);
                return response()->json([
                    'message_notif' => $request->message,
                    'success' => true,
                ], 202);
            } else
                return response()->json([], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $data = CheckDoc::find($id);
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
            $data = CheckDoc::with(['request', 'request.user'])->find($id);
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

    public function get_check_doc($id): JsonResponse
    {
        try {
            $data = CheckDoc::with(['request', 'request.user'])->where('request_id', '=', $id)->first();
            if ($data)
                return response()->json(
                    $data,
                    200
                );
            else
                return response()->json([
                    'message' => 'Your documents have not been reviewed !!'
                ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }
    
    public function get_failed(): JsonResponse
    {
        try {
            $data = CheckDoc::with(['request', 'request.user'])->where('is_failed', '=', true)->get();
            if ($data)
                return response()->json(
                    $data,
                    200
                );
            else
                return response()->json([
                    'message' => 'There are no rejected requests.'
                ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function get_unread_notif(): JsonResponse
    {
        try {
            $user = Auth::user();
//            $user = User::find(2);
//        $data = DB::table('notifications')->select('notifications.*')->where('notifiable_id', '=', $user->id)->get();
            foreach ($user->unreadNotifications as $notification) {
//                $notification->data = json_decode($notification->data['message']);
                $notification->markAsRead();
                //echo $notification->type;
            }
//        $data = $user->Notifications;
            $data = $user->unreadNotifications;
            $count = $user->unreadNotifications->count();
            return response()->json($data,
                200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function get_all_notif(): JsonResponse
    {
        try {
            $user = Auth::user();
//            $user = User::find(2);
            $data = $user->Notifications;
            return response()->json($data,
                200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }


}
