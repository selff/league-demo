<?php

namespace App\Repositories\Interfaces;

interface TeamsInterface
{
    public function generate();
    public function all();
    public function getRandomByCount(int $count);
}
