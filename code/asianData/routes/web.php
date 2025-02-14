<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\GameController;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/predict', [PredictionController::class, 'uploadAndPredict']);

// Ruta para mostrar el formulario
Route::get('/upload', function () {
    return view('upload');
})->name('upload.form');

// Ruta para procesar la imagen
Route::post('/upload', [PredictionController::class, 'uploadAndPredict'])->name('upload.image');

Route::post('/process-file', [GameController::class, 'processTextFile'])->name('process.file');


