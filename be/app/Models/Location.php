<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'address', 'description'];

    public function courts()
    {
        return $this->hasMany(Court::class, 'location_id');
    }
}
