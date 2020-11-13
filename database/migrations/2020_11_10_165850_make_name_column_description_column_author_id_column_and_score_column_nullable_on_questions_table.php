<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNameColumnDescriptionColumnAuthorIdColumnAndScoreColumnNullableOnQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->longText('description')->nullable()->change();
            $table->integer('author_id')->unsigned()->nullable()->change();
            $table->integer('section_id')->unsigned()->nullable()->change();
            $table->integer('question_category_id')->unsigned()->nullable()->change();
            $table->integer('score')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            //
        });
    }
}
