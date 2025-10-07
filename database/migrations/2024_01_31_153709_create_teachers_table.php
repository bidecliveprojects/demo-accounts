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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id');
            $table->integer('section_id');
            $table->integer('department_id');
            $table->integer('gender_id');
            $table->string('teacher_image');
            $table->string('teacher_name');
            $table->string('teacher_mobile_no');
            $table->string('teacher_address');
            $table->string('teacher_cnic');
            $table->integer('status')->default(1)->comment('1 = Active , 2 = Inactive');
            $table->string('created_by');
            $table->date('created_date');
            
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
