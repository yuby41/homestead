<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Statistic;

class StatisticController extends Controller
{
    public function storeStatistics($league_id, $season, $team_id)
    {
        $data = $this->apiFootballService->getTeamStatistics($league_id, $season, $team_id);
    
        if (isset($data['response'])) {
            $stats = $data['response'];
    
            Statistic::updateOrCreate(
                ['team_id' => $team_id, 'league_id' => $league_id, 'season' => $season],
                [
                    'matches_played' => $stats['fixtures']['played']['total'],
                    'wins' => $stats['fixtures']['wins']['total'],
                    'draws' => $stats['fixtures']['draws']['total'],
                    'losses' => $stats['fixtures']['loses']['total'],
                    'goals_scored' => $stats['goals']['for']['total'],
                    'goals_conceded' => $stats['goals']['against']['total'],
                    'clean_sheets' => $stats['clean_sheet']['total'],
                    'yellow_cards' => $stats['cards']['yellow']['total'],
                    'red_cards' => $stats['cards']['red']['total']
                ]
            );
    
            return response()->json(['message' => 'Estadísticas guardadas correctamente']);
        }
    
        return response()->json(['error' => 'No se encontraron estadísticas']);
    }

    public function predictMatch($home_team_id, $away_team_id, $season)
    {
        $homeStats = Statistic::where('team_id', $home_team_id)->where('season', $season)->first();
        $awayStats = Statistic::where('team_id', $away_team_id)->where('season', $season)->first();
    
        if (!$homeStats || !$awayStats) {
            return response()->json(['error' => 'No hay suficientes datos para generar un pronóstico']);
        }
    
        $homeWinProb = ($homeStats->wins / max($homeStats->matches_played, 1)) * 100;
        $awayWinProb = ($awayStats->wins / max($awayStats->matches_played, 1)) * 100;
        $drawProb = 100 - ($homeWinProb + $awayWinProb);
    
        return response()->json([
            'home_win' => round($homeWinProb, 2) . '%',
            'draw' => round($drawProb, 2) . '%',
            'away_win' => round($awayWinProb, 2) . '%'
        ]);
    }

}
