<?php

namespace App\Http\Controllers\Collector;

use App\Models\Bin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CollectionController extends Controller
{
    public function index()
    {
        // Retrieve all bins and their statuses
        $bins = Bin::all();
        return response()->json($bins);
    }

    public function updateBinStatus(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'status' => 'required|in:full,empty,overflowing',
        ]);

        // Find the bin and update its status
        $bin = Bin::findOrFail($id);
        $bin->status = $request->status;
        $bin->save();

        return response()->json(['message' => 'Bin status updated successfully.']);
    }

    public function alertCollectors($id)
    {
        // Logic to alert collectors about the bin status
        $bin = Bin::findOrFail($id);
        
        if ($bin->status === 'full') {
            // Notify collectors about the full bin
            // Notification logic here
        } elseif ($bin->status === 'overflowing') {
            // Notify collectors about the overflowing bin
            // Notification logic here
        }

        return response()->json(['message' => 'Collectors alerted successfully.']);
    }
}