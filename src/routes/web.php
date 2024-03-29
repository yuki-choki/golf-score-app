<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', 'HomeController@index');

Auth::routes(['verify' => true]);

Route::get('/users', 'UsersController@index')->name('users');
Route::get('/users/edit/', 'UsersController@edit')->name('users.edit');
Route::get('/users/update/', 'UsersController@update')->name('users.update');
Route::post('/users/update/', 'UsersController@update')->name('users.update');
Route::post('/users/friend/store', 'UsersController@friendStore')->name('users.friend.store');
Route::get('/users/friend/', 'UsersController@friend')->name('users.friend');
Route::post('/users/friend/update/{user}', 'UsersController@friendUpdate')->name('users.friend.update');
Route::post('/users/friend/delete/{user}', 'UsersController@friendDelete')->name('users.friend.delete');
Route::get('/users/getUser/', 'UsersController@getUser')->name('users.getUser');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/password/change', 'Auth\ChangePasswordController@showChangePasswordForm')->name('password.form');
Route::post('/password/change', 'Auth\ChangePasswordController@ChangePassword')->name('password.change');
Route::get('/scores/search', 'ScoreController@search')->name('scores.search');
Route::post('/scores/search', 'ScoreController@search')->name('scores.search.post');
Route::get('/scores/create/{game}/{half}', 'ScoreController@create')->name('scores.create');
Route::get('/scores/getS3Text', 'ScoreController@getS3Text');
Route::get('/scores/analysis', 'ScoreController@analysis')->name('scores.analysis');
Route::post('/scores/analysis', 'ScoreController@analysis')->name('scores.analysis.post');
Route::post('/scores/saveData', 'ScoreController@saveData')->name('scores.saveData');
Route::post('/scores/upload', 'ScoreController@upload')->name('scores.upload');
Route::get('/scores/addRound/{course}', 'ScoreController@addRound')->name('scores.addRound');
Route::post('/scores/storeRound/', 'ScoreController@storeRound')->name('scores.storeRound');
Route::resources([
    'scores' => 'ScoreController'
]);
Route::get('/login-guest', 'Auth\LoginController@guestLogin')->name('login.guest');