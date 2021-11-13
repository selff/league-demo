<?php

namespace App\Services;

use App\Exceptions\StartException;

class MatchService extends BaseService
{

    /**
     * Pick a random goals based on weights
     * ex: [2, 1]
     *
     * @return array
     * @throws \App\Exceptions\StartException
     */
    public function generateNewResult(): array
    {
        $goalsValues                        = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $probabilitiesOwnerTeamGoalsWeights = [9, 10, 8, 7, 4, 2, 1, 1, 0, 0];
        $probabilitiesGuestTeamGoalsWeights = [8, 7, 6, 5, 2, 1, 1, 0, 0, 0];

        return [
            $this->weightedRandomSimple($goalsValues, $probabilitiesOwnerTeamGoalsWeights),
            $this->weightedRandomSimple($goalsValues, $probabilitiesGuestTeamGoalsWeights),
        ];
    }

    /**
     * weighted_random_simple()
     * Pick a random item based on weights.
     * https://w-shadow.com/blog/2008/12/10/fast-weighted-random-choice-in-php/
     *
     * @param array $values  Array of elements to choose from
     * @param array $weights An array of weights. Weight must be a positive number.
     * @return mixed Selected element.
     */
    private function weightedRandomSimple(array $values, array $weights)
    {
        $count = count($values);
        $i = 0;
        $n = 0;
        $num = mt_rand(0, array_sum($weights));
        while($i < $count) {
            $n += $weights[$i];
            if($n >= $num) {
                break;
            }
            $i++;
        }
        return $values[$i];
    }
}
