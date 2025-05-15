// document.querySelectorAll('.formDelete').forEach(form => {
//     form.addEventListener('submit', function(e) {
//         e.preventDefault();
//         Swal.fire({
//             title: 'Yakin ingin menghapus?',
//             icon: 'warning',
//             showCancelButton: true,
//             confirmButtonColor: '#d33',
//             cancelButtonColor: '#3085d6',
//             confirmButtonText: 'Ya, hapus'
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 this.submit();
//             }
//         });
//     });
// });

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.formDelete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
});
