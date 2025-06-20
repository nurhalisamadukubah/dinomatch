<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image', 'dificulty'];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
