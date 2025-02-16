<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ManualMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'league_name',
        'home_team',
        'away_team',
        'match_date',
        'home_score',
        'away_score',
        'home_odds',
        'away_odds',
        'draw_odds'
    ];
}
