<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;

Route::view('/', 'home')->name('home');
Route::resource('recipes', RecipeController::class);