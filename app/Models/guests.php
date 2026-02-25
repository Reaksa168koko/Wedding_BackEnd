<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class guests extends Model
{
    use HasFactory;
      protected $fillable = ['event_id', 'guest_no', 'name', 'phone', 'address','status', 'note'];
          public function event()
    {
        return $this->belongsTo(events::class, 'event_id');
    }

    // One Guest has many Transactions
    public function transactions()
    {
        return $this->hasMany(transactions::class,'guest_id');
    }
}
