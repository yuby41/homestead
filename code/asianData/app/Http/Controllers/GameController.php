<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

class GameController extends Controller
{
    public function processTextFile(Request $request)
    {
        // Validar el archivo subido
        $request->validate([
            'file' => 'required|file|mimes:txt',
        ]);
    
        // Cargar el contenido del archivo
        $filePath = $request->file('file')->getRealPath();
        $text = file_get_contents($filePath);
    
        // Procesar las líneas del texto
        $lines = explode("\n", $text);
        $structuredData = [];
    
        foreach ($lines as $line) {
            // Ignorar líneas vacías o irrelevantes
            if (trim($line) === '' || strpos($line, 'vs') === false) {
                continue;
            }
    
            // Dividir en columnas
            $parts = preg_split('/\s+/', $line);
    
            // Validar y mapear los datos extraídos
            if (count($parts) >= 8) {
                $teamHome = $parts[0] ?? null;
                $teamAway = $parts[2] ?? null;
                $spread = $parts[3] ?? null;
                $odds = is_numeric($parts[4]) ? (float)$parts[4] : null;
                $overProb = is_numeric($parts[6]) ? (float)$parts[6] : null;
    
                // Ignorar si los datos clave no son válidos
                if (!$teamHome || !$teamAway || is_null($odds) || is_null($overProb)) {
                    \Log::warning('Línea ignorada por datos inválidos: ' . $line);
                    continue;
                }
    
                $structuredData[] = [
                    'team_home' => $teamHome,
                    'team_away' => $teamAway,
                    'spread' => $spread,
                    'odds' => $odds,
                    'over_0_5_prob' => $overProb,
                    'under_0_5_prob' => $parts[7] ?? null,
                ];
            } else {
                \Log::warning('Línea con formato incorrecto: ' . $line);
            }
        }
    
        // Guardar los datos válidos en la base de datos
        foreach ($structuredData as $data) {
            try {
                Game::create($data);
            } catch (\Exception $e) {
                \Log::error('Error al guardar registro: ', $data);
            }
        }
    
        return response()->json(['message' => 'Datos procesados y guardados exitosamente']);
    }
    
}
