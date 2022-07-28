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
//funcion reservada para superadmin
Route::get('/', [AuthController::class, 'getAllUsers']);

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

//routes for games create CRUD solo por admins, crear isAdmin middleware luego
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::post('/createGame', [GameController::class, 'createGame']); 
    Route::get('/myGames', [GameController::class, 'getGames']);
    Route::put('/updateMyGame/{id}', [GameController::class, 'updateMyGame']);
    Route::delete('/deleteMyGame/{id}', [GameController::class, 'deleteMyGame']);
});

//routes for channel create CRUD
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::post('/createChannel', [ChannelController::class, 'createChannel']); 
    //find channels from a specific game_id by URL
    Route::get('/findChannelsById/{id}', [ChannelController::class, 'findChannelByGameId']);
});

//Routes for user
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::post('/accessChannel', [UserController::class, 'accessChannel']); 
    Route::post('/leaveChannel', [UserController::class, 'leaveChannel']);
});

//Routes for message
Route::group(["middleware" => "jwt.auth"] , function() {
    Route::post('/createMessage', [MessageController::class, 'createMessage']); 
    Route::get('/getAllMessages/{id}', [MessageController::class, 'getAllMessagesByChannelId']);
});