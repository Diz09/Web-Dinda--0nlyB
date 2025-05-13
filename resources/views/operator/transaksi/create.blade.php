@extends('layouts.app_operator')

@section('content')
    <h3>Buat Transaksi</h3>

    {{-- Form untuk memilih kategori transaksi --}}
    <form method="GET" action="{{ route('operator.transaksi.create') }}">
        <label for="kategori">Kategori Transaksi</label>
        <select name="kategori" id="kategori" onchange="this.form.submit()">
            <option value="pemasukan" {{ $kategori == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
            <option value="pengeluaran" {{ $kategori == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
        </select>
    </form>

    <br>

    {{-- Form untuk menyimpan transaksi --}}
    <form method="POST" action="{{ route('operator.transaksi.store') }}">
        @csrf

        {{-- Hidden input agar tetap membawa kategori ke POST --}}
        <input type="hidden" name="kategori" value="{{ $kategori }}">

        {{-- Pilih Barang --}}
        <label for="barang_id">Pilih Barang</label>
        <select name="barang_id" id="barang_id" required>
            <option value="">-- Pilih Barang --</option>
            @foreach ($barangs as $barang)
                <option value="{{ $barang->id }}" data-harga="{{ $barang->harga }}">{{ $barang->nama_barang }}</option>
            @endforeach
        </select>

        {{-- Pilih Supplier --}}
        <label for="supplier_id">Pilih Supplier</label>
        <select name="supplier_id" id="supplier_id" required>
            <option value="">-- Pilih Supplier --</option>
            @foreach ($suppliers as $supplier)
                {{-- Filter berdasarkan kategori --}}
                @if(($kategori === 'pemasukan' && $supplier->konsumen) || ($kategori === 'pengeluaran' && $supplier->pemasok))
                    <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                @endif
            @endforeach
        </select>

        {{-- Jumlah (Qty) --}}
        <label for="qtyHistori">Jumlah (Qty)</label>
        <input type="number" name="qtyHistori" id="qtyHistori" required>

        {{-- Satuan --}}
        <label for="satuan">Satuan</label>
        <select name="satuan" id="satuan" required>
            <option value="">-- Pilih Satuan --</option>
            <option value="ton">Ton</option>
            <option value="kg">Kg</option>
            <option value="g">Gram</option>
        </select>

        {{-- Jumlah Harga (Rp) --}}
        <label for="jumlahRp">Jumlah Harga (Rp)</label>
        <input type="number" name="jumlahRp" id="jumlahRp" {{ $kategori == 'pemasukan' ? '' : 'required' }}>

        {{-- Tampilkan waktu transaksi --}}
        <label for="waktu">Waktu Transaksi</label>
        <input id='waktu' type="text" readonly value="{{ \Carbon\Carbon::now()->format('d F Y H:i') }}">

        <br><br>
        <button type="submit">Simpan Transaksi</button>
    </form>

    <script src="{{ asset('js/transaksi.js') }}"></script>
@endsection
