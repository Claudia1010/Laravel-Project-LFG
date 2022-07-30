<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AuthController;
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


//no token required
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//routes for users with token
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::post('/logout', [AuthController::class, 'logout']); 
    Route::put('/update', [AuthController::class, 'updateProfile']);
    Route::delete('/delete', [AuthController::class, 'deleteProfile']);
});

//Routes for users with token
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::post('/accessChannel', [UserController::class, 'accessChannel']); 
    Route::post('/leaveChannel', [UserController::class, 'leaveChannel']);
});

//Routes for users with token
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::get('/getAllGames', [GameController::class, 'getAllGames']);
});

//Routes for users with token
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::get('/getAllChannels', [ChannelController::class, 'getAllChannels']);
});

//Routes for messages with token
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::post('/createMessage', [MessageController::class, 'createMessage']); 
    Route::get('/getAllMessages/{id}', [MessageController::class, 'getAllMessagesByChannelId']);
    Route::put('/updateMessage/{id}', [MessageController::class, 'updateMessageById']);
    Route::delete('/deleteMessage/{id}', [MessageController::class, 'deleteMessageById']);
});

//Routes for games with Admin Token
Route::group(["middleware" => ["jwt.auth", "isAdmin"]] , function() {
    Route::post('/createGame', [GameController::class, 'createGame']); 
    Route::get('/getMyGames', [GameController::class, 'getMyGames']);
    Route::put('/updateMyGame/{id}', [GameController::class, 'updateMyGame']);
});

//Routes for channels with Admin Token
Route::group(["middleware" => ["jwt.auth", "isAdmin"]] , function() {
    Route::post('/createChannel', [ChannelController::class, 'createChannel']); 
    Route::get('/findChannelsById/{id}', [ChannelController::class, 'findChannelByGameId']);
    Route::put('/updateChannel/{id}', [ChannelController::class, 'updateChannelById']); 
});

//Routes for messages with Admin token
Route::group(["middleware" => ["jwt.auth", "isAdmin"]] , function() {
    Route::post('/createMessage', [MessageController::class, 'createMessage']); 
    Route::get('/getAllMessages/{id}', [MessageController::class, 'getAllMessagesByChannelId']);
    Route::put('/updateMessage/{id}', [MessageController::class, 'updateMessageById']);
    Route::delete('/deleteMessage/{id}', [MessageController::class, 'deleteMessageById']);
});

//Superadmin functions, superAdmin Token needed and userId/adminId by URL
Route::group(["middleware" => ["jwt.auth", "isSuperAdmin"]] , function() {
    Route::post('/user/admin/{id}', [UserController::class, 'userToAdmin']);
    Route::post('/user/remove_admin/{id}', [UserController::class, 'adminToUser']);
    Route::post('/user/super_admin/{id}', [UserController::class, 'userToSuperAdmin']);
    Route::post('/user/remove_superadmin/{id}', [UserController::class, 'superAdminToUser']);
    Route::get('/getAllUsers', [UserController::class, 'getAllUsers']);
});

//Routes for deletion channel with Superadmin token
Route::group(["middleware" => ["jwt.auth", "isSuperAdmin"]] , function() {
    Route::delete('/deleteChannel/{id}', [ChannelController::class, 'deleteChannelById']);
});

//Routes for deletion game with Superadmin token
Route::group(["middleware" => ["jwt.auth", "isSuperAdmin"]] , function() {
    Route::delete('/deleteGame/{id}', [GameController::class, 'deleteGameById']);
}); 

//Routes for messages with Superadmin token
Route::group(["middleware" => ["jwt.auth", "isSuperAdmin"]] , function() {
    Route::post('/createMessage', [MessageController::class, 'createMessage']); 
    Route::get('/getAllMessages/{id}', [MessageController::class, 'getAllMessagesByChannelId']);
    Route::put('/updateMessage/{id}', [MessageController::class, 'updateMessageById']);
    Route::delete('/deleteMessage/{id}', [MessageController::class, 'deleteMessageById']);
});