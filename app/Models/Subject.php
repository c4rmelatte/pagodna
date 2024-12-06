<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';
    protected $fillable = [
        'code',
        'name',
        'curriculum_id',
        'description'

    ];

    public function subject()
    {
        return $this->belongsTo(Curriculum::class);
    }
}
