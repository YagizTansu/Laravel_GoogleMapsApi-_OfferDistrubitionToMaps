<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CheckUserApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $users = User::get('api_token');
        foreach ($users as $user) {
            if ($request->header('api-token') == $user->api_token ) {
                return $next($request);
            }
        }
       // return redirect()->route('offers');
    }
}
