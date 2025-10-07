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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id');
            $table->string('emp_no');
            $table->string('emp_name');
            $table->string('emp_father_name');
            $table->date('date_of_birth');
            $table->string('cnic_no');
            $table->string('address');
            $table->string('emp_email');
            $table->string('phone_no');
            $table->integer('maritarial_status')->comment('1 = Maried , 2 = Unmaried');
            $table->integer('no_of_childern')->default(0);
            $table->string('relative_name');
            $table->string('relative_contact_no');
            $table->string('relative_address');
            $table->integer('status')->default(1)->comment('1 = Active , 2 = Inactive');
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
        Schema::dropIfExists('employees');
    }
};
