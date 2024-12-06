<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purpose extends Model
{
    protected $table = 'purposes';
    /** @use HasFactory<\Database\Factories\PurposeFactory> */
    use HasFactory;

    //treasury purpose(ID,UNif, etc.)
    protected $fillable = [
        'name',
        'price',
        'type'
    ];
}