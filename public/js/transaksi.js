// Data dari backend (harus disediakan dari Blade)
const barangsData = window.barangsData || {};
const suppliersData = window.suppliersData || {};

function konversiSatuanKeKg(satuan, qtyHistori) {
    switch (satuan) {
        case 'ton': return qtyHistori * 1000;
        case 'kg': return qtyHistori;
        case 'g': return qtyHistori / 1000;
        default: return qtyHistori;
    }
}

// --------------- CREATE MODAL ---------------
const kategoriSelect = document.getElementById('kategori');
const barangSelect = document.getElementById('barang_id');
const supplierSelect = document.getElementById('supplier_id');
const qtyInput = document.getElementById('qtyHistori');
const satuanSelect = document.getElementById('satuan');
const jumlahRpInput = document.getElementById('jumlahRp');

function fillBarangOptions(kategori) {
    const tipe = kategori === 'pengeluaran' ? 'pendukung' : 'produk';
    barangSelect.innerHTML = '<option value="">-- Pilih Barang --</option>';

    if (barangsData[tipe]) {
        barangsData[tipe].forEach(barang => {
            const option = document.createElement('option');
            option.value = barang.id;
            option.textContent = barang.nama;
            option.dataset.harga = barang.harga;
            barangSelect.appendChild(option);
        });
    }
    jumlahRpInput.value = '';
}

function fillSupplierOptions(kategori) {
    supplierSelect.innerHTML = '<option value="">-- Pilih Supplier --</option>';
    const tipe = kategori === 'pengeluaran' ? 'konsumen' : 'pemasok';
    suppliersData[tipe]?.forEach(supplier => {
        const option = document.createElement('option');
        option.value = supplier.id;
        option.textContent = supplier.nama;
        supplierSelect.appendChild(option);
    });
}

function hitungJumlahRpCreate() {
    const harga = parseFloat(barangSelect.selectedOptions[0]?.dataset.harga || 0);
    const qty = parseFloat(qtyInput.value || 0);
    const satuan = satuanSelect.value;
    const qtyKg = konversiSatuanKeKg(satuan, qty);
    jumlahRpInput.value = (harga * qtyKg).toFixed(0);
}

if (kategoriSelect) {
    kategoriSelect.addEventListener('change', () => {
        fillBarangOptions(kategoriSelect.value);
        fillSupplierOptions(kategoriSelect.value);
    });
    barangSelect.addEventListener('change', hitungJumlahRpCreate);
    qtyInput.addEventListener('input', hitungJumlahRpCreate);
    satuanSelect.addEventListener('change', hitungJumlahRpCreate);

    $('#createModal').on('show.bs.modal', () => {
        kategoriSelect.value = '';
        barangSelect.innerHTML = '<option value="">-- Pilih Barang --</option>';
        supplierSelect.innerHTML = '<option value="">-- Pilih Supplier --</option>';
        qtyInput.value = '';
        satuanSelect.value = '';
        jumlahRpInput.value = '';
    });
}

// --------------- EDIT MODAL ---------------
const kategoriEdit = document.getElementById('editKategori');
const barangEdit = document.getElementById('editBarang');
const supplierEdit = document.getElementById('editSupplier');
const qtyInputEdit = document.getElementById('editQty');
const satuanEdit = document.getElementById('editSatuan');
const jumlahRpInputEdit = document.getElementById('editJumlahRp');

// Tambahkan listener agar saat kategori diubah saat edit, barang & supplier di-reload
kategoriEdit?.addEventListener('change', function () {
    fillBarangOptionsEdit(this.value, barangEdit.value);
    fillSupplierOptionsEdit(this.value, supplierEdit.value);
    hitungJumlahRpEdit();
});


function fillBarangOptionsEdit(kategori, selectedId) {
    const tipe = kategori === 'pengeluaran' ? 'pendukung' : 'produk';
    barangEdit.innerHTML = '<option value="">-- Pilih Barang --</option>';

    if (barangsData[tipe]) {
        barangsData[tipe].forEach(barang => {
            const option = document.createElement('option');
            option.value = barang.id;
            option.textContent = barang.nama;
            option.dataset.harga = barang.harga;
            if (barang.id == selectedId) option.selected = true;
            barangEdit.appendChild(option);
        });
    }
}

function fillSupplierOptionsEdit(kategori, selectedId) {
    supplierEdit.innerHTML = '<option value="">-- Pilih Supplier --</option>';
    const tipe = kategori === 'pengeluaran' ? 'konsumen' : 'pemasok';
    suppliersData[tipe]?.forEach(supplier => {
        const option = document.createElement('option');
        option.value = supplier.id;
        option.textContent = supplier.nama;
        if (supplier.id == selectedId) option.selected = true;
        supplierEdit.appendChild(option);
    });
}

function hitungJumlahRpEdit() {
    const harga = parseFloat(barangEdit.selectedOptions[0]?.dataset.harga || 0);
    const qty = parseFloat(qtyInputEdit.value || 0);
    const satuan = satuanEdit.value;
    const qtyKg = konversiSatuanKeKg(satuan, qty);
    jumlahRpInputEdit.value = (harga * qtyKg).toFixed(0);
}

$('.btn-edit').on('click', function () {
    const data = this.dataset;
    fillBarangOptionsEdit(data.kategori, data.barang_id);
    fillSupplierOptionsEdit(data.kategori, data.supplier_id);

    kategoriEdit.value = data.kategori;
    qtyInputEdit.value = data.qty;
    satuanEdit.value = data.satuan;
    jumlahRpInputEdit.value = data.jumlahrp;
    document.getElementById('editWaktu').value = data.waktu;
    document.getElementById('formEdit').action = `/operator/transaksi/${data.id}`;

    console.log('Kategori:', kategori);
    console.log('Barang ID:', editBarang);
    console.log('BarangsData:', barangsData);
    console.log('Suppliers:', suppliersData);
    console.log('Selected Barang:', barangEdit.value);
    console.log('Selected Supplier:', supplierEdit.value);
    console.log('Qty:', qtyInputEdit.value);
    console.log('Satuan:', satuanEdit.value);
    console.log('Jumlah Rp:', jumlahRpInputEdit.value);
    console.log("Barang options:", $('#editBarang').html());
    console.log("Supplier options:", $('#editSupplier').html());
    console.log("Kategori:", kategoriEdit.value);
    console.log("Qty Input:", qtyInputEdit.value);
    console.log("Satuan:", satuanEdit.value);
});

barangEdit?.addEventListener('change', hitungJumlahRpEdit);
qtyInputEdit?.addEventListener('input', hitungJumlahRpEdit);
satuanEdit?.addEventListener('change', hitungJumlahRpEdit);