<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = ['bin_id', 'type', 'level', 'status', 'reported_by', 'message', 'handled_at', 'collector_name'];

    public function bin()
    {
        return $this->belongsTo(Bin::class);
    }

    /**
     * Get resolution time in hours
     */
    public function getResolutionTimeHours()
    {
        if (!$this->handled_at) {
            return null;
        }

        return $this->created_at->diffInHours($this->handled_at);
    }

    /**
     * Scope for resolved alerts
     */
    public function scopeResolved($query)
    {
        return $query->whereNotNull('handled_at');
    }

    /**
     * Scope for alerts by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for alerts by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get alert statistics
     */
    public static function getAlertStats($period = 'all')
    {
        $query = self::query();

        if ($period === 'week') {
            $query->where('created_at', '>=', now()->subWeek());
        } elseif ($period === 'month') {
            $query->where('created_at', '>=', now()->subMonth());
        }

        $totalAlerts = $query->count();
        $alertsByType = $query->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        $alertsByStatus = $query->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            'total_alerts' => $totalAlerts,
            'alerts_by_type' => $alertsByType,
            'alerts_by_status' => $alertsByStatus,
            'open_alerts' => $alertsByStatus['open'] ?? 0,
            'closed_alerts' => $alertsByStatus['closed'] ?? 0,
        ];
    }

    /**
     * Get average resolution time
     */
    public static function getAverageResolutionTime()
    {
        $averageResolutionTime = self::whereNotNull('handled_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, handled_at)) as avg_hours')
            ->value('avg_hours');

        return round($averageResolutionTime, 2);
    }

    /**
     * Get overall collection efficiency
     */
    public static function getOverallCollectionEfficiency()
    {
        $totalCollections = self::where('type', 'collector_action')->count();
        $totalBins = \App\Models\Bin::count();

        return $totalBins > 0 ? round(($totalCollections / $totalBins) * 100, 2) : 0;
    }
}
