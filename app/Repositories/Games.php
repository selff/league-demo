<?php

namespace App\Repositories;

use App\Models\Game;
use App\Models\Tournament;
use App\Repositories\Tournaments;
use App\Repositories\Interfaces\GamesInterface;
use App\Services\MatchService;
use App\Services\TournamentService;

class Games implements GamesInterface
{

    private $tournament;

    /**
     * Games constructor.
     *
     * @param \App\Models\Tournament $tournament
     */
    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    /**
     * @throws \App\Exceptions\StartException
     */
    public function launch()
    {
        $week = $this->getCurrentWeek();
        $matchesByWeek = (new TournamentService)->getMatchesByWeek($this->tournament, $week);
        $matches = [];
        foreach ($matchesByWeek as $matchMembers) {

            $matchResult = (new MatchService)->generateNewResult();
            $match = $this->processMatchResult($matchMembers);

            $matches[] = Game::firstOrCreate(
                [
                    'tournament_id' => $match['tournament_id'],
                    'owner_team_id' => $match['owner_team_id'],
                    'guest_team_id' => $match['guest_team_id']
                ],
                [
                    'owner_goals'   => $matchResult[0],
                    'guest_goals'   => $matchResult[1],
                    'week' => $week
                ]
            );
        }
        return $matches;
    }

    /**
     * @param $matchMembers
     * @return array
     */
    private function processMatchResult($matchMembers): array
    {
        return [
            'tournament_id' => $this->tournament->id,
            'owner_team_id' => $matchMembers['owner_team_id'],
            'guest_team_id' => $matchMembers['guest_team_id'],
            'owner_goals'   => 0,
            'guest_goals'   => 0,
        ];
    }

    /**
     * @return mixed
     */
    public function findAll()
    {
        return Game::where('tournament_id', $this->tournament->id)->get();
    }

    /**
     * @return array
     */
    public function findAllByWeeks(): array
    {
        $weeks = [];
        $games = $this->findAll();
        foreach($games as $game) {
            $weeks[$game->week][] = $game;
        }

        return $weeks;
    }

    /**
     * @return int|mixed|void
     */
    public function getNextWeek()
    {
        $gameWeeks = $this->findAllByWeeks();
        if (empty($gameWeeks)) {

            return 0;
        }
        $weeks = array_keys($gameWeeks);

        return max($weeks)+1;
    }

    /**
     * @return int|mixed|void|null
     */
    public function getCurrentWeek()
    {
        $week = $this->getNextWeek();

        return $week ? $week-1 : null;
    }
}
