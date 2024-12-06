<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\User;

use Illuminate\Http\Request;

class StudentController extends Controller
{

   
    // create student
    public function createStudent(Request $request)
    {

        // ** randomized id **
        // function generateRandomNumber() {
        //     $part1 = str_pad(mt_rand(0, 99), 2, '0', STR_PAD_LEFT); // Generate 2 digits
        //     $part2 = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT); // Generate 5 digits
        //     $part3 = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT); // Generate 3 digits

        //     return "{$part1}-{$part2}-{$part3}";
        // }

        // $generatedID = generateRandomNumber();

        $firstName = $request->input('fname');
        $middleName = $request->input('middleName');
        $surname = $request->input('surName');

        $age = $request->input('age');
        $address = $request->input('address');
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');
        $department = $request->input('department');

        $yearlevel = $request->input('studentYear');
        $course = $request->input('studentCourse');
        $block = $request->input('studentSection');

        // check for existing email
        $existingEmail = DB::table('users')->where('email', $email)->first();

        if ($existingEmail) {
            // placeholder
            return "email already existing";
        }

        DB::table('users')->insert([

            'surname' => $surname,
            'firstname' => $firstName,
            'middlename' => $middleName,
            'age' => $age,
            'address' => $address,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'department' => $department,
            'category' => 'students',
            'position' => 'students',
            'account_number' => 0,

        ]);

        // find id of user through given email value
        $studentID = DB::table('users')->where('email', $email)->first();

        // position
        DB::table('students')->insert([

            'user_id' => $studentID->id,
            'course' => $course,
            'year_level' => $yearlevel,
            'block' => $block

        ]);

        return redirect()->back();
    }
    // END CREATE STUDENT FUNCTION **************************************************************************

    // show students
    public function showStudents() {

        $programheadDepartment = session('userDepartment');

        // find students
        $students = DB::table('users')->where('category', 'students')->get();

        // find students
        $students = DB::table('users')
                        ->join('students', 'users.id', '=', 'students.user_id')
                        ->where('department', $programheadDepartment)
                        ->get();

        return view('programhead.pages.student', ['students' => $students]);
    }

    // update students
    public function updateStudent(Request $request, $id) {

        $firstName = $request->input('fname');
        $middleName = $request->input('middleName');
        $surname = $request->input('surName');
        $age = $request->input('age');
        $address = $request->input('address');
        $username = $request->input('username');

        $yearlevel = $request->input('studentYear');
        $course = $request->input('studentCourse');
        $block = $request->input('studentSection');

        // update the student
        DB::table('users')->where('id', $id)->update([

            'surname' => $surname,
            'firstname' => $firstName,
            'middlename' => $middleName,
            'age' => $age,
            'address' => $address,
            'username' => $username

        ]);

        // student info
        DB::table('students')->where('user_id', $id)->update([

            'course' => $course,
            'year_level' => $yearlevel,
            'block' => $block

        ]);

        return redirect()->back();
    }

    // delte students
    public function deleteStudent($id) {

        // delete the student
        DB::table('users')->where('id', $id)->delete();

        return redirect()->back();
    }
}



