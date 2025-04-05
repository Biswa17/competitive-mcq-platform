<?php
namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class VerifyAdminUserToken
{
    public function handle($request, Closure $next)
    {
        // For web routes, check session
        if ($request->is('admin/*') && !$request->expectsJson()) {
            if (!session()->has('admin_token')) {
                return redirect('/')->with('error', 'Please login to access the admin panel.');
            }
            
            try {
                // Verify the token from session
                $token = session('admin_token');
                JWTAuth::setToken($token);
                $user = JWTAuth::authenticate();
                
                if (!$user) {
                    session()->forget('admin_token');
                    return redirect('/')->with('error', 'Session expired. Please login again.');
                }
                
                if ($user->role !== 'admin') {
                    session()->forget('admin_token');
                    return redirect('/')->with('error', 'Unauthorized. Admin access only.');
                }
                
                // Attach user to the request
                $request->merge(['admin_user' => $user]);
                
                return $next($request);
            } catch (TokenExpiredException $e) {
                session()->forget('admin_token');
                return redirect('/')->with('error', 'Session expired. Please login again.');
            } catch (TokenInvalidException $e) {
                session()->forget('admin_token');
                return redirect('/')->with('error', 'Invalid session. Please login again.');
            } catch (Exception $e) {
                session()->forget('admin_token');
                return redirect('/')->with('error', 'Authentication error. Please login again.');
            }
        }
        
        // For API routes, check Authorization header
        try {
            // Attempt to authenticate the token
            $user = JWTAuth::parseToken()->authenticate();

            if ($user->role !== 'admin') {
                $response = [
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'Unauthorized. Admin role required.',
                    'response' => [],
                ];
                return response()->json($response, 403);
            }
            
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
                'message' => 'Token is missing or unauthorized.',
                'response' => [],
            ];
            return response()->json($response, 401);
        }

        return $next($request);
    }
}
