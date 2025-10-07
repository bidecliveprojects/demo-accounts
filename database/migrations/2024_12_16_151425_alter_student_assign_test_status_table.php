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
        Schema::table('student_assign_test_status', function (Blueprint $table) {
            $table->integer('assign_test_id')->after('id');
            $table->integer('assign_test_status')->default(1)->after('student_id');
            $table->date('submission_date')->nullable()->after('assign_test_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_assign_test_status', function (Blueprint $table) {
            //
        });
    }
};
