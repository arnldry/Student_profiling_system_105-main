<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_year',
        // support multiple possible flags
        'archived',
        'is_archived',
        'is_active',
    ];

    protected $casts = [
        'archived' => 'boolean',
        'is_archived' => 'boolean',
        'is_active' => 'boolean',
    ];
}
