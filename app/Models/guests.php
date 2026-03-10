<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class guests extends Model
{
    use HasFactory;

   protected $fillable = [
    'event_id',
    'name',
    'currency',
    'amount',
    'phone',
    'address',
    'status',
    'note'
];

    // A Guest belongs to one Event
    public function event()
    {
        return $this->belongsTo(events::class, 'event_id');
    }

    // A Guest has many Transactions
    public function transactions()
    {
        return $this->hasMany(transactions::class, 'guest_id');
    }
}