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
// Route::get('/', 'HomeController@questions')->name('questions');
Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/myquestions', 'HomeController@myquestions')->name('myquestions');
Route::get('/questions', 'HomeController@questions')->name('questions');
Route::get('/get_question/{id}', 'HomeController@get_question')->name('detail_questions');
Route::post('/add_submit_question', 'HomeController@add_submit_question')->name('add_submit_question');
Route::post('/edit_submit_question', 'HomeController@edit_submit_question')->name('edit_submit_question');
Route::post('/delete_submit_question', 'HomeController@delete_submit_question')->name('delete_submit_question');
Route::post('/comment_submit_question', 'HomeController@comment_submit_question')->name('comment_submit_question');
Route::post('/comment_update_submit_question', 'HomeController@comment_update_submit_question')->name('comment_update_submit_question');
Route::get('/comment_delete/{id}', 'HomeController@comment_delete')->name('comment_delete');
Route::get('/comment_refresh/{id}', 'HomeController@comment_refresh')->name('comment_refresh');



Route::get('/home', 'HomeController@index')->name('home');
