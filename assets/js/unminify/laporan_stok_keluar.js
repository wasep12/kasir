let laporan_stok_keluar = $("#laporan_stok_keluar").DataTable({
    responsive: true,
    scrollX: true,
    ajax: readUrl,
    columnDefs: [{
        searcable: false,
        orderable: false,
        targets: 0
    }],
    order: [[1, "asc"]], columns: [{
        data: null
    }
        , {
        data: "tanggal"
    }
        , {
        data: "barcode"
    }
        , {
        data: "nama_produk"
    }
        , {
        data: "jumlah"
    }
        , {
        data: "keterangan"
    }]
}

);
function reloadTable() {
    laporan_stok_keluar.ajax.reload()
}

function remove(id) {
    Swal.fire({
        title: "Hapus",
        text: "Hapus data ini?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal"
    }).then((result) => {
        // Memeriksa apakah tombol "Ya" ditekan
        if (result.isConfirmed) {
            $.ajax({
                url: deleteUrl,
                type: "POST",
                dataType: "json",
                data: { id: id },
                success: () => {
                    Swal.fire("Sukses", "Sukses Menghapus Data", "success");
                    reloadTable(); // Reload tabel setelah penghapusan
                },
                error: (err) => {
                    console.error("Error:", err);
                    Swal.fire("Gagal", "Terjadi kesalahan saat menghapus data", "error");
                }
            });
        } else {
            // Jika tombol Cancel ditekan
            Swal.fire("Dibatalkan", "Data tidak dihapus", "info");
        }
    });
}


laporan_stok_keluar.on("order.dt search.dt", () => {
    laporan_stok_keluar.column(0, {
        search: "applied", order: "applied"
    }).nodes().each((el, err) => {
        el.innerHTML = err + 1
    })
});
$(".modal").on("hidden.bs.modal", () => {
    $("#form")[0].reset();
    $("#form").validate().resetForm()
});