<?php

namespace App\Repositories;

use App\Models\Tournament;
use App\Repositories\Interfaces\TournamentsInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class Tournaments implements TournamentsInterface
{
    public function create(): Tournament
    {
        return Tournament::create(
            [
                'title' => 'League '. Str::upper(Str::random(3)),
                'members_count' => Config::get('constants.options.group_members_count'),
            ]
        );
    }

    public function find($id): Tournament
    {
        return Tournament::with('members')->findOrFail($id);
    }
}
