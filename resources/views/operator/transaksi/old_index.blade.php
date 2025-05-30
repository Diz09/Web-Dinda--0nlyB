@extends('layouts.app_operator')

@section('content')
<div class="container">
    <h1 class="mb-4">Transaksi</h1>
    
    {{-- <a href="#" class="btn btn-primary mb-3">Tambah Transaksi</a> --}}
    {{-- <a href="{{ route('operator.transaksi.create') }}" class="btn btn-sm btn-primary mb-3">Tambah Transaksi</a> --}}
    <!-- Tombol Tambah -->
    <button type="button" class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
        Tambah Transaksi
    </button>    

    <form method="GET" class="mb-4 flex items-center gap-2" style="text-align-last: end">
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
                    <td> 
                        {{-- <a href="{{ route('operator.transaksi.edit', $trx['id']) }}" class="btn btn-warning btn-sm">Edit</a> --}}
                        <!-- Tombol Edit -->
                        <button 
                            class="btn btn-primary btn-edit" 
                            data-id="{{ $trx['id'] }}"
                            data-kategori="{{ $trx['kategori'] }}"
                            data-barang_id="{{ $trx['barang_id'] }}"
                            data-supplier_id="{{ $trx['supplier_id'] }}"
                            data-qty="{{ $trx['qty'] }}"
                            data-satuan="{{ $trx['satuan'] }}"
                            data-jumlahrp="{{ $trx['jumlahRp'] }}"
                            data-waktu="{{ $trx['waktu_transaksi'] }}"
                            >
                            Edit
                        </button>

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

@include('operator.transaksi.create')
@include('operator.transaksi.edit')
<script const semuaBarang = @json($barangs); ></script>
<script src="{{ asset('js/transaksi.js') }}"></script>

@endsection
