<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;


class AuthController extends Controller
{

    public function register(Request $request)
    {
        // Validate
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response = $validator->errors();
            $msg = 'Validation Errors';
            $status = 422;
        } else {
            // Create the user
            try {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'password' => Hash::make($request->password),
                    'role' => $request->role ?? 'student', // Default role to student
                ]);

                // Generate API token for the new user
                $token = $user->createToken('YourAppName')->accessToken;

                $response = [
                    'user' => $user,
                    'token' => $token,
                ];
                $msg = 'Registration successful';
                $status = 201;
            } catch (\Exception $e) {
                $response = ['error' => $e->getMessage()];
                $msg = 'Registration failed';
                $status = 500;
            }
        }

        // Return response
        return $this->response($response, $status, $msg);
    }


    /**
     * Login API to generate JWT token.
     */
    public function login(Request $request)
    {
        // Validate
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response = $validator->errors();
            $msg = 'Validation Errors';
            $status = 422;
        } else {
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                $response = [];
                $msg = 'Invalid credentials';
                $status = 401;
            } else {
                $user = JWTAuth::user();
                $response = [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone_number' => $user->phone_number,
                        'role' => $user->role,
                    ],
                    'access_token' => $token,
                ];
                $msg = 'Login successful';
                $status = 200;
            }
        }

        // Return response
        return $this->response($response, $status, $msg);
    }

    /**
     * Logout API to invalidate the JWT token.
     */
    public function logout(Request $request)
    {
        // Validate
        $rules = [];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response = $validator->errors();
            $msg = 'Validation Errors';
            $status = 422;
        } else {
            try {
                // Invalidate the JWT token
                JWTAuth::invalidate(JWTAuth::getToken());

                $response = [];
                $msg = 'Successfully logged out';
                $status = 200;
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                $response = ['error' => $e->getMessage()];
                $msg = 'Logout failed. Token could not be invalidated.';
                $status = 500;
            }
        }

        // Return response
        return $this->response($response, $status, $msg);
    }
}
