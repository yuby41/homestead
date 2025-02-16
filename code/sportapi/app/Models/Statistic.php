<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Statistic extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'team_id',
        'league_id',
        'season',
        'matches_played',
        'wins',
        'draws',
        'losses',
        'goals_scored',
        'goals_conceded',
        'clean_sheets',
        'yellow_cards',
        'red_cards'
    ];
}
