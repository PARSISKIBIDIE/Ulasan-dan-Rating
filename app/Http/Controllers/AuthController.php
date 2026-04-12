<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ================= REGISTER =================

    public function showRegister()
    {
        return view('auth.register');
    }

public function register(Request $request)
{
    $request->validate([
        'name' => 'required',
        'password' => 'required|min:6|confirmed',
        'kelas' => 'required'
    ]);

    $user = User::create([
        'name' => $request->name,
        'password' => Hash::make($request->password),
        'role' => 'murid'
    ]);

    \App\Models\Murid::create([
        'user_id' => $user->id,
        'kelas' => $request->kelas
    ]);

    return redirect('/login')->with('success', 'Register berhasil!');
}

    // ================= LOGIN =================

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('name', $request->name)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Nama atau password salah!');
        }

        // Simpan session manual
        session([
            'user_id' => $user->id,
            'role' => $user->role,
            'name' => $user->name
        ]);

        // Redirect berdasarkan role
        if ($user->role == 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role == 'guru') {
            return redirect('/guru/dashboard');
        } else {
            return redirect('/murid/dashboard');
        }
    }

    // ================= LOGOUT =================

    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}