<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() // will modify by ORTEGA
    {
        // Schema::create('attendance', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedInteger('time_in');
        //     $table->unsignedInteger('time_out');

        //     $table->unsignedBigInteger('event_id');
        //     $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        //     $table->unsignedBigInteger('user_id');
        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        //     $table->unsignedBigInteger('employee_id');
        //     $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        // });

        // student class attendance checklist table
        Schema::create('student_class_attendance_checklist', function (Blueprint $table) {
            $table->text('id_number');
            $table->foreign('id_number')->references('id')->on('users')->onDelete('cascade');
            $table->text('studentName');
            $table->text('subject_id');
            $table->boolean('checklist')->default(false);
            $table->text('date');
            $table->text('term');
        });

        // student event attendance checklist table
        Schema::create('student_event_attendance_checklist', function (Blueprint $table) {
            $table->text('student_id');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('student_name');
            $table->text('event_name');
            $table->text('event_description');
            $table->text('course_year_block');
            $table->text('date');
            $table->text('time_start');
            $table->text('time_end');
            $table->boolean('checklist')->default(false);
            $table->text('program_head');
        });

        // employee dtr table
        Schema::create('employee_dtr', function (Blueprint $table) {
            $table->text('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('month_year');
            $table->text('day');
            $table->text('time_in');
            $table->text('late');
            $table->text('time_out');
            $table->text('undertime');
            $table->text('overtime');
            $table->text('hours_worked');
        });

        // employee payroll table
        Schema::create('employee_payroll', function (Blueprint $table) {
            $table->text('id_number');
            $table->text('department');
            $table->text('position');
            $table->text('pay_period');
            $table->text('pay_date');
            $table->decimal('salary', 8, 2)->default(0.00);
            //$table->text('additional_hours');
            $table->decimal('bonus', 8, 2)->default(0.00);
            $table->decimal('deduction', 8, 2)->default(0.00);
            //$table->decimal('insurance', 8, 2)->default(0.00);
            //$table->decimal('retirement_contribution', 8, 2)->default(0.00);
            $table->text('account_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_class_attendance_checklist');
        Schema::dropIfExists('student_event_attendance_checklist');
        Schema::dropIfExists('employee_dtr');
        Schema::dropIfExists('employee_payroll');
    }
};
