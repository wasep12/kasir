let isCetak = false,
    produk = [],
    transaksi = $("#transaksi").DataTable({
        responsive: true,
        lengthChange: false,
        searching: false,
        scrollX: true
    });

function reloadTable() {
    transaksi.ajax.reload();
}

function nota(jumlah) {
    let hasil = "",
        char = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        total = char.length;
    for (var r = 0; r < jumlah; r++) hasil += char.charAt(Math.floor(Math.random() * total));
    return hasil;
}

function getNama() {
    $.ajax({
        url: produkGetNamaUrl,
        type: "post",
        dataType: "json",
        data: {
            id: $("#barcode").val()
        },
        success: res => {
            $("#nama_produk").html(res.nama_produk);
            $("#sisa").html(`Sisa ${res.stok}`);
            checkEmpty();
        },
        error: err => {
            console.log(err);
        }
    });
}

function checkStok() {
    $.ajax({
        url: produkGetStokUrl,
        type: "post",
        dataType: "json",
        data: {
            id: $("#barcode").val()
        },
        success: res => {
            let barcode = $("#barcode").val(),
                nama_produk = res.nama_produk,
                jumlah = parseInt($("#jumlah").val()),
                stok = parseInt(res.stok),
                harga = parseInt(res.harga),
                dataBarcode = res.barcode,
                total = parseInt($("#total").html());

            if (stok < jumlah) {
                Swal.fire("Gagal", "Stok Tidak Cukup", "warning");
            } else {
                let a = transaksi.rows().indexes().filter((a, t) => dataBarcode === transaksi.row(a).data()[0]);
                if (a.length > 0) {
                    let row = transaksi.row(a[0]),
                        data = row.data();
                    if (stok < data[3] + jumlah) {
                        Swal.fire('Stok', "Stok Tidak Cukup", "warning");
                    } else {
                        data[3] = data[3] + jumlah;
                        row.data(data).draw();
                        indexProduk = produk.findIndex(a => a.id == barcode);
                        produk[indexProduk].stok = stok - data[3];
                        $("#total").html(total + harga * jumlah);
                    }
                } else {
                    produk.push({
                        id: barcode,
                        stok: stok - jumlah,
                        terjual: jumlah
                    });
                    transaksi.row.add([
                        dataBarcode,
                        nama_produk,
                        harga,
                        jumlah,
                        `<button name="${barcode}" class="btn btn-sm btn-danger" onclick="remove('${barcode}')">Hapus</button>`
                    ]).draw();
                    $("#total").html(total + harga * jumlah);
                    $("#jumlah").val("");
                    $("#tambah").attr("disabled", "disabled");
                    $("#bayar").removeAttr("disabled");
                }
            }
        }
    });
}

function bayarCetak() {
    isCetak = true;
}

function bayar() {
    isCetak = false;
}

function checkEmpty() {
    let barcode = $("#barcode").val(),
        jumlah = $("#jumlah").val();
    if (barcode !== "" && jumlah !== "" && parseInt(jumlah) >= 1) {
        $("#tambah").removeAttr("disabled");
    } else {
        $("#tambah").attr("disabled", "disabled");
    }
}

function checkUang() {
    let jumlah_uang = $('[name="jumlah_uang"').val(),
        total_bayar = parseInt($(".total_bayar").html());
    if (jumlah_uang !== "" && jumlah_uang >= total_bayar) {
        $("#add").removeAttr("disabled");
        $("#cetak").removeAttr("disabled");
    } else {
        $("#add").attr("disabled", "disabled");
        $("#cetak").attr("disabled", "disabled");
    }
}

function remove(nama) {
    let data = transaksi.row($("[name=" + nama + "]").closest("tr")).data(),
        stok = data[3],
        harga = data[2],
        total = parseInt($("#total").html());
    akhir = total - stok * harga;
    $("#total").html(akhir);
    transaksi.row($("[name=" + nama + "]").closest("tr")).remove().draw();
    $("#tambah").attr("disabled", "disabled");
    if (akhir < 1) {
        $("#bayar").attr("disabled", "disabled");
    }
}

function add() {
    let data = transaksi.rows().data(),
        produkData = [],
        barcodes = [],
        namaProduks = [];

    $.each(data, (index, value) => {
        produkData.push({
            barcode: value[0],       // Ambil ID barcode dari kolom 0
            nama_produk: value[1],  // Ambil nama produk dari kolom 1
            qty: value[3]           // Ambil jumlah produk dari kolom 3
        });

        // Masukkan barcode dan nama produk ke array untuk penggabungan
        barcodes.push(value[0]);
        namaProduks.push(value[1]);
    });

    $.ajax({
        url: addUrl,
        type: 'POST',
        data: {
            produk: JSON.stringify(produkData),        // Kirim data produk sebagai JSON
            barcodes: barcodes.join(','),             // Gabungkan barcode dengan koma
            nama_produks: namaProduks.join(','),      // Gabungkan nama produk dengan koma
            tanggal: $('#tanggal').val(),
            pelanggan: $('#pelanggan').val(),
            total_bayar: $('#total').text(),
            diskon: $('[name="diskon"]').val(),
            jumlah_uang: $('[name="jumlah_uang"]').val(),
            nota: $('#nota').text()
        },
        success: function (res) {
            Swal.fire('Sukses', 'Transaksi berhasil', 'success');
            window.location.reload();
        },
        error: function (err) {
            Swal.fire('Error', 'Transaksi gagal', 'error');
            console.error(err);
        }
    });
}
function kembalian() {
    // Ambil nilai total, jumlah uang, dan diskon dari elemen HTML
    let total = parseFloat($("#total").html()) || 0; // Total belanja
    let jumlah_uang = parseFloat($('[name="jumlah_uang"]').val()) || 0; // Uang yang dibayar pelanggan
    let diskon = parseFloat($('[name="diskon"]').val()) || 0; // Diskon yang diberikan

    // Hitung total setelah diskon
    let totalAfterDiscount = total - diskon;

    // Pastikan totalAfterDiscount tidak negatif
    if (totalAfterDiscount < 0) {
        totalAfterDiscount = 0;
    }

    // Hitung kembalian (tanpa menambahkan diskon ke kembalian)
    let kembalian = jumlah_uang - totalAfterDiscount;

    // Pastikan kembalian tidak negatif
    if (kembalian < 0) {
        kembalian = 0;
    }

    // Tampilkan kembalian akhir
    $(".kembalian").html(kembalian.toFixed(2));

    // Tambahkan logika untuk menampilkan diskon jika ada
    $(".diskon").html(diskon.toFixed(2)); // Tempatkan di elemen HTML untuk menampilkan diskon

    // Validasi tambahan jika diperlukan
    checkUang();
}


$("#barcode").select2({
    placeholder: "Barcode",
    ajax: {
        url: getBarcodeUrl,
        type: "post",
        dataType: "json",
        data: params => ({
            barcode: params.term
        }),
        processResults: res => ({
            results: res
        }),
        cache: true
    }
});

$("#pelanggan").select2({
    placeholder: "Pelanggan",
    ajax: {
        url: pelangganSearchUrl,
        type: "post",
        dataType: "json",
        data: params => ({
            pelanggan: params.term
        }),
        processResults: res => ({
            results: res
        }),
        cache: true
    }
});

$("#tanggal").datetimepicker({
    format: "dd-mm-yyyy h:ii:ss"
});

$(".modal").on("hidden.bs.modal", () => {
    $("#form")[0].reset();
    $("#form").validate().resetForm();
});

$(".modal").on("show.bs.modal", () => {
    let now = moment().format("D-MM-Y H:mm:ss"),
        total = $("#total").html(),
        jumlah_uang = $('[name="jumlah_uang"').val();
    $("#tanggal").val(now);
    $(".total_bayar").html(total);
    $(".kembalian").html(Math.max(jumlah_uang - total, 0));
});

$("#form").validate({
    errorElement: "span",
    errorPlacement: (err, el) => {
        err.addClass("invalid-feedback");
        el.closest(".form-group").append(err);
    },
    submitHandler: () => {
        add();
    }
});

$("#nota").html(nota(15));
