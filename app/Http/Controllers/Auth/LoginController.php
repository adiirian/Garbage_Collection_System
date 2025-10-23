<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            // Redirect based on user role (case insensitive)
            $user = Auth::user();
            $user->load('role');
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

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
