<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class events extends Model
{
    use HasFactory;
     protected $fillable = ['name', 'event_date', 'location', 'description'];

        public function guests()
    {
        return $this->hasMany(guests::class, 'event_id');
    }
    public function transactions()
{
    return $this->hasMany(transactions::class,'event_id');
}

}
