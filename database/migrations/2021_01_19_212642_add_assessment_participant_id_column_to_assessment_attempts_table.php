<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssessmentParticipantIdColumnToAssessmentAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assessment_attempts', function (Blueprint $table) {
            $table->integer('assessment_participant_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assessment_attempts', function (Blueprint $table) {
            $table->dropColumn('assessment_participant_id');
        });
    }
}
