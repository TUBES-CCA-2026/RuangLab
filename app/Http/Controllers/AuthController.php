<?php

namespace App\Http\Controllers;

use App\Models\MstRole;
use App\Models\MstUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->status != 1) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda belum aktif. Hubungi admin.']);
            }

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            if ($user->isAslab()) {
                return redirect()->intended(route('aslab.dashboard'));
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {

    $request->validate([
    'nama' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email', 'max:255', Rule::unique('mst_users', 'email')],
    'no_telp' => ['nullable', 'string', 'max:20'],
    'password' => ['required', 'string', 'min:8', 'confirmed'],
    ], [
        
    'email.unique' => 'Akun sudah digunakan.',
    'password.required' => 'Password wajib diisi.',
    'password.min' => 'Password minimal 8 karakter.',
    'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);
       
        $role = MstRole::whereRaw('LOWER(nama_role) = ?', ['mahasiswa'])->first()
            ?? MstRole::first();

        $user = MstUser::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => Hash::make($request->password),
            'id_role' => $role?->id,
            'status' => 1,
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Pendaftaran berhasil! Selamat datang di RuangLab.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
