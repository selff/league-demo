<?php

namespace App\Repositories;

use App\Models\Tournament;

class Members implements Interfaces\MembersInterface
{
    /**
     * @throws \App\Exceptions\StartException
     */
    public function generateTournamentMembers(Tournament $tournament)
    {
        if (!$tournament->members()->count()) {

            $teamsRandom = (new Teams)->getRandomByCount($tournament->members_count);

            foreach($teamsRandom as $team) {
                $tournament->members()->create(['team_name' => $team->name]);
            }
        }
    }
}
