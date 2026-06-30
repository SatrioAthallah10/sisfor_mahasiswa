<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'open_at',
        'close_at',
        'created_by',
    ];

    protected $casts = [
        'open_at' => 'datetime',
        'close_at' => 'datetime',
    ];

    public function records()
    {
        return $this->hasMany(AttendanceRecord::class);
    }
}
