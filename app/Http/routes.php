<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@indexAction']);

//tasks
Route::get('/tasks', ['as' => 'tasks', 'uses' => 'TaskController@indexAction']);
Route::post('/tasks/load', ['as' => 'tasks.load', 'uses' => 'TaskController@indexAction']);
Route::get('/tasks/new', ['as' => 'tasks.new', 'uses' => 'TaskController@newAction']);
Route::get('/tasks/edit', ['as' => 'tasks.edit', 'uses' => 'TaskController@editAction']);
Route::get('/tasks/remove', ['as' => 'tasks.remove', 'uses' => 'TaskController@removeAction']);


//Route::get('/', function () {
//
//    $teste = new \App\Services\Teste();
//    //$teste->p();
//    $teste->login();
//    die;
//
//
//    return view('welcome');
//});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
