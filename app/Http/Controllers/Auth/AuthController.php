<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\UserOtp;
use Carbon\Carbon;


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
                $token = JWTAuth::fromUser($user);
            
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

    public function generateOtp(Request $request)
    {
        // Validate the phone number
        $rules = [
            'phone_number' => 'required|digits:10',
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response = $validator->errors();
            $msg = 'Validation Errors';
            $status = 422;
        } else {
            try {
                // Check if the user exists
                $user = User::where('phone_number', $request->phone_number)->first();

                if (!$user) {
                    // If user does not exist, create a new user
                    $user = User::create([
                        'phone_number' => $request->phone_number,
                        'is_new_user' => true, // Default value
                    ]);
                }

                // Generate a 6-digit OTP
                $otp = rand(1000, 9999);

                // Save OTP to the database
                $expiresAt = Carbon::now()->addMinutes(3); // OTP valid for 3 minutes
                UserOtp::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'otp' => $otp,
                        'expires_at' => $expiresAt,
                        'is_used' => false,
                    ]
                );
                // Set the response
                $response = [
                    'user_id' => $user->id,
                    'otp' => $otp, // You can remove this if OTP is sent via SMS
                    'expires_at' => $expiresAt->toDateTimeString(),
                ];
                $msg = 'OTP generated successfully';
                $status = 200;
            } catch (\Exception $e) {
                // Handle exceptions
                $response = ['error' => $e->getMessage()];
                $msg = 'Failed to generate OTP';
                $status = 500;
            }
        }

        // Return response
        return $this->response($response, $status, $msg);
    }


    public function verifyOtp(Request $request)
    {
        // Validate the input
        $rules = [
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|size:4', 
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response = $validator->errors();
            $msg = 'Validation Errors';
            $status = 422;
        } else {
            try {
                // Check if OTP exists for the user
                $otpRecord = UserOtp::where('user_id', $request->user_id)
                                    ->where('otp', $request->otp)
                                    ->first();

                // Case 1: OTP does not exist
                if (!$otpRecord) {
                    $response = ['error' => 'The OTP is invalid or has been regenerated.'];
                    $msg = 'Invalid OTP';
                    $status = 400;
                }
                // Case 2: OTP already used
                elseif ($otpRecord->is_used) {
                    $response = ['error' => 'The OTP has already been used.'];
                    $msg = 'OTP Used';
                    $status = 400;
                }
                // Case 3: OTP expired
                elseif (Carbon::now()->greaterThan($otpRecord->expires_at)) {
                    $response = ['error' => 'The OTP has expired.'];
                    $msg = 'OTP Expired';
                    $status = 400;
                } else {
                    // Case 4: OTP is valid
                    // Mark OTP as used
                    $otpRecord->update(['is_used' => true]);

                    // You can generate the token or login the user
                    $user = User::find($request->user_id);
                    $token = JWTAuth::fromUser($user);

                    $response = [
                        'access_token' => $token,
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'phone_number' => $user->phone_number,
                            'is_new_user' => (bool) $user->is_new_user,

                        ]
                    ];
                    $msg = 'OTP verified successfully. User logged in.';
                    $status = 200;
                }
            } catch (\Exception $e) {
                // Handle exceptions
                $response = ['error' => $e->getMessage()];
                $msg = 'Failed to verify OTP';
                $status = 500;
            }
        }

        // Return response
        return $this->response($response, $status, $msg);
    }

    /**
     * Admin login for web interface.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminLogin(Request $request)
    {
        // Validate
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return redirect()->back()
                ->with('error', 'Invalid email or password')
                ->withInput($request->except('password'));
        }

        $user = JWTAuth::user();
        
        // Check if user has admin role
        if ($user->role !== 'admin') {
            // Invalidate the token since it's not an admin
            JWTAuth::invalidate(JWTAuth::getToken());
            
            return redirect()->back()
                ->with('error', 'Unauthorized. Admin access only.')
                ->withInput($request->except('password'));
        }

        // Store token in session
        session(['admin_token' => $token]);
        
        // Redirect to admin dashboard
        return redirect()->route('admin.dashboard');
    }
}
