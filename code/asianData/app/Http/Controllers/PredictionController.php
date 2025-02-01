<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OCRService;
use App\Services\GoalPredictionService;
use App\Models\Game;

class PredictionController extends Controller
{
    protected $ocrService;
    protected $goalPredictionService;

    public function __construct(OCRService $ocrService, GoalPredictionService $goalPredictionService)
    {
        $this->ocrService = $ocrService;
        $this->goalPredictionService = $goalPredictionService;
    }

    public function uploadAndPredict(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Guardar la imagen
        $path = $request->file('image')->store('images');

        // Extraer datos con OCR
        $data = $this->ocrService->extractDataFromImage(storage_path('app/' . $path));

        $predictions = [];
        foreach ($data as $matchData) {
            $match = Game::create($matchData);
            $predictions[] = $this->goalPredictionService->predictGoals($match);
        }

        // Redirigir con predicciones
        return redirect()->route('upload.form')->with('predictions', $predictions);
    }
}
