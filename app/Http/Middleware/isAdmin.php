<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $userId = auth()->user()->id;

        $user = User::find($userId);

        $isAdmin = $user->roles->contains(2);

        // dd($isAdmin);

        if (!$isAdmin) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'route not found'
                ],
                404
            );
        }




        return $next($request);
    }
}
