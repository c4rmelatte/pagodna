<?php

namespace App\Http\Controllers;
// use App\Http\Models\Deparment;
use App\Models\Department;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class HRController extends Controller
{
    // show *******************************************************************************************
    public function showEmployees()
    {

        // find employees
        $employees = DB::table('users')->where('position', '!=', 'students')->get();
        $departments = Department::latest()->select('name')->distinct()->get();

        return view('hr.pages.employee')->with(
            [
                'employees' => $employees,
                'departments' => $departments
            ]
        );
    }

    // update *******************************************************************************************
    public function updateEmployee(Request $request, $id)
    {

        $firstName = $request->input('fname');
        $middleName = $request->input('middleName');
        $surname = $request->input('surName');
        $age = $request->input('age');
        $address = $request->input('address');
        $username = $request->input('username');
        $accountNumber = $request->input('accountNumber');

        $timeIn = $request->input('timeIn');
        $timeOut = $request->input('timeOut');
        $rate = $request->input('rate');

        $insurance = $request->input('insurance');
        $retirement = $request->input('retirement');

        // update the employee
        DB::table('users')->where('id', $id)->update([

            'surname' => $surname,
            'firstname' => $firstName,
            'middlename' => $middleName,
            'age' => $age,
            'address' => $address,
            'username' => $username,
            'account_number' => $accountNumber

        ]);

        $employee = DB::table('users')->where('id', $id)->first();

        // position
        DB::table($employee->position)->insert([

            'user_id' => $employee->id,
            'time_in_schedule' => $timeIn,
            'time_out_schedule' => $timeOut,
            'rate' => $rate,
            'insurance' => $insurance,
            'retirement_contribution' => $retirement

        ]);

        return redirect()->back();
    }

    // delete *******************************************************************************************
    public function deleteEmployee($id)
    {

        // delete the employee
        DB::table('users')->where('id', $id)->delete();

        return redirect()->back();
    }

    // create employee *******************************************************************************************
    public function createEmployee(Request $request)
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

        $category = $request->input('employeeCategory');
        $position = $request->input('employeePosition');
        $age = $request->input('age');
        $address = $request->input('address');
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');
        $department = $request->input('department');
        $accountNumber = $request->input('accountNumber');

        $timeIn = $request->input('timeIn');
        $timeOut = $request->input('timeOut');

        $rate = $request->input('rate');
        $insurance = $request->input('insurance');
        $retirement = $request->input('retirement');

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
            'category' => $category,
            'position' => $position,
            'account_number' => $accountNumber

        ]);

        // find id of user through given email value
        $employeeID = DB::table('users')->where('email', $email)->first();

        // insert into position and category tables
        // category
        DB::table($category)->insert([

            'user_id' => $employeeID->id,


        ]);

        // position
        DB::table($position)->insert([

            'user_id' => $employeeID->id,
            'time_in_schedule' => $timeIn,
            'time_out_schedule' => $timeOut,
            'rate' => $rate,
            'insurance' => $insurance,
            'retirement_contribution' => $retirement

        ]);

        return redirect()->back();
    }
    // END CREATE EMPLOYEE FUNCTION **************************************************************************

}
