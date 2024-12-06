<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class AttendanceController extends Controller // OFF MODIFIED
{

    // PROFESSOR ############################################################################################################

    // show professor subjets list
    public function showSubjects() { // TO BE MODIFIED
        
        $professorID = "22-2222-222";

        $professor = DB::table('users')->where('role', 'professors')->where('user_id', $professorID)->first();

        $subjects = DB::table('example_subjects')->where('professor_id', $professorID)->get();

        return view('pages/prof_subjects', ['subjects' => $subjects, 'professor' => $professor]);

    }

    // show subject attendance
    public function showProfAttendance(Request $request) {
        
        $subjectID = $request->input('subjectID');

        $subject = DB::table('example_subjects')->where('subject_id', $subjectID)->first();

        return view('pages/prof_attendance', ['subject' => $subject, 'students' => '']);

    }

    // add date subject attendance
    public function addProfAttendance(Request $request) {
        
        $subjectID = $request->input('subjectID');
        $courseYearBlock = $request->input('courseYearBlock');
        $term = $request->input('term');
        $date = $request->input('date');

        // if existed
        $existedAttendance = DB::table('student_class_attendance_checklist')->where('subject_id', $subjectID)->where('date', $date)->where('term', $term)->first();

        if ($existedAttendance) {
            return redirect()->back()->with('alert', 'Cannot add attendance. Attendance already existing.');
        }

        $students = DB::table('example_student_subject')->where('course_year_block', $courseYearBlock)->get();

        // if ($students) {
        //     return $students;
        // }

        foreach ($students as $student) {

            $studentName = DB::table('users')->where('id', $student->student_id)->first();
            
            DB::table('student_class_attendance_checklist')->insert([

                'id_number' => $student->student_id,
                'studentName' => $studentName->name,
                'subject_id' => $subjectID,
                'checklist' => false,
                'date' => $date,
                'term' => $term

            ]);

        }

        return redirect()->back();

    }

    // find subject attendance with date and term
    public function findAttendance(Request $request) {
        
        $term = $request->input('term');
        $date = $request->input('date');
        $subjectID = $request->input('subjectID');

        $students = DB::table('student_class_attendance_checklist')->where('subject_id', $subjectID)->where('term', $term)->where('date', $date)->get();

        $subject = DB::table('example_subjects')->where('subject_id', $subjectID)->first();

        return view('pages/prof_attendance', ['students' => $students, 'subject' => $subject]);

    }

    // update subject attendance
    public function updateAttendance(Request $request) {

        $attendedIds = $request->input('attended', []);
        $term = $request->input('term');
        $date = $request->input('date');
        $subjectID = $request->input('subjectID');

        $students = DB::table('student_class_attendance_checklist')->where('subject_id', $subjectID)->where('term', $term)->where('date', $date)->get();

        $subject = DB::table('example_subjects')->where('subject_id', $subjectID)->first();

        foreach ($students as $student) {

            $isAttended = in_array($student->id_number, $attendedIds) ? true : false;

            DB::table('student_class_attendance_checklist')->where('id_number', $student->id_number)->where('subject_id', $subjectID)->where('term', $term)->where('date', $date)->update([

                'checklist' => $isAttended
    
            ]);
        }

        return redirect()->back();

    }

    // delete attendance on date and term
    public function deleteAttendance(Request $request) {

        $term = $request->input('term');
        $date = $request->input('date');
        $subjectID = $request->input('subjectID');

        DB::table('student_class_attendance_checklist')->where('subject_id', $subjectID)->where('term', $term)->where('date', $date)->delete();

        return redirect()->back();

    }

    // PROGRAM HEAD ################################################################################################################

    // show events
    public function showEvents($userID) { // TO BE MODIFIED

        $programHeadID = $userID;

        $programHead = DB::table('users')->where('position', 'program_heads')->where('id', $programHeadID)->first();

        $events = DB::table('student_event_attendance_checklist')->where('program_head', $programHeadID)->select('event_name')->distinct()->get();

        return view('programhead/pages/events/proghead_events', ['programHeadID' => $programHeadID, 'programHead' => $programHead, 'events' => $events]);
        
    }
    // show create page
    public function createEvents(Request $request) { // TO BE MODIFIED
        
        $programHeadID = $request->input('programHeadID');

        //$courseYearBlocks = DB::table('example_student_subject')->distinct()->pluck('course_year_block');

        $students = DB::table('students')->select('course', 'year_level', 'block')->distinct()->get();

        $courseYearBlocks = $students->map(function ($student) {
            return $student->course . ' ' . $student->year_level . '-' . $student->block;
        });

        return view('programhead/pages/events/create_event', ['programHeadID' => $programHeadID, 'courseYearBlocks' => $courseYearBlocks]);

    }

    // add/insert event
    public function insertEvents(Request $request) { // TO BE MODIFIED

        $programHeadID = $request->input('programHeadID');

        $eventName = $request->input('eventName');
        $date = $request->input('date');
        $timeStart = $request->input('timeStart');
        $timeEnd = $request->input('timeEnd');
        $description = $request->input('description');
        $selectedParticipants = $request->input('selectedParticipants', []);

        // check if exits *****<<
        $eventExists = DB::table('student_event_attendance_checklist')->where('event_name', $eventName)->exists();

        if ($eventExists) { 
            return redirect()->back()->with(['alert' => '*Event name already in use.*']);
        }
        // end check *****>>

        foreach ($selectedParticipants as $selectedParticipant) {

            [$course, $right] = explode(' ', $selectedParticipant); 

            [$year_level, $block] = explode('-', $right);

            $studentIDs = DB::table('students')->where('course', $course)->where('year_level', $year_level)->where('block', $block)->pluck('user_id');

            foreach ($studentIDs as $studentID) {

                $student = DB::table('users')->where('id', $studentID)->first();

                $studentFullname = "{$student->surname},  {$student->firstname} {$student->middlename}";

                DB::table('student_event_attendance_checklist')->insert([

                    'student_id' => $studentID,
                    'student_name' => $studentFullname,
                    'event_name' => $eventName,
                    'event_description' => $description,
                    'course_year_block' => $selectedParticipant,
                    'date' => $date,
                    'time_start' => $timeStart,
                    'time_end' => $timeEnd,
                    'checklist' => false,
                    'program_head' => $programHeadID
        
                ]);
            }

        }

        return redirect()->route('show.events', ['userID' => $programHeadID]);

    }

    // show event attendance
    public function showEventAttendance(Request $request) { // TO BE MODIFIED
        
        $eventName = $request->input('eventName');

        $event = DB::table('student_event_attendance_checklist')->where('event_name', $eventName)->first();

        $eventChecklist = DB::table('student_event_attendance_checklist')->where('event_name', $eventName)->get();

        $programHeadName = DB::table('users')->where('id', $eventChecklist[0]->program_head)->first();

        return view('programhead/pages/events/proghead_attendance', ['eventChecklist' => $eventChecklist, 'programHeadName' => $programHeadName, 'event' => $event]);

    }

    // update event attendance
    public function updateEventAttendance(Request $request) {

        $eventName = $request->input('eventName');
        
        $attendedIds = $request->input('attended', []);

        $event = DB::table('student_event_attendance_checklist')->where('event_name', $eventName)->get();

        foreach ($event as $student) {

            $isAttended = in_array($student->student_id, $attendedIds) ? true : false;

            DB::table('student_event_attendance_checklist')->where('event_name', $eventName)->where('student_id', $student->student_id)->update([

                'checklist' => $isAttended
    
            ]);
        }

        return redirect()->back();

    }

    // show edit event
    public function editEventAttendance(Request $request) {

        $eventName = $request->input('eventName');

        $event = DB::table('student_event_attendance_checklist')->where('event_name', $eventName)->first();
        
        return view('programhead/pages/events/update_event', ['event' => $event]);

    }

    // edit event

    public function insertEditEventAttendance(Request $request) {

        $eventName = $request->input('eventName');
        $date = $request->input('date');
        $timeStart = $request->input('timeStart');
        $timeEnd = $request->input('timeEnd');
        $description = $request->input('description');

        DB::table('student_event_attendance_checklist')->where('event_name', $eventName)->update([

            'event_description' => $description,
            'date' => $date,
            'time_start' => $timeStart,
            'time_end' => $timeEnd

        ]);
        
        return redirect()->back();

    }

    // delete event

    public function deleteEventAttendance(Request $request) {
        
        $eventName = $request->input('eventName');

        DB::table('student_event_attendance_checklist')->where('event_name', $eventName)->delete();

        return redirect()->route('show.events');

    }

    // student *****************************************************************************************************

    // show student's subject
    public function showStudentSubjects(Request $request) { // TO BE MODIFIED

        $studentID = '99-9999-999';

        $studentBlock = DB::table('example_student_subject')->where('student_id', $studentID)->first();

        $studentSubjects = DB::table('example_subjects')->where('course_year_block', $studentBlock->course_year_block)->get();

        return view('programhead/pages/events/student_subjects', ['studentBlock' => $studentBlock, 'studentSubjects' => $studentSubjects]);
    }

    // show student subject attendance page
    public function showStudentAttendancePage(Request $request) { // TO BE MODIFIED

        $studentID = $request->input('studentID');
        $subjectID = $request->input('subjectID');

        $subjectCode = DB::table('example_subjects')->where('subject_id', $subjectID)->first();

        return view('programhead/pages/events/student_attendance', ['studentID' => $studentID, 'subjectID' => $subjectID, 'subjectCode' => $subjectCode->subject_code]);
        
    }


    // show student's attendance on chosen subject
    public function showSubjectAttendance(Request $request) { // TO BE MODIFIED
        
        $studentID = $request->input('studentID'); 
        $subjectID = $request->input('subjectID');
        $term = $request->input('option');

        $subjectCode = DB::table('example_subjects')->where('subject_id', $subjectID)->first();

        $attendanceDates = DB::table('student_class_attendance_checklist')->where('id_number', $studentID)->where('subject_id', $subjectID)->where('term', $term)->orderBy('date', 'asc')->get();

        $present = 0;
        $absent = 0;

        foreach ($attendanceDates as $attendanceDate) {

            if ($attendanceDate->checklist == 1) {
                $present++;
            } else {
                $absent++;
            }
            
            
        }

        return view('programhead/pages/events/student_attendance', [
                                                                        'attendanceDates' => $attendanceDates,
                                                                        'studentID' => $studentID,
                                                                        'subjectID' => $subjectID,
                                                                        'term' => $term,
                                                                        'subjectCode' => $subjectCode->subject_code,
                                                                        'present' => $present,
                                                                        'absent' => $absent
                                                                    ]);

    }

}
