<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

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
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/logout', [UserController::class, 'logout']);
    Route::get('/userProfile', [UserController::class, 'userProfile']);
    Route::get('/send-verify-mail/{email}', [UserController::class, 'sedVerifyMail']);
    Route::post('/profile-update', [UserController::class, 'updateProfile']);
    Route::get('/refresh-token', [UserController::class, 'refreshToken']);


});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
