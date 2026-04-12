<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\JadwalMengajar;
use App\Models\Murid;
use App\Services\RatingService;
use Illuminate\Http\Request;

class SurveyController extends Controller
{

public function create($id)
{
    $jadwal = JadwalMengajar::with('guru.user')->find($id);

    $existingSurvey = null;
    $userId = session('user_id');
    if ($userId) {
        $existingSurvey = Survey::where('murid_id', $userId)->where('jadwal_id', $id)->first();
    }

    return view('survey.create', compact('jadwal', 'existingSurvey'));
}

public function store(Request $request)
{
    $murid = Murid::where('user_id', session('user_id'))->first();

    if (!$murid) {
        return redirect()->back()->with('error', 'Data murid tidak ditemukan');
    }

        // check eligibility before creating survey
        $check = RatingService::checkEligibility(session('user_id'), $request->jadwal_id);

        if (!$check['eligible']) {
            return redirect('/murid/dashboard')->with('error', $check['message']);
        }

    Survey::create([
        'murid_id' => session('user_id'),
        'guru_id' => $request->guru_id,
        'jadwal_id' => $request->jadwal_id,
        'rating' => $request->rating,
        'komentar' => $request->komentar
    ]);

    return redirect('/murid/dashboard')->with('success','Survey berhasil dikirim');
}

public function checkEligibility($id)
{
    $userId = session('user_id');

    if (!$userId) {
        return response()->json(['eligible' => false, 'message' => 'User tidak ditemukan'], 401);
    }

    $result = RatingService::checkEligibility($userId, $id);

    return response()->json($result);
}

}