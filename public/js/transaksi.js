document.addEventListener('DOMContentLoaded', function () {
    const barangSelect = document.getElementById('barang_id');
    const qtyInput = document.getElementById('qtyHistori'); // ganti dari 'qty'
    const satuanSelect = document.getElementById('satuan');
    const jumlahInput = document.getElementById('jumlahRp'); // ganti dari 'jumlah'

    function hitungTotal() {
        const selectedOption = barangSelect.options[barangSelect.selectedIndex];
        const harga = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
        const qty = parseFloat(qtyInput.value) || 0;
        const satuan = satuanSelect.value;

        let qty_kg = qty;
        if (satuan === 'ton') {
            qty_kg = qty * 1000;
        } else if (satuan === 'g') {
            qty_kg = qty / 1000;
        }

        const total = harga * qty_kg;
        jumlahInput.value = Math.round(total); // atau gunakan toFixed(2)
    }

    // Jalankan setiap kali ada perubahan
    barangSelect.addEventListener('change', hitungTotal);
    qtyInput.addEventListener('input', hitungTotal);
    satuanSelect.addEventListener('change', hitungTotal);
});
