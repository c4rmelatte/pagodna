<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';
    protected $fillable = [
        'name',
        'description',
        'building_id'
    ];
    
    public function building()
{
    return $this->belongsTo(Building::class);
}

}
