<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bin;
use App\Models\Alert;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard
     */
    public function index()
    {
        $binSummary = Bin::getBinSummaryStats();
        $alertStats = Alert::getAlertStats('all');
        $alertStats['average_resolution_time_hours'] = Alert::getAverageResolutionTime();
        $collectionEfficiency = Alert::getOverallCollectionEfficiency();
        $binCollectionRates = Bin::getCollectionEfficiencyData();

        return view('admin.analytics', compact('binSummary', 'alertStats', 'collectionEfficiency', 'binCollectionRates'));
    }

    /**
     * Get bin status summary
     */
    public function binSummary()
    {
        return response()->json(Bin::getBinSummaryStats());
    }

    /**
     * Get alert statistics
     */
    public function alertStats(Request $request)
    {
        $period = $request->get('period', 'all'); // all, week, month

        $alertStats = Alert::getAlertStats($period);
        $alertStats['average_resolution_time_hours'] = Alert::getAverageResolutionTime();

        return response()->json($alertStats);
    }

    /**
     * Get collection efficiency metrics
     */
    public function collectionEfficiency()
    {
        $overallEfficiency = Alert::getOverallCollectionEfficiency();
        $binCollectionRates = Bin::getCollectionEfficiencyData();

        return response()->json([
            'overall_efficiency' => $overallEfficiency,
            'bin_collection_rates' => $binCollectionRates,
        ]);
    }

    /**
     * Get collections count for today
     */
    public function collectionsToday()
    {
        $today = Carbon::today();
        $collectionsToday = Alert::where('type', 'collector_action')
            ->whereDate('handled_at', $today)
            ->count();
        return response()->json(['collections_today' => $collectionsToday]);
    }
}
