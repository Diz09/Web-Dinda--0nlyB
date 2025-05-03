@extends('layouts.app_operator')

@section('content')
<div class="container">
    <h1 class="mb-4">Transaksi</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    {{-- <a href="#" class="btn btn-primary mb-3">Tambah Transaksi</a> --}}
    <a href="{{ route('operator.transaksi.create') }}" class="btn btn-sm btn-primary mb-3">Tambah Transaksi</a>
    

    <form method="GET" class="mb-4 flex items-center gap-2">
        <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="border px-2 py-1 rounded">
        <span>s/d</span>
        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="border px-2 py-1 rounded">
        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Filter</button>
    </form>    

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Waktu</th>
                    <th>Kode Transaksi</th>
                    <th>Kode Barang</th>
                    <th>Supplier</th>
                    <th>Nama</th>
                    <th>Qty</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $i => $trx)
                <tr>
                    <td class="p-2">{{ $i + 1 }}</td>
                    <td class="p-2">{{ \Carbon\Carbon::parse($trx['waktu'])->format('d-m-Y H:i') }}</td>
                    <td class="p-2">{{ $trx['kode_transaksi'] }}</td>
                    <td class="p-2">{{ $trx['kode_barang'] }}</td>
                    <td class="p-2">{{ $trx['supplier'] }}</td>
                    <td class="p-2">{{ $trx['nama_barang'] }}</td>
                    <td class="b-pri">{{ $trx['qty'] }}</td>
                    <td class="b-pri">Rp {{ number_format($trx['masuk'], 0, ',', '.') }}</td>
                    <td class="b-pri">Rp {{ number_format($trx['keluar'], 0, ',', '.') }}</td>
                    <td class="b-pri">Rp {{ number_format($trx['total'], 0, ',', '.') }}</td>
                    <td> <a href="{{ route('operator.transaksi.edit', $trx['id']) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('operator.transaksi.destroy', $trx['id']) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            
        </table>
    </div>
</div>
@endsection
