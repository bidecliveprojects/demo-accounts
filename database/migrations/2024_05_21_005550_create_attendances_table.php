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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id');
            $table->integer('type')->default(1)->comment('1 = Employee, 2 = Student');
            $table->integer('emp_id');
            $table->integer('student_id');
            $table->date('date');
            $table->string('clock_in');
            $table->string('clock_out');
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
        Schema::dropIfExists('attendances');
    }
};
