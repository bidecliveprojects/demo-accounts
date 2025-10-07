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
        Schema::table('fees', function (Blueprint $table) {
            //$table->dropColumn('school_id');
            //$table->dropColumn('student_id');

            // $table->unsignedBigInteger('school_id')->after('id');

            // $table->foreign('school_id')
            //     ->references('id')
            //     ->on('companies')
            //     ->onDelete('cascade');

            // $table->unsignedBigInteger('student_id')->after('department_id');

            // $table->foreign('student_id')
            //     ->references('id')
            //     ->on('students')
            //     ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fees', function (Blueprint $table) {
            //
        });
    }
};
