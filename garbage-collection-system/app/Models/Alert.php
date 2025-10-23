<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'bin_id',
        'user_id',
        'status',
        'message',
    ];

    public function bin()
    {
        return $this->belongsTo(Bin::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}