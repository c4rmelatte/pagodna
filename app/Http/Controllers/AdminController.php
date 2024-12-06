<?php

namespace App\Http\Controllers;
use App\Models\Building;
use App\Models\Department;
use App\Models\announcements;
use App\Models\Curriculum;
use App\Models\Courses;
use App\Models\Subject;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function showStats() {
        $building = Building::count();
        $announcement = announcements::count();
        $curriculum = Curriculum::count();
        $courses = Courses::count();
        $subject = Subject::count();
        
        $department = Department::get();    
        $users = User::get();   
        $announcements = DB::table('announcements')->orderByDesc('id')->get();

        return view('admin.admin.admin')->with([
            'building' => $building,
            'departments' => $department,
            'announcement' => $announcement,
            'curriculum' => $curriculum,
            'courses' => $courses,
            'subject' => $subject,
            'users' => $users,
            'announcements' => $announcements,
        ]); 
    }
}
