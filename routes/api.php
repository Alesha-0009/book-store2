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
Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/search', [
        'as' => 'api.search',
        'uses' => 'Api\SearchController@search'
    ]);
    Route::get('/deleteBook/{book}', [
        'as' => 'api.deleteBook',
        'uses' => 'Api\SearchController@deleteBook'
    ]);
    Route::get('/getAuthors', [
        'as' => 'api.getAuthors',
        'uses' => 'Api\SearchController@getAuthors'
    ]);
    Route::get('/getGenres', [
        'as' => 'api.getGenres',
        'uses' => 'Api\SearchController@getGenres'
    ]);
    Route::post('/createAuthor', [
        'as' => 'api.createAuthor',
        'uses' => 'Api\SearchController@createAuthor'
    ]);
    Route::post('/createGenre', [
        'as' => 'api.createGenre',
        'uses' => 'Api\SearchController@createGenre'
    ]);
    Route::any('/publishBook', [
        'as' => 'api.publishBook',
        'uses' => 'Api\SearchController@publishBook'
    ]);
});