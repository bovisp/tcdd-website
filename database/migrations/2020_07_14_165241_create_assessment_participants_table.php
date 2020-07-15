<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessmentParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessment_participants', function (Blueprint $table) {
            $table->id();
            $table->integer('assessment_id')->unsigned();
            $table->integer('participant_id')->unsigned();

            $table->foreign('assessment_id')
                ->references('id')
                ->on('assessments');

            $table->foreign('participant_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assessment_participants');
    }
}
