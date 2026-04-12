<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalMengajar extends Model
{
    //
    protected $fillable = [
    'guru_id',
    'mapel',
    'kelas',
    'hari',
    'jam'
];

public function guru()
{
    return $this->belongsTo(Guru::class);
}
}
