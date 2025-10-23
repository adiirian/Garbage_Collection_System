<?php

namespace App\Http\Controllers\Public;

use App\Models\Alert;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AlertController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'bin_id' => 'required|exists:bins,id',
            'message' => 'required|string|max:255',
        ]);

        $alert = Alert::create([
            'bin_id' => $request->bin_id,
            'message' => $request->message,
        ]);

        return response()->json(['message' => 'Alert created successfully', 'alert' => $alert], 201);
    }

    public function index()
    {
        $alerts = Alert::with('bin')->get();
        return response()->json($alerts);
    }
}