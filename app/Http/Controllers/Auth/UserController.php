<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'student', // Default role to student
        ]);

        // Generate API token for the new user
        $token = $user->createToken('YourAppName')->accessToken;
        

        return response()->json(['user' => $user, 'token' => $token->token], 201);
    }
    /**
     * Log the user in.
     */
    public function login(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Attempt to login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('YourAppName')->accessToken;

            return response()->json(['user' => $user, 'token' => $token->token]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    



    public function registerUser(Request $request)
    {   
        $user = auth()->user();
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required','string','email','max:255',
                Rule::unique('users')->ignore($user->id), // Ignore current user's email
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            $response = $validator->errors();
            $msg = 'Validation Errors';
            $status = 422;
        } else {
            try {
                // Get the authenticated user
                $user = auth()->user();

                // Update user details
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'is_new_user' => false,
                ]);

                // Prepare the response
                $response = [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'is_new_user' => $user->is_new_user,
                ];
                $msg = 'User details updated successfully';
                $status = 200;
            } catch (\Exception $e) {
                // Handle exceptions
                $response = ['error' => $e->getMessage()];
                $msg = 'Failed to update user details';
                $status = 500;
            }
        }

        // Return response
        return $this->response($response, $status, $msg);
    }

}

