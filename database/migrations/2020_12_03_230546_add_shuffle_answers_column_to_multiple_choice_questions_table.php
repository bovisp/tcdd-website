<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShuffleAnswersColumnToMultipleChoiceQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('multiple_choice_questions', function (Blueprint $table) {
            $table->boolean('shuffle_answers')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('multiple_choice_questions', function (Blueprint $table) {
            $table->dropColumn('shuffle_answers');
        });
    }
}
