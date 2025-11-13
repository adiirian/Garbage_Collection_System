<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'reason',
        'penalty_date',
    ];

    protected $casts = [
        'penalty_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the penalty
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get penalties statistics
     */
    public static function getPenaltiesStats()
    {
        return [
            'total_penalties' => self::count(),
            'total_amount' => self::sum('amount'),
            'penalties_this_month' => self::whereMonth('penalty_date', now()->month)
                ->whereYear('penalty_date', now()->year)
                ->count(),
            'average_penalty_amount' => self::avg('amount'),
        ];
    }
}
