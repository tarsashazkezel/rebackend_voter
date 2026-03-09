<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\api\MeetingController;
use App\Http\Controllers\api\VoteController;
use App\Http\Controllers\api\AgendaItemController;
use App\Http\Controllers\api\ResolutionController;
use App\Http\Controllers\api\UserController; // <--- EZT HOZZÁADTAM

use App\Models\User;

/// 1. Az Angular ezt hívja meg a FORM elküldésekor (MENTÉS)
// URL: POST http://localhost:8000/api/reset-password
Route::post('/reset-password', [UserController::class, 'resetPassword']);

// 2. Ez a "technikai" útvonal az e-mailben kiküldött linkhez (ÁTIRÁNYÍTÁS)
// Fontos: a neve 'password.reset' kell legyen!
Route::get('/reset-password/{token}', function ($token) {
    // Itt csak átirányítjuk az embert az Angular frontendjére
    return redirect("http://localhost:4200/reset-password?token=" . $token . "&email=" . request('email'));
})->name('password.reset');

// 2. Ez fogadja az Angular kérését (E-mail küldés indítása)
// URL: http://localhost:8000/api/forgot-password
Route::post('/forgot-password', [UserController::class, 'forgotPassword']);


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post("register/confirm", [AuthController::class, 'confirmRegistration']);

Route::middleware('auth:sanctum')->group(function () {
    
    
    Route::get('/users', [UserController::class, 'index']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    

    Route::get('/meetings', [MeetingController::class, 'getMeetings']);
    Route::get('/meetings/{meeting}', [MeetingController::class, 'getMeeting']);
    Route::post('/meetings', [MeetingController::class, 'create']);
    Route::put('/users/{user}/toggle-status', [UserController::class, 'toggleStatus']);
    Route::post('/meetings/{meeting}/attend', [MeetingController::class, 'attend']);
    Route::put('/meetings/{meeting}/toggle-repeated', [MeetingController::class, 'toggleRepeated']);

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