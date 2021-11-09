<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $table = 'tournaments';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'members_count'
    ];

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

}
