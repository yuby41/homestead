<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_home',
        'team_away',
        'spread',
        'odds',
        'over_0_5_prob',
        'under_0_5_prob',
    ];
}
