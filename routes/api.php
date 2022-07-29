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

//no requerirán autentificación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//routes for users with authentification
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::post('/logout', [AuthController::class, 'logout']); 
    Route::put('/update', [AuthController::class, 'updateProfile']);
    Route::delete('/delete', [AuthController::class, 'deleteProfile']);
});

//routes for games create CRUD solo por admins
Route::group(["middleware" => ["jwt.auth", "isAdmin"]] , function() {
    Route::post('/createGame', [GameController::class, 'createGame']); 
    Route::get('/getMyGames', [GameController::class, 'getMyGames']);
    Route::put('/updateMyGame/{id}', [GameController::class, 'updateMyGame']);
    Route::delete('/deleteMyGame/{id}', [GameController::class, 'deleteMyGame']);
});

//routes for channel create CRUD only for admins
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::post('/createChannel', [ChannelController::class, 'createChannel']); 
    //find channels from a specific game_id by URL
    Route::get('/findChannelsById/{id}', [ChannelController::class, 'findChannelByGameId']);
    Route::put('/updateChannel/{id}', [ChannelController::class, 'updateChannelById']);
    Route::delete('/deleteChannel/{id}', [ChannelController::class, 'deleteChannelById']);
});

//Routes for user
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::post('/accessChannel', [UserController::class, 'accessChannel']); 
    Route::post('/leaveChannel', [UserController::class, 'leaveChannel']);
});

//Routes for messages, no extra middleware required
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::post('/createMessage', [MessageController::class, 'createMessage']); 
    Route::get('/getAllMessages/{id}', [MessageController::class, 'getAllMessagesByChannelId']);
    Route::put('/updateMessage/{id}', [MessageController::class, 'updateMessageById']);
    Route::delete('/deleteMessage/{id}', [MessageController::class, 'deleteMessageById']);
});

//Superadmin functions 
Route::group(["middleware" => ["jwt.auth", "isSuperAdmin"]] , function() {
    Route::post('/user/admin/{id}', [UserController::class, 'userToAdmin']);
    Route::post('/user/remove_admin/{id}', [UserController::class, 'adminToUser']);
    Route::post('/user/super_admin/{id}', [UserController::class, 'userToSuperAdmin']);
    Route::post('/user/remove_superadmin/{id}', [UserController::class, 'superAdminToUser']);
    Route::get('/user/get_all_admins',[UserController::class, 'getAllAdmins']);
    Route::get('/', [AuthController::class, 'getAllUsers']);
});
