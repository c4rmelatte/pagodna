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
        Schema::create('payments', function (Blueprint $table) {

        ///outline ni sir
            // $table->id();
            // $table->string('purpose');
            // $table->unsignedInteger('amount');
            // $table->string('receipt'); //idk what form of receipt is this

            // $table->unsignedBigInteger('user_id');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->timestamps();

        //gawa namin
            $table->id(); //payment id
            $table->string("name")->nullable(); // name nung bumili
            $table->decimal("price", 10, 2);  // presyo
            $table->decimal("amount", 10, 2); //amount ng binayad 
            $table->string("purpose");   //purpose - kung ano binili/binayaran (misc./tuition) tas may seperate migration pa to sana 
            $table->decimal("change", 10, 2); //sukli
            // $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade"); // id nung user sana nakacomment out lang kasi ala pang login
            $table->string("type"); //type kung miscellaneous or tuition
            $table->boolean("isPaid"); //status kung paid
            $table->timestamps(); 

            //need pa namin mag add ng 2 tables (purpose tas totalfunds)
            //yung totalfunds don mapupunta lahat ng binabayad
            // idea namin sa totalfunds as soon as magopen yung system magccreate na ng data don
            //++ sa totalfunds namin sana kukuha ng pansweldo yung payroll
            // - jib

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
