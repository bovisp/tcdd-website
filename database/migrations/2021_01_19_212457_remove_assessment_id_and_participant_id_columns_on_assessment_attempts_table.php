<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAssessmentIdAndParticipantIdColumnsOnAssessmentAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assessment_attempts', function (Blueprint $table) {
            $table->dropColumn('assessment_id');
            $table->dropColumn('participant_id');
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
            $table->integer('assessment_id')->unsigned();
            $table->integer('participant_id')->unsigned();
        });
    }
}
