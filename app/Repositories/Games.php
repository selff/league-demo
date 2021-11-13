<?php

namespace App\Repositories;

use App\Models\Game;
use App\Models\Tournament;
use App\Repositories\Interfaces\GamesInterface;
use App\Services\MatchService;
use App\Services\TournamentService;

class Games implements GamesInterface
{

    public $tournament;

    /**
     * @throws \App\Exceptions\StartException
     */
    public function launch(Tournament $tournament)
    {
        $matchesByWeek = (new TournamentService)->getMatchesByWeek($tournament, 0);
        $this->tournament = $tournament;
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
                ]
            );
        }
        return $matches;
    }

    /**
     * @param $matchesByWeek
     * @return array
     * @throws \App\Exceptions\StartException
     */
    private function processMatchResult($matchMembers): array
    {
        $tournament = $this->tournament->toArray();

        return [
            'tournament_id' => $tournament['id'],
            'owner_team_id' => $matchMembers['owner_team_id'],
            'guest_team_id' => $matchMembers['guest_team_id'],
            'owner_goals'   => 0,
            'guest_goals'   => 0,
        ];
    }
}
