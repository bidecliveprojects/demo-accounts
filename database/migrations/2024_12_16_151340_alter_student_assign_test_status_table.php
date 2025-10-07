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
            $table->dropColumn(['assing_test_id','submission_date']);
            $table->dropColumn(['assign_task_status']);
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
