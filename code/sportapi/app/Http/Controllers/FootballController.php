<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiFootballService;
use App\Services\DataExportService;
use App\Models\Statistic;
use App\Models\League;
use App\Models\Team;
use App\Models\Game;


class FootballController extends Controller
{
    protected $apiFootballService;

    public function __construct(ApiFootballService $apiFootballService)
    {
        $this->apiFootballService = $apiFootballService;
    }

    public function leagues()
    {
        return response()->json($this->apiFootballService->request('leagues'));
    }

    public function teams($league_id)
    {
        return response()->json($this->apiFootballService->request('teams', ['league' => $league_id]));
    }

    public function fixtures($league_id, $season)
    {
        return response()->json($this->apiFootballService->request('fixtures', ['league' => $league_id, 'season' => $season]));
    }

    public function liveMatches()
    {
        return response()->json($this->apiFootballService->getLiveMatches());
    }

    public function storeLeagues()
    {
        $data = $this->apiFootballService->request('leagues');
    
        foreach ($data['response'] as $league) {
            League::updateOrCreate(
                ['league_id' => $league['league']['id']],
                [
                    'name' => $league['league']['name'],
                    'country' => $league['country']['name'],
                    'logo' => $league['league']['logo']
                ]
            );
        }
    
        return response()->json(['message' => 'Ligas guardadas con éxito']);
    }

    public function storeTeams($league_id)
    {
        $data = $this->apiFootballService->request('teams', ['league' => $league_id]);
    
        foreach ($data['response'] as $team) {
            Team::updateOrCreate(
                ['team_id' => $team['team']['id']],
                [
                    'name' => $team['team']['name'],
                    'logo' => $team['team']['logo'],
                    'league_id' => $league_id
                ]
            );
        }
    
        return response()->json(['message' => 'Equipos guardados con éxito']);
    }

    public function storeMatches($league_id, $season)
    {
        $data = $this->apiFootballService->request('fixtures', ['league' => $league_id, 'season' => $season]);
    
        foreach ($data['response'] as $match) {
            Game::updateOrCreate(
                ['match_id' => $match['fixture']['id']],
                [
                    'league_id' => $league_id,
                    'home_team_id' => $match['teams']['home']['id'],
                    'away_team_id' => $match['teams']['away']['id'],
                    'date' => $match['fixture']['date'],
                    'status' => $match['fixture']['status']['long'],
                    'home_score' => $match['goals']['home'],
                    'away_score' => $match['goals']['away']
                ]
            );
        }
    
        return response()->json(['message' => 'Partidos guardados con éxito']);
    }

    public function storeStatistics($league_id, $season, $team_id)
    {
        $data = $this->apiFootballService->getTeamStatistics($league_id, $season, $team_id);

        if (!isset($data['response']) || empty($data['response'])) {
            return response()->json(['error' => 'No se encontraron estadísticas para este equipo'], 404);
        }

        $stats = $data['response'];

        Statistic::updateOrCreate(
            ['team_id' => $team_id, 'league_id' => $league_id, 'season' => $season],
            [
                'matches_played' => $stats['fixtures']['played']['total'] ?? 0,
                'wins' => $stats['fixtures']['wins']['total'] ?? 0,
                'draws' => $stats['fixtures']['draws']['total'] ?? 0,
                'losses' => $stats['fixtures']['loses']['total'] ?? 0,
                'goals_scored' => $stats['goals']['for']['total'] ?? 0,
                'goals_conceded' => $stats['goals']['against']['total'] ?? 0,
                'clean_sheets' => $stats['clean_sheet']['total'] ?? 0,
                'yellow_cards' => $stats['cards']['yellow']['total'] ?? 0,
                'red_cards' => $stats['cards']['red']['total'] ?? 0
            ]
        );

        return response()->json(['message' => 'Estadísticas guardadas correctamente']);
    }

    public function exportManualMatchesToCSV(DataExportService $dataExportService)
   {
       $dataExportService->exportManualMatches();
       return response()->json(['message' => 'Datos exportados con éxito', 'file' => storage_path('app/data/manual_matches.csv')]);
   }

   public function predictMatch(Request $request)
    {
        $request->validate([
            'home_odds' => 'required|numeric',
            'away_odds' => 'required|numeric',
            'draw_odds' => 'required|numeric'
        ]);
    
        return response()->json($this->apiFootballService->getPrediction(
            $request->home_odds,
            $request->away_odds,
            $request->draw_odds
        ));
    }

    public function showPredictForm()
   {
       return view('predict');
   }
   
   public function processPrediction(Request $request)
   {
       $request->validate([
           'home_odds' => 'required|numeric',
           'away_odds' => 'required|numeric',
           'draw_odds' => 'required|numeric'
       ]);
   
       $prediction = $this->apiFootballService->getPrediction(
           $request->home_odds,
           $request->away_odds,
           $request->draw_odds
       );
   
       return view('predict', ['prediction' => $prediction]);
   }

   public function fetchUpcomingMatches($league_id, $season)
    {
        $matches = $this->apiFootballService->getUpcomingMatches($league_id, $season);
        
        if (isset($matches['response'])) {
            return response()->json($matches['response']);
        }
    
        return response()->json(['error' => 'No se encontraron partidos'], 404);
    }

    public function predictUpcomingMatches($league_id, $season)
    {
        $matches = $this->apiFootballService->getUpcomingMatches($league_id, $season);

        // 🚀 Depuración: Guardar la respuesta de la API en los logs
        \Log::info("Partidos recibidos de la API:", $matches);
    
        if (!isset($matches['response']) || empty($matches['response'])) {
            \Log::warning("⚠️ No se encontraron partidos para la liga {$league_id} y la temporada {$season}");
            return response()->json(['error' => 'No se encontraron partidos'], 404);
        }
    
        return response()->json($matches); // Devuelve los partidos directamente para ver si existen
    }
    
}
