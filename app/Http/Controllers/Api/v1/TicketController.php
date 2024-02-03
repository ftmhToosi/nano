<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\TicketRequest;
use App\Http\Requests\User\TicketUpdateRequest;
use App\Models\ExpertAssignment;
use App\Models\Message;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Notificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Kavenegar;

class TicketController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();
            $data = Ticket::with('message')->where('user_id', '=', $user->id)->get();
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

    public function store(TicketRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $data = new Ticket();
            $admin = User::where('type', '=', 'admin')->first();
            if ($request->request_id) {
                $expert = ExpertAssignment::with('expert')->where('requests_id', '=', $request->request_id)->first();
                if ($expert)
                    $data->user2_id = $expert->user2_id;
                else
                    $data->user2_id = $admin->id;
            } else
                $data->user2_id = 2;
            $data->user_id = $user->id;
            $data->title = $request->title;
            $data->category = $request->category;
            $data->status = 'waiting';
            $data->priority = $request->priority;
            $data->last_update = now();
            $data->is_resolved = false;
//            $data->is_locked = false;
            $data->save();

            $message = new Message();
            $message->ticket_id = $data->id;
            $message->user_id = $user->id;
            $message->body = $request->body;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time().Str::random(10) .'.'.$file->getClientOriginalExtension();
                $folder = 'Ticket';
                $file->move( $folder, $filename );
                $message->file_name = $file->getClientOriginalName();
                $message->path = $folder .'/'. $filename;
            }
            $message->save();

            $user2 = User::find($data->user2_id);
            $messages = 'شما یک تیکت جدید دارید';
            $user2->notify(new Notificate($messages, $user->family, 0));

            $receptor = $user2->phone;
            $token = $user2->family;
            $token2 = null;
            $token3 = null;
            $template = "ticketExpert";
            Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);

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


    public function update(TicketUpdateRequest $request, $id): JsonResponse //reply
    {
        try {
            $data = Ticket::find($id);
            if ($data->status != 'closed') {
                $message = new Message();
                $user = Auth::user();
                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $filename = time().Str::random(10) .'.'.$file->getClientOriginalExtension();
                    $folder = 'Ticket';
                    $file->move( $folder, $filename );
                    $message->file_name = $file->getClientOriginalName();
                    $message->path = $folder .'/'. $filename;
                }
                $message->ticket_id = $data->id;
                $message->user_id = $user->id;
                $message->body = $request->body;
                $message->save();

                $data->status = 'waiting';
                $data->is_resolved = false;
                $data->last_update = now();
                $data->save();

                $user2 = User::find($data->user2_id);
                $messages = 'شما یک پیام جدید دارید';
                $user2->notify(new Notificate($messages, $user->family, 0));

                $receptor = $user2->phone;
                $token = $user2->family;
                $token2 = null;
                $token3 = null;
                $template = "replyUser";
                Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);
                return response()->json([
                    'success' => true,
                    'last_id' => $data->id,
                ], 201);
            } else
                return response()->json([], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function close_ticket($id): JsonResponse
    {
        try {
            $data = Ticket::find($id);
            if ($data) {
                $data->status = 'closed';
                $data->save();
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

    public function show($id): JsonResponse
    {
        try {
            $data = Ticket::with(['message', 'message.user'])->find($id);
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
}
