<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Payment;
use App\Models\TotalFunds;
use App\Models\purpose;
use App\Models\User;
use App\Models\Student;

class EnrollmentController extends Controller
{

    public function indexStudent(){

        $userID = session('userID');
        $userInfo = User::firstWhere('id',$userID);
        $studentInfo = Student::firstWhere('user_id',$userID);

        if($userInfo->position != 'students'){
            return redirect('/student');
        }

        $firstName = $userInfo->firstname;
        $middleName = $userInfo->middlename;
        $lastName = $userInfo->surname;
        $studentId = $studentInfo->id;
        $userGender = 'male';// $userInfo->gender;
        $studentCourse = $studentInfo->course;

        $studentSem = $studentInfo->semester;
        if ($studentSem == null){
            $studentSem = 'N/A';
        }

        $yearSection = $studentInfo->year_level."-".$studentInfo->block;
        

        return view('student.pages.enrollmentform')
        ->with('firstName', $firstName)
        ->with('lastName', $lastName)
        ->with('middleName', $middleName)
        ->with('studentId', $studentId)
        ->with('Gender', $userGender)
        ->with('Course', $studentCourse)
        ->with('Semester', $studentSem)
        ->with('yearSection', $yearSection);

        // $firstName = ('fname');
        // $middleName = $request->input('middleName');
        // $surname = $request->input('surName');

        // $age = $request->input('age');
        // $address = $request->input('address');
        // $username = $request->input('username');
        // $email = $request->input('email');
        // $password = $request->input('password');
        // $department = $request->input('department');

        // $yearlevel = $request->input('studentYear');
        // $course = $request->input('studentCourse');
        // $block = $request->input('studentSection');

    }

    public function payEnrollment(Request $request)
    {
        $money = $request->input("amount");
        $totalMiscPrice = purpose::where('type', 'miscellaneous')->sum('price');
        $totalPrice = purpose::whereIn('type', ['tuition', 'Other_Charges'])->sum('price');
        $price = $totalMiscPrice + $totalPrice;
        $change = $money - $price;
        $isPaid = FALSE;


        if ($money == $price) {
            $change = $money - $price;
            $isPaid = TRUE;
        } elseif ($money < $price) {
            $change =  $money - $price;
            return response()->json(['message' => 'Not enough money'], 404);
        } elseif ($money > $price) {
            $change =  $money - $price;
            $isPaid = TRUE;
        }

        Payment::create([
        'name' => 'name nung nagbayad kayo nalang mag ayos pls di ko alam pano to',
        'amount' => $money,
        'price' => $price,
        'change' => $change,
        'type' => 'Enrollment',
        'isPaid' => $isPaid
        ]);


        $funds = totalFunds::first();
        $totalAmount = $money - $change;

         $funds->increment(
            'funds',$totalAmount
         );

         return redirect()->to('page nung enrollment basta');


        }




    public function index(){

        $userId = 1;

        $user = User::where('id', $userId)->get();
        $purposes = purpose::where('id',$userId)->get();
        
    }

    
}
