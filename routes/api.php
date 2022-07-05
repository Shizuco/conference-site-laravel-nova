<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::match(['get', 'post'],'/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::match(['get', 'post'],'/conferencejoin', [UserController::class, 'conferencejoin']);
    Route::match(['get', 'post'],'/conferenceOut', [UserController::class, 'conferenceOut']);
});

Route::resource('/conferences', ConferenceController::class)->only([
    'index', 'show', 'store', 'update', 'destroy'
]);
/*Route::get('/conferences',[ConferenceController::class, 'index']);
Route::get('/conferences/{id}',[ConferenceController::class, 'show']);
Route::delete('/conferences/{id}',[ConferenceController::class, 'destroy']);
Route::post('/conferences',[ConferenceController::class, 'store']);
Route::put('/conferences/{id}',[ConferenceController::class, 'update']);*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});