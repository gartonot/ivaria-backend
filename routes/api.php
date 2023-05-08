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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//TODO:Роуты на блюда(crud)
Route::prefix('dishes')->group(function (){
    Route::get('/', 'App\Http\Controllers\DishesController@getDishes');
    Route::middleware('checkAuth')->group(function (){
        Route::post('/', 'App\Http\Controllers\DishesController@addDishes');
        Route::patch('/', 'App\Http\Controllers\DishesController@updateDishes');
        Route::delete('/', 'App\Http\Controllers\DishesController@deleteDishes');
    });
});

Route::prefix('categories-dish')->group(function (){
    Route::get('/', 'App\Http\Controllers\DishesController@getCategories');
    Route::middleware('checkAuth')->group(function () {
        Route::post('/', 'App\Http\Controllers\DishesController@addDishCategories');
        Route::put('/', 'App\Http\Controllers\DishesController@updateDishCategories');
        Route::delete('/', 'App\Http\Controllers\DishesController@deleteDishCategories');
    });
});

//TODO:Роуты на новости(crud)

Route::prefix('news')->group(function (){
    Route::get('/', 'App\Http\Controllers\NewsController@getNews');
    Route::middleware('checkAuth')->group(function (){
        Route::post('/', 'App\Http\Controllers\NewsController@addNews');
        Route::patch('/', 'App\Http\Controllers\NewsController@updateNews');
        Route::delete('/', 'App\Http\Controllers\NewsController@deleteNews');
    });
});

Route::prefix('categories-news')->group(function () {
    Route::get('/', 'App\Http\Controllers\NewsController@getNewsCategories');
    Route::middleware('checkAuth')->group(function (){
        Route::post('/', 'App\Http\Controllers\NewsController@addNewsCategories');
        Route::put('/', 'App\Http\Controllers\NewsController@updateNewsCategories');
        Route::delete('/', 'App\Http\Controllers\NewsController@deleteNewsCategories');
    });
});

Route::prefix('contacts')->group(function (){
    Route::get('/', 'App\Http\Controllers\ContactsController@getContacts');
    Route::middleware('checkAuth')->group(function (){
        Route::post('/', 'App\Http\Controllers\ContactsController@addContacts');
        Route::patch('/', 'App\Http\Controllers\ContactsController@updateContacts');
    });
});

//TODO:Роуты на услуги(crud)

Route::prefix('services')->group(function (){
    Route::get('/', 'App\Http\Controllers\ServicesController@getServices');
    Route::middleware('checkAuth')->group(function (){
        Route::post('/', 'App\Http\Controllers\ServicesController@addServices');
        Route::patch('/', 'App\Http\Controllers\ServicesController@updateServices');
        Route::delete('/', 'App\Http\Controllers\ServicesController@deleteServices');
    });
});

Route::post('/registration', 'App\Http\Controllers\AuthController@register');
Route::post('/login', 'App\Http\Controllers\AuthController@login');

Route::prefix('booking')->group(function (){
    Route::post('/add', 'App\Http\Controllers\BookingController@addBooking');
});
