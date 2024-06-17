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
        Schema::create('intensive_care_applications', function (Blueprint $table) {
            $table->id();
            $table->foreign('intensive_care_unit_id')->references('id')->on('intensive_care_units')->onDelete('cascade');
            $table->string('patient_name');
            $table->string('patient_phone');
            $table->string('status');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intensive_care_applications');
    }
};
