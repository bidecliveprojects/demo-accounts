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
        Schema::table('student_current_paras', function (Blueprint $table) {
            $table->integer('para_status')->default(1)->comment('1 = Remaining , 2 = Completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_current_paras', function (Blueprint $table) {
            $table->dropColumn('para_status');
        });
    }
};
