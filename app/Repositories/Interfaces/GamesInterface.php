<?php

namespace App\Repositories\Interfaces;

use App\Models\Team;
use App\Models\Tournament;

interface GamesInterface
{
    public function launch(Tournament $tournament);
}
