<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Repositories\Interfaces\TournamentsInterface;
use App\Services\TournamentService;
use App\Events\TournamentStarted;

class TournamentController extends Controller
{

    private $tournaments;

    public function __construct(TournamentsInterface $tournaments)
    {
        $this->tournaments = $tournaments;
    }

    /**
     * Tournament start
     *
     */
    public function start(TournamentService $tournamentService)
    {
        $tournament = $tournamentService->create($this->tournaments);

        TournamentStarted::dispatch($tournament);

        return redirect()->to('tournament/' . $tournament->id);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Tournament $tournament
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Tournament $tournament): \Illuminate\Contracts\View\View
    {

        return view('tournament', [
            'week' => 0,
            'tournament' => $tournament,
            'members' => $tournament->members
        ]);
    }
}
