<?php

namespace App\Http\Middleware;

use App\Contracts\AuthCheck;
use Illuminate\Http\Request;
use Closure;

use App\Library\AuthCheckLib;

class AuthenticateClientRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->bearerToken()) {

            $authLib = app()->make(AuthCheckLib::class);
            $response = $authLib->checkTokenAuthenticity($request);
            if (!$response) {
                return response()->json([
                    "message" => "Invalid Token"
                ], 403);
            }else{
                
                $request->merge(['user_id' => $response->user_id]);
            }
        } else {
            return response()->json([
                "message" => "Invalid Token"
            ], 403);
        }

        return $next($request);
    }
}
