<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Fetch all users from the database
        $users = User::all();

        // Pass the users to the view
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin,user,Training Staff,Trainer',
        ]);

        // Create a new user
        $user = new User;
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->role = $validatedData['role'];
        $user->save();

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }    

    public function update(Request $request, User $user)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'username' => 'required|unique:users,username,' . $user->id . ',id',
            'email' => 'required|email|unique:users,email,' . $user->id . ',id',
            'password' => 'nullable',
            'role' => 'required|in:admin,user,Training Staff,Trainer',
        ]);

        // Update the user
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->role = $validatedData['role'];
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}


// namespace App\Http\Controllers;

// use App\Models\User;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;

// class UserController extends Controller
// {
//     public function index()
//     {
        
//         // Fetch all users from the database
//         $users = User::all();
        
//         // Pass the users to the view
//         return view('users.index', compact('users'));
//     }
    

//     public function create()
//     {
//         return view('users.create');
//     }

//     public function store(Request $request)
//     {
//         // Validate the request data
//         $validatedData = $request->validate([
//             'username' => 'required|unique:users',
//             'password' => 'required',
//             'role' => 'required|in:admin,user',
//         ]);

//         // Create a new user
//         $user = new User;
//         $user->username = $validatedData['username'];
//         $user->password = Hash::make($validatedData['password']);
//         $user->role = $validatedData['role'];
//         $user->save();

//         return redirect()->route('users.index')->with('success', 'User created successfully');
//     }

//     public function edit(User $user)
//     {
//         return view('users.edit', compact('user'));
//     }

//     public function update(Request $request, User $user)
//     {
//         // Validate the request data
//         $validatedData = $request->validate([
//             'username' => 'required|unique:users,username,' . $user->id,
//             'password' => 'nullable',
//             'role' => 'required|in:admin,user',
//         ]);

//         // Update the user
//         $user->username = $validatedData['username'];
//         if ($request->filled('password')) {
//             $user->password = Hash::make($validatedData['password']);
//         }
//         $user->role = $validatedData['role'];
//         $user->save();

//         return redirect()->route('users.index')->with('success', 'User updated successfully');
//     }

//     public function destroy(User $user)
//     {
//         $user->delete();

//         return redirect()->route('users.index')->with('success', 'User deleted successfully');
//     }
// }
