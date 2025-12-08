@extends('layouts.app')

@section('content')
<style>
    .form-card {
        background: #fff;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 5px 18px rgba(0,0,0,.08);
        max-width: 900px;
        margin: auto;
    }
    .form-label { font-weight: 600; font-size: 14px; }
    .form-control { border-radius: 8px; font-size: 14px; }
    .btn-green {
        background: #7EA039;
        color: white;
        border-radius: 8px;
        padding: 8px 20px;
        border: none;
    }
</style>

<div class="container py-4">
    <div class="form-card">
        <h5 class="fw-semibold text-success mb-4">Edit Data Karyawan</h5>

        <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-control" value="{{ $karyawan->nip }}" required>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="{{ $karyawan->nama_lengkap }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $karyawan->email }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ $karyawan->no_hp }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ $karyawan->alamat }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Departemen</label>
                    <select name="departemen" class="form-control" required>
                        <option value="">Pilih</option>

                        @php
                            $departemens = [
                                "Digital", "IT", "Akunting", "IE", "Warehouse",
                                "Cutting", "Sewing", "QA", "Packing"
                            ];
                        @endphp

                        @foreach ($departemens as $dept)
                            <option value="{{ $dept }}" {{ $karyawan->departemen == $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="col-md-6">
                    <label class="form-label">Jabatan</label>
                    <select name="jabatan" class="form-control" required>
                        <option value="">Pilih</option>

                        @php
                            $jabatans = ["Operator", "Staff", "Leader", "Manajer"];
                        @endphp

                        @foreach ($jabatans as $jab)
                            <option value="{{ $jab }}" {{ $karyawan->jabatan == $jab ? 'selected' : '' }}>
                                {{ $jab }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="Laki-laki" {{ $karyawan->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $karyawan->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status Karyawan</label>
                    <select name="status_karyawan" class="form-control">
                        <option value="Kontrak" {{ $karyawan->status_karyawan == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                        <option value="Tetap" {{ $karyawan->status_karyawan == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                        <option value="Magang" {{ $karyawan->status_karyawan == 'Magang' ? 'selected' : '' }}>Magang</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" class="form-control" value="{{ $karyawan->tanggal_masuk }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ $karyawan->tanggal_lahir }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control" value="{{ $karyawan->tempat_lahir }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Gaji</label>
                    <input type="number" name="gaji" class="form-control" value="{{ $karyawan->gaji }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Foto Karyawan</label>
                    <input type="file" name="foto" class="form-control">

                    @if($karyawan->foto)
                        <div class="mt-2">
                            <img src="{{ asset('storage/'.$karyawan->foto) }}" width="80" class="rounded">
                        </div>
                    @endif
                </div>

            </div>

            <div class="d-flex justify-content-end mt-4 gap-2">
                <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn-green">Update</button>
            </div>

        </form>
    </div>
</div>
@endsection
