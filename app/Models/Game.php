<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $table = 'games';

    protected $primaryKey = 'id';

    protected $fillable = [
        'tournament_id', 'owner_team_id', 'guest_team_id', 'owner_goals', 'guest_goals', 'week'
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function ownerTeam()
    {
        return $this->hasOne(Member::class, 'owner_team_id');
    }

    public function guestTeam()
    {
        return $this->hasOne(Member::class, 'guest_team_id');
    }
}
