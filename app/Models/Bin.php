<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bin extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'area_name', 'level', 'collected', 'type', 'notes'];

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
            'collected' => $this->collected,
            'type' => $this->type,
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
        $collectedBins = self::where('collected', true)->count();
        $uncollectedBins = $totalBins - $collectedBins;
        $binsByType = self::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        $binsWithAlerts = self::has('alerts')->count();

        return [
            'total_bins' => $totalBins,
            'collected_bins' => $collectedBins,
            'uncollected_bins' => $uncollectedBins,
            'bins_by_type' => $binsByType,
            'bins_with_alerts' => $binsWithAlerts,
            'bins_without_alerts' => $totalBins - $binsWithAlerts,
            'paper_bins' => $binsByType['paper'] ?? 0,
            'glass_bins' => $binsByType['glass'] ?? 0,
            'plastic_bins' => $binsByType['plastic'] ?? 0,
            'metal_bins' => $binsByType['metal'] ?? 0,
            'total_alerts' => \App\Models\Alert::count(),
            'open_alerts' => \App\Models\Alert::where('status', 'open')->count(),
            'closed_alerts' => \App\Models\Alert::where('status', 'closed')->count(),
            'collections_today' => \App\Models\Alert::where('type', 'collector_action')
                ->whereDate('handled_at', today())
                ->count(),
        ];
    }

    /**
     * Get collection efficiency data grouped by bin type
     */
    public static function getCollectionEfficiencyData()
    {
        $types = ['paper', 'glass', 'plastic', 'metal'];
        $collectionRates = [];

        foreach ($types as $type) {
            $totalBinsOfType = self::where('type', $type)->count();
            $collectedBinsOfType = self::where('type', $type)->where('collected', true)->count();

            $collectionRates[] = [
                'type' => $type,
                'total_bins' => $totalBinsOfType,
                'collections' => $collectedBinsOfType,
                'efficiency_rate' => $totalBinsOfType > 0 ? round(($collectedBinsOfType / $totalBinsOfType) * 100, 2) : 0,
            ];
        }

        return $collectionRates;
    }
}
