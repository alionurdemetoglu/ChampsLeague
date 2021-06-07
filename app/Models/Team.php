<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'weight', 'points', 'played', 'win', 'draw', 'lose', 'goalDiff'];
    
}
