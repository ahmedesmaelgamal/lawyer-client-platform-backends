<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtClientMiddleware extends BaseMiddleware
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
        try {
            if (!$token = $request->bearerToken()) {
                return response()->json([
                    'msg' => 'Authorization Token not found',
                    'data' => null,
                    'status' => 401
                ]);
            }

            $client = Auth::guard('client_api')->check();
            if (!$client) {
                return response()->json([
                    'msg' => 'unauthorized',
                    'data' => null,
                    'status' => 401
                ]);
            }
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'msg' => 'Token is Invalid',
                    'data' => null,
                    'status' => 407
                ]);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json([
                    'msg' => 'Token is Expired',
                    'data' => null,
                    'status' => 408
                ]);
            } else {
                return response()->json([
                    'msg' => 'Authorization Token not found',
                    'data' => null,
                    'status' => 401
                ]);
            }
        }
        return $next($request);
    }
}
