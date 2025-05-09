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
            // Check if token exists before attempting to parse it
            if (!$request->bearerToken()) {
                $response = [
                    'status' => 'error',
                    'status_code' => 401,
                    'message' => 'Token is missing. Please log in.',
                    'response' => [],
                ];
                return response()->json($response, 401);
            }
            
            // Attempt to authenticate the token
            $user = JWTAuth::parseToken()->authenticate();
            
            if (!in_array($request->route()->getName(), ['logout', 'register_details'])) {
                if ($user->is_new_user) {
                    $response = [
                        'status' => 'error',
                        'status_code' => 403,
                        'message' => 'Please update your profile to access this resource.',
                        'response' => [],
                    ];
                    return response()->json($response, 403);
                }
            }

            // Attach user ID to the request
            $request->merge(['token_id' => $user->id]);
        } catch (TokenExpiredException $e) {
            $response = [
                'status' => 'error',
                'status_code' => 401,
                'message' => 'Your token has expired. Please log in again.',
                'response' => [],
            ];
            return response()->json($response, 401);
        } catch (TokenInvalidException $e) {
            $response = [
                'status' => 'error',
                'status_code' => 401,
                'message' => 'Your token is invalid.',
                'response' => [],
            ];
            return response()->json($response, 401);
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'status_code' => 401,
                'message' => 'Token is unauthorized or invalid.',
                'response' => [],
            ];
            return response()->json($response, 401);
        }

        return $next($request);
    }
}
