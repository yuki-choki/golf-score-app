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

Route::get('/', 'HomeController@index');

Auth::routes(['verify' => true]);

Route::get('/users', 'UsersController@index')->name('users');
Route::get('/users/edit/', 'UsersController@edit')->name('users.edit');
Route::get('/users/update/', 'UsersController@update')->name('users.update');
Route::post('/users/update/', 'UsersController@update')->name('users.update');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/password/change', 'Auth\ChangePasswordController@showChangePasswordForm')->name('password.form');
Route::post('/password/change', 'Auth\ChangePasswordController@ChangePassword')->name('password.change');
Route::get('/scores/search', 'ScoreController@search')->name('scores.search');
Route::post('/scores/search', 'ScoreController@search')->name('scores.search.post');
Route::post('/scores/create', 'ScoreController@create')->name('scores.create');
Route::get('/scores/analysis', 'ScoreController@analysis')->name('scores.analysis');
Route::post('/scores/analysis', 'ScoreController@analysis')->name('scores.analysis.post');
Route::resources([
    'scores' => 'ScoreController'
]);
Route::get('/login-guest', 'Auth\LoginController@guestLogin')->name('login.guest');