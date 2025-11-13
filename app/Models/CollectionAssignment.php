<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'collector_id',
        'assigned_area',
        'scheduled_date',
        'scheduled_time',
        'status',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime:H:i',
    ];

    /**
     * Get the collector that owns the assignment
     */
    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }

    /**
     * Check if assignment is overdue
     */
    public function isOverdue()
    {
        $scheduledDateTime = $this->scheduled_date->setTimeFromTimeString($this->scheduled_time);
        return now()->greaterThan($scheduledDateTime) && $this->status !== 'completed';
    }

    /**
     * Check if penalty can be applied
     */
    public function canApplyPenalty()
    {
        return $this->isOverdue() && $this->status === 'failed';
    }
}
