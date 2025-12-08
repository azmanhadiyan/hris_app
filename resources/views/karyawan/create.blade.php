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
    .form-label {
        font-weight: 600;
        font-size: 14px;
    }
    .form-control {
        border-radius: 8px;
        font-size: 14px;
    }
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
        <h5 class="fw-semibold text-success mb-4">Tambah Data Karyawan</h5>

        <form action="{{ route('karyawan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-control" required>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-control">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Departemen</label>
                    <select name="departemen" class="form-control" required>
                        <option value="">Pilih</option>
                        <option value="Digital">Digital</option>
                        <option value="IT">IT</option>
                        <option value="Akunting">Akunting</option>
                        <option value="IE">IE</option>
                        <option value="Warehouse">Warehouse</option>
                        <option value="Cutting">Cutting</option>
                        <option value="Cutting">Cutting</option>
                        <option value="Sewing">Sewing</option>
                        <option value="QA">QA</option>
                        <option value="Packing">Packing</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jabatan</label>
                    <select name="jabatan" class="form-control" required>
                        <option value="">Pilih</option>
                        <option value="Operator">Operator</option>
                        <option value="Staff">Staff</option>
                        <option value="Leader">Leader</option>
                        <option value="Manajer">Manajer</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="">Pilih</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status Karyawan</label>
                    <select name="status_karyawan" class="form-control">
                        <option value="">Default: Kontrak</option>
                        <option value="Kontrak">Kontrak</option>
                        <option value="Tetap">Tetap</option>
                        <option value="Magang">Magang</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Gaji</label>
                    <input type="number" name="gaji" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Foto Karyawan</label>
                    <input type="file" name="foto" class="form-control">
                </div>

            </div>

            <div class="d-flex justify-content-end mt-4 gap-2">
                <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn-green">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
