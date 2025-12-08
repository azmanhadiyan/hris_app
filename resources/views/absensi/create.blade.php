@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    <h3 class="fw-bold mb-3">➕ Tambah Absensi</h3>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">

            <form action="{{ route('absensi.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                
                    <div class="col-md-6">
                        <label class="form-label">Karyawan</label>
                        <select name="karyawan_id" class="form-control" required>
                            <option value="">Pilih</option>
                            @foreach ($karyawans as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jam Masuk</label>
                        <input type="time" name="jam_masuk" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jam Pulang</label>
                        <input type="time" name="jam_pulang" class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="Hadir">Hadir</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Alpha">Alpha</option>
                        </select>
                    </div>

                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('absensi.index') }}" class="btn btn-secondary me-2">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
