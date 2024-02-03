<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MessageUpdateRequest;
use App\Http\Requests\Admin\TicketExpertRequest;
use App\Http\Requests\Admin\TicketExpertUpdateRequest;
use App\Http\Requests\User\TicketUpdateRequest;
use App\Models\Message;
use App\Models\Requests;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Notificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Kavenegar;

class TicketExpertController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();
            $data = Ticket::where('user2_id', '=', $user->id)->get();
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

    public function store(TicketExpertRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $data = new Ticket();
            $user_id = Requests::find($request->request_id)->user_id;
            $data->user_id = $user_id;
            $data->user2_id = $user->id;
            $data->title = $request->title;
            $data->category = $request->category;
            $data->status = 'waiting';
            $data->priority = $request->priority;
            $data->last_update = now();
            $data->is_resolved = false;
//            $data->is_locked = false;
            $data->save();

            $message = new Message();
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

            $user2 = User::find($user_id);
            $messages = 'شما یک تیکت جدید دارید';
            $user2->notify(new Notificate($messages, $user->family, 0));

            $receptor = $user2->phone;
            $token = 'کاربرگرامی';
            $token2 = null;
            $token3 = null;
            $template = "ticket";
            Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);

            return response()->json([
                'success' => true,
                'last_id' => $data->id,
            ], 201);
            
        } catch(\Kavenegar\Exceptions\ApiException $e){
            // در صورتی که خروجی وب سرویس 200 نباشد این خطا رخ می دهد
            echo $e->errorMessage();
        }
        catch(\Kavenegar\Exceptions\HttpException $e){
            // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
            echo $e->errorMessage();
        }
    }

    public function update(TicketExpertUpdateRequest $request, $id): JsonResponse
    {
        try {
            $data = Ticket::find($id);
            if ($data) {
                $data->title = $request->title ?? $data->title;
                $data->category = $request->category ?? $data->category;
                $data->priority = $request->priority ?? $data->priority;
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

    public function update_message(MessageUpdateRequest $request, $id): JsonResponse
    {
        try {
            $data = Message::find($id);
            if ($data) {
                $data->body = $request->body ?? $data->body;
                if ($request->file('file')) {
                    $file = $request->file('file');
                    $filename = time().Str::random(10) .'.'.$file->getClientOriginalExtension();
                    $folder = 'Ticket';
                    $file->move( $folder, $filename );
                    $data->file_name = $file->getClientOriginalName();
                    $data->path = $folder .'/'. $filename;
                }
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

    public function reply(TicketUpdateRequest $request, $id): JsonResponse
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

                $data->status = 'resolved';
                $data->is_resolved = true;
                $data->last_update = now();
                $data->save();


                $user2 = User::find($data->user_id);
                $messages = 'به تیکت شما پاسخ داده شد.';
                $user2->notify(new Notificate($messages, $user->family, 0));

                $receptor = $user2->phone;
                $token = 'کاربرگرامی';
                $token2 = null;
                $token3 = null;
                $template = "replyTicket";
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

    public function destroy($id): JsonResponse
    {
        try {
            $data = Ticket::find($id);
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

    public function archive(): JsonResponse
    {
        try {
            $expert = Auth::user();
            $data = Ticket::where('user2_id', '=', $expert->id)->where('status', '=', 'closed')->get();
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


    public function opened(): JsonResponse
    {
        try {
            $expert = Auth::user();
            $data = Ticket::where('user2_id', '=', $expert->id)->where('status', '!=', 'closed')->get();
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

    public function unresolved(): JsonResponse
    {
        try {
            $expert = Auth::user();
            $data = Ticket::where('user2_id', '=', $expert->id)->where('status', '!=', 'resolved')->get();
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
