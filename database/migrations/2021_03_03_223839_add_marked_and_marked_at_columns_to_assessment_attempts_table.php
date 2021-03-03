<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMarkedAndMarkedAtColumnsToAssessmentAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assessment_attempts', function (Blueprint $table) {
            $table->boolean('marked')->default(0);
            $table->date('marked_on')->nullable();
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
            $table->dropColumn('marked');
            $table->dropColumn('marked_on');
        });
    }
}
