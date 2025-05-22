<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = ['username', 'room_id', 'level', 'wins'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function gameResults()
    {
        return $this->hasMany(GameResult::class);
    }
}
