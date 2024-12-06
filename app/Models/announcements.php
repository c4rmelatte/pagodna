<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class announcements extends Model
{
    protected $table = 'announcements';
    /** @use HasFactory<\Database\Factories\AnnouncementsFactory> */
    use HasFactory;
}
