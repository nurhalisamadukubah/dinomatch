<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'gallery_id'];

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function gameResults()
    {
        return $this->hasMany(GameResult::class);
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
