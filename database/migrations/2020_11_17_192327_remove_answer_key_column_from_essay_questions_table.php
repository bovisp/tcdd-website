<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAnswerKeyColumnFromEssayQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('essay_questions', function (Blueprint $table) {
            $table->dropColumn('answer_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('essay_questions', function (Blueprint $table) {
            $table->longText('answer_key')->nullable();
        });
    }
}
