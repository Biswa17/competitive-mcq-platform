<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        // Build query with filters
        $query = User::query();
        
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }
        
        // Get all users and paginate them
        $users = $query->paginate(10);
        
        // Pass the data to the view
        return view('admin.users.index', [
            'users' => $users
        ]);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Pass the user data to the view
        return view('admin.users.show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Pass the data to the view
        return view('admin.users.create');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Pass the user data to the view
        return view('admin.users.edit', [
            'user' => $user
        ]);
    }

    /**
     * Store a newly created user in the database.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,student,teacher',
        ]);

        try {
            // Create the user record
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'is_new_user' => false,
            ]);

            // Redirect back with success message
            return redirect()->route('admin.users')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->route('admin.users.create')->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone_number' => [
                'required',
                'string',
                'max:15',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => 'required|string|in:admin,student,teacher',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            // Update the user record
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'role' => $request->role,
            ];
            
            // Only update password if provided
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            
            $user->update($userData);

            // Redirect back with success message
            return redirect()->route('admin.users')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->route('admin.users.edit', $user)->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Delete the user record
            $user->delete();

            return redirect()->route('admin.users')->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
