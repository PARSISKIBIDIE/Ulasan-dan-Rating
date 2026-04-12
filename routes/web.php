<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\JadwalMengajarController;
use App\Http\Controllers\MuridController;
use App\Http\Controllers\SurveyController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ================= HALAMAN UTAMA =================
Route::get('/', function () {

    if (!session()->has('user_id')) {
        return redirect('/register');
    }

    if (session('role') == 'admin') {
        return redirect('/admin/dashboard');
    } elseif (session('role') == 'guru') {
        return redirect('/guru/dashboard');
    } else {
        return redirect('/murid/dashboard');
    }

});

// ================= REGISTER =================
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

// ================= LOGIN =================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// ================= LOGOUT =================
Route::get('/logout', [AuthController::class, 'logout']);

// ================= DASHBOARD (PAKAI MIDDLEWARE) =================
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->middleware('role:admin');

Route::get('/guru/dashboard', function () {
    return view('dashboard.guru');
})->middleware('role:guru');

Route::get('/murid/dashboard', [MuridController::class, 'dashboard'])->middleware('role:murid');
Route::post('/murid/replies/mark-read', [MuridController::class, 'markRepliesRead'])->middleware('role:murid');
Route::post('/murid/reply', [MuridController::class, 'storeReply'])->middleware('role:murid');

//dashboard admin

Route::prefix('admin')->middleware('role:admin')->group(function () {

    Route::get('/guru', [GuruController::class, 'index']);
    Route::get('/guru/create', [GuruController::class, 'create']);
    Route::post('/guru/import-preview', [GuruController::class, 'importPreview']);
    Route::post('/guru/store', [GuruController::class, 'store']);
    Route::get('/guru/edit/{id}', [GuruController::class, 'edit']);
    Route::post('/guru/update/{id}', [GuruController::class, 'update']);
    Route::get('/guru/delete/{id}', [GuruController::class, 'destroy']);

    Route::get('/hasil-survey', [AdminController::class, 'hasilSurvey']);
    Route::post('/reset-surveys', [AdminController::class, 'resetSurveys']);
    Route::post('/reset-surveys/now', [AdminController::class, 'runResetNow']);
    Route::post('/day-release/toggle/{day}', [AdminController::class, 'toggleDayRelease']);

});

//jadwal mengajar
Route::get('/admin/jadwal', [JadwalMengajarController::class,'index']);
Route::get('/admin/jadwal/show/{kelas}', [JadwalMengajarController::class,'show']);
Route::get('/admin/jadwal/create', [JadwalMengajarController::class,'create']);
Route::post('/admin/jadwal/store', [JadwalMengajarController::class,'store']);
Route::get('/admin/jadwal/edit/{id}', [JadwalMengajarController::class,'edit']);
Route::post('/admin/jadwal/update/{id}', [JadwalMengajarController::class,'update']);
Route::get('/admin/jadwal/delete/{id}', [JadwalMengajarController::class,'destroy']);

//survey
Route::get('/survey/{id}', [SurveyController::class,'create']);
Route::post('/survey/store', [SurveyController::class,'store']);
// AJAX endpoint to check rating eligibility (returns JSON)
Route::get('/ajax/check-eligibility/{id}', [SurveyController::class,'checkEligibility'])->middleware('role:murid');

// guru melihat survey
Route::get('/guru/survey',[GuruController::class,'survey']);
Route::get('/guru/rating',[GuruController::class,'rating']);
Route::post('/guru/survey/reply', [GuruController::class, 'storeReply'])->middleware('role:guru');