<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin', 'Admin\AdminController@index');
Route::get('/admin/sections', 'Admin\Sections\SectionsController@index');
Route::resource('/api/admin/sections', 'Admin\Sections\Api\SectionsController');
Route::get('/admin/portal/languages', 'Admin\Portal\Languages\LanguagesController@index');
Route::resource('/api/admin/portal/languages', 'Admin\Portal\Languages\Api\LanguagesController');
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