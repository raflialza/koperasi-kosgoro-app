<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // --- BAGIAN LOGIKA REDIRECT DINAMIS ---
            $user = Auth::user(); // Ambil data user yang baru saja login

            if ($user->role === 'admin' || $user->role === 'super_admin') {
                // Jika admin atau super_admin, arahkan ke route 'home'
                return redirect()->intended(route('home'));
            }

            if ($user->role === 'anggota') {
                // Jika anggota, arahkan ke route profilnya
                return redirect()->intended(route('anggota.dataDiri'));
            }
            
            // Fallback default jika ada role lain (meskipun seharusnya tidak terjadi)
            return redirect('/');
        }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
}


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
