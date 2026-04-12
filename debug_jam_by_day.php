<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

\Illuminate\Support\Facades\Log::info('Testing MuridController output...');

// Simulasi session login
session_start();
$_SESSION['user_id'] = 1;  // Ganti dengan ID murid yang sebenarnya
$_SESSION['role'] = 'murid';

// Simulate request
$request = new \Illuminate\Http\Request();
$session = app('session.store');
$session->put('user_id', 1);
$session->put('role', 'murid');

// Call controller directly
$controller = new \App\Http\Controllers\MuridController();

// Test dengan murid yang memiliki kelas XII RPL 1
$murid = \App\Models\Murid::find(1);  // paris - XII RPL 1

echo "Murid Kelas: " . $murid->kelas . "\n";

$allJadwal = \App\Models\JadwalMengajar::where('kelas', $murid->kelas)->get();
echo "Total Jadwal untuk kelas: " . $allJadwal->count() . "\n";
echo "Jadwal by Hari:\n";

$grouped = $allJadwal->groupBy('hari');
foreach($grouped as $hari => $items) {
    echo "  $hari: " . $items->count() . " jadwal\n";
}

// Test jamByDayAll creation
$jamByDayAll = [];
$jamByDayAll = $allJadwal->groupBy(function($item){
    return trim(strtolower($item->hari));
})->map(function($items){
    return $items->pluck('jam')->unique()->values()->all();
})->toArray();

echo "\njamByDayAll keys: " . implode(', ', array_keys($jamByDayAll)) . "\n";
echo "jamByDayAll data:\n";
foreach($jamByDayAll as $day => $jams) {
    echo "  '$day': " . json_encode($jams) . "\n";
}
