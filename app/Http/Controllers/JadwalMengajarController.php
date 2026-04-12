<?php
namespace App\Http\Controllers;

use App\Models\JadwalMengajar;
use App\Models\Guru;
use Illuminate\Http\Request;

class JadwalMengajarController extends Controller
{
    public function index()
    {
        $jadwal = JadwalMengajar::with('guru')->get();
        
        // Daftar kelas yang tersedia
        $daftar_kelas = [
            'X RPL 1', 'X RPL 2', 'X MPLB', 'X AK', 'X TKJ',
            'XI RPL 1', 'XI RPL 2', 'XI AK', 'XI TKJ', 'XI MPLB',
            'XII RPL 1', 'XII RPL 2', 'XII MPLB', 'XII AK', 'XII TKJ'
        ];
        
        // Kelompokkan jadwal per kelas and sort within each group
        $jadwal_per_kelas = [];
        foreach ($daftar_kelas as $kelas) {
            $group = $jadwal->where('kelas', $kelas)->values();
            $jadwal_per_kelas[$kelas] = $this->sortJadwalCollection($group);
        }
        
        return view('admin.jadwal.index', compact('jadwal_per_kelas', 'daftar_kelas'));
    }

    public function create()
    {
        $guru = Guru::all();

        // build global jam options from existing jadwal (unique, sorted by start time)
        $allJams = JadwalMengajar::pluck('jam')->unique()->filter()->values()->all();

        $sortJamByStartTime = function($jamList) {
            $jamList = array_values(array_filter(array_unique($jamList)));
            usort($jamList, function($a, $b) {
                $aStart = (int) preg_replace('/\D/', '', trim(explode('-', $a)[0] ?? ''));
                $bStart = (int) preg_replace('/\D/', '', trim(explode('-', $b)[0] ?? ''));
                return $aStart <=> $bStart;
            });
            return $jamList;
        };

        try {
            $sortedJams = $sortJamByStartTime($allJams);
            $jamOptions = collect($sortedJams);
        } catch (\Throwable $e) {
            $jamOptions = collect($allJams);
        }

        return view('admin.jadwal.create', compact('guru', 'jamOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required',
            'mapel' => 'required',
            'kelas' => 'required',
            'hari' => 'required',
            'jam' => 'required',
        ]);

        JadwalMengajar::create($request->all());

        return redirect('/admin/jadwal/show/' . urlencode($request->kelas))->with('success','Jadwal berhasil ditambahkan');
    }

    public function edit($id)
{
    $jadwal = JadwalMengajar::findOrFail($id);
    $guru = Guru::all();

        // Build jam options same as create() so edit form uses identical dropdown
        $allJams = JadwalMengajar::pluck('jam')->unique()->filter()->values()->all();
        $sortJamByStartTime = function($jamList) {
            $jamList = array_values(array_filter(array_unique($jamList)));
            usort($jamList, function($a, $b) {
                $aStart = (int) preg_replace('/\D/', '', trim(explode('-', $a)[0] ?? ''));
                $bStart = (int) preg_replace('/\D/', '', trim(explode('-', $b)[0] ?? ''));
                return $aStart <=> $bStart;
            });
            return $jamList;
        };

        try {
            $sortedJams = $sortJamByStartTime($allJams);
            $jamOptions = collect($sortedJams);
        } catch (\Throwable $e) {
            $jamOptions = collect($allJams);
        }

        return view('admin.jadwal.edit', compact('jadwal','guru','jamOptions'));
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'guru_id' => 'required',
            'mapel' => 'required',
            'kelas' => 'required',
            'hari' => 'required',
            'jam' => 'required',
        ]);

        $jadwal = JadwalMengajar::findOrFail($id);

        $jadwal->update([
            'guru_id' => $request->guru_id,
            'mapel' => $request->mapel,
            'kelas' => $request->kelas,
            'hari' => $request->hari,
            'jam' => $request->jam
        ]);

        return redirect('/admin/jadwal/show/' . urlencode($request->kelas))->with('success','Jadwal berhasil diupdate');
    }

public function destroy($id)
{
    $jadwal = JadwalMengajar::findOrFail($id);
    $kelas = $jadwal->kelas;
    $jadwal->delete();

    return redirect('/admin/jadwal/show/' . urlencode($kelas))->with('success','Jadwal berhasil dihapus');
}

public function show($kelas)
{
    $kelas = urldecode($kelas);
    $jadwal = JadwalMengajar::with('guru')->where('kelas', $kelas)->get();
    // sort jadwal for display (hari order + jam start)
    $jadwal = $this->sortJadwalCollection($jadwal);
    $guru = Guru::all();
    // build global jam options for the inline add form
    $allJams = JadwalMengajar::pluck('jam')->unique()->filter()->values()->all();
    $sortJamByStartTime = function($jamList) {
        $jamList = array_values(array_filter(array_unique($jamList)));
        usort($jamList, function($a, $b) {
            $aStart = (int) preg_replace('/\D/', '', trim(explode('-', $a)[0] ?? ''));
            $bStart = (int) preg_replace('/\D/', '', trim(explode('-', $b)[0] ?? ''));
            return $aStart <=> $bStart;
        });
        return $jamList;
    };

    try {
        $sortedJams = $sortJamByStartTime($allJams);
        $jamOptions = collect($sortedJams);
    } catch (\Throwable $e) {
        $jamOptions = collect($allJams);
    }

    return view('admin.jadwal.show', compact('jadwal', 'guru', 'kelas', 'jamOptions'));
}

    /**
     * Sort a collection of JadwalMengajar by weekday order and jam start time.
     * Returns a Collection.
     */
    private function sortJadwalCollection($collection)
    {
        $orders = ['Senin'=>1,'Selasa'=>2,'Rabu'=>3,'Kamis'=>4,'Jumat'=>5,'Sabtu'=>6,'Minggu'=>7];

        $arr = $collection instanceof \Illuminate\Support\Collection ? $collection->values()->all() : (array) $collection;

        usort($arr, function($a, $b) use ($orders) {
            $hariA = trim($a->hari ?? '');
            $hariB = trim($b->hari ?? '');
            $ordA = $orders[$hariA] ?? 999;
            $ordB = $orders[$hariB] ?? 999;
            if ($ordA !== $ordB) return $ordA <=> $ordB;

            $aStart = (int) preg_replace('/\D/', '', trim(explode('-', $a->jam ?? '')[0] ?? ''));
            $bStart = (int) preg_replace('/\D/', '', trim(explode('-', $b->jam ?? '')[0] ?? ''));
            return $aStart <=> $bStart;
        });

        return collect($arr);
    }
}
