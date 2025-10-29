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
            'departemen' => 'required',
            'jabatan' => 'required',
        ]);

        Karyawan::create($validated + [
            'status_karyawan' => $request->status_karyawan ?? 'Kontrak',
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil ditambahkan!');
    }

    public function edit(Karyawan $karyawan)
    {
        return view('karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:karyawans,email,' . $karyawan->id,
            'departemen' => 'required',
            'jabatan' => 'required',
        ]);

        $karyawan->update($validated);

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil dihapus!');
    }
}
