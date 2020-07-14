<?php

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

Route::get('/', function () {
    // return User::with('permissions')
    //     ->get()
    //     ->filter(function ($user) {
    //         return $user->permissions->count() === 0 || $user->permissions->where('name', '!=', 'manage assessments')->count();
    //     });
    // $permission = Permission::whereName('test test')->first();

    // return $permission->users;
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
Route::resource('/api/assessments', 'Assessments\Assessments\Api\AssessmentsController');

Route::get('/permissions', 'Permissions\PermissionsController@index');
Route::resource('/api/permissions', 'Permissions\Api\PermissionsController');
Route::put('/api/permissions/{permission}/users', 'Permissions\Api\UserPermissionsController@update');
Route::get('/api/permissions/{permission}/users/create', 'Permissions\Api\UserPermissionsController@create');
Route::post('/api/permissions/{permission}/users', 'Permissions\Api\UserPermissionsController@store');