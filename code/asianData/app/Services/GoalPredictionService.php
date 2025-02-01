<?php

namespace App\Services;

use App\Models\Game;

class GoalPredictionService
{
    public function predictGoals(Game $match)
    {
        // Reglas para predecir goles
        if ($match->over_0_5_prob > 55) {
            if ($match->over_0_5_prob >= 79 || $match->handicap < 0) {
                return [
                    'prediction' => 'High Probability of Goals',
                    'confidence' => $match->over_0_5_prob
                ];
            }
        }

        return [
            'prediction' => 'Uncertain',
            'confidence' => $match->over_0_5_prob
        ];
    }
}
