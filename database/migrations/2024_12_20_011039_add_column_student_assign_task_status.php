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
        //
        Schema::table('student_assign_test_status', function (Blueprint $table) {
            $table->integer('assign_test_status')->default(1)->after('student_id')->comment('1 = Pending, 2 = Pending');
            $table->decimal('no_of_marks_recieved')->default(0)->after('assign_test_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
