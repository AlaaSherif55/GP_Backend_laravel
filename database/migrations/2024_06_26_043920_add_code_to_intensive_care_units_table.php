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
        Schema::table('intensive_care_units', function (Blueprint $table) {
            Schema::table('intensive_care_units', function (Blueprint $table) {
                $table->string('code')->nullable()->after('id'); // Add the new column
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intensive_care_units', function (Blueprint $table) {
            Schema::table('intensive_care_units', function (Blueprint $table) {
                $table->dropColumn('code'); // Rollback the column addition
            });
        });
    }
};
