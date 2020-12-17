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


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group([
    'namespace' => "Backend",
    'middleware' => ['auth']
], function() {
    Route::name('admin.')->prefix('admin')->group(function() {
        // Dashboard Route
        Route::get('/', 'BoxController@index')->name('index');

        /**
         * Boxes Routes
         *  */
        Route::get('boxes/livred-boxes', 'BoxController@getLivredBoxes')->name('getLivredBoxes')->middleware('superAdmin');
        Route::get('boxes/livred-boxes/{id}', 'BoxController@getLivredBoxesById')->name('getLivredBoxesById')->middleware('superAdmin');
        Route::get('boxes/set-received/', function() {return view('backend.rubrics.boxes.setReceived');})->name('setRecievedPage')->middleware('superAdmin');
        Route::post('boxes/set-received/{code}', 'BoxController@setRecieved')->name('setRecieved')->middleware('superAdmin');
        Route::get('boxes/set-returned/', function() {return view('backend.rubrics.boxes.setReturned');})->name('setReturnedPage')->middleware('superAdmin');
        Route::post('boxes/set-returned/{code}', 'BoxController@setReturned')->name('setReturned')->middleware('superAdmin');
        Route::post('boxes/change-status/{id}/{newStatusId}', 'BoxController@changeStatus')->name('changeStatus')->middleware('deliveryMan');
        Route::post('boxes/assign-box/{id}/{userId}', 'BoxController@assignBox')->name('assignBox')->middleware('superAdmin');
        Route::post('boxes/download-file/{id}', 'BoxController@downlaodFile')->name('downlaodFile')->middleware('superAdmin');
        Route::get('boxes/show-file/{id}', 'BoxController@showFile')->name('showFile')->middleware('superAdmin');
        Route::resource('boxes', 'BoxController');

        /**
         * Users Routes
         *  */
        Route::resource('users', 'UserController');

        /**
         * Configs Routes
         *  */
        Route::name('configs.')->prefix('configs')->group(function() {

            Route::get('/', 'ConfigController@index')->name('index');
            Route::put('/update/{id}', 'ConfigController@update')->name('update');
        });

        /**
         * Services Routes
         *  */
        Route::resource('services', 'ServiceController');

        /**
         * Services Routes
         *  */
        Route::resource('contacts', 'ContactController');

        /**
         * Wilayas Routes
         *  */
        Route::post('/change-price/{id}', 'WilayaController@changePrice')->name('changePrice')->middleware('superAdmin');
        Route::post('/toggle-availability/{id}', 'WilayaController@toggleAvailablity')->name('toggleAvailablity')->middleware('superAdmin');
        Route::post('/add-service/{id}', 'WilayaController@addService')->name('addService')->middleware('superAdmin');
        Route::post('/delete-service/{id}', 'WilayaController@deleteService')->name('deleteService')->middleware('superAdmin');
        Route::resource('wilayas', 'WilayaController');

    });
});
