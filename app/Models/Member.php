<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'tournament_id',
        'team_name'
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
