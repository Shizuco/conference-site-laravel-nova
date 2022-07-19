<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/conferenceJoin/{id}', [UserController::class, 'conferenceJoin']);
    Route::post('/conferenceOut/{id}', [UserController::class, 'conferenceOut']);
    Route::post('/isOnConference/{id}', [UserController::class, 'getConference']);
    Route::get('/conferences/{id}', [ConferenceController::class, 'show']);
    Route::get('/conferences/{id}/reports', [ReportController::class, 'index']);
    Route::get('/conferences/{conference_id}/reports/{report_id}', [ReportController::class, 'show']);
    Route::get('/conferences/{conference_id}/report/{report_id}/comment', [CommentController::class, 'index']);
    Route::post('/conferences/{conference_id}/report/{report_id}/comment', [CommentController::class, 'store']);

    Route::group(['middleware' => ['isRigthAnnouncer']], function () {
        Route::post('/conferences/{id}/reports', [ReportController::class, 'store']);
        Route::delete('/conferences/{conference_id}/reports/{report_id}', [ReportController::class, 'destroy']);
        Route::put('/conferences/{conference_id}/reports/{report_id}', [ReportController::class, 'update']);
    });

    Route::put('/conferences/{conference_id}/report/{report_id}/comment/{id}', [CommentController::class, 'update']);

    Route::group(['middleware' => ['isAdmin']], function () {
        Route::delete('/conferences/{id}', [ConferenceController::class, 'destroy']);
        Route::post('/conferences', [ConferenceController::class, 'store']);
        Route::put('/conferences/{id}', [ConferenceController::class, 'update']);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
