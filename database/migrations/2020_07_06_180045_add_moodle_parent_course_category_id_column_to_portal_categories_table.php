<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoodleParentCourseCategoryIdColumnToPortalCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('portal_categories', function (Blueprint $table) {
            $table->integer('moodle_parent_course_category_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('portal_categories', function (Blueprint $table) {
            $table->dropColumn('moodle_parent_course_category_id');
        });
    }
}
