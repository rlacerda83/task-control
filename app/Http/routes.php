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
    //dashboard
    Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@indexAction']);

    //tasks
    Route::get('/tasks', ['as' => 'tasks', 'uses' => 'TaskController@indexAction']);
    Route::post('/tasks/load', ['as' => 'tasks.load', 'uses' => 'TaskController@indexAction']);
    Route::get('/tasks/new', ['as' => 'tasks.new', 'uses' => 'TaskController@newAction']);
    Route::get('/tasks/edit/{id}', ['as' => 'tasks.edit', 'uses' => 'TaskController@editAction']);
    Route::post('/tasks/save', ['as' => 'tasks.save', 'uses' => 'TaskController@saveAction']);
    Route::get('/tasks/remove/{id}', ['as' => 'tasks.remove', 'uses' => 'TaskController@removeAction']);
    Route::post('/tasks/process', ['as' => 'tasks.process', 'uses' => 'TaskController@processAction']);

    //hourcontrol
    Route::get('/hours-control', ['as' => 'hours-control', 'uses' => 'HoursControlController@indexAction']);
    Route::post('/hours-control/load', ['as' => 'hours-control.load', 'uses' => 'HoursControlController@indexAction']);
    Route::get('/hours-control/new', ['as' => 'hours-control.new', 'uses' => 'HoursControlController@newAction']);
    Route::get('/hours-control/edit/{id}', ['as' => 'hours-control.edit', 'uses' => 'HoursControlController@editAction']);
    Route::post('/hours-control/save', ['as' => 'hours-control.save', 'uses' => 'HoursControlController@saveAction']);
    Route::get('/hours-control/remove/{id}', ['as' => 'hours-control.remove', 'uses' => 'HoursControlController@removeAction']);

    //config
    Route::post('/configuration/save', ['as' => 'configuration.save', 'uses' => 'ConfigurationController@saveAction']);
    Route::get('/configuration', ['as' => 'configuration', 'uses' => 'ConfigurationController@showAction']);
});
