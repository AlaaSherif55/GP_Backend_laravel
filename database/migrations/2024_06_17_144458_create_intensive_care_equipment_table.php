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
        Schema::create('intensive_care_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intensive_care_unit_id')->references('id')->on('intensive_care_units')->onDelete('cascade');
            $table->foreignId('equipment_id')->references('id')->on('equipments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intensive_care_equipment');
    }
};
