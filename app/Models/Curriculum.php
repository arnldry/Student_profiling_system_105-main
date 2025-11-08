<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    protected $table = 'curriculum'; // explicitly use singular table

    protected $fillable = [
        'name',
        'is_archived',
    ];
}
