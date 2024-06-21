<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('nurses', function (Blueprint $table) {
            $table->string('image')->nullable()->change();
            $table->string('university')->nullable()->change();
            $table->string('qualifications')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->integer('fees')->nullable()->change();
            $table->string('work_start')->nullable()->change();
            $table->string('work_end')->nullable()->change();
            $table->string('work_days')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nurses', function (Blueprint $table) {
            $table->dropColumn([
                'image',
                'university',
                'qualifications',
                'city',
                'fees',
                'work_start',
                'work_end',
                'work_days',
            ]);
        });
    }
};
