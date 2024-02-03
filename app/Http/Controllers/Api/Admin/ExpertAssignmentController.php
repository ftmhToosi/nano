<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExpertAssignmentRequest;
use App\Models\Assessment;
use App\Models\CheckDoc;
use App\Models\Committee;
use App\Models\Credit;
use App\Models\ExpertAssignment;
use App\Models\Report;
use App\Models\Requests;
use App\Models\User;
use App\Notifications\Notificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kavenegar;

class ExpertAssignmentController extends Controller
{
    public function expert_assignment(ExpertAssignmentRequest $request): JsonResponse
    {
        try {
            $data = new ExpertAssignment();
            $data->requests_id = $request->requests_id;
            $data->user2_id = $request->user2_id;
            $data->save();

            $sender = Auth::user();
            $expert = User::find($request->user2_id);
            $requests = Requests::find($request->requests_id);
            $message = 'درخواست جدید به شناسه ' .$requests->shenaseh. ' به شما اختصاص یافت.';
            $expert->notify(new Notificate($message, $sender->family, $request->requests_id));

            $receptor = $expert->phone;
            $token = $requests->shenaseh;
            $token2 = null;
            $token3 = null;
            $template = "assign";
            Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);
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

    public function change_expert(ExpertAssignmentRequest $request): JsonResponse
    {
        try {
            $data = ExpertAssignment::where('requests_id', '=', $request->requests_id)->first();
            $data->user2_id = $request->user2_id;
            $data->save();
            CheckDoc::where('request_id', '=', $data->requests_id)->delete();
            Assessment::where('request_id', '=', $data->requests_id)->delete();
            Report::where('request_id', '=', $data->requests_id)->delete();
            Committee::where('request_id', '=', $data->requests_id)->delete();
            Credit::where('request_id', '=', $data->requests_id)->delete();

            $sender = Auth::user();
            $expert = User::find($request->user2_id);
            $requests = Requests::find($data->requests_id);
            $message = 'درخواست جدید به شناسه ' .$requests->shenaseh. ' به شما اختصاص یافت.';
            $expert->notify(new Notificate($message, $sender->family, $data->requests_id));

            $receptor = $expert->phone;
            $token = $requests->shenaseh;
            $token2 = null;
            $token3 = null;
            $template = "assign";
            Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);

            return response()->json([
                'success' => true,
            ], 202);
        }catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function get_expert($id): JsonResponse
    {
        try {
            $user = Auth::user();
            $data = ExpertAssignment::with('expert')->where('request_id', '=', $id)
//                ->whereHas('request', function($query) use ($user){
//                $query->where('user_id', '=', $user->id);
//            })
                ->get();
            if ($data)
                return response()->json(
                    $data,
                    200
                );
            else
                return response()->json([
                    'message' => 'You do not have an expert !!'
                ], 404);
        }catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function get_request_with_expert($id): JsonResponse
    {
        try {
//            $user = Auth::user();
            $data = ExpertAssignment::with(['request', 'request.facilities', 'request.warranty'])->where('user2_id', '=', $id)->get();
            if ($data)
                return response()->json(
                    $data,
                    200
                );
            else
                return response()->json([
                    'message' => 'You do not have an referred !!'
                ], 404);
        }catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function get_current_requests($id): JsonResponse
    {
        try {
//            $user = Auth::user();
            $data = ExpertAssignment::with('request')
                ->where('user2_id', '=', $id)
                ->whereHas('request', function ($query) {
                    $query->where('is_finished', false);
                })
                ->get();
            if ($data)
                return response()->json(
                    $data,
                    200
                );
            else
                return response()->json([
                    'message' => 'You have no current requests !!'
                ], 404);
        }catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function get_count_projects(Request $request): JsonResponse
    {
        try {
            $data = ExpertAssignment::with('expert')->where('user2_id', '=', $request->user2_id)->count();
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
}
