<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('image')->nullable()->change();
            $table->string('university')->nullable()->change();
            $table->string('qualifications')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('Address')->nullable()->change();
            $table->string('clinic_fees')->nullable()->change();
            $table->string('specialization')->nullable()->change();
            $table->string('clinic_work_start')->nullable()->change();
            $table->string('clinic_work_end')->nullable()->change();
            $table->string('home_work_start')->nullable()->change();
            $table->string('home_work_end')->nullable()->change();
            $table->string('work_days')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('image')->change();
            $table->string('university')->change();
            $table->string('qualifications')->change();
            $table->string('city')->change();
            $table->string('Address')->change();
            $table->string('clinic_fees')->change();
            $table->string('specialization')->change();
            $table->string('clinic_work_start')->change();
            $table->string('clinic_work_end')->change();
            $table->string('home_work_start')->change();
            $table->string('home_work_end')->change();
            $table->string('work_days')->change();
        });
    }
};
