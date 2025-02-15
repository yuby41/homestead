<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiFootballService;

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
}
