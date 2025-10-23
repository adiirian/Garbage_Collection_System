<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bin extends Model
{
    use HasFactory;

    protected $fillable = [
        'location',
        'status',
        'level',
    ];

    const STATUS_EMPTY = 'empty';
    const STATUS_FULL = 'full';
    const STATUS_OVERFLOWING = 'overflowing';

    public function isFull()
    {
        return $this->status === self::STATUS_FULL;
    }

    public function isEmpty()
    {
        return $this->status === self::STATUS_EMPTY;
    }

    public function isOverflowing()
    {
        return $this->status === self::STATUS_OVERFLOWING;
    }

    public function updateStatus($level)
    {
        if ($level >= 80) {
            $this->status = self::STATUS_OVERFLOWING;
        } elseif ($level >= 50) {
            $this->status = self::STATUS_FULL;
        } else {
            $this->status = self::STATUS_EMPTY;
        }

        $this->level = $level;
        $this->save();
    }
}