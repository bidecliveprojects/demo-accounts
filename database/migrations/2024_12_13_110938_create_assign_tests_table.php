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
        Schema::create('assign_tests', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('section_id');
            $table->integer('type')->default(1)->comment('1 = All Students, 2 = Specific Students');
            $table->json('student_ids_array')->nullable();
            $table->string('title');
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('no_of_marks');
            $table->integer('status')->default(1)->comment('1 = Active, 2 = Inactive');
            $table->string('created_by');
            $table->date('created_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assign_tests');
    }
};
