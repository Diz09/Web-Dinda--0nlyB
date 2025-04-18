@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Tambah Presensi Karyawan</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('presensi.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="karyawan_id" class="form-label">Nama Karyawan</label>
            <select name="karyawan_id" id="karyawan_id" class="form-select" required>
                <option value="">-- Pilih Karyawan --</option>
                @foreach($karyawans as $karyawan)
                    <option value="{{ $karyawan->id }}">{{ $karyawan->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control"
                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
        </div>

        <div class="mb-3">
            <label for="jam_masuk" class="form-label">Jam Masuk</label>
            <input type="time" name="jam_masuk" id="jam_masuk" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="jam_pulang" class="form-label">Jam Pulang</label>
            <input type="time" name="jam_pulang" id="jam_pulang" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('presensi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
