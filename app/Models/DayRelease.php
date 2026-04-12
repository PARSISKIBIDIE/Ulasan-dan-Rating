<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayRelease extends Model
{
    protected $fillable = [
        'day',
        'released',
    ];
}
