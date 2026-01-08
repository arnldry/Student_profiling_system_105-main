<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class RiasecResult extends Model
{
  protected $fillable = [
    'user_id',
    'code',
    'scores',
    'is_retake',
    'previous_result_id',
    'admin_reopened'
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
    return $this->belongsTo(RiasecResult::class, 'previous_result_id');
  }
}
