<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
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
    return redirect('/api/documentation');
});


Route::get('/reset-Password', [UserController::class, 'reset_Passwordload']);
Route::post('/reset', [UserController::class, 'resetPassword'])->name('reset');
