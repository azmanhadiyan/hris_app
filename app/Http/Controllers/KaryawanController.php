<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::query();

        // Filter berdasarkan field yang diinput
        if ($request->nip) {
            $query->where('nip', 'like', "%{$request->nip}%");
        }
        if ($request->nama) {
            $query->where('nama_lengkap', 'like', "%{$request->nama}%");
        }
        if ($request->email) {
            $query->where('email', 'like', "%{$request->email}%");
        }
        if ($request->departemen) {
            $query->where('departemen', 'like', "%{$request->departemen}%");
        }
        if ($request->jabatan) {
            $query->where('jabatan', 'like', "%{$request->jabatan}%");
        }

        // Masih tetap paginasi, tapi data sudah terfilter
        $karyawans = $query->paginate(10)->withQueryString();

        return view('karyawan.index', compact('karyawans'));
    }


    public function create()
    {
        return view('karyawan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|unique:karyawans',
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:karyawans',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
            'jabatan' => 'required',
            'departemen' => 'required',
            'tanggal_masuk' => 'required|date',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable',
            'gaji' => 'nullable|numeric',
            'foto' => 'nullable|image|max:2048',
        ]);

        // upload foto
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('karyawan', 'public');
        }

        $validated['status_karyawan'] = $request->status_karyawan ?? 'Kontrak';

        Karyawan::create($validated);

        return redirect()
            ->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil ditambahkan!');
    }


    public function edit(Karyawan $karyawan)
    {
        return view('karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $validated = $request->validate([
            'nip' => 'required|unique:karyawans,nip,' . $id,
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:karyawans,email,' . $id,
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
            'jabatan' => 'required',
            'departemen' => 'required',
            'tanggal_masuk' => 'required|date',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable',
            'gaji' => 'nullable|numeric',
            'foto' => 'nullable|image|max:2048',
        ]);

        // foto upload
        if ($request->hasFile('foto')) {

            // hapus foto lama
            if ($karyawan->foto && file_exists(storage_path('app/public/' . $karyawan->foto))) {
                unlink(storage_path('app/public/' . $karyawan->foto));
            }

            // upload baru
            $validated['foto'] = $request->file('foto')->store('karyawan', 'public');
        }

        $validated['status_karyawan'] = $request->status_karyawan ?? $karyawan->status_karyawan;

        $karyawan->update($validated);

        return redirect()
            ->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui!');
    }


    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil dihapus!');
    }
}
