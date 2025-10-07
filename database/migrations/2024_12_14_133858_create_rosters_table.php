<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rosters', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->integer('school_id');
            $table->integer('school_campus_id');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade'); // Foreign key referencing sections table
            $table->string('day_of_week'); // Day of the week (e.g., Monday, Tuesday)
            $table->integer('total_periods'); // Total periods for the day
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
        Schema::dropIfExists('rosters');
    }
}
