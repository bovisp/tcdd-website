<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublishedPublishDateAndShowColumnsToAssessmentAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assessment_attempts', function (Blueprint $table) {
            $table->boolean('published')->default(0);
            $table->boolean('show')->default(0);
            $table->date('publish_date')->nullable();
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
            $table->dropColumn('published');
            $table->dropColumn('show');
            $table->dropColumn('publish_date');
        });
    }
}
