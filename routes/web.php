<?php

use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tournament/{tournamentId}', [TournamentController::class, 'show']);
Route::post('/tournament/start', [TournamentController::class, 'start']);
Route::post('/game/last', [AjaxController::class, 'lastDay']);
Route::post('/game/new', [AjaxController::class, 'newDay']);
Route::post('/game/all', [AjaxController::class, 'allDays']);
Route::post('/game/edit', [AjaxController::class, 'editMatch']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
