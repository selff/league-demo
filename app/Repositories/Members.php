<?php

namespace App\Repositories;

use App\Models\Tournament;

class Members implements Interfaces\MembersInterface
{
    /**
     * @throws \App\Exceptions\StartException
     */
    public function generate(Tournament $tournament)
    {
        if ($tournament->members()->count() === 0) {

            $teamsRandom = (new Teams)->getRandomByCount($tournament->members_count);

            foreach($teamsRandom as $team) {
                $tournament->members()->create(['team_name' => $team->name]);
            }
        }
    }
}
