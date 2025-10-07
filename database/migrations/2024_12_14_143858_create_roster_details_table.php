<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRosterDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roster_details', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->foreignId('roster_id')->constrained('rosters')->onDelete('cascade'); // Foreign key referencing rosters table
            $table->foreignId('time_slot_id')->constrained('time_slots')->onDelete('cascade'); // Foreign key referencing subject_teacher_assignments table
            $table->foreignId('subject_teacher_assignment_id')->constrained('subject_teacher_assignments')->onDelete('cascade'); // Foreign key referencing subject_teacher_assignments table
            $table->integer('status')->default(1)->comment('1 = Active , 2 = Inactive');
            $table->string('created_by');
            $table->date('created_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roster_details');
    }
}
