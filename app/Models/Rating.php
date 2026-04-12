<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Survey;
use App\Models\Murid;

class Rating extends Model
{
    
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function murid()
    {
        return $this->belongsTo(Murid::class);
    }

}