document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll(".form-kloter-selesai");

    forms.forEach(form => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin ingin menyelesaikan kloter ini?',
                text: "Data akan diproses ke riwayat gaji.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Selesaikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
