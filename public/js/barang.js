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
    // Penangan untuk Peringatan Hapus Barang
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
});
