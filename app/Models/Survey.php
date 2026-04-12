<?php

namespace App\Models;
use App\Models\User;
use App\Models\Guru;
use App\Models\SurveyAnswer;
use App\Models\Rating;
use App\Models\JadwalMengajar;
use App\Models\Reply;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = [
        'murid_id',
        'guru_id',
        'jadwal_id',
        'rating',
        'komentar'
    ];

    public function murid()
{
    return $this->belongsTo(User::class, 'murid_id');
}

public function guru()
{
    return $this->belongsTo(Guru::class);
}
    
        public function replies()
        {
            return $this->hasMany(Reply::class);
        }

        public function muridReplies()
        {
            return $this->hasMany(MuridReply::class, 'survey_id');
        }

public function answers()
{
    return $this->hasMany(SurveyAnswer::class);
}

public function rating()
{
    return $this->hasOne(Rating::class);
}

public function jadwal()
{
    return $this->belongsTo(JadwalMengajar::class, 'jadwal_id');
}
}
