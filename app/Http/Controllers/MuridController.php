<?php

namespace App\Http\Controllers;

use App\Models\Murid;
use App\Models\JadwalMengajar;
use App\Models\Survey;
use App\Models\Reply;
use App\Services\RatingService;
use App\Models\DayRelease;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\MuridReply;

class MuridController extends Controller
{
    public function dashboard()
    {
        $user_id = session('user_id');

        if (! $user_id) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $murid = Murid::where('user_id', $user_id)->first();

        if (! $murid) {
            // Jika tidak ada data Murid, buat placeholder sehingga halaman tidak crash.
            // Kelas di-set sementara; admin atau user perlu memperbarui.
            $murid = Murid::create([
                'user_id' => $user_id,
                'kelas' => 'Belum Ditentukan'
            ]);

            session()->flash('warning', 'Profil murid belum lengkap — kelas di-set sementara. Hubungi admin untuk memperbarui.');
        }

        $jadwalQuery = JadwalMengajar::with('guru.user')
                    ->where('kelas', $murid->kelas);

        // Jika table day_releases ada, tampilkan hanya hari yang telah dikeluarkan oleh admin.
        if (Schema::hasTable('day_releases')) {
            $releasedDays = DayRelease::where('released', true)->get()->map(function($r){
                return trim(strtolower($r->day));
            })->filter()->values()->all();

            if (!empty($releasedDays)) {
                // use case-insensitive comparison for jadwal.hari (trim + lower)
                $jadwalQuery->where(function($q) use ($releasedDays) {
                    foreach ($releasedDays as $rd) {
                        $q->orWhereRaw('LOWER(TRIM(hari)) = ?', [$rd]);
                    }
                });
            } else {
                // Tidak ada hari yang dikeluarkan -> tampilkan tidak ada jadwal
                $jadwalQuery->whereRaw('0 = 1');
            }
        }

        $jadwal = $jadwalQuery->get();

        // compute eligibility per jadwal for this murid
        $eligibilities = [];

        foreach ($jadwal as $j) {
            $eligibilities[$j->id] = RatingService::checkEligibility($user_id, $j->id);
        }

        // fetch unread replies and read replies (paginated) to this murid's surveys (only if table exists)
        if (Schema::hasTable('replies')) {
            $surveyIds = Survey::where('murid_id', $user_id)->pluck('id');

            $replies = Reply::whereIn('survey_id', $surveyIds)
                        ->whereNull('read_at')
                        ->with(['guru.user','survey'])
                        ->latest()
                        ->get();

            $readReplies = Reply::whereIn('survey_id', $surveyIds)
                        ->whereNotNull('read_at')
                        ->with(['guru.user','survey'])
                        ->latest()
                        ->paginate(10, ['*'], 'read_page');
        } else {
            $replies = collect();
            $readReplies = null;
        }

        // prepare jam data: keep per-class jadwal for jam-by-day grouping,
        // but build a global jamOptions list from all jadwal so all classes share the same jam dropdown.
        $allJadwal = JadwalMengajar::where('kelas', $murid->kelas)->get();
        $globalJams = JadwalMengajar::pluck('jam')->unique()->filter()->values()->all();

        // function to sort jam by start time (HH.MM - HH.MM format)
        $sortJamByStartTime = function($jamList) {
            $jamList = array_unique($jamList);  // remove duplicates
            usort($jamList, function($a, $b) {
                $aStart = (int) str_replace('.', '', explode('-', trim($a))[0] ?? '0');
                $bStart = (int) str_replace('.', '', explode('-', trim($b))[0] ?? '0');
                return $aStart <=> $bStart;
            });
            return array_values($jamList);
        };

        // build sorted unique jam options (global across all classes, sort by start time)
        $jamOptions = collect();
        try {
            $sortedJams = $sortJamByStartTime($globalJams);
            $jamOptions = collect($sortedJams);
        } catch (\Throwable $e) {
            $jamOptions = collect($globalJams);
        }

        // jam per hari from all jadwal (sorted by start time) - used to populate jam dropdown when a hari is selected
        $jamByDayAll = [];
        try {
            // normalize keys to lower(trim(hari)) so client-side matching is simpler and robust
            $jamByDayAll = $allJadwal->groupBy(function($item){
                return trim(strtolower($item->hari));
            })->map(function($items) use ($sortJamByStartTime) {
                $jams = $items->pluck('jam')->unique()->all();
                return $sortJamByStartTime($jams);
            })->toArray();
            
            \Illuminate\Support\Facades\Log::debug('jamByDayAll for class ' . $murid->kelas, $jamByDayAll);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error building jamByDayAll:', ['error' => $e->getMessage()]);
            $jamByDayAll = [];
        }

        // build mapel options grouped by hari (unique & sorted)
        $mapelByDay = [];
        try {
            $grouped = $jadwal->groupBy('hari');
            foreach ($grouped as $hari => $items) {
                $mapelByDay[$hari] = $items->pluck('mapel')->unique()->sort()->values()->all();
            }
        } catch (\Throwable $e) {
            $mapelByDay = [];
        }

        $weekdayMap = [1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu',7=>'Minggu'];
        $todayName = $weekdayMap[date('N')] ?? null;
        $mapelToday = $todayName && isset($mapelByDay[$todayName]) ? $mapelByDay[$todayName] : [];

        return view('dashboard.murid', compact('murid','jadwal','eligibilities','replies','readReplies','jamOptions','mapelByDay','mapelToday','todayName','jamByDayAll'));
    }

    /**
     * Mark all replies for this murid as read.
     */
    public function markRepliesRead(Request $request)
    {
        $user_id = session('user_id');

        if (! Schema::hasTable('replies')) {
            return response()->json(['success' => true, 'updated' => 0]);
        }

        $surveyIds = Survey::where('murid_id', $user_id)->pluck('id');

        if ($surveyIds->isEmpty()) {
            return response()->json(['success' => true, 'updated' => 0]);
        }

        $updated = Reply::whereIn('survey_id', $surveyIds)
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);

        return response()->json(['success' => true, 'updated' => $updated]);
    }

    /**
     * Store a reply from a murid (student) to a survey (reply to guru)
     */
    public function storeReply(Request $request)
    {
        $user_id = session('user_id');
        if (! $user_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'message' => 'required|string|max:2000'
        ]);

        $survey = Survey::findOrFail($request->survey_id);

        if ($survey->murid_id != $user_id) {
            return response()->json(['success' => false, 'message' => 'Anda hanya dapat membalas komentar milik Anda.'], 403);
        }

        if (! Schema::hasTable('murid_replies')) {
            // If migration not applied, return error
            return response()->json(['success' => false, 'message' => 'Fitur balasan belum tersedia di server.'], 500);
        }

        $mr = MuridReply::create([
            'survey_id' => $survey->id,
            'murid_id' => $user_id,
            'message' => $request->message
        ]);

        return response()->json(['success' => true, 'reply' => [
            'id' => $mr->id,
            'message' => $mr->message,
            'created_at' => $mr->created_at->toDateTimeString(),
            'murid_name' => auth()->user()->name ?? null
        ]]);
    }
}