<?php

namespace App\Repositories\Interfaces;

use App\Models\Team;
use App\Models\Tournament;

interface MembersInterface
{
    public function generate(Tournament $tournament);
}
