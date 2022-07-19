<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
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
Route::get('/conferences', [ConferenceController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);
Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
Route::get('/conferences/{id}/reports', [ReportController::class, 'index']);
Route::get('/conferences/{conference_id}/reports/{report_id}', [ReportController::class, 'show']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/conferenceJoin/{id}', [UserController::class, 'conferenceJoin']);
    Route::post('/conferenceOut/{id}', [UserController::class, 'conferenceOut']);
    Route::post('/isOnConference/{id}', [UserController::class, 'getConference']);
    Route::get('/conferences/{id}', [ConferenceController::class, 'show']);
    Route::post('/conferences/{id}/reports', [ReportController::class, 'store']);

    Route::group(['middleware' => ['isAdmin']], function () {
        Route::delete('/conferences/{id}', [ConferenceController::class, 'destroy']);
        Route::post('/conferences', [ConferenceController::class, 'store']);
        Route::put('/conferences/{id}', [ConferenceController::class, 'update']);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
?>