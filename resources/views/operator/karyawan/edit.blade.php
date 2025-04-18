<!-- resources/views/operator/barang/edit.blade.php -->

@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Edit Karyawan</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" required value="{{ old('nama', $karyawan->nama) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Jabatan</label>
            <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $karyawan->jabatan) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Gaji per Jam</label>
            <input type="number" name="gaji_per_jam" class="form-control" required value="{{ old('gaji_per_jam', $karyawan->gaji_per_jam) }}">
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
