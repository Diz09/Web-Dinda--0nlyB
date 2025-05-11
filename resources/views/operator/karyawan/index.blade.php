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
                <th>No Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($karyawans as $i => $k)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $k->nama }}</td>
                <td>{{ $k->jenis_kelamin }}</td>
                <td>
                    @if($k->no_telepon)
                        {{ $k->no_telepon }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a href="{{ route('karyawan.edit', $k->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('karyawan.destroy', $k->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus karyawan ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>    
</div>
@endsection
