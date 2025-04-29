<?php
namespace App\Services;

use App\Models\ManualMatch;
use Illuminate\Support\Facades\Storage;

class DataExportService
{
    public function exportManualMatches()
    {
        $matches = ManualMatch::all();

        $csvData = "league_name,home_team,away_team,match_date,home_score,away_score,home_odds,away_odds,draw_odds\n";

        foreach ($matches as $match) {
            $csvData .= "{$match->league_name},{$match->home_team},{$match->away_team},{$match->match_date},{$match->home_score},{$match->away_score},{$match->home_odds},{$match->away_odds},{$match->draw_odds}\n";
        }

        Storage::put('data/manual_matches.csv', $csvData);
    }
}
