<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    const ROLE_USER = 1;

    public function register(Request $request)
    {
        try {

            Log::info('User register');

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);
    
    
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
    
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->password)
            ]);
    
            $user->roles()->attach(self::ROLE_USER);
    
            $token = JWTAuth::fromUser($user);
    
            return response()->json(compact('user', 'token'), 201);

        } catch (\Exception $exception) {

            Log::error("Error registering user: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error registering user'
                ],
                500
            );
        }    

    }

    public function login(Request $request)
    {
        try {

            Log::info('Login user');

            $input = $request->only('email', 'password');
            $jwt_token = null;
    
            if (!$jwt_token = JWTAuth::attempt($input)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Email or Password',
                ], Response::HTTP_UNAUTHORIZED);
            }
    
            return response()->json([
                'success' => true,
                'token' => $jwt_token,
            ]);

        } catch (\Exception $exception) {
            
            Log::error("Error login user: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error login user'
                ],
                500
            );
        }
    }

    public function getProfile()
    {
        return response()->json(auth()->user());  //data del token
    }

    public function logout(Request $request)
    {
        try{
        $this->validate($request, [
            'token' => 'required'
        ]);

            JWTAuth::invalidate($request->token);
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);  //status 500
        }
    }

    public function updateProfile(Request $request){
    
        try {

            Log::info('Updating user profile');
            
            $user_id = auth()->user()->id;
           
            if (!$user_id) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User not found'
                    ],
                    404
                );
            }

            $user = User::find($user_id);

            $validator = Validator::make($request->all(), [
                'name' => ['string', 'max:255'],
                'email' => ['string', 'max:255'],
                'password' => ['string', 'max:255', 'min:6'],
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

            $name = $request->input("name");
            $email = $request->input("email");
            $password = $request -> input("password");

            if (isset($name)) {
                $user->name = $name;
            }

            if (isset($email)) {
                $user->email = $email;
            }

            if (isset($password)) {
                $user->password = $password;
            }

            $user->save();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User updated',
                    'data' => $user
                ],
                201
            );
        } catch (\Exception $exception) {

            Log::error("Error updating user: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error updating user'
                ],
                500
            );
        }
    }

    public function deleteProfile(){

        try {
        
            Log::info('Deleting user profile');

            $user_id = auth()->user()->id;
           
            if (!$user_id) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User not found'
                    ],
                    404
                );
            }

            $user = User::find($user_id);

            $user->delete();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User deleted'
                ],
                200
            );

        } catch (\Exception $exception) {

            Log::error("Error deleting user: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error deleting user'
                ],
                500
            );
        }

    }

}
