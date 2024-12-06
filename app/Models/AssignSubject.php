<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignSubject extends Model
{
    protected $table = 'assigned_subject';
    protected $fillable = [
        'curriculum_id',
        'subject_id',
        'department_id',
        'prof_id',
        'course_id',
        'section_id',
        'assigned_by'
    ];

    public function assigned_subject()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function professor()
    {
        return $this->belongsTo(User::class, 'prof_id')->where('position', 'professors');
    }
    
    

    

}
