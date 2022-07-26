<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;

class UserController extends Controller
{
    const ROLE_DEFAULT_USER = 1;
    const ROLE_ADMIN = 2;
    const ROLE_SUPER_ADMIN = 3;

    public function accessChannel(Request $request){
        
        try {

            Log::info('Accessing channel');

            $validator = Validator::make($request->all(), [
                'channel_id' => ['required', 'integer']
            ]);
    
    
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            
            //checked if the channelId is correct,and save it in channelId
            $channelId = $request->input('channel_id');

            //$channel is the object channel that matchs with the channelId
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
                'message' => 'User joined  to channel successfully'
            ], 200
        );

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

    public function leaveChannel(Request $request){

        try {

            Log::info('Leaving channel');

            $validator = Validator::make($request->all(), [
                'channel_id' => ['required', 'integer']
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

            $user->channels()->detach($channelId);

            return response()->json([
                'success' => true,
                'message' => 'Channel left successfully'
            ]);

        } catch (\Exception $exception) {

            Log::error("Error leaving channel: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error leaving channel'
                ],
                500
            );
        }
    }

    public function userToAdmin($userId) {

        try {
            
            Log::info("Upgrading user to admin");

            $user = User::find($userId);

            if (!$user) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User not found'
                    ],
                    404
                );
            }

            $user->roles()->attach(self::ROLE_ADMIN);

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User '. $user->name .' promoted to admin'
                ],
                201
            );

        } catch (\Exception $exception) {
            
            Log::error("Error promoting user to admin" . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error promoting user to admin'
                ],
                500
            );
        }
    }

    public function adminToUser($userId){
        try {

            Log::info("Degrading admin to user");

            $user = User::find($userId);

            if(!$user){
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User not found'
                    ],
                    404
                );
            }

            $user->roles()->detach(self::ROLE_ADMIN);

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Admin '. $user->name .' degraded to user'
                ],
                201
            );

        } catch (\Exception $exception) {
            Log::error("Error degrading admin to user" . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error degrading admin to user'
                ],
                500
            );
        }
    }

    public function userToSuperAdmin($userId) {

        try {
            
            $user = User::find($userId);

            if (!$user) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User not found'
                    ],
                    404
                );
            }

            // $user->roles()->detach(self::ROLE_DEFAULT_USER);
            $user->roles()->attach(self::ROLE_SUPER_ADMIN);

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User '. $user->name .' promoted to super_admin'
                ],
                201
            );

        } catch (\Exception $exception) {
            
            Log::error("Error promoting user to super_admin" . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error promoting user to super_admin'
                ],
                500
            );
        }
    }


    public function superAdminToUser($userId){
        try {

            Log::info("Degrading super admin to user");

            $user = User::find($userId);

            if(!$user){
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User not found'
                    ],
                    404
                );
            }

            $user->roles()->detach(self::ROLE_SUPER_ADMIN);
            
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Super admin '. $user->name .' degraded to user'
                ],
                201
            );

        } catch (\Exception $exception) {
            Log::error("Error degrading super admin to user" . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error degrading super admin to user'
                ],
                500
            );
        }
    }

    public function getAllUsers()
    {
        try {
            Log::info('Getting all users');
            $users = User::all();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Users retrieved successfully',
                    'data' => $users
                ]
            );

        } catch (\Exception $exception) {
            Log::error("Error retrieving users " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error retrieving users'
                ],
                500
            );
        }
    }

    
}
