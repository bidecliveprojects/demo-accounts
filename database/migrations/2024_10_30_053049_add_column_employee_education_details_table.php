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
        Schema::table('employee_education_details', function (Blueprint $table) {
            $table->integer('skills_writing')->default(2)->after('writing_skills')->comment('1 = Good, 2 = Not');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_education_details', function (Blueprint $table) {
            $table->dropColumn('school_id');
        });
    }
};
