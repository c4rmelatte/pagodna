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
    public function up()  // will modify by ORTEGA
    {
        Schema::create('payroll', function (Blueprint $table) {
            $table->id(); // unnecessary
            $table->text('department');
            $table->text('position');
            $table->text('pay_period');
            $table->text('pay_date');
            $table->decimal('base_salary', 8, 2)->default(0.00);
            //$table->text('additional_hours');
            $table->decimal('bonus', 8, 2)->default(0.00);
            //$table->decimal('tax', 8, 2)->default(0.00);
            $table->decimal('insurance', 8, 2)->default(0.00);
            $table->decimal('retirement_contribution', 8, 2)->default(0.00);
            $table->text('account_number');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // additions from us ************************************

            // $table->text('pay_period');
            // $table->text('pay_date');
            // $table->text('salary', 8, 2)->default(0.00);
            // $table->decimal('bonus', 8, 2)->default(0.00);

            // *******************************************************

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
        Schema::dropIfExists('payroll');
    }
};
