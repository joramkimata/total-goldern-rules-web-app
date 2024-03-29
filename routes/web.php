<?php

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

Route::get('/', 'AppController@index')->name('app.login');
Route::get('/login', 'AppController@login')->name('login');
Route::get('/reset-password', 'AppController@resetPassword')->name('app.reset.password');
Route::post('/reset-password', 'AppController@doResetPassword')->name('app.reset.password.do');
Route::post('/password-reset', 'AppController@passwordReset')->name('app.password.reset');
Route::get('/password-reset', 'AppController@getPasswordReset')->name('app.password.reset.ui');
Route::get('/activated', 'AppController@activated')->name('activated');
Route::post('/login', 'AppController@dologin')->name('app.dologin');
Route::post('/register', 'AppController@doRegister')->name('app.doRegister');
Route::get('/app/redirect', 'AppController@doRegisterRedirect')->name('app.doRegister.redirect');

Route::group(['middleware' => 'auth'], function() {
	Route::get('/dashboard', 'AppController@dashboard')->name('app.dashboard');
	Route::get('/app/logout', 'AppController@logout')->name('app.logout');
	Route::get('/app/changepassword/{id}', 'AppController@changepassword')->name('app.changepassword');
	//Users
	Route::group(['middleware' => 'admin'], function() {
		Route::post('app/users', 'UserController@store')->name('users.store');
		Route::get('/app/users', 'UserController@index')->name('app.users');
		Route::get('/app/reminders', 'ReminderController@index')->name('app.reminders');
		Route::get('/app/reminders/refresh', 'ReminderController@refresh')->name('app.reminders.refresh');
		Route::post('/app/reminders', 'ReminderController@save')->name('app.reminders');
		Route::get('/app/users/refresh', 'UserController@refresh')->name('users.refresh');
		Route::post('/app/users/deactivate', 'UserController@deactivate')->name('users.deactivate');
		Route::post('/users/activate', 'UserController@activate')->name('users.activate');
		Route::get('/app/users/edit/{id}', 'UserController@edit')->name('users.edit');
		Route::post('/app/users/update/{id}', 'UserController@update')->name('users.update');
		Route::post('/app/users/update/{id}/password', 'UserController@updatePassword')->name('users.update.password');
		Route::post('/app/users/activate', 'UserController@activateAll')->name('app.users.activateAll');
		Route::post('/app/user/activate', 'UserController@activateSingle')->name('app.users.activateSingle');
        Route::get('/app/users/activate/refresh', 'UserController@activateAllRefresh')->name('app.users.activateAll.refresh');
        Route::get('/app/reports', 'ReportController@index')->name('app.reports');
        Route::post('/app/reports', 'ReportController@fetchReport')->name('app.reports.fetch');
        Route::post('/app/reports/view-staffs', 'ReportController@viewStaff')->name('app.reports.viewstaffs');
	});
	//Settings
	Route::get('app/settings', 'SettingController@index')->name('app.settings');
	Route::post('app/settings/changepassword', 'SettingController@changepassword')->name('app.settings.changepassword');
	Route::post('app/settings/changeemail', 'SettingController@changeemail')->name('app.settings.changeemail');
	Route::post('app/settings/changeappname', 'SettingController@changeappname')->name('app.settings.changeappname');
	Route::post('app/settings/changeapplogo', 'SettingController@changeapplogo')->name('app.settings.changeapplogo');
	//Quiz
	Route::get('app/quiz', 'QuizController@index')->name('app.quiz')->middleware('admin');
	Route::get('app/quiz/staff', 'QuizController@staff')->name('app.quiz.staff');
	Route::post('app/quiz', 'QuizController@store')->name('quiz.store')->middleware('admin');
	Route::post('quiz/publish/{id}', 'QuizController@publish')->name('quiz.publish');
	Route::post('quiz/unpublish/{id}', 'QuizController@unpublish')->name('quiz.unpublish');
	Route::get('quiz/results/seenX/{id}/{uxid}', 'QuizController@seenxResults')->name('quiz.results.seenx');
	Route::get('quiz/results/seen/{id}', 'QuizController@seenResults')->name('quiz.results.seen');
	Route::post('quiz/publish/results/{id}', 'QuizController@publishResults')->name('quiz.publish.results');
	Route::post('quiz/unpublish/results/{id}', 'QuizController@unpublishResults')->name('quiz.unpublish.results');
	Route::get('quiz/report/{id}', 'QuizController@report')->name('quiz.report');
	Route::post('quiz/destroy/{id}', 'QuizController@destroy')->name('quiz.destroy');
	Route::post('quiz/{id}/destroy', 'QuizController@destroyQuiz')->name('quiz.delete');
	Route::get('quiz/edit/{id}', 'QuizController@edit')->name('quiz.edit');
	Route::get('quiz/start/{id}', 'QuizController@startQuiz')->name('quiz.start');
	Route::get('quiz/refresh', 'QuizController@refresh')->name('quiz.refresh');
	Route::get('quiz/deleted', 'QuizController@deleted')->name('quiz.deleted');
	Route::post('quiz/update/{id}', 'QuizController@update')->name('quiz.update');
	Route::post('quiz/cancel/{id}', 'QuizController@cancel')->name('quiz.cancel');
	Route::post('quiz/attempt', 'QuizController@attempt')->name('quiz.attempt');
	Route::get('quiz/attempt/refresh', 'QuizController@attemptRefresh')->name('quiz.attempt.refresh');

	//Question
	Route::group(['middleware' => 'admin'], function() {
		Route::get('quiz/add/question/{id}', 'QuestionController@add')->name('quiz.add.questions');
		Route::get('quiz/preview/question/{id}', 'QuizController@preview')->name('quiz.preview.questions');
		Route::post('quiz/store/question/{id}', 'QuestionController@store')->name('quiz.store.questions');
		Route::post('quiz/storeAndContinue/question/{id}', 'QuestionController@storeAndContinue')->name('quiz.storeAndContinue.questions');
		Route::post('quiz/question/edit/{id}', 'QuestionController@edit')->name('question.edit');
		Route::post('quiz/question/answer/delete/{id}', 'QuestionController@deleteAnswer')->name('question.answer.delete');

        Route::post('quiz/question/update/{id}', 'QuestionController@updateQn')->name('question.update');
        Route::post('quiz/checkreminders', 'QuizController@checkreminders')->name('quiz.checkreminders');
	});

	Route::group(['middleware' => 'admin'], function() {
		Route::get('app/departments', 'DepartmentController@index')->name('app.departments');
	});

});
