<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function handle(Request $request, Closure $next)
	{
		try {
			$user = JWTAuth::parseToken()->authenticate();
			
		} catch (Exception $e) {
			if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException) {
				return response()->json(['status' => 'Token is Invalid']);
			} else if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException) {
				return response()->json(['status' => 'Token is Expired']);
			} else {
				return response()->json(['status' => 'Authorization Token not found']);
			}
		}
		return $next($request);
	}
}
