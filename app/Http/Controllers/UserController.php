<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function accessChannel(Request $request){
        try {

            Log::info('Entering channel');

            $validator = Validator::make($request->all(), [
                'channel_id' => 'required|integer'
            ]);
    
    
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            //si el channelId es válido, guardo el id pasado en la variable channelId
            $channelId = $request->input('channel_id');
            //$channel es el objeto channel que coincide con el id pasado
            $channel = Channel::find($channelId);

            if(!$channel){
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Missing channel'
                    ],
                    404
                );
            }
            
            $userId = auth()->user()->id;

            $user = User::find($userId);

            $user->channels()->attach($channelId);

            return response()->json([
                'success' => true,
                'message' => 'User joined successfully'
            ]);

        } catch (\Exception $exception) {

            Log::error("Error accessing channel: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error accessing channel'
                ],
                500
            );
        }
    }
}