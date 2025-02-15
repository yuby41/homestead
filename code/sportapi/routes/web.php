<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FootballController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/leagues', [FootballController::class, 'leagues']);
Route::get('/teams/{league_id}', [FootballController::class, 'teams']);
Route::get('/fixtures/{league_id}/{season}', [FootballController::class, 'fixtures']);
Route::get('/live', [FootballController::class, 'liveMatches']);