{{-- @extends('layouts.app_operator') --}}
@extends('layouts.app_operator')

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
</div>

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('formEditBarang').addEventListener('submit', function(e) {
    e.preventDefault(); // Tahan submit dulu

    const form = this; // <- tambahkan ini

    const kategoriLama = '{{ $kategori }}';
    const kategoriBaru = document.getElementById('kategori').value;

    const melibatkanProduk = (kategoriLama === 'produk' || kategoriBaru === 'produk');

    if (melibatkanProduk && kategoriLama !== kategoriBaru) {
        Swal.fire({
            title: 'Peringatan!',
            text: 'Anda akan mengubah kategori yang melibatkan Produk. Yakin ingin melanjutkan?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let inputConfirm = document.createElement("input");
                inputConfirm.type = "hidden";
                inputConfirm.name = "confirm_produk";
                inputConfirm.value = "1";
                form.appendChild(inputConfirm);
                form.submit();
            }
        });
    } else {
        form.submit(); // Tidak melibatkan produk, langsung submit
    }
});

</script>
@endsection
