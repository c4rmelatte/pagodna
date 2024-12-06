<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //    use HasFactory;
    protected $table = 'payments';
    protected $fillable = [
        'name',
        'amount',
        'purpose',
        'price',
        'change',
        'type',
        'isPaid',
        // 'user_id'
    ];
}
