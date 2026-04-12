<?php

namespace App\Models;
use App\Models\User;
use App\Models\Survey;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    //
     protected $fillable = [
        'user_id',
        'nip',
        'mapel',
        'foto'
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}

public function surveys()
{
    return $this->hasMany(Survey::class);
}
}
