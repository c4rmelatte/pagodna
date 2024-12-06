<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->longText("description");
            $table->json("target");
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
