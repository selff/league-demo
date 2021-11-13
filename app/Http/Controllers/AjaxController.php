<?php

namespace App\Http\Controllers;

use App\Events\MatchDay;
use App\Models\Game;
use App\Models\Tournament;
use Illuminate\Support\Facades\Request;


class AjaxController extends Controller
{
    /**
     * New games day
     */
    public function newDay(Request $request): \Illuminate\Http\JsonResponse
    {

        // find tournament
        $tournament = Tournament::with('members')->findOrFail($request->tournamentId);

        // launch match(s)
        MatchDay::dispatch($tournament);

        return $this->returnDayData();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    private function returnDayData()
    {
        return response()->json(
            [
                'week'             => $this->week,
                'launches'         => $this->launches,
                'week_table'       => $this->calculateTable(Game::where('tournament_id',$this->tournament->id)->get()),
                'week_results'     => $this->prepareWeekMatchesResult($this->getMatchesByWeek($this->tournament, $this->week)),
                'week_productions' => $this->getWeekProduction($this->getMatchesSplitWeeks($this->getAllMatches())),
                'log' => $this->getLogMatches()
            ]
        );
    }
}
