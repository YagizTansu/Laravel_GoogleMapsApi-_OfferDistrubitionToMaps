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
        $doesExist=User::where('api_token',$request->header('api-token'))->exists();

            if ($doesExist == true) {
                return $next($request);
            }
        }
       // return redirect()->route('offers');
}
