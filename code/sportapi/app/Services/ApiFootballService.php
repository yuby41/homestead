<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ApiFootballService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('API_FOOTBALL_KEY');
        $this->baseUrl = env('API_FOOTBALL_URL', 'https://v3.football.api-sports.io/');
    }

    public function request($endpoint, $params = [], $cacheTime = 3600)
    {
        $cacheKey = md5($endpoint . json_encode($params));

        return Cache::remember($cacheKey, $cacheTime, function () use ($endpoint, $params) {
            $response = Http::withHeaders([
                'x-apisports-key' => $this->apiKey,
                'Accept' => 'application/json',
            ])->get("{$this->baseUrl}{$endpoint}", $params);

            return $response->successful() ? $response->json() : ['error' => true, 'message' => $response->body()];
        });
    }

    // 🚀 **Aquí definimos el método faltante**
    public function getUpcomingMatches($league_id, $season)
    {
        return $this->request('fixtures', [
            'league' => $league_id,
            'season' => $season,
            'status' => 'NS' // Solo partidos no iniciados
        ]);

        \Log::info("Respuesta API de partidos: ", $response);

        return $response;
    }

    public function getPrediction($home_odds, $away_odds, $draw_odds)
    {
        $response = Http::post('http://127.0.0.1:5000/predict', [
            'home_odds' => $home_odds,
            'away_odds' => $away_odds,
            'draw_odds' => $draw_odds
        ]);
    
        return $response->json();
    }

    public function getLiveOdds($fixture_id)
    {
        return $this->request('odds/live', [
            'fixture' => $fixture_id
        ]);
    }
    
    public function getPreMatchOdds($fixture_id, $bookmaker_id = 8)
    {
        return $this->request('odds', [
            'fixture' => $fixture_id,
            'bookmaker' => $bookmaker_id // ID del bookmaker (por defecto Bet365)
        ]);
    }


}
