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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('university');
            $table->string('qualifications');
            $table->string('city');
            $table->string('Address');
            $table->string('price');
            $table->boolean('online');
            $table->string('specialization');
            $table->boolean('visit')->default(0);
            $table->string('work_start');
            $table->string('work_end');
            $table->string('work_days');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
