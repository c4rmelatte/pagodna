<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration // MODIFIED BY ORTEGA 10:19 AM NOV 18 ***********************
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // POSITIONS TABLE ***************************************************************************

        // PROFESSORS
        Schema::create('professors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // schedule time in time out
            $table->TEXT('time_in_schedule');
            $table->TEXT('time_out_schedule');

            // salary
            $table->decimal('rate', 8, 2)->default(0.00);

            // deductions
            $table->decimal('insurance', 8, 2)->default(0.00);
            $table->decimal('retirement_contribution', 8, 2)->default(0.00);

            $table->timestamps();
        });

        // TREASURY
        Schema::create('treasury', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // schedule time in time out
            $table->TEXT('time_in_schedule');
            $table->TEXT('time_out_schedule');

            // salary
            $table->decimal('rate', 8, 2)->default(0.00);

            // deductions
            $table->decimal('insurance', 8, 2)->default(0.00);
            $table->decimal('retirement_contribution', 8, 2)->default(0.00);

            $table->timestamps();
        });

        // PROGRAM HEADS
        Schema::create('program_heads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // schedule time in time out
            $table->TEXT('time_in_schedule');
            $table->TEXT('time_out_schedule');

            // salary
            $table->decimal('rate', 8, 2)->default(0.00);

            // deductions
            $table->decimal('insurance', 8, 2)->default(0.00);
            $table->decimal('retirement_contribution', 8, 2)->default(0.00);

            $table->timestamps();
        });

        // REGISTRAR
        Schema::create('registrar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // schedule time in time out
            $table->TEXT('time_in_schedule');
            $table->TEXT('time_out_schedule');

            // salary
            $table->decimal('rate', 8, 2)->default(0.00);

            // deductions
            $table->decimal('insurance', 8, 2)->default(0.00);
            $table->decimal('retirement_contribution', 8, 2)->default(0.00);

            $table->timestamps();
        });

        // HR
        Schema::create('hr', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // schedule time in time out
            $table->TEXT('time_in_schedule');
            $table->TEXT('time_out_schedule');

            // salary
            $table->decimal('rate', 8, 2)->default(0.00);

            // deductions
            $table->decimal('insurance', 8, 2)->default(0.00);
            $table->decimal('retirement_contribution', 8, 2)->default(0.00);

            $table->timestamps();
        });

        // ADMIN
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // schedule time in time out
            $table->TEXT('time_in_schedule');
            $table->TEXT('time_out_schedule');

            // salary
            $table->decimal('rate', 8, 2)->default(0.00);

            // deductions
            $table->decimal('insurance', 8, 2)->default(0.00);
            $table->decimal('retirement_contribution', 8, 2)->default(0.00);

            $table->timestamps();
        });

        // STUDENTS TABLE ***************************************************************************

        // STUDENTS
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // course_year_block
            $table->TEXT('course');
            $table->TEXT('year_level');
            $table->TEXT('block');
            


            $table->timestamps();
        });

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('professors');
        Schema::dropIfExists('treasury');
        Schema::dropIfExists('program_heads');
        Schema::dropIfExists('registrar');
        Schema::dropIfExists('students');
        Schema::dropIfExists('admin');
        Schema::dropIfExists('hr');
    }
};
