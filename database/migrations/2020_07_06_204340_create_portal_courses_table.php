<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortalCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portal_courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('moodle_course_id')->unsigned();
            $table->integer('portal_category_id')->unsigned();
            $table->integer('portal_language_id')->unsigned();
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
        Schema::dropIfExists('portal_courses');
    }
}
