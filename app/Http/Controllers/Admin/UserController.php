<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MstRole;
use App\Models\MstUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = MstUser::with('role');

        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->cari . '%')
                  ->orWhere('email', 'like', '%' . $request->cari . '%');
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('role', function ($q) use ($request) {
                $q->whereRaw('LOWER(nama_role) = ?', [strtolower($request->role)]);
            });
        }

        $users = $query->orderBy('nama')->paginate(15)->withQueryString();
        $roles = MstRole::orderBy('nama_role')->get();

        return view('admin.user.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = MstRole::orderBy('nama_role')->get();
        return view('admin.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('mst_users', 'email')],
            'no_telp'  => ['nullable', 'digits_between:8,12'],
            'id_role'  => ['required', 'exists:mst_roles,id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'status'   => ['required', 'boolean'],
        ], [
            'no_telp.digits_between' => 'Nomor telepon harus berupa angka (8-12 digit), tanpa huruf atau simbol.',
        ]);

        MstUser::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'no_telp'  => $request->no_telp,
            'id_role'  => $request->id_role,
            'password' => Hash::make($request->password),
            'status'   => $request->status,
        ]);

        return redirect()->route('admin.user.index')
            ->with('success', 'Akun pengguna berhasil dibuat.');
    }

    public function edit($id)
    {
        $user = MstUser::findOrFail($id);
        $roles = MstRole::orderBy('nama_role')->get();
        return view('admin.user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = MstUser::findOrFail($id);

        $request->validate([
            'nama'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255', Rule::unique('mst_users', 'email')->ignore($user->id)],
            'no_telp' => ['nullable', 'digits_between:8,12'],
            'id_role' => ['required', 'exists:mst_roles,id'],
            'status'  => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'no_telp.digits_between' => 'Nomor telepon harus berupa angka (8-12 digit), tanpa huruf atau simbol.',
        ]);

        $data = [
            'nama'    => $request->nama,
            'email'   => $request->email,
            'no_telp' => $request->no_telp,
            'id_role' => $request->id_role,
            'status'  => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.user.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = MstUser::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
