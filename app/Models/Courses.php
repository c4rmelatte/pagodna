<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    protected $table = 'courses';
    protected $fillable = [
        'name',
        'description',
        'department_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // public function curriculum()
    // {
    //     return $this->belongsTo(Curriculum::class);
    // }
}
