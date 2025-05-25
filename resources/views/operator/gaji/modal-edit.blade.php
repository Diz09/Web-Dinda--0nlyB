<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form method="POST" action="{{ route('presensi.tonikan.store') }}">
            @csrf
            <input type="hidden" name="kloter_id" value="{{ $selectedKloter->id }}">

            <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit Jumlah Ton Ikan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
            <div class="mb-3">
                <label for="jumlah_ton" class="form-label">Jumlah Ton Ikan (ton)</label>
                <input id="jumlah_ton" type="number" name="jumlah_ton" class="form-control" value="{{ old('jumlah_ton', $selectedKloter->tonIkan->jumlah_ton) }}" required>
            </div>

            <div class="mb-3">
                <label for="harga_ikan_per_ton" class="form-label">Harga Ikan Per Ton (Rp)</label>
                <input id="harga_ikan_per_ton" type="number" name="harga_ikan_per_ton" class="form-control" value="{{ old('harga_ikan_per_ton', $selectedKloter->tonIkan->harga_ikan_per_ton) }}" required>
            </div>
            </div>

            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Data Ton Ikan</button>
            </div>
        </form>
        </div>
    </div>
</div>
