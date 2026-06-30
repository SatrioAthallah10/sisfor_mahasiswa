<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'question',
        'question_file',
        'description',
        'available_from',
        'due_at',
        'created_by',
    ];

    protected $casts = [
        'available_from' => 'datetime',
        'due_at' => 'datetime',
    ];

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
