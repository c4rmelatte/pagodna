<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class PayrollController extends Controller   // ORTEGA *******************************
{

    // view main payroll dashboard
    public function managePayroll()
    {

        return view('treasury/pages/payroll/manage_payslip');

    }

    // view create payslip page
    public function createPayslip()
    {

        return view('treasury/pages/payroll/create_payslip');

    }

    // insert payslip data to table
    public function insertPayslip(Request $request)
    {

        $id = $request->input('employeeID');

        // find the employee and check its role
        $employee = DB::table('users')->where('position', '!=', 'students')->where('id', $id)->first();

        if ($employee === null) {
            return redirect()->back()->with('alert', '*employee not found while creating.*')->withInput();
        }

        // get the position data
        $positionInfo = DB::table($employee->position)->where('user_id', $id)->first();

        $dateString = $request->input('payPeriod');

        $payslipExist = DB::table('payroll')->where('user_id', $id)->where('pay_period', $dateString)->first();

        if ($payslipExist) {
            return redirect()->back()->with('alert', '*payslip already existing.*')->withInput();
        }

        // Convert the string into a timestamp
        $timestamp = strtotime($dateString . '-01'); // Add a day to make it a valid date
        $payPeriod = date('M Y', $timestamp);

        $records = DB::table('employee_dtr')
                    ->where('user_id', $id)
                    ->where('month_year', $payPeriod)
                    ->pluck('hours_worked') // Retrieves a plain array
                    ->map(function ($value) { // Convert array to collection and process
                        return (float) preg_replace('/[^0-9.]/', '', $value);
                    });

        $totalHours = $records->sum();

        // get salary from dtr

        // could add function for reducing funds on treasury

        if (!$employee) {
            return redirect()->back()->with('alert', '*employee not found.*')->withInput();
        }

        $department = $employee->department;
        $position = $employee->position;
        
        $payDate = $request->input('payDate');

        $salary = $totalHours * $positionInfo->rate;
        
        // $additionalHours = $request->input('additionalHours');
        $bonus = $request->input('bonus');
        // $federalTax = $request->input('federalTax');
        $healthInsurance = $positionInfo->insurance;
        $retirementContribution = $positionInfo->retirement_contribution;
        $accountDigits = $employee->account_number;

        DB::table('payroll')->insert([

            'user_id' => $id,
            'department' => $department,
            'position' => $position,
            'pay_period' => $dateString,
            'pay_date' => $payDate,
            'base_salary' => $salary,
            // 'additional_hours' => $additionalHours,
            'bonus' => $bonus,
            //'deduction' => '',
            'insurance' => $healthInsurance,
            'retirement_contribution' => $retirementContribution,
            'account_number' => $accountDigits

            // $table->text('department');
            // $table->text('position');
            // $table->text('pay_period');
            // $table->text('pay_date');
            // $table->decimal('base_salary', 8, 2)->default(0.00);
            // //$table->text('additional_hours');
            // $table->decimal('bonus', 8, 2)->default(0.00);
            // //$table->decimal('tax', 8, 2)->default(0.00);
            // $table->decimal('insurance', 8, 2)->default(0.00);
            // $table->decimal('retirement_contribution', 8, 2)->default(0.00);
            // $table->text('account_number');

            // $table->unsignedBigInteger('user_id');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;

        ]);

        return redirect()->route('manage.payslip');

    }

    // find employee payslip
    public function findEmployee(Request $request)
    {

        $id = $request->input('id');
        $month = $request->input('month');

        $employee = DB::table('payroll')->where('user_id', $id)->where('pay_period', $month)->first();

        if ($employee) {

            return redirect()->back()->with(['found' => 'found', 'id' => $id, 'month' => $month, 'alert' => '*payslip found.*'])->withInput();

        } else {

            return redirect()->back()->with('alert', '*employee or payslip not found.*')->withInput();

        }


    }

    // go to update page with payslip data
    public function updatePayslip(Request $request)
    {

        $id = $request->input('id');
        $month = $request->input('month');

        $employee = DB::table('payroll')->where('user_id', $id)->where('pay_period', $month)->first();

        if (!$employee) {
            return "Employee not found for this ID and pay period.";
        }

        return view('treasury/pages/payroll/update_payslip', ['employee' => $employee]);

    }

    // update payslip with new data
    public function insertUpdatedPayslip(Request $request)
    {

        $employeeID = $request->input('id');
        // $department = $request->input('department');
        // $position = $request->input('position');
        $payPeriod = $request->input('month');
        $payDate = $request->input('payDate');
        // $baseSalary = $request->input('baseSalary');
        // $additionalHours = $request->input('additionalHours');
        $bonus = $request->input('bonus');
        // $federalTax = $request->input('federalTax');
        // $healthInsurance = $request->input('healthInsurance');
        // $retirementContribution = $request->input('retirementContribution');
        // $accountDigits = $request->input('accountDigits');

        DB::table('payroll')->where('user_id', $employeeID)->where('pay_period', $payPeriod)->update([

        //     'user_id' => $employeeID,
        //     'department' => $department,
        //     'position' => $position,
        //     'pay_period' => $payPeriod,
        'pay_date' => $payDate,
        //     'base_salary' => $baseSalary,  
        //     'additional_hours' => $additionalHours,
        'bonus' => $bonus,
        //     'tax' => $federalTax,
        //     'insurance' => $healthInsurance,
        //     'retirement_contribution' => $retirementContribution,
        //     'account_number' => $accountDigits

        ]);

        return redirect()->back();

    }

    // show data of payslip
    public function showPayslip(Request $request)
    {

        $id = $request->input('id');
        $month = $request->input('month');

        $employee = DB::table('payroll')->where('user_id', $id)->where('pay_period', $month)->first();

        $employeeName = DB::table('users')->where('position', '!=', 'students')->where('id', $id)->first();

        $totalEarnings = $employee->base_salary + $employee->bonus;

        $totalDeductions = $employee->insurance + $employee->retirement_contribution;

        $netPay = $totalEarnings - $totalDeductions;

        return view('treasury/pages/payroll/payslip', [
            'employee' => $employee,
            'employeeName' => $employeeName,
            'totalEarnings' => $totalEarnings,
            'totalDeductions' => $totalDeductions,
            'netPay' => $netPay
        ]);

    }

    // back to manage_payslip
    public function backPayslip(Request $request)
    {

        $id = $request->input('id');
        $month = $request->input('month');

        return view('treasury/pages/payroll/manage_payslip', ['idBacked' => $id, 'monthBacked' => $month]);

    }

    // delete payslip
    public function deletePayslip(Request $request)
    {

        $id = $request->input('id');
        $month = $request->input('month');

        DB::table('payroll')->where('user_id', $id)->where('pay_period', $month)->delete();

        return redirect()->back();

    }

}
