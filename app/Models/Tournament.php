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

    public function numberOfMatchesPerWeek()
    {
        if ($this->mebers_count === 0) {
            return 0;
        } elseif($this->mebers_count % 2) {
            return $this->mebers_count;
        } else {
            return $this->mebers_count / 2;
        }
    }

}
