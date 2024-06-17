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
        Schema::create('intensive_care_units', function (Blueprint $table) {
            $table->id();
            $table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('cascade');
            $table->integer('capacity')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intensive_care_units');
    }
};
