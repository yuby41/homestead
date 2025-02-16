<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManualMatch;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ManualMatchController extends Controller
{
    public function importManualMatches(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048', // Validar formato y tamaño del archivo
        ]);
    
        $file = $request->file('file');
    
        // Guardar el archivo en storage
        $path = $file->storeAs('private/data', 'manual_matches.csv');
    
        // Leer el archivo CSV
        $csv = Reader::createFromPath(storage_path('app/' . $path), 'r');
        $csv->setHeaderOffset(0);
    
        foreach ($csv as $row) {
            ManualMatch::updateOrCreate(
                [
                    'league_name' => $row['League'],
                    'home_team' => $row['Home Team'],
                    'away_team' => $row['Away Team'],
                    'match_date' => $row['Start Time (UTC)'],
                ],
                [
                    'home_score' => is_numeric($row['Home Score']) ? (int)$row['Home Score'] : null,
                    'away_score' => is_numeric($row['Away Score']) ? (int)$row['Away Score'] : null,
                    'home_odds' => is_numeric($row['ML Home Current']) ? (float)$row['ML Home Current'] : null,
                    'away_odds' => is_numeric($row['ML Away Current']) ? (float)$row['ML Away Current'] : null,
                    'draw_odds' => is_numeric($row['ML Draw Current']) ? (float)$row['ML Draw Current'] : null
                ]
            );
        }
    
        return redirect()->route('upload.csv')->with('success', 'Archivo CSV importado correctamente.');
    }

    public function showUploadForm()
    {
        return view('upload_csv');
    }

}
