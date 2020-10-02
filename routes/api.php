<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('auth', 'ApiController@authenticate');
Route::post('register', 'ApiController@register');

Route::group(['middleware' => ['jwt.verify']], function() {
	// Authenticated routes go here!
	Route::get('me', 'ApiController@getAuthenticatedUser');
	Route::get('dashboard', 'ApiController@dashboard');
	Route::get('quiz/start/{id}', 'ApiController@startQuiz');
	Route::post('quiz/attempt/{id}/', 'ApiController@attempt');
	Route::post('settings/changepassword', 'ApiController@changepassword');
});
