<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeekMatch extends Model
{
    use HasFactory;
    
    protected $fillable = ['week', 'homeTeam', 'awayTeam', 'homeScore', 'awayScore'];
}
