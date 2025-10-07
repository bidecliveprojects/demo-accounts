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
        Schema::create('employee_payroll_data_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('epd_id');
            $table->integer('emp_id');
            $table->decimal('basic_salary',15,3);
            $table->decimal('total_allowance',15,3);
            $table->decimal('total_additional_allowance',15,3);
            $table->decimal('gross_salary',15,3);
            $table->decimal('total_deduction',15,3);
            $table->decimal('net_salary',15,3);
            $table->integer('status')->default(1)->comment('1 = Active, 2 = Inactive');
            $table->string('created_by');
            $table->date('created_date');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_payroll_data_detail');
    }
};
