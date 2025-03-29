<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException; // Import JWTException for missing token

class AllowGuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Allows guest access if no valid token is provided.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Check if a token is present and try to authenticate
            if ($token = JWTAuth::getToken()) {
                $user = JWTAuth::parseToken()->authenticate();
                // Attach authenticated user ID to the request
                $request->merge(['token_id' => $user->id]);
                // Optionally, attach the full user object if needed later
                $request->attributes->set('auth_user', $user);
            } else {
                // No token found, treat as guest
                $request->merge(['token_id' => -1]);
                $request->attributes->set('auth_user', null);
            }
        } catch (TokenExpiredException $e) {
            // Token expired, treat as guest
            $request->merge(['token_id' => -1]);
            $request->attributes->set('auth_user', null);
        } catch (TokenInvalidException $e) {
            // Token invalid, treat as guest
            $request->merge(['token_id' => -1]);
            $request->attributes->set('auth_user', null);
        } catch (JWTException $e) {
            // Token missing or other JWT error, treat as guest
            $request->merge(['token_id' => -1]);
            $request->attributes->set('auth_user', null);
        } catch (Exception $e) {
            // Any other exception, treat as guest (or log error)
            // Log::error('Error in AllowGuestMiddleware: ' . $e->getMessage()); // Optional logging
            $request->merge(['token_id' => -1]);
            $request->attributes->set('auth_user', null);
        }

        // Proceed with the request
        return $next($request);
    }
}
