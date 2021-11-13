<?php

namespace App\Listeners;

use App\Events\MatchDay;
use App\Repositories\Games;

class MatchesPlay
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param MatchDay $event
     * @return void
     * @throws \App\Exceptions\StartException
     */
    public function handle(MatchDay $event)
    {
        // Play the games
        (new Games)->launch($event->tournament);
    }
}
