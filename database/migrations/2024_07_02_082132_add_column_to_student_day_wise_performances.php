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
        Schema::table('student_day_wise_performances', function (Blueprint $table) {
            $table->string('performance_activity_type')->default(1)->comment('1 = No of Lines, 2 = Leave, 3 = Holiday')->after('performance_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_day_wise_performances', function (Blueprint $table) {
            $table->dropColumn('performance_activity_type');
        });
    }
};
