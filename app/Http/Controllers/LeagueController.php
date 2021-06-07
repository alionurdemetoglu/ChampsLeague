<?php

namespace App\Http\Controllers;

use App\Models\Match;
use App\Models\Team;
use App\Models\WeekMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use function Psy\sh;

class LeagueController extends Controller
{
    public function simulate() {
        $teams = $this->prepareTeams();
        $teamNames = array_map(array($this, 'getTeamName'), $teams);
        $round = $this->generateFixture($teamNames);
        $round1 = $round;
        shuffle($round1);
        $round = array_merge($round1, $round);
        $simulations = [];
        foreach ($round as $week => $matches) {
            foreach ($matches as $match) {
                $homeKey = array_search($match['Home'], $teamNames);
                $awayKey = array_search($match['Away'], $teamNames);
                $homeScore = round(random_int(0,5) * $teams[$homeKey]->weight / 10);
                $awayScore = round(random_int(0,5) * $teams[$awayKey]->weight / 10);
                $matchObj = new WeekMatch([
                    "week"      => $week,
                    "homeTeam"  => $match['Home'],
                    "awayTeam"  => $match['Away'],
                    "homeScore" => $homeScore,
                    "awayScore" => $awayScore
                ]);
                $teams[$awayKey]->played += 1;
                $teams[$homeKey]->played += 1;
                if (($matchObj->homeScore <=> $matchObj->awayScore) == -1) {
                    $teams[$homeKey]->lose += 1;
                    $teams[$awayKey]->win += 1;
                    $teams[$awayKey]->point += 3;
                    $teams[$homeKey]->goalDiff -= ($matchObj->awayScore - $matchObj->homeScore);
                    $teams[$awayKey]->goalDiff += ($matchObj->awayScore - $matchObj->homeScore);
                } else if ($matchObj->homeScore <=> $matchObj->awayScore) {
                    $teams[$awayKey]->lose += 1;
                    $teams[$homeKey]->win += 1;
                    $teams[$homeKey]->point += 3;
                    $teams[$awayKey]->goalDiff -= ($matchObj->homeScore - $matchObj->awayScore);
                    $teams[$homeKey]->goalDiff += ($matchObj->homeScore - $matchObj->awayScore);
                } else {
                    $teams[$awayKey]->draw += 1;
                    $teams[$homeKey]->draw += 1;
                    $teams[$homeKey]->point += 1;
                    $teams[$awayKey]->point += 1;
    
                }
                $simulations[] = $matchObj;
            }
        }
        usort($teams, (array($this, "comparePoint")));
        return response()->json([$simulations, $teams]);
    }
    
    private function prepareTeams() {
        $team1 = new Team([
            'name' => "Manchester City",
            'weight' => 9,
            'point'  => 0,
            'played' => 0,
            'win'    => 0,
            'draw'   => 0,
            'lose'   => 0,
            'goalDiff' => 0
        ]);
        $team2 = new Team([
            'name' => "Tottenham Hotspur",
            "weight" => 6,
            'point'  => 0,
            'played' => 0,
            'win'    => 0,
            'draw'   => 0,
            'lose'   => 0,
            'goalDiff' => 0
        ]);
        $team3 = new Team([
            'name' => "Crystal Palace",
            "weight" => 4,
            'point'  => 0,
            'played' => 0,
            'win'    => 0,
            'draw'   => 0,
            'lose'   => 0,
            'goalDiff' => 0
        ]);
        $team4 = new Team([
            'name' => "Sheffield United",
            "weight" => 1,
            'point'  => 0,
            'played' => 0,
            'win'    => 0,
            'draw'   => 0,
            'lose'   => 0,
            'goalDiff' => 0
        ]);
        return [$team1, $team2, $team3, $team4];
    }
    
    private function getTeamName($ar) {
        return $ar->getAttribute('name');
    }
    
    private function comparePoint($a, $b) {
        if ($a->point == $b->point) {
            return 0;
        }
        return ($a->point > $b->point) ? -1 : 1;    
    }
    
    private function generateFixture($teams) {
        $away = array_splice($teams,count($teams)/2);
        $home = $teams;
        for ($i=0; $i < count($home)+count($away)-1; $i++){
            for ($j=0; $j<count($home); $j++){
                $round[$i][$j]["Home"]=$home[$j];
                $round[$i][$j]["Away"]=$away[$j];
            }
            
            if(count($home)+count($away)-1 > 2){
                $splice = array_splice($home,1,1);
                array_unshift($away, array_shift($splice));
                array_push($home,array_pop($away));
            }
        }
        return $round;
    }
}
