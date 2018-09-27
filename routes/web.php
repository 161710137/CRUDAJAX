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

Route::get('/', function () {
    return view('welcome');
});
Route::get('siswa','SiswaController@index');
Route::get('siswa/json','SiswaController@json')->name('a');
Route::post('siswas', 'SiswaController@store');
Route::post('ajaxdata/postdata', 'SiswaController@postdata')->name('ajaxdata.postdata');
Route::get('ajaxdata/fetchdata', 'SiswaController@fetchdata')->name('ajaxdata.fetchdata');
Route::get('ajaxdata/getdata', 'SiswaController@removedata')->name('ajaxdata.removedata');

Route::post('siswa/edit/{id}','SiswaController@update');
Route::get('siswa/getEdit/{id}','SiswaController@edit');