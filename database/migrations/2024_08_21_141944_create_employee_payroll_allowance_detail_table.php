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
        Schema::create('employee_payroll_allowance_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('epdd_id');
            $table->integer('type')->comment('1 = Normal Allowance, 2 = Additional Allowance');
            $table->integer('at_id');
            $table->decimal('amount',15,3);
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
        Schema::dropIfExists('employee_payroll_allowance_detail');
    }
};
