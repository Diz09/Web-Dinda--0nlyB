// SweetAlert konfirmasi hapus
document.querySelectorAll('.formDeleteSupplier').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
});

// Populate modal edit
document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function () {
        document.getElementById('editNama').value = this.dataset.nama;
        document.getElementById('editAlamat').value = this.dataset.alamat;
        document.getElementById('editTelepon').value = this.dataset.telepon;
        document.getElementById('editRekening').value = this.dataset.rekening || '';
        document.getElementById('editKategori').value = this.dataset.kategori;
        document.getElementById('editForm').action = this.dataset.action;
    });
});