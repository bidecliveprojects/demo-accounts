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
        Schema::create('employee_education_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->integer('hafiz_status')->default(1)->comment('1 = Yes, 2 = No');
            $table->string('memorization_location_for_hafiz')->nullable();
            $table->string('teacher_name_for_hafiz')->nullable();
            $table->integer('taraweeh_recitation')->default(1)->comment('1 = Yes, 2 = No');
            $table->integer('tajweed_completion')->default(1)->comment('1 = Yes, 2 = No');
            $table->string('schooling_completed')->nullable();
            $table->integer('computer_skills')->default(1)->comment('1 = Yes, 2 = No');
            $table->integer('writing_skills')->default(1)->comment('1 = Yes, 2 = No');
            $table->integer('spiritual_connection')->default(1)->comment('1 = Yes, 2 = No');
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');
            $table->integer('status')->default(1)->comment('1 = Active, 2 = Inactive');
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
        Schema::dropIfExists('employee_education_details');
    }
};
