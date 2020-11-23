<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRichTextColumnToEssayQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('essay_questions', function (Blueprint $table) {
            $table->boolean('rich_text')->default(0);
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
            $table->dropColumn('rich_text');
        });
    }
}
