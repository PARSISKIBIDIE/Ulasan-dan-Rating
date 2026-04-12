<?php

namespace App\Models;
use App\Models\Survey;
use App\Models\Question;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    //
    public function survey()
{
    return $this->belongsTo(Survey::class);
}

public function question()
{
    return $this->belongsTo(Question::class);
}
}
