<?php

namespace App\Http\Controllers;
use App\Models\Building;
use App\Models\Department;
use App\Models\Courses;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{

    public function index()
    {

        $department = Department::get();
        // return $department;

        $buildings = Building::all();
        // $buildings->save();

        return view('admin.department.createmodal', compact('department, buildings'));

    }

    public function destroy(Department $department)
    {
        $department->delete();
        return Redirect::to('/admin/departments');
    }



    public function store(Request $request)
    {

        $departments = new Department();
        $departments->name = $request->get('name');
        $departments->description = $request->get('description');
        $departments->building_id = $request->get('building_id');
        $departments->save();

        return Redirect::to('/admin/departments');
    }


    public function update(Request $request, $id)
    {
        // Find the department by its ID
        $department = Department::findOrFail($id);

        // Update department with the new data
        $department->update([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'building_id' => $request->get('building_id'),
        ]);

        // Redirect to the departments list or other relevant page
        return Redirect::to('/admin/departments');
    }

    public function showCourses(Department $department)
    {

        $departments = Department::findOrFail($department->id);

        $courses = Courses::where('department_id', $department->id)
            ->latest()
            ->get('name'); 

        $count = $courses->count(); 
        //check lang kung may naadd kana ba na course sa dept or wala pa
        //kupal kaba?

        return view('admin.department.components.showcourses')
            ->with('departments', $departments)
            ->with('count', $count)
            ->with('courses', $courses);
    }
}
