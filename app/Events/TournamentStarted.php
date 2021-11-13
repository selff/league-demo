<?php

namespace App\Events;

use App\Models\Tournament;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TournamentStarted
{
    use Dispatchable, SerializesModels;

    /**
     * @var \App\Models\Tournament
     */
    public $tournament;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

}
