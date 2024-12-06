<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    // find user
    public function loginUser(Request $request)
    {
        
        $email = $request->input('email');
        $password = $request->input('password');

        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = DB::table('users')->where('email', $email)->where('password', $password)->first();

        if (!$user) {
            // placeholder return
            return redirect()->back()->withErrors(['email' => 'Invalid credentials.']);
        }

        // Save user ID in the session
        $request->session()->put('user', $user);
        $request->session()->put('userID', $user->id);
        $request->session()->put('userPosition', $user->position);
        $request->session()->put('userDepartment', $user->department);

        if ($user->position == 'program_heads') {

            $departmentID = DB::table('departments')->where('name', $user->department)->select('id')->first();

            $courses = DB::table('courses')->where('department_id', $departmentID->id)->get();

            $request->session()->put('courses', $courses);
        }

        $userRole = $user->position;

        if ($userRole == 'students') {
            return redirect()->route('student');

        } elseif ($userRole == 'program_heads') {
            return redirect()->route('programhead');

        } elseif ($userRole == 'professors') {
            return redirect()->route('professor');

        } elseif ($userRole == 'hr') {
            return redirect()->route('hr');

        } elseif ($userRole == 'admin') {
            return redirect()->route('admin');

        } elseif ($userRole == 'treasury') {
            return redirect()->route('treasury');

        } elseif ($userRole == 'registrar') {
            // return view of registrar

        }
    }

    // logout user
    public function logoutUser(Request $request)
    {
        // Clear the session
        $request->session()->flush();

        // Redirect to login page
        return redirect()->route('login');
    }
}
