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
        Schema::create('student_parent_and_guardian_informations', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->integer('city_id');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('father_qualification');
            $table->string('mother_qualification');
            $table->string('cnic_no');
            $table->string('mobile_no');
            $table->string('parent_email');
            $table->string('father_occupation');
            $table->string('mother_tongue');
            $table->string('home_address');
            $table->string('specify_any_health_problem_medication');
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
        Schema::dropIfExists('student_parent_and_guardian_informations');
    }
};
