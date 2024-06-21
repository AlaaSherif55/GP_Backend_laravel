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
        Schema::table('nurse_appointments', function (Blueprint $table) {
            $table->enum('status', ['cancelled', 'accepted', 'pending'])->default('pending');
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nurse_appointments', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('notes');
        });
    }
};
