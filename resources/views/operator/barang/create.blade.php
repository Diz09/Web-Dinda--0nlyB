@php
    use Carbon\Carbon;
    $defaultExp = Carbon::now()->addYear()->toDateString();
@endphp

{{-- @extends('layouts.app_operator')

@section('content')
<h2>Tambah Barang Produk</h2>

<form action="{{ route('barang.store') }}" method="POST">
    @csrf

    <div>
        <label>Kode Produk</label>
        <input type="text" name="kode" value="{{ $newKode }}" readonly>
    </div>

    <div>
        <label>Nama Barang</label>
        <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" required>
    </div>

    <div>
        <label>Qty (Stok)</label>
        <input type="number" name="qty" value="{{ old('qty') ?? 0 }}" min="0" required>
    </div>

    <div>
        <label>Harga (Rp)</label>
        <input type="number" name="harga" value="{{ old('harga') }}" min="0" required>
    </div>

    <div>
        <label>Expired</label>
        <input type="date" name="exp" value="{{ old('exp', $defaultExp) }}" required>
    </div>

    <button type="submit">Simpan</button>
</form>
@endsection --}}

<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('barang.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode Produk</label>
                        <input type="text" class="form-control" name="kode" value="{{ $newKode }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" name="nama_barang" value="{{ old('nama_barang') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="qty" class="form-label">Qty (Stok)</label>
                        <input type="number" class="form-control" name="qty" value="{{ old('qty') ?? 0 }}" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control" name="harga" value="{{ old('harga') }}" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="exp" class="form-label">Expired</label>
                        <input type="date" class="form-control" name="exp" value="{{ old('exp', $defaultExp) }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>