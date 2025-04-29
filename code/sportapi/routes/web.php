<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{FootballController, StatisticController, ManualMatchController};

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

Route::post('/store-statistics/{league_id}/{season}/{team_id}', [StatisticController::class, 'storeStatistics']);

Route::get('/predict/{home_team_id}/{away_team_id}/{season}', [StatisticController::class, 'predictMatch']);

Route::post('/import-manual-matches', [ManualMatchController::class, 'importManualMatches']);
Route::get('/upload-csv', [ManualMatchController::class, 'showUploadForm'])->name('upload.csv');
Route::post('/upload-csv', [ManualMatchController::class, 'importManualMatches'])->name('import.csv');

Route::get('/export-manual-matches', [FootballController::class, 'exportManualMatchesToCSV']);
Route::post('/predict-match', [FootballController::class, 'predictMatch']);

Route::get('/predict', [FootballController::class, 'showPredictForm'])->name('predict.form');
Route::post('/predict', [FootballController::class, 'processPrediction'])->name('predict.process');

Route::get('/matches/{league_id}/{season}', [FootballController::class, 'fetchUpcomingMatches']);
Route::get('/predict-matches/{league_id}/{season}', [FootballController::class, 'predictUpcomingMatches']);
