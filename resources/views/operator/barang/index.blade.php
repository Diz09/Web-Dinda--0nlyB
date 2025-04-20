@extends('layouts.app_operator')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Barang</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <a href="{{ route('barang.create') }}" class="btn btn-primary mb-3">Tambah Barang</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangs as $barang)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $barang->kode }}</td>
                    <td>{{ $barang->nama }}</td>
                    <td>{{ $barang->kategori }}</td>
                    <td>{{ $barang->harga }}</td>
                    <td>{{ $barang->stok }}</td>
                    <td>
                        <div class="d-flex flex-column gap-1">
                            <!-- Kurangi stok satu per satu -->
                            <form action="{{ route('barang.kurang', $barang->id) }}" method="POST" class="d-flex gap-1 align-items-center">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">-</button>
                                <input type="number" name="jumlah" value="1" min="1" class="form-control form-control-sm" style="width: 60px;">
                            </form>
                    
                            <!-- Tambah stok satu per satu -->
                            <form action="{{ route('barang.tambah', $barang->id) }}" method="POST" class="d-flex gap-1 align-items-center">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm">+</button>
                                <input type="number" name="jumlah" value="1" min="1" class="form-control form-control-sm" style="width: 60px;">
                            </form>
                    
                            <!-- Tombol Hapus -->
                            <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>                    
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
