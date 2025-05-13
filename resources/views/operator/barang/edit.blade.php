{{-- @extends('layouts.app_operator') --}}
{{-- @extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3>Edit Barang</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('barang.update', $barang->id) }}" method="POST" id="formEditBarang">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama_barang" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" required>
        </div>

        @php
            $kategori = 'lainnya';
            if ($barang->produk) $kategori = 'produk';
            elseif ($barang->mentah) $kategori = 'mentah';
            elseif ($barang->dasar) $kategori = 'dasar';
        @endphp

        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select name="kategori" id="kategori" class="form-select" required>
                <option value="mentah" {{ $kategori == 'mentah' ? 'selected' : '' }}>Mentah</option>
                <option value="dasar" {{ $kategori == 'dasar' ? 'selected' : '' }}>Dasar</option>
                <option value="produk" {{ $kategori == 'produk' ? 'selected' : '' }}>Produk</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="exp" class="form-label">Tanggal Expired</label>
            <input type="date" class="form-control" id="exp" name="exp" value="{{ old('exp', $barang->exp ? date('Y-m-d', strtotime($barang->exp)) : '') }}">
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga (Rp)</label>
            <input type="number" class="form-control" id="harga" name="harga" value="{{ old('harga', $barang->harga) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div> --}}

<div class="modal fade" id="editModal{{ $barang->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $barang->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $barang->id }}">Edit Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form class="formEditBarang" method="POST" action="{{ route('barang.update', $barang->id) }}">
            @csrf
            @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                    </div>

                    @php
                        $kategori = 'lainnya';
                        if ($barang->produk) $kategori = 'produk';
                        elseif ($barang->pendukung) $kategori = 'pendukung'; // Sesuaikan jika hanya ada pendukung
                    @endphp

                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select kategori-select" required>
                            <option value="pendukung" {{ $kategori == 'pendukung' ? 'selected' : '' }}>Pendukung</option>
                            <option value="produk" {{ $kategori == 'produk' ? 'selected' : '' }}>Produk</option>
                        </select>
                        <input type="hidden" class="kategori-lama" value="{{ $kategori }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Expired</label>
                        <input type="date" class="form-control" name="exp" value="{{ old('exp', $barang->exp ? date('Y-m-d', strtotime($barang->exp)) : '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control" name="harga" value="{{ old('harga', $barang->harga) }}" required>
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
