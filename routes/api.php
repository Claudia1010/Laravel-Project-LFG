<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AuthController;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//no auth required
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//routes for users with authentification
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::post('/logout', [AuthController::class, 'logout']); 
    Route::put('/update', [AuthController::class, 'updateProfile']);
    Route::delete('/delete', [AuthController::class, 'deleteProfile']);
});

//Routes for users with authentication
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::post('/accessChannel', [UserController::class, 'accessChannel']); 
    Route::post('/leaveChannel', [UserController::class, 'leaveChannel']);
});

//Routes for users with authentication
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::get('/getAllGames', [GameController::class, 'getAllGames']);
});

//Routes for users with authentication
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::get('/getAllChannels', [ChannelController::class, 'getAllChannels']);
});

//Routes for messages, only user authentication required
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::post('/createMessage', [MessageController::class, 'createMessage']); 
    Route::get('/getAllMessages/{id}', [MessageController::class, 'getAllMessagesByChannelId']);
    Route::put('/updateMessage/{id}', [MessageController::class, 'updateMessageById']);
    Route::delete('/deleteMessage/{id}', [MessageController::class, 'deleteMessageById']);
});

//Routes for games only for admins
Route::group(["middleware" => ["jwt.auth", "isAdmin"]] , function() {
    Route::post('/createGame', [GameController::class, 'createGame']); 
    Route::get('/getMyGames', [GameController::class, 'getMyGames']);
    Route::put('/updateMyGame/{id}', [GameController::class, 'updateMyGame']);
    
});

//Routes for channels only for admins
Route::group(["middleware" => ["jwt.auth", "isAdmin"]] , function() {
    Route::post('/createChannel', [ChannelController::class, 'createChannel']); 
    Route::get('/findChannelsById/{id}', [ChannelController::class, 'findChannelByGameId']);
    Route::put('/updateChannel/{id}', [ChannelController::class, 'updateChannelById']);
    
});

//Superadmin functions 
Route::group(["middleware" => ["jwt.auth", "isSuperAdmin"]] , function() {
    Route::post('/user/admin/{id}', [UserController::class, 'userToAdmin']);
    Route::post('/user/remove_admin/{id}', [UserController::class, 'adminToUser']);
    Route::post('/user/super_admin/{id}', [UserController::class, 'userToSuperAdmin']);
    Route::post('/user/remove_superadmin/{id}', [UserController::class, 'superAdminToUser']);
    Route::get('/user/get_all_admins',[UserController::class, 'getAllAdmins']);
    Route::get('/getAllUsers', [AuthController::class, 'getAllUsers']);
    Route::delete('/deleteChannel/{id}', [ChannelController::class, 'deleteChannelById']);
    Route::delete('/deleteGame/{id}', [GameController::class, 'deleteGame']);
});
