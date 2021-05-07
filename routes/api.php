<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::group(array('namespace' => 'Api\V1', 'prefix' => 'v1'), function () {
	//Menu Routes
    Route::get('/menu', 'MenuController@index')->middleware('api_token');
	Route::post('/add-menu', 'MenuController@postMenu')->middleware('api_token');
	Route::put('/update-menu/{id}', 'MenuController@putMenu')->middleware('api_token');
	Route::delete('/delete-menu/{id}', 'MenuController@deleteMenu')->middleware('api_token');

	//FoodCategory Routes
    Route::get('/categories', 'FoodCategoryController@index')->middleware('api_token');
	Route::post('/add-category', 'FoodCategoryController@postFoodCategory')->middleware('api_token');
	Route::put('/update-category/{id}', 'FoodCategoryController@putFoodCategory')->middleware('api_token');
	Route::delete('/delete-category/{id}', 'FoodCategoryController@deleteFoodCategory')->middleware('api_token');
	
	//Food Routes
    Route::get('/food', 'FoodController@index')->middleware('api_token');
	Route::post('/add-food', 'FoodController@postFood')->middleware('api_token');
	Route::put('/update-food/{id}', 'FoodController@putFood')->middleware('api_token');
	Route::delete('/delete-food/{id}', 'FoodController@deleteFood')->middleware('api_token');
});
