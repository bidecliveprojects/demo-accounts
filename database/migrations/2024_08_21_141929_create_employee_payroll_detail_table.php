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
        Schema::create('employee_payroll_detail', function (Blueprint $table) {
            $table->id();
            $table->string('month_year', 7);
            $table->integer('employee_type_id')->comment('1 = Normal , 2 = Teacher, 3 = Nazim, 4 = Naib Nazim, 5 = Mohavin');
            $table->integer('job_type_id')->comment('1 = Full Time, 2 = Part Time');
            $table->integer('employment_status_id')->comment('1 = Permanent, 2 = Contract Base');
            $table->integer('status')->default(1)->comment('1 = Active, 2 = Inactive');
            $table->string('created_by');
            $table->date('created_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_payroll_detail');
    }
};
