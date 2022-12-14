<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/conferences', [ConferenceController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);
Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/user/change', [UserController::class, 'update']);

    Route::get('/plans', [PlanController::class, 'index']);
    Route::get('/intent', [PlanController::class, 'intent']);
    Route::post('/subscribe', [SubscriptionController::class, 'session']);

    Route::get('/plan', [UserController::class, 'getPlan']);
    Route::post('/planCancel', [SubscriptionController::class, 'cancelPlan']);

    Route::get('/meeting/{id}', [MeetingController::class, 'show']);
    Route::post('/meeting', [MeetingController::class, 'store']);
    Route::put('/meeting/{id}', [MeetingController::class, 'update']);
    Route::get('/meeting', [MeetingController::class, 'index']);
    Route::delete('/meeting/{id}', [MeetingController::class, 'destroy']);

    Route::post('/conferenceJoin/{id}', [UserController::class, 'conferenceJoin']);
    Route::post('/conferenceOut/{id}', [UserController::class, 'conferenceOut']);
    Route::post('/isOnConference/{id}', [UserController::class, 'getConference']);
    Route::get('/isFavorite/{id}', [UserController::class, 'isFavorite']);
    Route::get('/favorite', [UserController::class, 'getFavorite']);
    Route::post('/favorite/{id}', [UserController::class, 'favorite']);
    Route::post('/unfavorite/{id}', [UserController::class, 'unfavorite']);
    Route::get('/currentCategory/{id}', [CategoryController::class, 'currentCategory']);
    Route::get('/rootCategories', [CategoryController::class, 'rootCategories']);
    Route::get('/subCategories/{id}', [CategoryController::class, 'subCategories']);
    Route::get('/conferences/{id}', [ConferenceController::class, 'show']);
    Route::get('/conferencesByName', [ConferenceController::class, 'conferencesByName']);
    Route::get('/reportsByName', [ReportController::class, 'reportsByName']);
    Route::get('/category/{id}/getConferences', [CategoryController::class, 'getConferences']);
    Route::get('/category/{id}/getReports', [CategoryController::class, 'getReports']);
    Route::get('/conferences/{id}/reports', [ReportController::class, 'index']);
    Route::get('/conferences/{conference_id}/reports/{report_id}', [ReportController::class, 'show']);
    Route::get('/conferences/{conference_id}/reports/{report_id}/file', [ReportController::class, 'getFile']);
    Route::get('/conferences/{conference_id}/reports/uploadFile', [ReportController::class, 'uploadFile']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/conferences/report/{id}/comment', [CommentController::class, 'index']);
    Route::post('/conferences/{conference_id}/report/{report_id}/comment', [CommentController::class, 'store']);
    Route::delete('/conferences/{conference_id}/reports/{report_id}', [ReportController::class, 'destroy']);

    Route::group(['middleware' => ['isRigthAnnouncer']], function () {
        Route::post('/conferences/{id}/reports', [ReportController::class, 'store']);
        Route::put('/conferences/{conference_id}/reports/{report_id}', [ReportController::class, 'update']);
    });

    Route::put('/conferences/{conference_id}/report/{report_id}/comment/{id}', [CommentController::class, 'update']);

    Route::group(['middleware' => ['isAdmin']], function () {
        Route::get('/conferencesDownloadCsv', [ConferenceController::class, 'downloadCsv']);
        Route::get('/conferences/{id}/reportsDownloadCsv', [ReportController::class, 'downloadCsv']);
        Route::get('/conferences/reports/{id}/commentDownloadCsv', [CommentController::class, 'downloadCsv']);
        Route::get('/conferences/{id}/listenersDownloadCsv', [UserController::class, 'downloadCsv']);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
