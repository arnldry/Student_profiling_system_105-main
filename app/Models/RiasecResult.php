<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class RiasecResult extends Model
{
  protected $fillable = [
    'user_id', 
    'code', 
    'scores'
  ];

  protected $casts = [
    'scores' => 'array',
  ];

  
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
