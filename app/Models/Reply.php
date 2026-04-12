<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'guru_id',
        'message',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
