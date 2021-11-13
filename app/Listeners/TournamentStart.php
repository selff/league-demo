<?php

namespace App\Listeners;

use App\Events\TournamentStarted;
use App\Repositories\Members;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TournamentStart
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
     * @param TournamentStarted $event
     * @return void
     * @throws \App\Exceptions\StartException
     */
    public function handle(TournamentStarted $event)
    {

        // Fill tournament by members
        (new Members)->generateTournamentMembers($event->tournament);

    }
}
