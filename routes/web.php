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


Route::get('/upload', 'UploadController@uploadFile');
Route::resource('images', 'UploadController', ['only' => ['store', 'destroy']]);
Route::post('/newFolder', 'RemoteFolders@createDirectory');
Route::post('/changeDirectory', 'RemoteFolders@changeDirectory');
Route::get('/newFolder', 'RemoteFolders@index');

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/viewfile','UploadController@viewFile');
