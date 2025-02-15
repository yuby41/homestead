<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiFootballService;
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
}
