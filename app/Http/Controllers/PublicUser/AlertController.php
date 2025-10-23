<?php

namespace App\Http\Controllers\PublicUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bin;
use App\Models\Alert;

class AlertController extends Controller
{
    public function dashboard()
    {
        $bins = Bin::all();
        $userAlerts = Alert::where('reported_by', Auth::id())->get();

        return view('public.dashboard', compact('bins', 'userAlerts'));
    }

    // Public user reports a bin (by id or lat/lng)
    public function store(Request $request)
    {
        $data = $request->validate([
            'bin_id'    => 'nullable|exists:bins,id',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'message'   => 'nullable|string'
        ]);

        $bin = null;

        if (! empty($data['bin_id'])) {
            $bin = Bin::find($data['bin_id']);
        } elseif (! empty($data['latitude']) && ! empty($data['longitude'])) {
            // find nearest bin using a simple distance formula (no haversine)
            $lat = $data['latitude'];
            $lng = $data['longitude'];

            $bin = Bin::orderByRaw(
                "((latitude - ?) * (latitude - ?) + (longitude - ?) * (longitude - ?)) ASC",
                [$lat, $lat, $lng, $lng]
            )->first();
        }

        if (! $bin) {
            return response()->json(['message' => 'Bin not found'], 404);
        }

        $alert = Alert::create([
            'bin_id'     => $bin->id,
            'type'       => 'public_report',
            'level'      => null,
            'status'     => 'open',
            'reported_by' => Auth::id(),
            'message'    => $data['message'] ?? 'Public report'
        ]);

        return response()->json($alert, 201);
    }

    public function welcome(Request $request)
    {
        \Log::info("Request received: {$request->method()} {$request->path()}");

        return response()->json(['message' => 'Welcome to the Garbage Collection System!']);
    }
}