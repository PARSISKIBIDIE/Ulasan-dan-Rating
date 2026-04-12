<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Test dengan murid yang memiliki kelas XII RPL 1
$murid = \App\Models\Murid::find(1);  // paris - XII RPL 1

echo "Murid Kelas: " . $murid->kelas . "\n";

$allJadwal = \App\Models\JadwalMengajar::where('kelas', $murid->kelas)->get();
echo "Total Jadwal untuk kelas: " . $allJadwal->count() . "\n";

// Sorting function sama seperti di controller
$sortJamByStartTime = function($jamList) {
    $jamList = array_unique($jamList);
    usort($jamList, function($a, $b) {
        $aStart = (int) str_replace('.', '', explode('-', trim($a))[0] ?? '0');
        $bStart = (int) str_replace('.', '', explode('-', trim($b))[0] ?? '0');
        return $aStart <=> $bStart;
    });
    return array_values($jamList);
};

// Test jamByDayAll dengan sorting
echo "\njamByDayAll dengan sorting:\n";
$jamByDayAll = $allJadwal->groupBy(function($item){
    return trim(strtolower($item->hari));
})->map(function($items) use ($sortJamByStartTime) {
    $jams = $items->pluck('jam')->unique()->all();
    return $sortJamByStartTime($jams);
})->toArray();

foreach($jamByDayAll as $day => $jams) {
    echo "  '$day': " . json_encode($jams) . "\n";
}

// Test semua jam diurutkan
echo "\nSemua jam (diurutkan):\n";
$allJams = $allJadwal->pluck('jam')->unique()->filter()->all();
$sortedJams = $sortJamByStartTime($allJams);
echo json_encode($sortedJams) . "\n";
