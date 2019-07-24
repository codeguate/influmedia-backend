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
Route::resource('users', 'UsersController');
Route::resource('roles', 'RolesController');
Route::resource('codigos', 'CodigosController');

Route::get('send', "UsersController@sendEmail");
Route::get('filter/{id}/users/{state}', "UsersController@getThisByFilter");
Route::get('filter/{id}/codigos/{state}', "CodigosController@getThisByFilter");

Route::get('rol/{id}/users', "Users@getUsersByRol");

Route::put('check/codigo/{id}', 'CodigosController@marcar');
Route::post('users/password/reset', 'UsersController@recoveryPassword');
Route::post('users/{id}/changepassword', "UsersController@changePassword");

Route::post('login', 'AuthenticateController@login');
Route::post('upload', 'AuthenticateController@uploadAvatar');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');