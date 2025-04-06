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
    }
}
