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
        Schema::table('student_document_against_registrations', function (Blueprint $table) {
            $table->string('father_guardian_cnic_back')->nullable()->after('father_guardian_cnic');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_document_against_registrations', function (Blueprint $table) {
            //
        });
    }
};
