<?php

namespace App\Http\Controllers\StoreFront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    /**
     * Get the authenticated user's details.
     */
    public function getUserDetails(Request $request)
    {
        // Define validation rules (empty in this case)
        $rules = [];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Initialize response variables
        $response = [];
        $msg = '';
        $status = 200;

        // If validation fails, return error response
        if ($validator->fails()) {
            $response = $validator->errors();
            $msg = 'Validation Errors';
            $status = 422;
        } else {
            // Get the authenticated user
            $user = auth()->user();

            if ($user) {
                // Prepare response for successful case
                $response = [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'role' => $user->role,
                    'is_new_user' => $user->is_new_user,
                ];
                $msg = 'User details retrieved successfully';
                $status = 200;
            } else {
                // Handle case when user is not found or unauthorized
                $response = [];
                $msg = 'User not found or unauthorized';
                $status = 404;
            }
        }

        // Return final response at the end
        return $this->response($response, $status, $msg);
    }
}
