<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Murid extends Model
{
    protected $fillable = [
        'user_id',
        'kelas'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}