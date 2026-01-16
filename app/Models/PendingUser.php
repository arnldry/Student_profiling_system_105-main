<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PendingUser extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'first_name',
        'last_name',
        'middle_name',
        'suffix',
        'email_verification_token',
        'email_verification_sent_at',
    ];

    protected $casts = [
        'email_verification_sent_at' => 'datetime',
    ];
}