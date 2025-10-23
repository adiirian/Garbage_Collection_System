<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    const ADMIN = 'admin';
    const COLLECTOR = 'collector';
    const PUBLIC_USER = 'public_user';

    public static function getAvailableRoles()
    {
        return [
            self::ADMIN,
            self::COLLECTOR,
            self::PUBLIC_USER,
        ];
    }
}