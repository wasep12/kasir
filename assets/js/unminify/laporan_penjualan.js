$(document).ready(function () {
    const table = $('#laporan_penjualan').DataTable({
        ajax: readUrl, // URL untuk mendapatkan data
        columns: [
            { data: 'no' },
            { data: 'tanggal' },
            { data: 'nama_produk' },
            { data: 'total_bayar' },
            { data: 'jumlah_uang' },
            { data: 'diskon' },
            { data: 'pelanggan' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-primary btn-sm btn-edit" data-id="${row.id}">Edit</button>
                        <button class="btn btn-danger btn-sm btn-delete" data-id="${row.id}">Delete</button>
                    `;
                },
                orderable: false,
                searchable: false,
            },
        ],
    });

    // Event handler untuk tombol Edit
    $('#laporan_penjualan').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        editData(id);
    });

    // Event handler untuk tombol Delete
    $('#laporan_penjualan').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        deleteData(id, table);
    });
});



function reloadTable() {
    laporan_penjualan.ajax.reload();
}

function remove(id) {
    Swal.fire({
        title: "Hapus",
        text: "Hapus data ini?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        // Periksa apakah yang dipilih adalah tombol konfirmasi (Ya)
        if (result.value === true) {
            // Jika tombol "Ya" ditekan
            $.ajax({
                url: deleteUrl,
                type: "POST",
                dataType: "json",
                data: { id: id },
                success: (response) => {
                    if (response.status === "success") {
                        Swal.fire("Sukses", "Data berhasil dihapus", "success");
                        reloadTable(); // Reload tabel setelah penghapusan
                    } else {
                        Swal.fire("Gagal", "Data tidak ditemukan atau tidak bisa dihapus", "error");
                    }
                },
                error: (xhr, status, error) => {
                    console.error("Error:", error);
                    Swal.fire("Gagal", "Terjadi kesalahan saat menghapus data", "error");
                }
            });
        } else {
            // Jika tombol "Cancel" ditekan
            Swal.fire("Dibatalkan", "Data tidak dihapus", "info");
        }
    });
}




laporan_penjualan.on("order.dt search.dt", () => {
    laporan_penjualan.column(0, {
        search: "applied",
        order: "applied"
    }).nodes().each((el, idx) => {
        el.innerHTML = idx + 1; // Mengisi nomor urut setelah pencarian atau pengurutan
    });
});

$(".modal").on("hidden.bs.modal", () => {
    if ($("#form")[0]) {
        $("#form")[0].reset();
    }
    if ($("#form").validate()) {
        $("#form").validate().resetForm();
    }
});
