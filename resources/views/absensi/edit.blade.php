@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    <h3 class="fw-bold mb-3">✏️ Edit Absensi</h3>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">

            <form action="{{ route('absensi.update', $data->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Karyawan</label>
                        <select name="karyawan_id" class="form-control" required>
                            <option value="">Pilih</option>
                            @foreach ($karyawans as $k)
                                <option value="{{ $k->id }}" 
                                    {{ $data->karyawan_id == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" 
                               value="{{ $data->tanggal }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jam Masuk</label>
                        <input type="time" name="jam_masuk" class="form-control" 
                               value="{{ $data->jam_masuk }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jam Pulang</label>
                        <input type="time" name="jam_pulang" class="form-control" 
                               value="{{ $data->jam_pulang }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="Hadir" {{ $data->status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="Izin" {{ $data->status == 'Izin' ? 'selected' : '' }}>Izin</option>
                            <option value="Sakit" {{ $data->status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="Alpha" {{ $data->status == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                        </select>
                    </div>

                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('absensi.index') }}" class="btn btn-secondary me-2">Kembali</a>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
