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

Route::post('/store-leagues', [FootballController::class, 'storeLeagues']);
Route::post('/store-teams/{league_id}', [FootballController::class, 'storeTeams']);
Route::post('/store-matches/{league_id}/{season}', [FootballController::class, 'storeMatches']);
