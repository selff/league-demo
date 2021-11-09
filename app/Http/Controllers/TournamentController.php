<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Repositories\Interfaces\TournamentsInterface;
use App\Services\TournamentService;


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

        return redirect()->to('tournament/' . $tournament->id);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id): \Illuminate\Contracts\View\View
    {
        $tournament = Tournament::findOrFail($id);
        return view('tournament', [
            'week' => 0,
            'tournament' => $tournament,
            'members' => $tournament->members
        ]);
    }
}
