<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bin;
use App\Models\Alert;
use Carbon\Carbon;

class CollectionController extends Controller
{
    public function dashboard()
    {
        $bins = Bin::where('level', '!=', 'empty')->get();
        $openAlerts = Alert::where('status', 'open')->with('bin')->get();

        // Get today's collections
        $todayCollections = Alert::where('type', 'collector_action')
            ->where('status', 'closed')
            ->whereDate('handled_at', today())
            ->count();

        // Get collected bins with collector info
        $collectedBins = Alert::where('type', 'collector_action')
            ->where('status', 'closed')
            ->with('bin')
            ->orderBy('handled_at', 'desc')
            ->get()
            ->map(function ($alert) {
                return [
                    'bin_name' => $alert->bin->name ?? 'Unknown',
                    'collector_name' => $alert->collector_name ?? 'Unknown',
                    'collected_at' => $alert->handled_at,
                ];
            });

        // Get system overview stats
        $totalBins = Bin::count();
        $binsNeedingAttention = Bin::where('level', '!=', 'empty')->count();
        $totalAlerts = Alert::count();
        $totalCollections = Alert::where('type', 'collector_action')->where('status', 'closed')->count();

        return view('collector.dashboard', compact('bins', 'openAlerts', 'todayCollections', 'collectedBins', 'totalBins', 'binsNeedingAttention', 'totalAlerts', 'totalCollections'));
    }



    public function updateStatus(Request $request, $binId)
    {
        $bin = Bin::findOrFail($binId);
        $bin->level = $request->input('level', 'empty');
        $bin->save();

        return response()->json(['message' => 'Bin status updated', 'bin' => $bin]);
    }

    // Get bin data for editing
    public function getBin($binId)
    {
        $bin = Bin::findOrFail($binId);
        return response()->json($bin);
    }

    // Collector marks that they handled a bin (mark cleaned)
    public function markCleaned(Request $request, $binId)
    {
        $bin = Bin::findOrFail($binId);
        $user = auth()->user();

        $bin->level = 'empty';
        $bin->collected = true;
        $bin->save();

        // Create a new alert for the collection action
        Alert::create([
            'bin_id' => $bin->id,
            'type' => 'collector_action',
            'level' => 'empty',
            'status' => 'closed',
            'message' => "Bin {$bin->name} collected by {$user->name}",
            'handled_at' => Carbon::now(),
            'collector_name' => $user->name,
        ]);

        // Close related open alerts for this bin
        $alerts = Alert::where('bin_id', $bin->id)->where('status', 'open')->get();
        foreach ($alerts as $alert) {
            $alert->status = 'closed';
            $alert->handled_at = Carbon::now();
            $alert->collector_name = $user->name;
            $alert->save();
        }

        return response()->json([
            'message' => 'Bin marked cleaned',
            'bin' => $bin,
            'collector_name' => $user->name
        ]);
    }

    // Show bins index
    public function index()
    {
        $bins = Bin::all();
        return view('collector.bins.index', compact('bins'));
    }

    // Show create bin form
    public function createBinForm()
    {
        return view('collector.bins.create');
    }

    // Create a new bin
    public function createBin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'level' => 'required|in:empty,partial,full,overflowing',
        ]);

        $bin = Bin::create([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'level' => $request->level,
            'collected' => false,
        ]);

        // Check if request expects JSON (AJAX) or has Accept: application/json header
        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'message' => 'Bin created successfully',
                'bin' => $bin
            ]);
        }

        return redirect()->route('collector.bins.index')->with('success', 'Bin created successfully');
    }

    // Delete a bin
    public function deleteBin(Request $request, $binId)
    {
        $bin = Bin::findOrFail($binId);

        // Delete associated alerts first
        $bin->alerts()->delete();

        // Delete the bin
        $bin->delete();

        // Check if request expects JSON (AJAX) or has Accept: application/json header
        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'message' => 'Bin deleted successfully'
            ]);
        }

        return redirect()->route('collector.bins.index')->with('success', 'Bin deleted successfully');
    }

    // Show edit form for a bin
    public function editBin($binId)
    {
        $bin = Bin::findOrFail($binId);

        // Check if request expects JSON (AJAX) or has Accept: application/json header
        if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
            return response()->json($bin);
        }

        return view('collector.bins.edit', compact('bin'));
    }

    // Update a bin
    public function updateBin(Request $request, $binId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'level' => 'required|in:empty,partial,full,overflowing',
        ]);

        $bin = Bin::findOrFail($binId);
        $bin->update([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'level' => $request->level,
        ]);

        // Check if request expects JSON (AJAX) or has Accept: application/json header
        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'message' => 'Bin updated successfully',
                'bin' => $bin
            ]);
        }

        return redirect()->route('collector.bins.index')->with('success', 'Bin updated successfully');
    }
}