<?php

namespace App\Services;

use thiagoalessio\TesseractOCR\TesseractOCR;

class OCRService
{
    public function extractDataFromImage($imagePath)
    {
        $ocr = new TesseractOCR($imagePath);
        $text = $ocr->run();

        // Procesar el texto extraído y convertirlo en datos estructurados
        $data = $this->parseTextToData($text);

        return $data;
    }

    private function parseTextToData($text)
    {
        // Implementa lógica para procesar el texto y convertirlo en datos estructurados
        $lines = explode("\n", $text);
        $structuredData = [];

        foreach ($lines as $line) {
            // Extrae columnas clave (Betting Event, Spread, Probabilities, etc.)
            $columns = preg_split('/\s{2,}/', $line);

            if (count($columns) >= 6) {
                $structuredData[] = [
                    'team_home' => $columns[0],
                    'team_away' => $columns[1],
                    'over_0_5_prob' => (float)str_replace('%', '', $columns[5]),
                    'over_0_5_odds' => (float)$columns[6],
                    'handicap' => (float)$columns[2],
                    'moneyline_home' => (float)$columns[3],
                    'moneyline_away' => (float)$columns[4],
                ];
            }
        }

        return $structuredData;
    }
}