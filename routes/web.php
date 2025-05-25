<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\MicroBusController;

Route::view('/', 'home')->name('home');
Route::resource('recipes', RecipeController::class);
Route::post('/ms/grocery', [MicroBusController::class, 'grocery']); // Microservice A function
Route::get('/ms/grocery/list', [MicroBusController::class, 'viewGrocery']);
Route::post('/ms/sort', [MicroBusController::class, 'sort']); // Microservice B function
Route::post('/ms/generate', [MicroBusController::class, 'generate']); // Microservice C function
Route::get('/ms/tip', [MicroBusController::class, 'tip']); // Microservice D function
