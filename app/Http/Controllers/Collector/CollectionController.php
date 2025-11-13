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
        $user = auth()->user();
        $bins = Bin::all(); // Fetch all bins for filtering
        $openAlerts = Alert::where('status', 'open')->with('bin')->get();

        // Get today's collections
        $todayCollections = Alert::where('type', 'collector_action')
            ->where('status', 'closed')
            ->whereDate('handled_at', today())
            ->count();

        // Get system overview stats
        $totalBins = Bin::count();
        $binsNeedingAttention = Bin::where('level', '!=', 'empty')->count();
        $totalAlerts = Alert::count();
        $totalCollections = Alert::where('type', 'collector_action')->where('status', 'closed')->count();

        // Get assigned areas for this collector
        $assignedAreas = $user->assignedAreas ?? [];

        // Get bins in assigned areas
        $assignedBins = Bin::whereIn('area_name', $assignedAreas)->get();

        // Get recently collected bins
        $collectedBins = Alert::where('type', 'collector_action')
            ->where('status', 'closed')
            ->with('bin')
            ->orderBy('handled_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($alert) {
                return [
                    'bin_name' => $alert->bin->name ?? 'Unknown',
                    'collector_name' => $alert->collector_name,
                    'collected_at' => $alert->handled_at,
                ];
            });

        return view('collector.dashboard', compact('bins', 'openAlerts', 'todayCollections', 'totalBins', 'binsNeedingAttention', 'totalAlerts', 'totalCollections', 'assignedBins', 'collectedBins'));
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

    public function profile()
    {
        $user = auth()->user();
        return response()->json($user);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'address' => 'required|string|max:255',
            'age' => 'required|integer|min:18|max:100',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:15360',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'age' => $request->age,
        ];

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && \Storage::disk('public')->exists($user->profile_picture)) {
                \Storage::disk('public')->delete($user->profile_picture);
            }

            // Convert image to base64
            $image = $request->file('profile_picture');
            $imageData = file_get_contents($image->getRealPath());
            $imageExtension = $image->extension();
            $base64Image = 'data:image/' . $imageExtension . ';base64,' . base64_encode($imageData);
            $data['profile_picture_data'] = $base64Image;
            $data['profile_picture'] = null; // Clear file path since using base64
        }

        $user->update($data);

        // Check if request expects JSON (AJAX) or has Accept: application/json header
        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => $user
            ]);
        }

        return redirect()->route('collector.profile')->with('success', 'Profile updated successfully');
    }

    public function getAssignments()
    {
        $user = auth()->user();
        $assignments = \App\Models\CollectionAssignment::where('collector_id', $user->id)->with('collector')->get();
        return response()->json($assignments);
    }

    public function updateAssignmentStatus(Request $request, $id)
    {
        $assignment = \App\Models\CollectionAssignment::findOrFail($id);
        $status = $request->input('status');
        $assignment->status = $status;
        $assignment->save();

        return response()->json([
            'message' => 'Assignment status updated',
            'assignment' => $assignment
        ]);
    }

    // Show bins index
    public function index()
    {
        $bins = Bin::all();
        $binsByType = $bins->groupBy('type');
        return view('collector.bins.index', compact('bins', 'binsByType'));
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
            'area_name' => 'required|string|max:255',
            'level' => 'required|in:empty,partial,full,overflowing',
        ]);

        $bin = Bin::create([
            'name' => $request->name,
            'area_name' => $request->area_name,
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
            'area_name' => 'required|string|max:255',
            'level' => 'required|in:empty,partial,full,overflowing',
        ]);

        $bin = Bin::findOrFail($binId);
        $bin->update([
            'name' => $request->name,
            'area_name' => $request->area_name,
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

    public function showProfile()
    {
        $user = auth()->user();
        return view('collector.profile', compact('user'));
    }
}
