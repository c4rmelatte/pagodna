<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalFunds extends Model
{
    protected $table = 'total_funds';
    use HasFactory;
    protected $fillable = [
        'funds',
    ];
}
