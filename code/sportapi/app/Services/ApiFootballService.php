<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiFootballService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('API_FOOTBALL_KEY');
        $this->baseUrl = env('API_FOOTBALL_URL', 'https://v3.football.api-sports.io/');
    }

    public function request($endpoint, $params = [])
    {
        $response = Http::withHeaders([
            'x-apisports-key' => $this->apiKey,
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}{$endpoint}", $params);

        if ($response->failed()) {
            return ['error' => true, 'message' => $response->body()];
        }

        return $response->json();
    }

    // Obtener partidos en vivo
    public function getLiveMatches()
    {
        return $this->request('fixtures', ['live' => 'all']);
    }
}
