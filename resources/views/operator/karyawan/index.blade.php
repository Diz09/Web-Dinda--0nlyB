@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Daftar Karyawan</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('karyawan.create') }}" class="btn btn-primary mb-3">+ Tambah Karyawan</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($karyawans as $i => $k)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $k->nama }}</td>
                <td>{{ $k->jenis_kelamin }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>    
</div>
@endsection
