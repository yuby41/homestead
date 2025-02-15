<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'league_id',
        'home_team_id',
        'away_team_id',
        'date', 'status',
        'home_score',
        'away_score'
    ];
}
