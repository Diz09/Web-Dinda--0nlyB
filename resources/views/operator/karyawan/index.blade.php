@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Daftar Karyawan</h3>

    <button class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
        Tambah Karyawan
    </button>

    {{-- Modal Create --}}
    @include('operator.karyawan.create')

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
                <td>{{ $i + 1 }}</td>
                <td>{{ $k->nama }}</td>
                <td>{{ $k->jenis_kelamin }}</td>
                <td>{{ $k->no_telepon ?? '-' }}</td>
                <td>
                    {{-- Tombol Edit --}}
                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $k->id }}">
                        Edit
                    </button>

                    {{-- Modal Edit --}}
                    @include('operator.karyawan.edit', ['karyawan' => $k])

                    {{-- Tombol Hapus --}}
                    <form action="{{ route('karyawan.destroy', $k->id) }}" method="POST" class="d-inline formDelete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // SweetAlert sukses
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: @json(session('success')),
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    // SweetAlert error validasi
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            html: `{!! implode('<br>', $errors->all()) !!}`,
        });
    @endif
</script>
<script src="{{ asset('js/karyawan.js') }}"></script>
@endsection
