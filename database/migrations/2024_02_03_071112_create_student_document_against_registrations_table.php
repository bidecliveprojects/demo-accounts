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
        Schema::create('student_document_against_registrations', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->string('birth_certificate');
            $table->string('father_guardian_cnic');
            $table->string('passport_size_photo');
            $table->string('copy_of_last_report');
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
        Schema::dropIfExists('student_document_against_registrations');
    }
};
