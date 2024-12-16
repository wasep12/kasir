<!DOCTYPE html>
<html>

<head>
    <title>Menampilkan Trend</title>
    <?php $this->load->view('partials/head'); ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php $this->load->view('includes/nav'); ?>
        <?php $this->load->view('includes/aside'); ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col">
                            <h1 class="m-0 text-dark">MENAMPILKAN TREND</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h3 class="card-title">Trend Penjualan Perbulan</h3>
                                </div>
                                <div class="card-body">
                                    <!-- Form untuk memilih tahun -->
                                    <form action="<?php echo base_url('peramalan'); ?>" method="post">
                                        <div class="form-group">
                                            <label for="tahun">Pilih Tahun</label>
                                            <select class="form-control" id="tahun" name="tahun">
                                                <?php for ($i = date('Y') - 5; $i <= date('Y') + 5; $i++): ?>
                                                <option value="<?php echo $i; ?>"
                                                    <?php echo ($i == $tahun) ? 'selected' : ''; ?>><?php echo $i; ?>
                                                </option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Tampilkan Data</button>
                                    </form>

                                    <div class="mt-4">
                                        <canvas id="trendChart" width="400" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="card p-3">
                        <h3 class="text-center mb-3">Perhitungan Least Square untuk Tahun <?= $tahun; ?></h3>

                        <?php if (!empty($peramalan_data['data'])) : ?>
                        <table class="table table-bordered table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Bulan</th>
                                    <th>\(\Sigma X\) (Waktu)</th>
                                    <th>\(\Sigma Y\) (Penjualan)</th>
                                    <th>\(\Sigma X^2\)</th>
                                    <th>\(\Sigma XY\)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($peramalan_data['data'] as $row) : ?>
                                <tr>
                                    <!-- Mengonversi angka bulan menjadi nama bulan -->
                                    <td><?= $bulan[$row['bulan'] - 1]; ?></td>
                                    <td><?= $row['x']; ?></td>
                                    <td><?= $row['y']; ?></td>
                                    <td><?= $row['x2']; ?></td>
                                    <td><?= $row['xy']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>

                        <div class="container mt-2">
                            <h4 class="mt-2">Rumus Perhitungan Trend:</h4>
                            <p>\( Y = a + bX \)</p>
                            <p>Dimana:</p>
                            <ul>
                                <li>Y = Peramalan Tren</li>
                                <li>a = Intercept (nilai tetap)</li>
                                <li>b = Slope (kemiringan garis tren)</li>
                                <li>X = Waktu (bulan atau periode)</li>
                            </ul>

                            <!-- Tombol untuk menampilkan penjelasan lebih lanjut -->
                            <button class="btn btn-primary" id="toggleDetailsBtn">Detail lebih lanjut</button>

                            <!-- Penjelasan rinci yang disembunyikan -->
                            <div id="details" style="display:none;">
                                <h4 class="mt-3">Hasil Perhitungan Least Square:</h4>
                                <p>Berikut adalah data yang digunakan dalam perhitungan:</p>
                                <ul>
                                    <li>
                                        \(\Sigma X\): Jumlah total dari nilai variabel independen (\(X\)), yang biasanya
                                        mewakili periode waktu atau urutan data.
                                    </li>
                                    <li>
                                        \(\Sigma Y\): Jumlah total dari nilai variabel dependen (\(Y\)), yaitu data yang
                                        diamati atau diukur pada periode tertentu.
                                    </li>
                                    <li>
                                        \(\Sigma X^2\): Jumlah total dari nilai kuadrat variabel independen (\(X^2\)),
                                        digunakan untuk menghitung koefisien dalam model.
                                    </li>
                                    <li>
                                        \(\Sigma XY\): Jumlah hasil perkalian antara nilai variabel independen (\(X\))
                                        dan variabel dependen (\(Y\)) untuk setiap data.
                                    </li>
                                </ul>
                                <p>Hasil perhitungan menggunakan data di atas menghasilkan:</p>
                                <ul>
                                    <li>
                                        Konstanta (\(a\)): <?= round($peramalan_data['a'], 2); ?>
                                    </li>
                                    <li>
                                        Koefisien (\(b\)): <?= round($peramalan_data['b'], 2); ?>
                                    </li>
                                </ul>
                                <p>Data ini diolah untuk menghasilkan persamaan garis tren \( Y = a + bX \) yang
                                    digunakan
                                    untuk memperkirakan nilai \(Y\) berdasarkan nilai \(X\).</p>
                            </div>

                            <?php else : ?>
                            <div class="alert alert-warning">
                                Data untuk tahun <?= $tahun; ?> tidak ditemukan. Silakan periksa kembali data yang
                                tersedia.
                            </div>
                            <?php endif; ?>
                        </div>

                        <script>
                        // JavaScript untuk men-toggle visibilitas penjelasan
                        document.getElementById('toggleDetailsBtn').addEventListener('click', function() {
                            var details = document.getElementById('details');
                            if (details.style.display === 'none') {
                                details.style.display = 'block';
                                this.textContent = 'Detail lebih sedikit'; // Ubah teks tombol
                            } else {
                                details.style.display = 'none';
                                this.textContent = 'Detail lebih lanjut'; // Ubah teks tombol kembali
                            }
                        });
                        </script>

            </section>
        </div>
    </div>

    <?php $this->load->view('includes/footer'); ?>
    <?php $this->load->view('partials/footer'); ?>

    <script src="<?php echo base_url('assets/vendor/adminlte/plugins/chart.js/Chart.min.js') ?>"></script>
    <script>
    // Data bulan dan produk dari controller
    var bulan = <?php echo json_encode($bulan); ?>;
    var trendData = <?php echo json_encode($trend_data); ?>;
    // Membuat dataset untuk setiap produk
    var datasets = [];

    // Mengelompokkan data berdasarkan nama_produk
    var groupedData = {};

    trendData.forEach(function(data) {
        if (!groupedData[data.nama_produk]) {
            groupedData[data.nama_produk] = Array(12).fill(0); // Inisialisasi array bulan
        }
        groupedData[data.nama_produk][data.bulan - 1] = data.qty;
    });

    // Membuat dataset dari data yang telah dikelompokkan
    for (var product in groupedData) {
        if (groupedData.hasOwnProperty(product)) {
            datasets.push({
                label: product,
                data: groupedData[product],
                borderColor: randomColor(),
                fill: false
            });
        }
    }

    // Fungsi untuk menghasilkan warna acak
    function randomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // Membuat chart menggunakan Chart.js
    var ctx = document.getElementById('trendChart').getContext('2d');
    var trendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: bulan, // Label bulan
            datasets: datasets // Dataset peramalan
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true, // Mulai dari 0
                    ticks: {
                        // Mengatur agar sumbu y menggunakan angka bulat
                        callback: function(value) {
                            // Memastikan angka yang tampil adalah bulat (tanpa desimal)
                            return value % 1 === 0 ? value : '';
                        }
                    }
                }
            }
        }
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
</body>

</html>