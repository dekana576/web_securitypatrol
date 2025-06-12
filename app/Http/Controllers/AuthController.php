<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function Login(Request $request)
    {
        // Validasi input username dan password
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
    
        // Ambil data request username dan password saja
        $credential = $request->only('username', 'password');
    
        // Cek jika data username dan password valid
        if (Auth::attempt($credential)) {
            // Kalau berhasil, simpan data user di variabel $user
            $user = Auth::user();
    
            if ($user->role === 'admin') {
                return redirect()->intended('user');
            }
    
            if ($user->role === 'security') {
                return redirect()->intended('security');
            }
    
            // Jika role tidak valid, logout user dan tampilkan pesan error
            Auth::logout();
            return redirect('/login')
                ->withInput()
                ->withErrors(['login_gagal' => 'Anda tidak memiliki akses yang valid.']);
        }
    
        // Jika autentikasi gagal, kembalikan ke halaman login
        return redirect('/login')
            ->withInput()
            ->withErrors(['login_gagal' => 'These credentials do not match our records.']);
    }
    

    public function logout(Request $request)
    {
        // Logout dengan menghapus session dan autentikasi
        $request->session()->flush();
        Auth::logout();

        // Kembali ke halaman login
        return redirect('login');
    }

   
}
