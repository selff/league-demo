<?php

namespace App\Services;

use App\Models\Tournament;
use App\Repositories\Members;
use App\Repositories\Interfaces\TournamentsInterface;

class TournamentService extends BaseService
{
    /**
     * @param \App\Repositories\Interfaces\TournamentsInterface $tournaments
     * @return \App\Models\Tournament
     * @throws \App\Exceptions\StartException
     */
    public function create(TournamentsInterface $tournaments): Tournament
    {
        // Generate new tournament!
        $tournament = $tournaments->create();

        // Fill tournament by members
        $membersRepo = new Members;
        $membersRepo->generate($tournament);

        return $tournament;
    }


}
