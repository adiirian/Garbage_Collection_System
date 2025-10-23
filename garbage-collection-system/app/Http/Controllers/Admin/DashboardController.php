<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $bins = Bin::all();
        $fullBins = $bins->where('status', 'full');
        $overflowingBins = $bins->where('status', 'overflowing');
        $emptyBins = $bins->where('status', 'empty');

        return response()->json([
            'total_bins' => $bins->count(),
            'full_bins' => $fullBins->count(),
            'overflowing_bins' => $overflowingBins->count(),
            'empty_bins' => $emptyBins->count(),
            'bins' => $bins
        ]);
    }
}