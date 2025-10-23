<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bin;
use App\Models\Alert;

class DashboardController extends Controller
{
    // Simple dashboard data: bins and open alerts
    public function index()
    {
        $bins = Bin::all();
        $openAlerts = Alert::where('status', 'open')->with('bin')->get();

        // Get real stats for dashboard
        $binSummary = Bin::getBinSummaryStats();
        $alertStats = Alert::getAlertStats('all');
        $todayCollections = Alert::where('type', 'collector_action')
            ->whereDate('handled_at', today())
            ->count();

        return view('admin.dashboard', compact('bins', 'openAlerts', 'binSummary', 'alertStats', 'todayCollections'));
    }

    // Endpoint to update bin level from sensor (simulate)
    public function updateBinLevel($binId, $level)
    {
        $bin = Bin::findOrFail($binId);

        $previous = $bin->level;
        $bin->level = $level;
        $bin->save();

        if (in_array($level, ['full', 'overflowing'])) {
            Alert::create([
                'bin_id' => $bin->id,
                'type' => 'level_change',
                'level' => $level,
                'status' => 'open',
                'message' => "Sensor reported level {$level}"
            ]);
        }

        return response()->json(['bin' => $bin, 'previous' => $previous]);
    }
}
