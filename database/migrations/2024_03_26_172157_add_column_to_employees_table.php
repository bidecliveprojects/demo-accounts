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
        Schema::table('employees', function (Blueprint $table) {
            $table->integer('emp_type')->default(1)->comment('1 = Normal , 2 = Teacher, 3 = Nazim, 4 = Naib Nazim, 5 = Mohavin')->after('emp_no');
            $table->string('emp_image')->after('school_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('emp_type');
            $table->dropColumn('emp_image');
        });
    }
};
