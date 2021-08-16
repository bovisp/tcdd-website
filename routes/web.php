<?php

use App\Question;
use App\Assessment;
use App\QuestionType;
use App\AssessmentPageContentType;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/uploads', 'FileUpload\Api\FileUploadController@store');
Route::delete('/uploads', 'FileUpload\Api\FileUploadController@destroy');
Route::post('/uploads/drawing', 'FileUpload\Api\FileUploadController@storeDrawing');

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
Route::patch('/api/users/{user}/password', 'Users\Api\PasswordChangeController@update');
Route::get('/api/users/{user}/attempt/{attempt}/review', 'Users\Api\AttemptReviewController@show');

Route::get('/api/roles', 'Roles\Api\RolesController@index');
Route::get('/api/supervisors/{role}', 'Supervisors\Api\SupervisorsController@index');
Route::post('/api/users/{user}/role/{role}', 'Supervisors\Api\SupervisorsController@store');

Route::get('/assessments/assessment-types', 'Assessments\AssessmentTypes\AssessmentTypesController@index');
Route::resource('/api/assessments/assessment-types', 'Assessments\AssessmentTypes\Api\AssessmentTypesController');
Route::get('/assessments', 'Assessments\Assessments\AssessmentsController@index');
Route::get('/assessments/{assessment}', 'Assessments\Assessments\AssessmentsController@show');
Route::resource('/api/assessments', 'Assessments\Assessments\Api\AssessmentsController');
Route::delete('/api/assessments/{assessment}/instructors', 'Assessments\Assessments\Api\AssessmentInstructorsController@destroy');
Route::get('/api/assessments/{assessment}/instructors/create', 'Assessments\Assessments\Api\AssessmentInstructorsController@create');
Route::post('/api/assessments/{assessment}/instructors', 'Assessments\Assessments\Api\AssessmentInstructorsController@store');
Route::delete('/api/assessments/{assessment}/participants', 'Assessments\Assessments\Api\AssessmentParticipantsController@destroy');
Route::get('/api/assessments/{assessment}/participants/create', 'Assessments\Assessments\Api\AssessmentParticipantsController@create');
Route::post('/api/assessments/{assessment}/participants', 'Assessments\Assessments\Api\AssessmentParticipantsController@store');
Route::patch('/api/assessments/{assessment}/participants/activate/{user}', 'Assessments\Assessments\Api\AssessmentParticipantsActivationController@update');

Route::post('/api/assessments/{assessment}/page', 'Assessments\Assessments\Api\AssessmentQuestionPagesController@store');
Route::delete('/api/assessments/{assessment}/page/{page}', 'Assessments\Assessments\Api\AssessmentQuestionPagesController@destroy');
Route::patch('/api/assessments/{assessment}/page', 'Assessments\Assessments\Api\AssessmentQuestionPagesController@update');
Route::get('/api/assessments/{assessment}/pages', 'Assessments\Assessments\Api\AssessmentQuestionPagesController@index');
Route::get('/api/assessments/{assessment}/questions', 'Assessments\Assessments\Api\AssessmentQuestionsController@index');
Route::patch('/api/assessments/{assessment}/lock', 'Assessments\Assessments\Api\AssessmentLockController@update');
Route::post('/api/assessments/{assessment}/duplicate', 'Assessments\Assessments\Api\DuplicateAssessmentController@store');
Route::post('/api/assessments/{assessment}/page/{page}/add-question', 'Assessments\Assessments\Api\AssessmentQuestionPageContentController@addQuestion');
Route::post('/api/assessments/{assessment}/page/{page}/add-content', 'Assessments\Assessments\Api\AssessmentQuestionPageContentController@addContent');
Route::delete('/api/assessments/{assessment}/page/content/{content}', 'Assessments\Assessments\Api\AssessmentQuestionPageContentController@destroy');
Route::delete('/api/assessments/{assessment}/page/{page}/content', 'Assessments\Assessments\Api\AssessmentQuestionPageContentController@destroyTempItem');
Route::patch('/api/assessment/{assessment}/page/{page}/change-order', 'Assessments\Assessments\Api\AssessmentQuestionPageContentController@reorder');
Route::patch('/api/assessments/{assessment}/questions/{item}/change-score', 'Assessments\Assessments\Api\AssessmentQuestionContentController@changeScore');
Route::patch('/api/assessments/{assessment}/questions/{item}/change-page', 'Assessments\Assessments\Api\AssessmentQuestionContentController@changePage');
Route::get('/api/assessments/{assessment}/attempts/{attemptId}', 'Assessments\Assessments\Api\AssessmentAttemptsController@show');
Route::get('/api/assessments/{assessment}/attempts', 'Assessments\Assessments\Api\AssessmentAttemptsController@index');
Route::get('/api/assessments/{assessment}/answers', 'Assessments\Assessments\Api\AssessmentAnswersController@index');
Route::get('/api/assessments/{assessment}/attempt/{attempt}/answer', 'Assessments\Assessments\Api\AssessmentAnswersController@show');
Route::post('/api/assessments/{assessment}/attempt/{attempt}/mark', 'Assessments\Assessments\Api\AssessmentMarksController@store');
Route::patch('/api/assessments/{assessment}/attempt/{attempt}/mark/{mark}', 'Assessments\Assessments\Api\AssessmentMarksController@update');
Route::patch('/api/assessments/{assessment}/attempt/{attempt}/mark/{mark}/update-score', 'Assessments\Assessments\Api\AssessmentMarksController@updateScore');
Route::patch('/api/assessments/{assessment}/review/all', 'Assessments\Assessments\Api\AssessmentReviewAllController@update');
Route::patch('/api/assessments/{assessment}/attempts/{attempt}/review', 'Assessments\Assessments\Api\AssessmentReviewController@update');
Route::patch('/api/assessments/{assessment}/update-completion','Assessments\Assessments\Api\AssessmentMarkingCompletionController@update' );

Route::get('/assessment/{assessment}', 'Assessments\Assessment\AssessmentAttemptController@index');
Route::post('/api/assessment/{assessment}/attempt', 'Assessments\Assessment\Api\AssessmentAttemptController@store');
Route::get('/assessment/{assessment}/attempt/{attempt}', 'Assessments\Assessment\AssessmentAttemptController@show');
Route::get('/api/assessment/{assessment}/attempt/{attempt}', 'Assessments\Assessment\Api\AssessmentAttemptController@show');
Route::patch('/api/assessment/{assessment}/attempt/{attempt}', 'Assessments\Assessment\Api\AssessmentAttemptController@update');

Route::get('/api/assessment/{assessment}/attempt/{attempt}/time', 'Assessments\Assessment\Api\AttemptTimeController@index');
Route::get('/api/assessment/{assessment}/attempt/{attempt}/question/{question}', 'Assessments\Assessment\Api\AttemptQuestionController@show');
Route::patch('/api/assessment/{assessment}/attempt/{attempt}/answers', 'Assessments\Assessment\Api\AttemptAnswersController@update');
Route::get('/api/assessment/{assessment}/attempt/{attempt}/review', 'Assessments\Assessment\Api\AttemptReviewController@index');
Route::patch('/api/assessment/{assessment}/attempt/{attempt}/submit', 'Assessments\Assessment\Api\AttemptSubmitController@update');
Route::patch('/api/assessment/{assessment}/attempt/{attempt}/all-answers', 'Assessments\Assessment\Api\AttemptAnswersController@allAnswers');

Route::get('/permissions', 'Permissions\PermissionsController@index');
Route::resource('/api/permissions', 'Permissions\Api\PermissionsController');
Route::put('/api/permissions/{permission}/users', 'Permissions\Api\UserPermissionsController@update');
Route::get('/api/permissions/{permission}/users/create', 'Permissions\Api\UserPermissionsController@create');
Route::post('/api/permissions/{permission}/users', 'Permissions\Api\UserPermissionsController@store');

Route::resource('/api/question-types', 'Questions\Types\Api\QuestionTypesController');
Route::get('/questions/categories', 'Questions\Categories\QuestionCategoriesController@index');
Route::post('/api/questions/id', 'Questions\Questions\Api\QuestionsController@id');
Route::post('/api/questions/{question}/duplicate', 'Questions\Questions\Api\DuplicateQuestionController@store');
Route::resource('/api/questions/categories', 'Questions\Categories\Api\QuestionCategoriesController');
Route::get('/questions', 'Questions\Questions\QuestionsController@index');
Route::resource('/api/questions', 'Questions\Questions\Api\QuestionsController');
Route::get('/api/questions/{question}/editors', 'Questions\Questions\Api\QuestionEditorsController@index');
Route::get('/questions/types', 'Questions\Types\QuestionTypesController@index');
Route::get('api/questions/{question}/data', 'Questions\Questions\Api\QuestionsController@questionTypeData');

Route::get('/admin/tags', 'Admin\Tags\TagsController@index');
Route::resource('/api/admin/tags', 'Admin\Tags\Api\TagsController');

Route::post('/api/content-builder/{contentBuilder}/content', 'ContentBuilder\Api\ContentPartController@store');
Route::post('/api/content-builder/{contentBuilder}/animation', 'ContentBuilder\Api\AnimationPartController@store');
Route::post('/api/content-builder/{contentBuilder}/media', 'ContentBuilder\Api\MediaPartController@store');
Route::post('/api/content-builder/{contentBuilder}/tab', 'ContentBuilder\Api\TabPartController@store');
Route::get('/api/content-builder/{contentBuilder}', 'ContentBuilder\Api\ContentBuilderController@index');\
Route::patch('api/content-builder/{contentBuilder}/change-order', 'ContentBuilder\Api\ContentBuilderController@reorder');

Route::get('/api/parts/types', 'ContentBuilderTypes\Api\ContentBuilderTypesController@index');
Route::patch('/api/parts/{part}/content', 'ContentBuilder\Api\ContentPartController@update');
Route::patch('/api/parts/{part}/animation', 'ContentBuilder\Api\AnimationPartController@update');
Route::patch('/api/parts/{part}/media', 'ContentBuilder\Api\MediaPartController@update');
Route::patch('/api/parts/{part}/tab', 'ContentBuilder\Api\TabPartController@update');
Route::delete('/api/parts/tab-section-parts', 'ContentBuilder\Api\PartsController@destroyTabSectionPart');
Route::delete('/api/parts/{part}', 'ContentBuilder\Api\PartsController@destroy');
Route::get('/api/parts/{part}', 'ContentBuilder\Api\PartsController@show');

Route::get('/issues', 'Issues\IssuesController@index');
Route::resource('/api/issues', 'Issues\Api\IssuesController');