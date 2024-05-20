<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\NotesController;

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


Route::post('/forget-password', [UserController::class, 'forget_Password']);

Route::group(['middleware' => 'api'], function ($routes) {
    // Router User
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/logout', [UserController::class, 'logout']);
    Route::get('/userProfile', [UserController::class, 'userProfile']);
    Route::get('/send-verify-mail/{email}', [UserController::class, 'sendVerifyMail']);
    Route::post('/profile-update', [UserController::class, 'updateProfile']);
    Route::get('/refresh-token', [UserController::class, 'refreshToken']);
    //Router Note
    Route::get('/notes_all', [UserController::class, 'noteList']);
    Route::post('/notes_creacte', [UserController::class, 'noteCreate']);
    Route::put('/note-update/{id}', [UserController::class, 'noteUpdate']);
    Route::delete('/note-delete/{id}', [UserController::class, 'noteDestroy']);
    Route::get('/note-detail/{id}', [UserController::class, 'noteDetail']);


});


