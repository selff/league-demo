<?php

namespace App\Repositories\Interfaces;

use App\Models\Team;
use App\Models\Tournament;

interface MembersInterface
{
    public function create(array $data);
    public function generate(Tournament $tournament);
}
