{{-- modal create --}}
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="{{ route('operator.transaksi.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="createModalLabel">Tambah Transaksi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="kategori">Kategori</label>
            <select name="kategori" id="kategori" class="form-control" required>
              <option value="">-- Pilih Kategori --</option>
              <option value="pemasukan">Pemasukan</option>
              <option value="pengeluaran">Pengeluaran</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="barang_id">Pilih Barang</label>
            <select name="barang_id" id="barang_id" class="form-control" required>
              <option value="">-- Pilih Barang --</option>
              {{-- Opsi akan diisi lewat JS sesuai kategori --}}
            </select>
          </div>

          <div class="mb-3">
            <label for="supplier_id">Pilih Supplier</label>
            <select name="supplier_id" id="supplier_id" class="form-control" required>
              <option value="">-- Pilih Supplier --</option>
              {{-- Opsi akan diisi lewat JS sesuai kategori --}}
            </select>
          </div>

          <div class="mb-3">
            <label for="qtyHistori">Jumlah (Qty)</label>
            <input type="number" name="qtyHistori" id="qtyHistori" class="form-control" min="1" required>
          </div>

          <div class="mb-3">
            <label for="satuan">Satuan</label>
            <select name="satuan" id="satuan" class="form-control" required>
              <option value="">-- Pilih Satuan --</option>
              <option value="ton">Ton</option>
              <option value="kg">Kg, L, Kwh, Satuan</option>
              <option value="g">g, mL</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="jumlahRp">Jumlah Harga (Rp)</label>
            <input type="number" name="jumlahRp" id="jumlahRp" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="waktu_transaksi">Waktu Transaksi</label>
            <input type="datetime-local" class="form-control" name="waktu_transaksi" id="waktu_transaksi" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </form>
    </div>
  </div>
</div>
