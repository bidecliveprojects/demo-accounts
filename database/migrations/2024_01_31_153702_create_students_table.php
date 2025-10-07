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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id');
            $table->integer('department_id');
            $table->integer('teacher_id');
            $table->string('registration_no');
            $table->date('date_of_admission');
            $table->string('student_name');
            $table->date('date_of_birth');
            $table->string('previous_school');
            $table->string('grade_class_applied_for');
            $table->string('reference');
            $table->string('class_timing');
            $table->integer('fees');
            $table->string('concession_fees');
            $table->string('consession_fees_image');
            $table->integer('status')->default(1)->comment('1 = Active , 2 = Inactive');
            $table->string('created_by');
            $table->date('created_date');

            //$table->integer('teacher_id');
            //$table->integer('department_id');
            //$table->integer('city_id');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
