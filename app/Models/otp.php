<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class otp extends Model
{
    use HasFactory;
      protected $fillable = [
        'user_id',
        'otp',
        'expires_at'
    ];

  
    protected $dates = [
        'expires_at'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

   //note
    public function isExpired()
    {
        return Carbon::now()->gt($this->expires_at);
    }
}
