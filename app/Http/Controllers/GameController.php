<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    public function createGame(Request $request)
    {
        try {
            Log::info('Creating game');
        
            $validator = Validator::make($request->all(), [
                'game_name' => ['required', 'string', 'max:255', 'min:3'],
                'genre' => ['required', 'string', 'max:255', 'min:3'],
                'age' => ['required', 'integer'],
                'developer' => ['required', 'string', 'max:255', 'min:3']
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

            $user_id = auth()->user()->id;

            $game_name = $request->input("game_name");
            $genre = $request->input("genre");
            $age = $request->input("age");
            $developer = $request->input("developer");

            $game = new Game();

            $game->game_name = $game_name;
            $game->user_id = $user_id;
            $game->genre = $genre;
            $game->age = $age;
            $game->developer = $developer;

            $game->save();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'New game created'
                ],
                201
            );
        } catch (\Exception $exception) {
            Log::error("Error creating game: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error creating game'
                ],
                500
            );
        }
    }
}