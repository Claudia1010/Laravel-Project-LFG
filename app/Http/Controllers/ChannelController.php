<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ChannelController extends Controller
{
    public function createChannel(Request $request)
    {
        try {
            Log::info('Creating channel');
        
            $validator = Validator::make($request->all(), [
                'channel_name' => ['required', 'string', 'max:255', 'min:3'],
                'game_id' => ['required', 'integer']
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

            $channelName = $request->input("channel_name");
            $gameId = $request->input("game_id");

            $channel = new Channel();
            
            $channel->channel_name = $channelName;
            $channel->game_id = $gameId;
          
            $channel->save();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'New channel created'
                ],
                201
            );
        } catch (\Exception $exception) {
            Log::error("Error creating channel: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error creating channel'
                ],
                500
            );
        }
    }

    public function findChannelByGameId($id)
    {
        try {

            Log::info('Finding channels');

            $channels = Channel::query()
                ->where('game_id', '=', $id)//busca el id (pasado por params)= a la clave foranea game_id en el modelo de channel
                ->get()
                ->toArray();

            if (!$channels) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => "Channel doesnt exists"
                    ],
                    404
                );
            };

            return response()->json(
                [
                    'success' => true,
                    'message' => "Getting channels",
                    'data' => $channels
                ],
                200
            );

        } catch (\Exception $exception) {
            Log::error("Error getting channels: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error getting channels"
                ],
                500
            );
        }
    }

    public function updateChannelById(Request $request, $id){
    
        try {

            Log::info('Updating channel');
            
            $adminId = auth()->user()->id;
           
            if (!$adminId) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User not found'
                    ],
                    404
                );
            }

            $channel = Channel::find($id);
            
            $validator = Validator::make($request->all(), [
                'channel_name' => ['required','string', 'max:255'],
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

            $channelName = $request->input('channel_name');

            $channel->channel_name = $channelName;

            $channel->save();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Channel name updated',
                    'data' => $channel
                ],
                201
            );
        } catch (\Exception $exception) {

            Log::error("Error updating channel: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error updating channel'
                ],
                500
            );
        }
    }

    public function deleteChannelById($id){

        try {
        
            Log::info('Deleting channel');

            $adminId = auth()->user()->id;
           
            if (!$adminId) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Admin not found'
                    ],
                    404
                );
            }

            $channel = Channel::find($id);

            if (!$channel) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => "Missing channel"
                    ]
                );
            }
            //If adminId and the channel specified with the Id, are okey, proceed with the deletion
            $channel->delete();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Channel deleted'
                ],
                200
            );

        } catch (\Exception $exception) {

            Log::error("Error deleting channel: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error deleting channel'
                ],
                500
            );
        }
    }
}


