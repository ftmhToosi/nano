<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ConfirmReportRequest;
use App\Http\Requests\Admin\ReportRequest;
use App\Http\Requests\Admin\ReportUpdateRequest;
use App\Models\ExpertAssignment;
use App\Models\Report;
use App\Models\Requests;
use App\Models\User;
use App\Notifications\Notificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Kavenegar;

class ReportController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $data = Report::with(['request', 'request.user'])->get();
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

    public function store(ReportRequest $request): JsonResponse
    {
        try {
            $sender = Auth::user();
            $data = new Report();
            $data->request_id = $request->request_id;
            $data->is_accepted = false;
           $file = $request->file('file');
//            $path = Storage::put('public/Reports', $file);
            $filename = time().Str::random(10) .'.'.$file->getClientOriginalExtension();
            $folder = 'Reports';
            $file->move( $folder, $filename );
            $data->file_name = $file->getClientOriginalName();
            $data->path = $folder .'/'. $filename;
            $data->save();

            $requests = Requests::find($request->request_id);
            $message = 'یک فایل گزارش برای درخواست ' .$requests->shenaseh. ' برای بررسی دارید';
            $admins = User::where('type', '=', 'admin')->get();
            foreach ($admins as $admin){
                $admin->notify(new Notificate($message, $sender->family, $request->request_id));
                $receptor = $admin->phone;
                $token = 'مدیرمحترم';
                $token2 = null;
                $token3 = null;
                $template = "noticesAdmin";
                Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);
            }

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

    public function confirm_report_admin(ConfirmReportRequest $request, $id): JsonResponse
    {
        try {
            $data = Report::find($id);
            if ($data) {
                $data->is_accepted = $request->is_accepted;
                $user_id = ExpertAssignment::query()->where('requests_id', '=', $data->request_id)->first()->user2_id;
                $user = User::find($user_id);
                $sender = Auth::user();
                if ($request->is_accepted == false) {
                    $user->notify(new Notificate($request->message, $sender->family, $data->request_id));
                } else {
                    $requests = Requests::find($data->request_id);
                    $message = 'گزارش مرحله ارزیابی برای درخواست ' .$requests->shenaseh. ' تایید شد';
                    $user->notify(new Notificate($message, $sender->family, $data->request_id));
                }
                $data->save();

                $receptor = $user->phone;
                $token = $user->family;
                $token2 = null;
                $token3 = null;
                $template = "noticesExpert";
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

    public function update(ReportUpdateRequest $request, $id): JsonResponse
    {
        try {
            $data = Report::where('request_id', '=', $id)->first();
            if ($data) {
                $sender = Auth::user();
                $file = $request->file('file');
//            $path = Storage::put('public/Reports', $file);
                $filename = time().Str::random(10) .'.'.$file->getClientOriginalExtension();
                $folder = 'Reports';
                $file->move( $folder, $filename );
                $data->file_name = $file->getClientOriginalName();
                $data->path = $folder .'/'. $filename;
                $data->save();

                $requests = Requests::find($data->request_id);
                $message = 'یک فایل گزارش ویرایش شده برای درخواست ' .$requests->shenaseh. ' برای بررسی دارید';
                $admins = User::where('type', '=', 'admin')->get();
                foreach ($admins as $admin){
                    $admin->notify(new Notificate($message, $sender->family, $data->request_id));
                    $receptor = $admin->phone;
                    $token = 'مدیرمحترم';
                    $token2 = null;
                    $token3 = null;
                    $template = "noticesAdmin";
                    Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);
                }
                return response()->json([
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
            $data = Report::find($id);
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
            $data = Report::with(['request', 'request.user'])->find($id);
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

    public function get_report($id): JsonResponse
    {
        try {
            $data = Report::select('id', 'is_accepted', 'request_id')->with(['request', 'request.user'])->where('request_id', '=', $id)->first();
            if ($data)
                return response()->json(
                    $data,
                    200
                );
            else
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have an Evaluation report !!'
                ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function get_report_for_admin($id): JsonResponse
    {
        try {
            $data = Report::with(['request', 'request.user'])->where('request_id', '=', $id)->first();
            if ($data)
                return response()->json(
                    $data,
                    200
                );
            else
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have an Evaluation report !!'
                ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
