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

    public function updateMessageById(Request $request, $messageId){
    
        try {

            Log::info('Updating message');
            
            $userId = auth()->user()->id;
           
            if (!$userId) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User not found'
                    ],
                    404
                );
            }

            $message = Message::find($messageId);
            
            $validator = Validator::make($request->all(), [
                'message_text' => ['required','string', 'max:65535']
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => $validator->errors()
                    ],
                    400
                );
            }

            if ($message->user_id != $userId) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Message created by another user'
                    ]
                );
            }


            $messageText = $request->input('message_text');
        
            $message->message_text = $messageText;
            
            $message->save();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Message updated',
                    'data' => $message
                ],
                201
            );
        }catch (\Exception $exception) {

            Log::error("Error updating message: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error updating message'
                ],
                500
            );
        }
    }

     public function deleteMessageById($messageId){

        try {
        
            Log::info('Deleting message');

            $userId = auth()->user()->id;
           
            if (!$userId) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User not found'
                    ],
                    404
                );
            }

            $message = Message::find($messageId);

            if (!$message) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => "Missing message"
                    ]
                );
            }

            if ($message->user_id != $userId) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Message created by another user'
                    ]
                );
            }
            //If userId and the message specified with the Id, are okey, proceed with the deletion
            $message->delete();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Message deleted'
                ],
                200
            );

        } catch (\Exception $exception) {

            Log::error("Error deleting message: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error deleting message'
                ],
                500
            );
        }
    }
}
