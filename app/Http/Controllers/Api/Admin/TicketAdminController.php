<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TicketAdminUpdateRequest;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Notificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Kavenegar;

class TicketAdminController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();
//            if ($user->type == 'admin') {
            $data = Ticket::all();
            return response()->json(
                $data,
                200
            );
//            }
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function update(TicketAdminUpdateRequest $request, $id): JsonResponse
    {
        try {

            $data = Ticket::find($id);
            if ($data){
                $data->user2_id = $request->user2_id ?? $data->user2_id;
                $data->title = $request->title ?? $data->title;
                $data->category = $request->category ?? $data->category;
                $data->priority = $request->priority ?? $data->priority;
                $data->save();

                if ($request->user2_id){
                    $sender = User::find($data->user_id);
                    $user2 = User::find($data->user2_id);
                    $messages = 'شما یک تیکت جدید دارید';
                    $user2->notify(new Notificate($messages, $sender->family, 0));

                    $receptor = $user2->phone;
                    $token = $user2->family;
                    $token2 = null;
                    $token3 = null;
                    $template = "ticketExpert";
                    Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);
                }
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

    public function archive(): JsonResponse
    {
        try {
            $data = Ticket::where('status', '=', 'closed')->get();
            if ($data) {
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

    public function reopen($id): JsonResponse
    {
        try {
            $data = Ticket::find($id);
            if ($data->status == 'closed'){
                $data->status = 'open';
                $data->save();
                return response()->json([
                    'success' => true,
                ], 202);
            }
            else
                return response()->json([], 404);
        }catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function opened(): JsonResponse
    {
        try {
            $data = Ticket::where('status', '!=', 'closed')->get();
            if ($data) {
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

    public function unresolved(): JsonResponse
    {
        try {
            $data = Ticket::where('status', '!=', 'resolved')->get();
            if ($data) {
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
