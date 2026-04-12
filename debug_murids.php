<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== All Murids ===\n";
$murids = \App\Models\Murid::with('user')->limit(10)->get();
foreach ($murids as $m) {
    $userName = $m->user ? $m->user->name : 'NO USER';
    echo sprintf("ID: %d, User: %s (ID %d), Kelas: %s\n", $m->id, $userName, $m->user_id, $m->kelas);
}

echo "\n=== Murids with valid class (not 'Belum Ditentukan') ===\n";
$validMurids = \App\Models\Murid::where('kelas', '!=', 'Belum Ditentukan')->limit(5)->get();
foreach ($validMurids as $m) {
    $userName = $m->user ? $m->user->name : 'NO USER';
    echo sprintf("ID: %d, User: %s (ID %d), Kelas: %s\n", $m->id, $userName, $m->user_id, $m->kelas);
}
