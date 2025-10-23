<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bin extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'latitude', 'longitude', 'level', 'notes', 'collected'];

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    /**
     * Get analytics data for this bin
     */
    public function getAnalyticsData()
    {
        $totalAlerts = $this->alerts()->count();
        $openAlerts = $this->alerts()->where('status', 'open')->count();
        $closedAlerts = $this->alerts()->where('status', 'closed')->count();
        $collectionActions = $this->alerts()->where('type', 'collector_action')->count();

        return [
            'bin_id' => $this->id,
            'name' => $this->name,
            'current_level' => $this->level,
            'total_alerts' => $totalAlerts,
            'open_alerts' => $openAlerts,
            'closed_alerts' => $closedAlerts,
            'collection_actions' => $collectionActions,
            'efficiency_rate' => $totalAlerts > 0 ? round(($collectionActions / $totalAlerts) * 100, 2) : 0,
        ];
    }

    /**
     * Get overall bin summary statistics
     */
    public static function getBinSummaryStats()
    {
        $totalBins = self::count();
        $binsByLevel = self::selectRaw('level, COUNT(*) as count')
            ->groupBy('level')
            ->pluck('count', 'level');

        $binsWithAlerts = self::has('alerts')->count();

        return [
            'total_bins' => $totalBins,
            'bins_by_level' => $binsByLevel,
            'bins_with_alerts' => $binsWithAlerts,
            'bins_without_alerts' => $totalBins - $binsWithAlerts,
            'empty_bins' => $binsByLevel['empty'] ?? 0,
            'partial_bins' => $binsByLevel['partial'] ?? 0,
            'full_bins' => $binsByLevel['full'] ?? 0,
            'overflowing_bins' => $binsByLevel['overflowing'] ?? 0,
            'total_alerts' => \App\Models\Alert::count(),
            'open_alerts' => \App\Models\Alert::where('status', 'open')->count(),
            'closed_alerts' => \App\Models\Alert::where('status', 'closed')->count(),
            'collections_today' => \App\Models\Alert::where('type', 'collector_action')
                ->whereDate('handled_at', today())
                ->count(),
        ];
    }

    /**
     * Get collection efficiency data grouped by bin level
     */
    public static function getCollectionEfficiencyData()
    {
        $levels = ['empty', 'partial', 'full', 'overflowing'];
        $collectionRates = [];

        foreach ($levels as $level) {
            $binsAtLevel = self::where('level', $level)->with('alerts')->get();
            $totalBinsAtLevel = $binsAtLevel->count();
            $collectionsAtLevel = 0;

            foreach ($binsAtLevel as $bin) {
                $collectionsAtLevel += $bin->alerts->where('type', 'collector_action')->count();
            }

            $collectionRates[] = [
                'level' => $level,
                'total_bins' => $totalBinsAtLevel,
                'collections' => $collectionsAtLevel,
                'efficiency_rate' => $totalBinsAtLevel > 0 ? round(($collectionsAtLevel / $totalBinsAtLevel) * 100, 2) : 0,
            ];
        }

        return $collectionRates;
    }
}
