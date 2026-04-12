<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\Guru;
use App\Models\Rating;
use App\Models\DayRelease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function hasilSurvey()
    {
        // Ambil semua survey dengan relasi
        $surveys = Survey::with(['murid', 'guru.user', 'murid.murid'])->get();

        // Ambil semua rating dengan relasi
        $ratings = Rating::with(['murid.user', 'survey.guru.user'])->get();

        // Kelompokkan survey berdasarkan guru
        $surveysByGuru = $surveys->groupBy('guru_id');

        // Hitung statistik untuk setiap guru
        $guruStats = collect();
        foreach ($surveysByGuru as $guruId => $guruSurveys) {
            $guru = Guru::with('user')->find($guruId);
            if ($guru) { // Pastikan guru ada
                $guruStats->put($guruId, [
                    'guru' => $guru,
                    'total_survey' => $guruSurveys->count(),
                    'avg_rating' => round($guruSurveys->avg('rating'), 1),
                    'surveys' => $guruSurveys
                ]);
            }
        }

        return view('admin.hasil-survey', compact('guruStats', 'ratings'));
    }

    /**
     * Show admin dashboard with day release controls.
     */
    public function dashboard()
    {
        $dayReleases = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('day_releases')) {
            $dayReleases = DayRelease::orderBy('id')->get();
            Log::debug('dayReleases fetched:', $dayReleases->toArray());
        }

        return view('dashboard.admin', compact('dayReleases'));
    }

    /**
     * Toggle released state for a given day (e.g. 'Senin').
     */
    public function toggleDayRelease(Request $request, $day)
    {
        $dayRaw = trim(strtolower($day));

        if (!\Illuminate\Support\Facades\Schema::hasTable('day_releases')) {
            return redirect('/admin/dashboard')->with('error', 'Tabel day_releases belum dibuat. Jalankan migrasi.');
        }

        // case-insensitive match (trim + lower) to be robust to input variations
        $dr = DayRelease::whereRaw('LOWER(TRIM(day)) = ?', [$dayRaw])->first();
        if (! $dr) {
            return redirect('/admin/dashboard')->with('error', 'Hari tidak ditemukan: ' . $day);
        }

        $dr->released = ! $dr->released;
        $dr->save();

        return redirect('/admin/dashboard')->with('success', 'Status hari ' . $dr->day . ' diupdate.');
    }

    /**
     * Reset all surveys so students can submit again.
     */
    public function resetSurveys(Request $request)
    {
        $kelas = $request->input('kelas');
        $hari = $request->input('hari');

        $query = \App\Models\Survey::query();

        if ($kelas) {
            $query->whereHas('jadwal', function($q) use ($kelas) {
                $q->where('kelas', $kelas);
            });
        }

        if ($hari) {
            $query->whereHas('jadwal', function($q) use ($hari) {
                $q->where('hari', $hari);
            });
        }

        $count = $query->count();
        if ($count === 0) {
            return redirect('/admin/dashboard')->with('info', 'Tidak ada survey yang sesuai untuk direset.');
        }

        $query->delete();

        $msg = 'Berhasil mereset ' . $count . ' survey.';
        if ($kelas && $hari) $msg .= ' (Kelas: ' . $kelas . ', Hari: ' . $hari . ')';
        elseif ($kelas) $msg .= ' (Kelas: ' . $kelas . ')';
        elseif ($hari) $msg .= ' (Hari: ' . $hari . ')';

        return redirect('/admin/dashboard')->with('success', $msg);
    }

    /**
     * Run the surveys:reset command immediately (admin-only HTTP trigger)
     */
    public function runResetNow(Request $request)
    {
        try {
            Artisan::call('surveys:reset');
            $output = Artisan::output();
            return redirect('/admin/dashboard')->with('success', 'Reset otomatis dijalankan. ' . trim($output));
        } catch (\Exception $e) {
            Log::error('Failed to run surveys:reset via HTTP: ' . $e->getMessage());
            return redirect('/admin/dashboard')->with('error', 'Gagal menjalankan reset otomatis: ' . $e->getMessage());
        }
    }
}