<?php

namespace App\Models;
use App\Models\SurveyAnswer;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    public function answers()
{
    return $this->hasMany(SurveyAnswer::class);
}
}
