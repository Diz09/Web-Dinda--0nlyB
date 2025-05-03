@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 d-flex justify-between items-center">
        <span>Tambah Transaksi</span>
        <a href="{{ route('operator.transaksi.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form dropdown kategori dan tipe_barang --}}
    <div class="mb-3 d-flex gap-2 align-items-end">
        <div>
            <label for="kategori" class="form-label">Kategori Transaksi</label>
            <select name="kategori" id="kategori" class="form-control">
                <option value="pemasukan" {{ $kategori === 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                <option value="pengeluaran" {{ $kategori === 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
        </div>

        @if ($kategori === 'pengeluaran')
        <div>
            <label for="tipe_barang" class="form-label">Tipe Barang</label>
            <select name="tipe_barang" id="tipe_barang" class="form-control">
                <option value="mentah" {{ $tipe === 'mentah' ? 'selected' : '' }}>Mentah</option>
                <option value="dasar" {{ $tipe === 'dasar' ? 'selected' : '' }}>Dasar</option>
            </select>
        </div>
        @endif
    </div>

    {{-- Form utama transaksi --}}
    <form method="POST" action="{{ route('operator.transaksi.store') }}">
        @csrf

        {{-- Input autocomplete nama barang --}}
        <div class="mb-3">
            <label class="form-label">Pilih / Tambah Barang</label>
            <input list="daftar-barang" name="nama_barang" id="nama_barang" class="form-control" value="{{ old('nama_barang') }}" required>
            <datalist id="daftar-barang">
                @foreach ($barangs as $barang)
                    <option data-id="{{ $barang->id }}" value="{{ $barang->nama_barang }}"></option>
                @endforeach
            </datalist>
            <input type="hidden" name="barang_id" id="barang_id" value="{{ old('barang_id') }}">
        </div>

        @if ($kategori === 'pengeluaran')
            <input type="hidden" name="tipe_barang" value="{{ $tipe }}">
        @endif
        <input type="hidden" name="kategori" value="{{ $kategori }}">

        <div class="mb-3">
            <label class="form-label">Supplier</label>
            <select name="supplier_id" class="form-control">
                <option value="">-- Pilih Supplier --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="qty">Qty</label>
            <input type="number" name="qty" class="form-control" required min="1">
        </div>        

        <div class="mb-3">
            <label class="form-label">Jumlah (Rp)</label>
            <input type="number" name="jumlahRp" class="form-control" value="{{ old('jumlahRp') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Waktu Transaksi</label>
            <input type="datetime-local" name="waktu_transaksi" class="form-control" value="{{ old('waktu_transaksi', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

{{-- JavaScript untuk reload otomatis --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kategoriSelect = document.getElementById('kategori');
        const tipeSelect = document.getElementById('tipe_barang');

        function reloadWithParams() {
            const kategori = kategoriSelect.value;
            const tipe = tipeSelect?.value || '';
            const params = new URLSearchParams();

            if (kategori) params.set('kategori', kategori);
            if (kategori === 'pengeluaran' && tipe) {
                params.set('tipe_barang', tipe);
            }

            window.location.href = `{{ route('operator.transaksi.create') }}?${params.toString()}`;
        }

        kategoriSelect.addEventListener('change', reloadWithParams);
        if (tipeSelect) {
            tipeSelect.addEventListener('change', reloadWithParams);
        }
    });
</script>

@endsection
