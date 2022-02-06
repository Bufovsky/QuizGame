<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Score extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'game_id', 'score'];


    /**
     * Set relations.
     */
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
