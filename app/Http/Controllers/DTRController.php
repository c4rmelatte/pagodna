<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class DTRController extends Controller // OFFLINE MODIFIED
{

    public function index()
    {

        return view('pages/dtr_input');

    }

    // check role if the id is an employee
    public function checkRole(Request $request)
    {

        $request->validate([
            'idInput' => 'required'
        ]);

        // TO BE CHANGED
        $idInput = $request->input('idInput');

        $currentDateCheck = $request->input('currentDateCheck');

        // find employee
        $employee = DB::table('users')->where('position', '!=', 'students')->where('id', $idInput)->first();

        if ($employee) {

            $employeeCheck = DB::table('employee_dtr')->where('user_id', $idInput)->where('day', $currentDateCheck)->first();

            if (empty($employeeCheck->time_in)) {
                $timeInOut = 'TIME IN';
            } else if (empty($employeeCheck->time_in) === false && empty($employeeCheck->time_out) === false && $employeeCheck->time_out !== '00:00:00') {
                return redirect()->back()->with('alert', 'You have already timed out.');
            } else {
                $timeInOut = 'TIME OUT';
            }
            
            $employeeFullname = "{$employee->surname}, {$employee->firstname} {$employee->middlename}";

            return redirect()->back()->with([
                'name' => $employeeFullname,
                'idInput' => $idInput,
                'timeInOut' => $timeInOut
            ]);
        } else {
            return redirect()->back()->with('alert', 'Employee not found.');
        }

    }

    // insert time in and time out into database
    public function logTime(Request $request)
    {

        // TO BE CHANGED
        $idInputHidden = $request->input('idInputHidden');

        $currentTime = $request->input('currentTime');
        $currentDate = $request->input('currentDate');

        $monthYear = Carbon::parse($currentDate)->format('M Y');

        $employee = DB::table('employee_dtr')->where('user_id', $idInputHidden)->where('day', $currentDate)->first();
        // get users role // MODIFIED 11:05 PM NOV 18
        $user = DB::table('users')->where('id', $idInputHidden)->first();
        $schedule = DB::table($user->position)->where('user_id', $idInputHidden)->first();

        if (empty($employee)) {

            $seconds = "00";

            //calculate late time in
            list($hours, $minutes) = explode(":", $currentTime);
            $currentTimeSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;

            list($hours, $minutes) = explode(":", $schedule->time_in_schedule);
            $scheduleTimeInSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;

            $lateSeconds = $currentTimeSeconds - $scheduleTimeInSeconds;

            if ($lateSeconds <= 0) {
                $currentTime = $schedule->time_in_schedule;
                $lateTimeIn = "00:00:00";

            } else {
                $hours = floor($lateSeconds / 3600);
                $minutes = floor(($lateSeconds % 3600) / 60);
                $seconds = $lateSeconds % 60;

                // back to the format
                $lateTimeIn = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
            }

            //kung needed maglagay ka rin ng code para mahandle yung time in na logged after time out schedule ##########################################################
#############################################################################################################################################################

            DB::table('employee_dtr')->insert([

                'month_year' => $monthYear,
                'user_id' => $idInputHidden,
                'day' => $currentDate,
                'time_in' => $currentTime,
                'late' => $lateTimeIn,
                'time_out' => '00:00:00',
                'undertime' => '00:00:00',
                'overtime' => '00:00:00',
                'hours_worked' => '0.00 Hours'

            ]);
        } else {

            //calculate late time out

            $seconds = "00";
            list($hours, $minutes) = explode(":", $currentTime);
            $currentTimeSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;

            list($hours, $minutes) = explode(":", $schedule->time_out_schedule);
            $scheduleTimeOutSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;

            $undertimeSeconds = $scheduleTimeOutSeconds - $currentTimeSeconds;

            if ($undertimeSeconds == 0) {
                $undertime = "00:00:00";
                $overtime = "00:00:00";

            } elseif ($undertimeSeconds < 0) {

                $undertime = "00:00:00";
                $overtimeSeconds = $currentTimeSeconds - $scheduleTimeOutSeconds;

                $hours = floor($overtimeSeconds / 3600);
                $minutes = floor(($overtimeSeconds % 3600) / 60);
                $seconds = $overtimeSeconds % 60;

                // back to the format
                $overtime = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);

            } else {
                $hours = floor($undertimeSeconds / 3600);
                $minutes = floor(($undertimeSeconds % 3600) / 60);
                $seconds = $undertimeSeconds % 60;

                // back to the format
                $undertime = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
                $overtime = "00:00:00";
            }

            // calculate hours_worked

            $seconds = "00";
            list($hours, $minutes) = explode(":", $currentTime);
            $currentTimeSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;

            list($hours, $minutes) = explode(":", $employee->time_in);
            $timeInSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;

            $secondsWorked = $currentTimeSeconds - $timeInSeconds;

            $hours = number_format($secondsWorked / 3600, 2);

            $hoursWorked = $hours . " Hours";

            DB::table('employee_dtr')->where('user_id', $idInputHidden)->where('day', $currentDate)->update([

                'time_out' => $currentTime,
                'undertime' => $undertime,
                'overtime' => $overtime,
                'hours_worked' => $hoursWorked

            ]);
        }

        return redirect()->back();

    }

    // get dtr data of the employee and display it // MODIFIED
    public function getDTR(Request $request, $userID)
    {

        // TO BE CHANGED
        //$id = '22-2222-222';
        $id = $userID;

        $employeePosition = DB::table('users')->where('id', $id)->first();

        $position = $employeePosition->position;

        $currentDate = $request->input('currentDate');
        $monthYear = Carbon::parse($currentDate)->format('M Y');

        $employee = DB::table('employee_dtr')->where('user_id', $id)->where('month_year', $monthYear)->get();
        $monthYears = DB::table('employee_dtr')->where('user_id', $id)->distinct()->pluck('month_year');

        
        if ($position == 'program_heads') {
            return view('programhead/pages/programheaddtr', [
                'employee' => $employee,
                'monthYears' => $monthYears,
                'id' => $id,
                'monthYearDisplay' => $monthYear
            ]);

        } elseif ($position == 'professors') {
            return view('professor/pages/professordtr', [
                'employee' => $employee,
                'monthYears' => $monthYears,
                'id' => $id,
                'monthYearDisplay' => $monthYear
            ]);

        } elseif ($position == 'hr') {
            return view('hr/pages/hrdtr', [
                'employee' => $employee,
                'monthYears' => $monthYears,
                'id' => $id,
                'monthYearDisplay' => $monthYear
            ]); 

        } elseif ($position == 'admin') {
            return view('admin/pages/admindtr', [
                'employee' => $employee,
                'monthYears' => $monthYears,
                'id' => $id,
                'monthYearDisplay' => $monthYear
            ]);
        } elseif ($position == 'treasury') { // TREASURY VIEW DTR **********************************************************

            return view('treasury/pages/treasurydtr', [
                'employee' => $employee,
                'monthYears' => $monthYears,
                'id' => $id,
                'monthYearDisplay' => $monthYear
            ]); 

        } elseif ($position == 'registrar') {
            
            // deprecated!!!

        }

    }

    // get dtr data selected from the dropdown
    public function getDateDTR(Request $request)
    {

        $monthYear = $request->input('selected_date');

        // TO BE CHANGED
        $id = $request->input('employeeID');

        $employeePosition = DB::table('users')->where('id', $id)->first();

        $position = $employeePosition->position;

        $employee = DB::table('employee_dtr')->where('user_id', $id)->where('month_year', $monthYear)->get();
        $monthYears = DB::table('employee_dtr')->where('user_id', $id)->distinct()->pluck('month_year');

        if ($position == 'program_heads') {
            return view('programhead/pages/programheaddtr', [
                'employee' => $employee,
                'monthYears' => $monthYears,
                'id' => $id,
                'monthYearDisplay' => $monthYear
            ]);

        } elseif ($position == 'professors') {
            return view('professor/pages/professordtr', [
                'employee' => $employee,
                'monthYears' => $monthYears,
                'id' => $id,
                'monthYearDisplay' => $monthYear
            ]);

        } elseif ($position == 'hr') {
            return view('hr/pages/hrdtr', [
                'employee' => $employee,
                'monthYears' => $monthYears,
                'id' => $id,
                'monthYearDisplay' => $monthYear
            ]); 

        } elseif ($position == 'admin') {
            return view('admin/pages/admindtr', [
                'employee' => $employee,
                'monthYears' => $monthYears,
                'id' => $id,
                'monthYearDisplay' => $monthYear
            ]);
        } elseif ($position == 'treasury') { // TREASURY VIEW DTR **********************************************************

            return view('treasury/pages/treasurydtr', [
                'employee' => $employee,
                'monthYears' => $monthYears,
                'id' => $id,
                'monthYearDisplay' => $monthYear
            ]); 

        } elseif ($position == 'registrar') {
            
            // deprecated!!!

        }

    }

    // login example user // TO BE CHANGED
    public function login(Request $request)
    {

        // TO BE CHANGED
        $id = $request->input('login_user');

        // example login
        $validID = DB::table('users')->where('role', '!=', 'student')->where('id', $id)->first();

        if (!$validID) {
            return redirect()->back()->with('alert', 'user not found.');
        } else {
            return redirect()->route('show.time', ['id' => $id]);
        }
    }
}
