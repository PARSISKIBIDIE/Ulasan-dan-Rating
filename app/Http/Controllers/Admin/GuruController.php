<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\User;
use App\Models\Survey;
use App\Models\Reply;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Data\TranscribedTeachers;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('user')->get();

        $previewTeachers = [];
        if ($gurus->isEmpty()) {
            $previewTeachers = TranscribedTeachers::teachers();
        }

        return view('admin.guru.index', compact('gurus', 'previewTeachers'));
    }

    /**
     * Import preview teachers into users+gurus tables so admin can edit/delete normally.
     */
    public function importPreview(Request $request)
    {
        $teachers = TranscribedTeachers::teachers();

        $created = 0;
        $skipped = 0;
        $nipBase = 1234567890;
        $i = 1;

        foreach ($teachers as $t) {
            $name = $t['name'];

            // skip if a user with same name already exists
            if (User::where('name', $name)->where('role','guru')->exists()) {
                $skipped++;
                $i++;
                continue;
            }

            // create user record (no email column in this schema)
            $user = User::create([
                'name' => $name,
                'password' => Hash::make('12345678'),
                'role' => 'guru'
            ]);

            // find next available NIP
            $nipCandidate = (string)($nipBase + $i);
            while (Guru::where('nip', $nipCandidate)->exists()) {
                $i++;
                $nipCandidate = (string)($nipBase + $i);
            }

            Guru::create([
                'user_id' => $user->id,
                'nip' => $nipCandidate,
                'mapel' => $t['mapel']
            ]);

            $created++;
            $i++;
        }

        return redirect('/admin/guru')->with('success', "Import selesai. Dibuat: $created; Dilewati: $skipped");
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required|min:6',
            'nip' => 'required',
            'mapel' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => 'guru'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('gurus', 'public');
        }

        Guru::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'mapel' => $request->mapel,
            'foto' => $fotoPath
        ]);

        return redirect('/admin/guru');
    }

    public function edit($id)
    {
        $guru = Guru::with('user')->findOrFail($id);
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'required',
            'nip' => 'required',
            'mapel' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $guru->user->update([
            'name' => $request->name
        ]);

        $dataUpdate = [
            'nip' => $request->nip,
            'mapel' => $request->mapel
        ];

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('gurus', 'public');
            $dataUpdate['foto'] = $fotoPath;
        }

        $guru->update($dataUpdate);

        return redirect('/admin/guru');
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        $guru->user->delete(); // otomatis hapus guru karena cascade
        return redirect('/admin/guru');
    }

    public function survey()
    {
    $guru = Guru::where('user_id', session('user_id'))->first();

    if (!$guru) {
        return redirect('/guru/dashboard')->with('error', 'Data guru tidak ditemukan');
    }

    if (Schema::hasTable('replies')) {
        $surveys = Survey::where('guru_id', $guru->id)
                    ->whereNotNull('komentar')
                    ->with(['murid', 'replies.guru.user', 'muridReplies.murid'])
                    ->latest()
                    ->get();
    } else {
        // replies table not created yet (migration not run)
        $surveys = Survey::where('guru_id', $guru->id)
                    ->whereNotNull('komentar')
                    ->with(['murid', 'muridReplies.murid'])
                    ->latest()
                    ->get();
    }

    return view('admin.guru.survey', compact('surveys', 'guru'));
}

    public function storeReply(Request $request)
    {
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'message' => 'required|string|max:2000'
        ]);

        $guru = Guru::where('user_id', session('user_id'))->first();
        if (!$guru) {
            return back()->with('error', 'Data guru tidak ditemukan');
        }

        $survey = Survey::findOrFail($request->survey_id);

        if ($survey->guru_id != $guru->id) {
            return back()->with('error', 'Anda hanya dapat membalas komentar untuk guru Anda sendiri');
        }

        Reply::create([
            'survey_id' => $survey->id,
            'guru_id' => $guru->id,
            'message' => $request->message
        ]);

        return redirect('/guru/survey')->with('success', 'Balasan berhasil dikirim');
    }


public function rating()
{
    $guru = Guru::where('user_id', session('user_id'))->first();

    if (!$guru) {
        return redirect('/guru/dashboard')->with('error', 'Data guru tidak ditemukan');
    }

    $ratings = Survey::where('guru_id', $guru->id)
                ->whereNotNull('rating')
                ->with(['murid', 'murid.murid'])
                ->latest()
                ->get();

   return view('admin.guru.rating', compact('ratings', 'guru'));
}
}