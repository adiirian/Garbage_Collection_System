<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $roles = Role::all();
        return view('auth.register', compact('roles'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        // Load the role relationship
        $user->load('role');

        Auth::login($user);

        // Redirect based on user role (case insensitive)
        if ($user->role && strtolower($user->role->name) === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role && strtolower($user->role->name) === 'collector') {
            return redirect()->route('collector.collection.index');
        } elseif ($user->role && strtolower($user->role->name) === 'public') {
            return redirect()->route('public.alerts.index');
        } else {
            return redirect()->route('public.alerts.index');
        }
    }
}
