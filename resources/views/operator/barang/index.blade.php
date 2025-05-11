@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">
        Data Barang 
        @if($filter === 'produk') - Produk
        @elseif($filter === 'mentah') - Mentah
        @elseif($filter === 'dasar') - Dasar
        @endif
    </h3>
    

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- <a href="{{ route('barang.create') }}" class="btn btn-sm btn-primary mb-3">Tambah Produk</a> --}}
    @if(request('filter') == 'produk')
        <a href="{{ route('barang.create') }}" class="btn btn-sm btn-primary mb-3">Tambah Produk</a>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            @if(request('filter'))
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Qty</th>
                        <th>Exp</th>
                        <th>Harga</th>
                        <th>Update Stock</th> {{-- Tambahan --}}
                        <th>Aksi</th> {{-- Kolom edit & hapus --}} 
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangs as $i => $barang)
                        @php
                            $kategori = '-';
                            $kode = '-';
                            if ($barang->pendukung) {
                                $kategori = 'Pendukung';
                                $kode = $barang->pendukung->kode;
                            } elseif ($barang->produk) {
                                $kategori = 'Produk';
                                $kode = $barang->produk->kode;
                            }
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $kode }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $kategori }}</td>
                            <td>{{ $barang->qty ?? '-' }}</td>
                            <td>{{ $barang->exp ? \Carbon\Carbon::parse($barang->exp)->format('d-m-Y') : '-' }}</td>
                            <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('barang.updateQty', $barang->id) }}" method="POST" class="d-flex">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="qty" value="{{ $barang->qty }}" class="form-control form-control-sm me-1" style="width:80px">
                                    <button type="submit" class="btn btn-sm btn-warning">-</button>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-sm btn-info">Edit</a>
                                <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                                </form>
                            </td>                            
                        </tr>
                    @endforeach
                </tbody>
            @else
                <thead class="table-dark">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Exp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangs as $barang)
                        @php
                            $kode = $barang->produk->kode ?? $barang->pendukung->kode ?? '-';
                        @endphp
                        <tr>
                            <td>{{ $kode }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->exp ? \Carbon\Carbon::parse($barang->exp)->format('d-m-Y') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
    </div>
    
</div>
@endsection
