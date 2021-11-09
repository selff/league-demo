<?php

namespace App\Repositories;

use App\Models\Member;
use App\Models\Tournament;

class Members implements Interfaces\MembersInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Member::updateOrCreate($data);
    }

    /**
     * @throws \App\Exceptions\StartException
     */
    public function generate(Tournament $tournament)
    {
        $teamsRepo = new Teams;
        $teamsRepo->generate();
        $teamsRandom = $teamsRepo->getRandomByCount($tournament->members_count);

        foreach($teamsRandom as $team) {
            $this->create(['tournament_id' => $tournament->id, 'team_name' => $team->name]);
        }
    }
}
