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

            $userId = auth()->user()->id;

            $gameName = $request->input("game_name");
            $genre = $request->input("genre");
            $age = $request->input("age");
            $developer = $request->input("developer");

            $game = new Game();

            $game->game_name = $gameName;
            $game->user_id = $userId;
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

    public function getAllGames()
    {
        try {
            Log::info('Getting all games');
            $games = Game::all();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Games retrieved successfully',
                    'data' => $games
                ]
            );

        } catch (\Exception $exception) {
            Log::error("Error retrieving games " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error retrieving games'
                ],
                500
            );
        }
    }
    
    public function getMyGames()
    {
        try {

            Log::info("Getting Games created by admin");

            $adminId = auth()->user()->id;

            $games = Game::query()
            ->where('user_id', '=', $adminId)
            ->get()
            ->toArray();

            
            if (!$games) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => "You haven't created any game yet"
                    ],
                    404
                );
            };

            return response()->json(
                [
                    'success' => true,
                    'message' => "Getting games created by admin ".$adminId,
                    'data' => $games
                ],
                200
            );

        } catch (\Exception $exception) {

            Log::error("Error getting games: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error getting games created by admin ".$adminId
                ],
                500
            );
        }
    }

    public function deleteGameById($gameId){

        try {
        
            Log::info('Deleting game');

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

            $game = Game::find($gameId);

            if (!$game) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => "Missing game"
                    ]
                );
            }

            // if ($game->user_id != $adminId) {
            //     return response()->json(
            //         [
            //             'success' => false,
            //             'message' => 'Game created by another admin'
            //         ]
            //     );
            // }
            $game->delete();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Game deleted'
                ],
                200
            );

        } catch (\Exception $exception) {

            Log::error("Error deleting game: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error deleting game'
                ],
                500
            );
        }
    }

    public function updateMyGame(Request $request, $gameId){
    
            try {
    
                Log::info('Updating game');
                
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
    
                $game = Game::find($gameId);
                
                if (!$game) {

                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Game not found'
                        ],
                        404
                    );
                }

                if ($game->user_id != $adminId) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Game created by another admin'
                        ],
                        
                    );
                }

                $validator = Validator::make($request->all(), [
                    'game_name' => ['string', 'max:255'],
                    'genre' => ['string', 'max:255'],
                    'age' => ['integer'],
                    'developer' => ['string', 'max:255']
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
    
                $gameName = $request->input("game_name");
                $genre = $request->input("genre");
                $age = $request-> input("age");
                $developer = $request -> input("developer");
    
                if (isset($gameName)) {
                    $game->game_name = $gameName;
                }
    
                if (isset($genre)) {
                    $game->genre = $genre;
                }
    
                if(isset($age)){
                    $game->age = $age;
                }
                if (isset($developer)) {
                    $game->developer = $developer;
                }
    
                $game->save();
    
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Game updated',
                        'data' => $game
                    ],
                    201
                );
            } catch (\Exception $exception) {
    
                Log::error("Error updating game: " . $exception->getMessage());
    
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Error updating game'
                    ],
                    500
                );
            }
    }
}


