<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrawingQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drawing_questions', function (Blueprint $table) {
            $table->id();
            $table->integer('question_id')->unsigned();
            $table->boolean('rich_text')->default(0);
            $table->boolean('text_answer')->default(0);
            $table->text('drawing_options');
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
        Schema::dropIfExists('drawing_questions');
    }
}
