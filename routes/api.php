<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ConversationController;

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

Route::post('login', [UserController::class, 'login']);
Route::get('logout', [UserController::class, 'logout'])->middleware('jwt.verify');

Route::get('customers', [UserController::class, 'list_customers'])->middleware('jwt.verify');
Route::delete('customers/{id}', [UserController::class, 'delete_customers'])->middleware('jwt.verify');


Route::get('conversations', [ConversationController::class, 'index'])->middleware('jwt.verify');
Route::get('conversations/{id}', [ConversationController::class, 'details'])->middleware('jwt.verify');
Route::post('conversations', [ConversationController::class, 'create'])->middleware('jwt.verify');