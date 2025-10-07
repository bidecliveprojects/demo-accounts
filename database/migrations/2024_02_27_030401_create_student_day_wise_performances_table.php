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
        Schema::create('student_day_wise_performances', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id');
            $table->integer('student_id');
            $table->integer('para_id');
            $table->date('performance_date');
            $table->integer('no_of_lines');
            $table->integer('status')->comment('1 = Active , 2 = Inactive');
            $table->string('created_by');
            $table->date('date');

            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_day_wise_performances');
    }
};
