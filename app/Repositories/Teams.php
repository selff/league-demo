<?php

namespace App\Repositories;

use App\Exceptions\StartException;
use App\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;


class Teams implements Interfaces\TeamsInterface
{

    /**
     * @throws \App\Exceptions\StartException
     */
    public function generate()
    {
        if (Storage::exists(Config::get('constants.options.football_clubs_list'))) {
            $data = Storage::get(Config::get('constants.options.football_clubs_list'));
            $clubs = json_decode($data, false);
            foreach($clubs->data ?? [] as $club) {
                Team::updateOrCreate(['name' => $club->name]);
            }
        } else {
            throw new StartException('Something Went Wrong. Check path file '.
                                     Config::get('constants.options.football_clubs_list'));
        }
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return Team::all();
    }

    /**
     * @throws \App\Exceptions\StartException
     */
    public function getRandomByCount(int $count)
    {
        $teams = $this->all();
        if (empty($teams)) {
            return [];
        }
        $random = $teams->random($count);
        return $random->all();
    }
}
