<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class LifeValuesResult extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'scores',
        'is_retake',
        'previous_result_id',
        'admin_reopened',
    ];


    protected $casts = [
        'scores' => 'array',
        'is_retake' => 'boolean',
        'admin_reopened' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function previousResult()
    {
        return $this->belongsTo(LifeValuesResult::class, 'previous_result_id');
    }
}
