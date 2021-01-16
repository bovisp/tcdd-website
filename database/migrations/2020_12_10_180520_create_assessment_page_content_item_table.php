<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessmentPageContentItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessment_page_content_items', function (Blueprint $table) {
            $table->id();
            $table->integer('model_id')->unsigned();
            $table->string('type');
            $table->integer('assessment_page_content_id')->unsigned();
            $table->integer('question_number')->unsigned()->nullable();
            $table->integer('question_score')->unsigned()->nullable();
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
        Schema::dropIfExists('assessment_page_content_item');
    }
}
