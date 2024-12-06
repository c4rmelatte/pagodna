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
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('status');
            $table->unsignedInteger('enrollment_date');

            $table->string('lastname');
            $table->string('firstname');
            $table->string('middlename');
            $table->string('yearSection');
            $table->string('gender');


            // college
            $table->string('course');
            $table->string('semester');
            $table->string('subject-code');
            $table->string('subject-title');
            $table->string('schedule');
            $table->string('room');
            $table->string('lec-lab-units');
            $table->string('total-units');
            $table->string('rate-per-unit');
            $table->string('total-subject-fee');

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
        Schema::dropIfExists('enrollments');
    }
};
