<?php

namespace App\Services;

use App\Models\JadwalMengajar;
use App\Models\Murid;
use App\Models\Survey;

class RatingService
{
    /**
     * Check whether a user (murid) is allowed to give rating for the given jadwal.
     * Returns array with 'eligible' => bool and 'message' => string.
     */
    public static function checkEligibility($user_id, $jadwal_id)
    {
        $jadwal = JadwalMengajar::find($jadwal_id);

        if (!$jadwal) {
            return ['eligible' => false, 'message' => 'Jadwal tidak ditemukan'];
        }

        $murid = Murid::where('user_id', $user_id)->first();

        if (!$murid) {
            return ['eligible' => false, 'message' => 'Data murid tidak ditemukan'];
        }

        $allJadwal = JadwalMengajar::where('kelas', $murid->kelas)->get();

        $surveyJadwalIds = Survey::where('murid_id', $user_id)->pluck('jadwal_id')->toArray();

        // if user already submitted survey for this jadwal, return already given
        if (in_array($jadwal->id, $surveyJadwalIds)) {
            return ['eligible' => false, 'already' => true, 'message' => 'Rating dan ulasan sudah diberikan'];
        }

        $byHari = $allJadwal->groupBy('hari');

        $getKey = function ($jam) {
            if (preg_match('/\d+/', $jam, $m)) {
                return intval($m[0]);
            }
            return strtolower($jam);
        };

        // 1) Same-day: require previous jam(s) to be rated first
        if (isset($byHari[$jadwal->hari])) {
            $dayCollection = $byHari[$jadwal->hari]->sortBy(function ($item) use ($getKey) {
                return $getKey($item->jam);
            })->values();

            $ids = $dayCollection->pluck('id')->toArray();
            $pos = array_search($jadwal->id, $ids);

            if ($pos !== false && $pos > 0) {
                $previousIds = array_slice($ids, 0, $pos);
                foreach ($previousIds as $pid) {
                    if (!in_array($pid, $surveyJadwalIds)) {
                        return ['eligible' => false, 'message' => 'Berikan rating di jam sebelumnya terlebih dahulu'];
                    }
                }
            }
        }

        // 2) Previous-day: require all ratings for previous day before allowing this day
        $weekdays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $idx = array_search($jadwal->hari, $weekdays);

        if ($idx !== false) {
            $prev = $weekdays[($idx - 1 + 7) % 7];
            if (isset($byHari[$prev]) && $byHari[$prev]->count() > 0) {
                $prevIds = $byHari[$prev]->pluck('id')->toArray();
                foreach ($prevIds as $pid) {
                    if (!in_array($pid, $surveyJadwalIds)) {
                        return ['eligible' => false, 'message' => 'Lengkapi rating semua jam di ' . $prev . ' terlebih dahulu'];
                    }
                }
            }
        }

        return ['eligible' => true, 'message' => ''];
    }
}
