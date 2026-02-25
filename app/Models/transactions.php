<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactions extends Model
{
    use HasFactory;
     protected $fillable = ['event_id','guest_id', 'currency', 'amount', 'note'];
     
         public function guest()
    {
        return $this->belongsTo(guests::class,'guest_id');
    }

      public function event()
    {
        return $this->belongsTo(events::class);
    }
    
}
