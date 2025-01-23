<?php
namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class VerifyUserToken
{
    public function handle($request, Closure $next)
    {
        try {
            // Attempt to authenticate the token
            $user = JWTAuth::parseToken()->authenticate();

            // Attach user ID to the request
            $request->merge(['token_id' => $user->id]);
        } catch (TokenExpiredException $e) {
            $response = [
                'status' => 'error',
                'status_code' => 401,
                'message' => 'Your token has expired. Please log in again.',
                'response' => null,
            ];
            return response()->json($response, 401);
        } catch (TokenInvalidException $e) {
            $response = [
                'status' => 'error',
                'status_code' => 401,
                'message' => 'Your token is invalid.',
                'response' => null,
            ];
            return response()->json($response, 401);
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'status_code' => 401,
                'message' => 'Token is missing or unauthorized.',
                'response' => null,
            ];
            return response()->json($response, 401);
        }

        return $next($request);
    }
}
