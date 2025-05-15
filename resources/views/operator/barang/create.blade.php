@php
    use Carbon\Carbon;
    $defaultExp = Carbon::now()->addYear()->toDateString();
@endphp

<div class="modal modal-ct fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah {{ ucfirst($filter) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('barang.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="filter" value="{{ $filter }}">
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode {{ ucfirst($filter) }}</label>
                        <input id="kode" type="text" class="form-control" name="kode" value="{{ $newKode }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input id="nama_barang" type="text" class="form-control" name="nama_barang" value="{{ old('nama_barang') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="qty" class="form-label">Qty (Stok)</label>
                        <input id="qty" type="number" class="form-control" name="qty" value="{{ old('qty') ?? 0 }}" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga (Rp)</label>
                        <input id="harga" type="number" class="form-control" name="harga" value="{{ old('harga') }}" min="0" required>
                    </div>
                    {{-- <div class="mb-3">
                        <label for="exp" class="form-label">Expired</label>
                        <input type="date" class="form-control" name="exp" value="{{ old('exp', $defaultExp) }}" required>
                    </div> --}}
                    @if ($filter === 'produk')
                        <div class="mb-3">
                            <label for="exp" class="form-label">Expired</label>
                            <input id="exp" type="date" class="form-control" name="exp" value="{{ old('exp', $defaultExp) }}" required>
                        </div>
                    @else
                        <div class="mb-3">
                            <label for="exp" class="form-label">Expired</label>
                            <input id="exp" type="date" class="form-control" name="exp"  required>
                        </div>
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>