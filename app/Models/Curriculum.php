<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    protected $table = 'curriculums';
    protected $fillable = [
        'code',
        'name',
        'course_id',
        'level'
    ];

    public function course()
    {
        return $this->belongsTo(Courses::class);
    }


    // public function courses()
    // {
    //     return $this->hasMany(Courses::class, 'curriculum_id');
    // }

}
