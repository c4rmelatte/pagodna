<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Section;
use App\Models\Department;
use App\Models\Courses;
class SectionController extends Controller
{
    public function index(){


        $sections = Section::with('courses')
        ->orderBy('year_level')  // You can change this to any field you want to order by
        ->orderBy('block')      // Additional sorting can be done here
        ->get();

        $departments = Department::all();
        $courses = Courses::all();


    
        return view('admin.pages.section')->with(['courses' => $courses, 'sections' => $sections, 'departments'=> $departments]);
    }

    public function store(Request $request)
    {
        $section = new Section();
        $section->department_id = $request->get('department_id');
        $section->course_id = $request->get('course_id');
        $section->year_level = $request->get('year_level');
        $section->block = $request->get('block');
        $section->save();

        return Redirect::to('/admin/section');
    }

}
