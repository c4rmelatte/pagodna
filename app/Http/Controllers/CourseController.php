<?php

namespace App\Http\Controllers;
use App\Models\Department;
use App\Models\Courses;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Courses::latest()->get();
        //$departments = Department::latest()->select('name')->distinct()->get();

        $departments = Department::select('id', 'name')
                                    ->latest('id')
                                    ->groupBy('name')
                                    ->get();

        return view('admin.pages.courses')->with(['courses' => $courses, 'departments' => $departments]);
    }

    public function store(Request $request)
    {
        $course = new Courses();
        $course->name = $request->get('name');
        $course->description = $request->get('description');
        $course->department_id = $request->get('department_id');
        $course->save();

        return Redirect::to('/admin/courses');
    }

    public function destroy(Courses $course)
    {
        $course->delete();
        return Redirect::to('/admin/courses');
    }

    public function update(Request $request, $id)
    {
        // Find the course by its ID
        $course = Courses::findOrFail($id);

        // Update course with the new data
        $course->update([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'department_id' => $request->get('department_id'),
        ]);

        // Redirect to the departments list or other relevant page
        return Redirect::to('/admin/courses');
    }


}
