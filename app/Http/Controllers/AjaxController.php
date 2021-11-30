<?php

namespace App\Http\Controllers;

use App\Events\MatchDay;
use App\Models\Game;
use App\Models\Tournament;
use App\Repositories\Games;
use App\Repositories\Tournaments;
use Illuminate\Http\Request;


class AjaxController extends Controller
{
    /**
     * New games day
     */
    public function newDay(Request $request): \Illuminate\Http\JsonResponse
    {
        // find tournament
        $tournament = (new Tournaments())->find($request->input('tournamentId'));

        // launch match(s)
        MatchDay::dispatch($tournament);

        return $this->returnDayData($tournament);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    private function returnDayData(Tournament $tournament)
    {
        return response()->json(
            [

                'week'             => (new Games)->getNextWeek($tournament),
                'launches'         => [],//$this->launches,
                'week_table'       => [],//$this->calculateTable(Game::where('tournament_id',$tournament->id)->get()),
                'week_results'     => [],//$this->prepareWeekMatchesResult($this->getMatchesByWeek($tournament, $this->week)),
                'week_productions' => [],//$this->getWeekProduction($this->getMatchesSplitWeeks($this->getAllMatches())),
                'log' => [],//$this->getLogMatches()
            ]
        );
    }

    public function lastDay(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            []
        );
    }
}
