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
        Schema::create('nurse_ratings', function (Blueprint $table) {
            $table->id();           
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('nurse_id')->constrained('nurses')->onDelete('cascade');
            $table->integer('rating')->unsigned()->check('rating >= 1 and rating <= 5');
            $table->text('review')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nurse_ratings');
    }
};
