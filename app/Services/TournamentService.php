<?php

namespace App\Services;

use App\Events\TournamentStarted;
use App\Models\Tournament;
use App\Repositories\Interfaces\TournamentsInterface;

class TournamentService extends BaseService
{

    private $tournament;
    private $launches;
    private $matchesPerWeek;

    /**
     * @throws \App\Exceptions\StartException
     */
    public function create(TournamentsInterface $tournaments): Tournament
    {
        // Generate new tournament!
        $_tournament = $tournaments->create();

        TournamentStarted::dispatch($_tournament);

        return $_tournament;
    }

    /**
     * @param \App\Models\Tournament $tournament
     * @param $weekId
     * @return array
     */
    public function getMatchesByWeek(Tournament $tournament, $weekId): array
    {
        $this->tournament = $tournament;

        $matchesByWeeks = $this->setTournamentProperties();

        return $this->generateMatchesWeekData($matchesByWeeks[$weekId]);
    }

    /**
     * @return array
     */
    private function setTournamentProperties(): array
    {
        $countMembers = $this->tournament->members_count ?? 0;
        if ($countMembers % 2) {
            $this->matchesPerWeek = 1;
            $this->launches = $countMembers;
        } else {
            $this->matchesPerWeek = $countMembers / 2;
            $matchesCount = $countMembers * ($countMembers - 1);
            $this->launches = $matchesCount / ($this->matchesPerWeek ?? 1);
        }

        return $this->getMatchesSplitWeeks($this->makeSchedule());
    }

    /**
     * @param $matchesByWeek
     * @return array
     */
    private function generateMatchesWeekData($matchesByWeek): array
    {
        $weekGames = [];
        foreach ($matchesByWeek as $matchMembers) {
            $weekGames[] = [
                'tournament_id' => $this->tournament->id,
                'owner_team_name' => $this->tournament->members[$matchMembers[0]]->team_name,
                'guest_team_name' => $this->tournament->members[$matchMembers[1]]->team_name,
                'owner_team_id' => $this->tournament->members[$matchMembers[0]]->id,
                'guest_team_id' => $this->tournament->members[$matchMembers[1]]->id,
                'owner_goals'   => 0,
                'guest_goals'   => 0,
            ];
        }

        return $weekGames;
    }

    /**
     * Generate all matches
     * ex: [[Team1, Team2], [Team2, Team1], ...]
     *
     * @return array
     */
    private function makeSchedule(): array
    {

        $members = $this->tournament->members;
        $matches = [];
        foreach ($members as $i => $member1) {
            foreach ($members as $j => $member2) {
                if ($i !== $j && !in_array([$i, $j], $matches)) {
                    $matches[] = [$i, $j];
                    $matches[] = [$j, $i];
                }
            }
        }
        //dd($this->tournament->members);
        return $matches;
    }


    /**
     * Generate schedule matches by weeks
     * ex: [[[Team1, Team2], [Team3, Team4]], ...]
     *
     * @param array $matches
     * @return array
     */
    protected function getMatchesSplitWeeks(array $matches): array
    {
        $weeksSchedule = [];
        $i             = $j = 0;
        $match         = array_shift($matches); // [y1,y2]
        while ($match) {
            if ($j > 0) { // j=1
                for ($z = 0; $z < $j; $z++) { // z=0
                    foreach ($weeksSchedule[$i][$z] as $_team) { // [x1,x2]
                        if (in_array($_team, $match)) {
                            $j = 0;
                            $i++;
                            if ($i === $this->launches) {
                                $i = 0;
                            }
                            break 2;
                        }
                    }
                }
            }
            if (!isset($weeksSchedule[$i][$j])) {
                $weeksSchedule[$i][$j] = $match;
                $match = array_shift($matches);
            }
            $j++;
            if ($j === $this->matchesPerWeek) {
                $i++;
                $j = 0;
            }
            if ($i === $this->launches) {
                $i = 0;
            }
        }

        return $weeksSchedule;
    }

}
