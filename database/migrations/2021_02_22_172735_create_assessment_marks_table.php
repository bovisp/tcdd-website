<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessmentMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessment_marks', function (Blueprint $table) {
            $table->id();
            $table->integer('assessment_attempt_id')->unsigned();
            $table->float('mark', 8, 2)->unsigned();
            $table->integer('assessment_page_content_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assessment_marks');
    }
}
