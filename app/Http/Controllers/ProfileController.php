<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user()->load('role');
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user()->load('role');
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama'    => ['required', 'string', 'max:255'],
            'no_telp' => ['nullable', 'digits_between:1,13'],
            'email'   => ['required', 'email', Rule::unique('mst_users', 'email')->ignore($user->id)],
            'password'       => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'no_telp.digits_between' => 'Nomor telepon harus berupa angka (1-13 digit), tanpa huruf atau simbol.',
        ]);

        $data = [
            'nama'    => $request->nama,
            'no_telp' => $request->no_telp,
            'email'   => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
