<?php

use App\Classes\PortalViews;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin', 'Admin\AdminController@index');
Route::get('/admin/sections', 'Admin\Sections\SectionsController@index');
Route::resource('/api/admin/sections', 'Admin\Sections\Api\SectionsController');
Route::get('/admin/portal/languages', 'Admin\Portal\Languages\LanguagesController@index');
Route::resource('/api/admin/portal/languages', 'Admin\Portal\Languages\Api\LanguagesController');
Route::get('/admin/portal/categories', 'Admin\Portal\Categories\CategoriesController@index');
Route::resource('/api/admin/portal/categories', 'Admin\Portal\Categories\Api\CategoriesController');
Route::get('/admin/portal/courses', 'Admin\Portal\Courses\CoursesController@index');
Route::resource('/api/admin/portal/courses', 'Admin\Portal\Courses\Api\CoursesController');
Route::get('/admin/reports', 'Admin\Reports\ReportController@index');
Route::post('/api/admin/reports', 'Admin\Reports\Api\ReportController@generate');
Route::post('/api/admin/report/get-fiscal', 'Admin\Reports\Api\FiscalYearController@index');
// Route::post('/api/admin/reports', 'Admin\Reports\Api\ReportController@generate');

Route::get('/users', 'Users\UsersController@index');
Route::get('/users/{user}', 'Users\UsersController@show');
Route::get('/api/users/me', 'Users\Api\MeController@me');
Route::get('/api/users/{user}', 'Users\Api\UsersController@show');
Route::get('/api/users', 'Users\Api\UsersController@index');
Route::get('/api/users/moodle/create', 'MoodleUsers\Api\MoodleUsersController@create');
Route::post('/api/users/moodle', 'MoodleUsers\Api\MoodleUsersController@store');

Route::get('/api/roles', 'Roles\Api\RolesController@index');
Route::get('/api/supervisors/{role}', 'Supervisors\Api\SupervisorsController@index');
Route::post('/api/users/{user}/role/{role}', 'Supervisors\Api\SupervisorsController@store');

Route::get('/assessments/assessment-types', 'Assessments\AssessmentTypes\AssessmentTypesController@index');
Route::resource('/api/assessments/assessment-types', 'Assessments\AssessmentTypes\Api\AssessmentTypesController');
Route::get('/assessments', 'Assessments\Assessments\AssessmentsController@index');
Route::get('/assessments/{assessment}', 'Assessments\Assessments\AssessmentsController@show');
Route::resource('/api/assessments', 'Assessments\Assessments\Api\AssessmentsController');
Route::put('/api/assessments/{assessment}/instructors', 'Assessments\Assessments\Api\AssessmentInstructorsController@update');
Route::get('/api/assessments/{assessment}/instructors/create', 'Assessments\Assessments\Api\AssessmentInstructorsController@create');
Route::post('/api/assessments/{assessment}/instructors', 'Assessments\Assessments\Api\AssessmentInstructorsController@store');
Route::put('/api/assessments/{assessment}/participants', 'Assessments\Assessments\Api\AssessmentParticipantsController@update');
Route::get('/api/assessments/{assessment}/participants/create', 'Assessments\Assessments\Api\AssessmentParticipantsController@create');
Route::post('/api/assessments/{assessment}/participants', 'Assessments\Assessments\Api\AssessmentParticipantsController@store');

Route::get('/permissions', 'Permissions\PermissionsController@index');
Route::resource('/api/permissions', 'Permissions\Api\PermissionsController');
Route::put('/api/permissions/{permission}/users', 'Permissions\Api\UserPermissionsController@update');
Route::get('/api/permissions/{permission}/users/create', 'Permissions\Api\UserPermissionsController@create');
Route::post('/api/permissions/{permission}/users', 'Permissions\Api\UserPermissionsController@store');

Route::get('/questions/categories', 'Questions\Categories\QuestionCategoriesController@index');
Route::resource('/api/questions/categories', 'Questions\Categories\Api\QuestionCategoriesController');
Route::get('/questions', 'Questions\Questions\QuestionsController@index');
Route::resource('/api/questions', 'Questions\Questions\Api\QuestionsController');
Route::get('/api/questions/{question}/editors', 'Questions\Questions\Api\QuestionEditorsController@index');
Route::get('/questions/types', 'Questions\Types\QuestionTypesController@index');
Route::resource('/api/questions/types', 'Questions\Types\Api\QuestionTypesController');

Route::get('/admin/tags', 'Admin\Tags\TagsController@index');
Route::resource('/api/admin/tags', 'Admin\Tags\Api\TagsController');