<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function createMessage(Request $request)
    {
        try {

            Log::info("Creating a message");

            $validator = Validator::make($request->all(), [
                'channel_id' => ['required', 'integer'],
                'message_text' => ['required', 'string', 'max:65535']
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => $validator->errors()
                    ],
                    400
                );
            };

            $channelId = $request->input('channel_id');
            $messageText = $request->input('message_text');
            $userId = auth()->user()->id;

            $message = new Message();

            $message->channel_id = $channelId;
            $message->message_text = $messageText;
            $message->user_id = $userId;

            $message->save();

            return response()->json(
                [
                    'success' => true,
                    'message' => "Message created"
                ],
                200
            );
        } catch (\Exception $exception) {
            Log::error("Error creating message: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error creating message"
                ],
                500
            );
        }
    }

    public function getAllMessagesByChannelId($id)
    {
        try {

            Log::info('Getting all messages');

            $channel = Channel::find($id);

            if (!$channel) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => "Missing channel"
                    ]
                );
            }

            $messages = Message::query()->where('channel_id', $id)->select('users.name', 'messages.message_text')
            ->join('users','messages.user_id','=','users.id')
            ->orderBy('messages.created_at','ASC')
            ->get()
            ->toArray();
           

            if ($messages == []) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => "No messages created"
                    ],
                    404
                );
            }

            return response()->json(
                [
                    'success' => true,
                    'message' => "Messages retrieved successfully",
                    'data' => $messages
                ],
                200
            );
        } catch (\Exception $exception) {
            Log::error("Error getting messages: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error getting messages"
                ],
                500
            );
        }
    }
}
