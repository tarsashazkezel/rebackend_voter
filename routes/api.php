<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\api\MeetingController;
use App\Http\Controllers\api\VoteController;
use App\Http\Controllers\api\AgendaItemController;
use App\Http\Controllers\api\ResolutionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/meetings', [MeetingController::class, 'getMeetings']);
    Route::get('/meetings/{meeting}', [MeetingController::class, 'getMeeting']);
    Route::post('/meetings', [MeetingController::class, 'create']);
    Route::put('/meetings/{meeting}', [MeetingController::class, 'update']);
    Route::delete('/meetings/{meeting}', [MeetingController::class, 'delete']);
    
    Route::post('/votes', [VoteController::class, 'create']);
    Route::get('/votes', [VoteController::class, 'getVotes']);
    Route::get('/votes/{vote}', [VoteController::class, 'getVote']);
    Route::put('/votes/{vote}', [VoteController::class, 'update']);
    Route::delete('/votes/{vote}', [VoteController::class, 'destroy']);

    Route::get('/agenda-items', [AgendaItemController::class, 'index']);
    Route::get('/agenda-items/{agendaItem}', [AgendaItemController::class, 'show']);
    Route::post('/agenda-items', [AgendaItemController::class, 'store']);
    Route::put('/agenda-items/{agendaItem}', [AgendaItemController::class, 'update']);
    Route::delete('/agenda-items/{agendaItem}', [AgendaItemController::class, 'destroy']);
    
    Route::post('/resolutions', [ResolutionController::class, 'store']);
    Route::get('/resolutions', [ResolutionController::class, 'getResolutions']);
    Route::get('/resolutions/{resolution}', [ResolutionController::class, 'getResolution']);
    Route::put('/resolutions/{resolution}', [ResolutionController::class, 'update']);
    Route::delete('/resolutions/{resolution}', [ResolutionController::class, 'deleteResolution']);

    Route::post('/logout', [AuthController::class, 'logout']);
});