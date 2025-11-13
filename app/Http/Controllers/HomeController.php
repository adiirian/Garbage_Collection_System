<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Redirect authenticated users to their dashboard
        $user = auth()->user();
        if ($user) {
            $user->load('role');
            if ($user->role->name === 'Admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role->name === 'Collector') {
                return redirect()->route('collector.collection.index');
            } else {
                return redirect()->route('residents.alerts.index');
            }
        }

        return view('welcome', [
            'user' => $user,
            'isAuthenticated' => auth()->check(),
            'routes' => [
                'login' => route('login'),
                'register' => route('register'),
                'logout' => route('logout'),
                'adminDashboard' => route('admin.dashboard'),
                'adminAnalytics' => route('admin.analytics'),
                'collectorDashboard' => route('collector.collection.index'),
                'publicDashboard' => route('residents.alerts.index'),
            ]
        ]);
    }
}
