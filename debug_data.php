<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== DayRelease Data ===\n";
$days = \App\Models\DayRelease::all()->toArray();
foreach ($days as $d) {
    echo sprintf("ID: %d, Day: '%s', Released: %d\n", $d['id'], $d['day'], $d['released']);
}

echo "\n=== JadwalMengajar Sample (First 20) ===\n";
$jadwals = \App\Models\JadwalMengajar::limit(20)->get();
foreach ($jadwals as $j) {
    echo sprintf("ID: %d, Kelas: %s, Hari: '%s', Jam: '%s'\n", $j->id, $j->kelas, $j->hari, $j->jam);
}

echo "\n=== Unique Hari in JadwalMengajar ===\n";
$uniqueHari = \App\Models\JadwalMengajar::distinct('hari')->pluck('hari')->toArray();
foreach ($uniqueHari as $h) {
    echo "- '$h'\n";
}

echo "\n=== Count by Kelas ===\n";
$byKelas = \App\Models\JadwalMengajar::distinct('kelas')->pluck('kelas')->toArray();
foreach ($byKelas as $k) {
    $count = \App\Models\JadwalMengajar::where('kelas', $k)->count();
    echo "Kelas '$k': $count jadwal\n";
}
