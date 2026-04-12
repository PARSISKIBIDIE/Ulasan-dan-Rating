<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MuridReply extends Model
{
    use HasFactory;

    protected $table = 'murid_replies';

    protected $fillable = [
        'survey_id',
        'murid_id',
        'message'
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function murid()
    {
        return $this->belongsTo(\App\Models\User::class, 'murid_id');
    }
}
