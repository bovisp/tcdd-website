<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMarkerIdAndCommentsColumnsToAssessmentMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assessment_marks', function (Blueprint $table) {
            $table->integer('marker_id')->nullable();
            $table->longText('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assessment_marks', function (Blueprint $table) {
            $table->dropColumn('marker_id');
            $table->dropColumn('description');
        });
    }
}
