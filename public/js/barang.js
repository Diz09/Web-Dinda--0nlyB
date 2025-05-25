document.addEventListener('DOMContentLoaded', function () {
    
    // Peringatan Ubah Kateogri saat Edit Barang
    document.querySelectorAll('.formEditBarang').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const kategoriLama = form.querySelector('.kategori-lama').value;
            const kategoriBaru = form.querySelector('.kategori-select').value;

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
                        const inputConfirm = document.createElement("input");
                        inputConfirm.type = "hidden";
                        inputConfirm.name = "confirm_produk";
                        inputConfirm.value = "1";
                        form.appendChild(inputConfirm);
                        form.submit();
                    }
                });
            } else {
                form.submit();
            }
        });
    });

    // Peringatan Hapus Barang
    document.querySelectorAll('.formDeleteBarang').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Tahan submit dulu

            Swal.fire({
                title: 'Hapus Data?',
                text: "Data akan dihapus secara permanen. Lanjutkan?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Jika setuju, submit form
                }
            });
        });
    });

    // Cek Nama Barang Sudah Ada atau Belum
    const namaBarangInput = document.querySelector('#nama_barang');
    if (namaBarangInput) {
        namaBarangInput.addEventListener('change', function () {
            const form = document.getElementById('checkForm');
            if (form) {
                form.submit(); // kirim GET ke backend untuk cek nama barang
            }
        });
    }

    // peringatan jika barang sudah ada
    const dataDiv = document.getElementById('barang-data');
    const barangSudahAda = dataDiv?.dataset.barangSudahAda === '1';
    if (barangSudahAda) {
        Swal.fire({
            icon: 'warning',
            title: 'Barang Sudah Ada!',
            text: 'Barang dengan nama tersebut sudah ada. Sistem akan menyesuaikan stok.',
            confirmButtonText: 'Mengerti'
        });
    }

    // document.addEventListener('DOMContentLoaded', function () {
    const kodeInputWrapper = document.getElementById('kode')?.closest('.mb-3');
    if (kodeInputWrapper) {
        const barangSudahAda = document.getElementById('barangSudahAda')?.value === '1';
        const namaBarang = document.getElementById('namaBarang')?.value || '';
        const newKode = document.getElementById('newKode')?.value || '';

        // Kosongkan dulu isinya
        kodeInputWrapper.innerHTML = '';

        const label = document.createElement('label');
        label.className = 'form-label';
        label.setAttribute('for', 'kode');
        label.innerText = 'Kode';
        kodeInputWrapper.appendChild(label);

        if (barangSudahAda && namaBarang !== '') {
            const input = document.createElement('input');
            input.id = 'kode';
            input.type = 'text';
            input.className = 'form-control is-invalid';
            input.value = 'Barang sudah ada. Tidak dibuat kode baru.';
            input.disabled = true;

            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.innerHTML = `Barang <strong>${namaBarang}</strong> sudah ada, data digabung.`;

            kodeInputWrapper.appendChild(input);
            kodeInputWrapper.appendChild(feedback);
        } else {
            const input = document.createElement('input');
            input.id = 'kode';
            input.type = 'text';
            input.className = 'form-control';
            input.name = 'kode';
            input.value = newKode;
            input.readOnly = true;

            kodeInputWrapper.appendChild(input);
        }
    }

    namaBarangInput.addEventListener('change', function () {
        const nama = this.value;
        const filter = document.querySelector('input[name="filter"]')?.value || '';

        fetch(`/barang/check?nama_barang=${encodeURIComponent(nama)}&filter=${filter}`)
            .then(response => response.json())
            .then(data => {
                // Update nilai hidden
                document.getElementById('barangSudahAda').value = data.barang_sudah_ada ? '1' : '0';
                document.getElementById('namaBarang').value = data.nama_barang || '';
                document.getElementById('newKode').value = data.kode || '';

                // Jalankan ulang fungsi perbarui tampilan input #kode
                updateKodeInput();
            });
    });
    
    function updateKodeInput() {
        const kodeInputWrapper = document.getElementById('kode')?.closest('.mb-3');
        if (!kodeInputWrapper) return;

        const barangSudahAda = document.getElementById('barangSudahAda')?.value === '1';
        const namaBarang = document.getElementById('namaBarang')?.value || '';
        const newKode = document.getElementById('newKode')?.value || '';

        kodeInputWrapper.innerHTML = '';

        const label = document.createElement('label');
        label.className = 'form-label';
        label.setAttribute('for', 'kode');
        label.innerText = 'Kode';
        kodeInputWrapper.appendChild(label);

        if (barangSudahAda && namaBarang !== '') {
            const input = document.createElement('input');
            input.id = 'kode';
            input.type = 'text';
            input.className = 'form-control is-invalid';
            input.value = 'Barang sudah ada. Tidak dibuat kode baru.';
            input.disabled = true;

            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.innerHTML = `Barang <strong>${namaBarang}</strong> sudah ada, data digabung.`;

            kodeInputWrapper.appendChild(input);
            kodeInputWrapper.appendChild(feedback);
        } else {
            const input = document.createElement('input');
            input.id = 'kode';
            input.type = 'text';
            input.className = 'form-control';
            input.name = 'kode';
            input.value = newKode;
            input.readOnly = true;

            kodeInputWrapper.appendChild(input);
        }
    }

});
