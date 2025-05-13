<!-- Modal -->
<div class="modal fade" id="editKuartalModal" tabindex="-1" aria-labelledby="editKuartalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editKuartalModalLabel">Edit Kuartal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">

                <!-- Form Buat Kuartal Baru -->
                <form method="GET" class="mb-3" action="">
                    <input type="hidden" name="buat_kuartal" value="1">
                    <button class="btn btn-success w-100 mb-3">+ Buat Kuartal Baru</button>
                </form>

                <!-- Form Simpan Ton Ikan -->
                <form method="POST" action="{{ route('presensi.tonikan.store') }}">
                    @csrf
                    <input type="hidden" name="kuartal_id" value="{{ $selectedKuartal->id }}">

                    <div class="mb-3">
                        <label for="jumlah_ton">Jumlah Ton Ikan (Kuartal {{ $selectedKuartal->nama_kuartal }})</label>
                        <input type="number" name="jumlah_ton" class="form-control" id="jumlah_ton"
                               value="{{ old('jumlah_ton', $selectedKuartal->tonIkan->jumlah_ton ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="harga_ikan_per_ton">Harga Ikan Per Ton (Rp)</label>
                        <input type="number" name="harga_ikan_per_ton" class="form-control" id="harga_ikan_per_ton"
                               value="{{ old('harga_ikan_per_ton', $selectedKuartal->tonIkan->harga_ikan_per_ton ?? 1000000) }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Simpan Data Ton Ikan</button>
                </form>

            </div>
        </div>
    </div>
</div>
