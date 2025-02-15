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

    public function clearCache($endpoint, $params = [])
    {
        $cacheKey = md5($endpoint . json_encode($params));
        Cache::forget($cacheKey);
    }
    // Obtener partidos en vivo
    public function getLiveMatches()
    {
        return $this->request('fixtures', ['live' => 'all']);
    }
}
