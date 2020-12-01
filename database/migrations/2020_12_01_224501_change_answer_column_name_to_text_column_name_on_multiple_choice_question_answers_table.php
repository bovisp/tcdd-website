<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAnswerColumnNameToTextColumnNameOnMultipleChoiceQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('multiple_choice_question_answers', function (Blueprint $table) {
            $table->renameColumn('answer', 'text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('multiple_choice_question_answers', function (Blueprint $table) {
            //
        });
    }
}
