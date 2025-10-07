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
        Schema::create('generate_fee_voucher_datas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('generate_fee_voucher_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('student_id');

            $table->decimal('amount',15,3);

            $table->foreign('generate_fee_voucher_id')
                ->references('id')
                ->on('generate_fee_vouchers')
                ->onDelete('cascade');

            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('cascade');
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');
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
        Schema::dropIfExists('generate_fee_voucher_datas');
    }
};
