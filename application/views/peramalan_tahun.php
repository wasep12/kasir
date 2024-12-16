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
                                    <h3 class="card-title">Trend Penjualan Pertahun</h3>
                                </div>
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="container mt-3">
                                                <!-- Menambahkan chart -->
                                                <h3 class="text-center">Grafik Penjualan Pertahun</h3>
                                                <canvas id="salesChart" width="400" height="200"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="card mt-3 p-3">
                                    <h3 class="text-center mb-3">Perhitungan Least Square
                                        Berdasarkan Tahun</h3>

                                    <?php if (isset($message)): ?>
                                    <div class="alert alert-warning">
                                        <?= $message; ?>
                                    </div>
                                    <?php elseif (!empty($data_tahun)): ?>
                                    <table class="table table-bordered table-striped">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>Tahun</th>
                                                <th>Total Penjualan (Y)</th>
                                                <th>X</th>
                                                <th>\( \Sigma X \)</th>
                                                <th>\( \Sigma XY \)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $x_sum = 0;
                                                $xy_sum = 0;
                                                $x = 1; // X dimulai dari 1
                                                ?>
                                            <?php foreach ($data_tahun as $row): ?>
                                            <tr>
                                                <td><?= $row['tahun']; ?></td>
                                                <td><?= number_format($row['penjualan']); ?>
                                                </td>
                                                <td><?= $x; ?></td>
                                                <td>
                                                    <?php
                                                            $x_sum += $x;
                                                            echo $x_sum;
                                                            ?>
                                                </td>
                                                <td>
                                                    <?php
                                                            $xy_sum += $x * $row['penjualan'];
                                                            echo $xy_sum;
                                                            ?>
                                                </td>
                                            </tr>
                                            <?php $x++; endforeach; ?>
                                        </tbody>
                                    </table>

                                    <div class="container">
                                        <!-- Tombol untuk menampilkan penjelasan lebih lanjut -->
                                        <button class="btn btn-primary" id="toggleDetailsBtn">Detail lebih
                                            lanjut</button>

                                        <!-- Penjelasan rinci yang disembunyikan -->
                                        <div id="details" style="display:none;">
                                            <!-- Tampilkan persamaan Least Square -->
                                            <h4 class="mt-3">Persamaan Least Square: <?= $equation; ?></h4>
                                            <p>
                                                Persamaan Least Square yang dihitung adalah
                                                <strong>y = mx + b</strong>, di
                                                mana:
                                            </p>
                                            <ul>
                                                <li><strong>y</strong> adalah nilai prediksi (output) berdasarkan tahun
                                                    (x) yang kita masukkan ke dalam persamaan.</li>
                                                <li><strong>x</strong> adalah tahun yang digunakan dalam perhitungan,
                                                    yang diambil dari data penjualan per tahun.</li>
                                                <li><strong>m</strong> adalah <strong>slope (kemiringan)</strong> yang
                                                    menunjukkan seberapa besar perubahan nilai y (penjualan) setiap kali
                                                    ada perubahan 1 unit pada x (tahun).</li>
                                                <li><strong>b</strong> adalah <strong>intercept (potongan sumbu
                                                        Y)</strong>, yaitu nilai y saat x = 0. Intercept ini memberikan
                                                    nilai awal yang mungkin terjadi jika tahun (x) dimulai dari titik
                                                    tertentu.</li>
                                            </ul>

                                            <h5>Slope (m): <?= $slope; ?></h5>
                                            <p>
                                                Slope (m) menggambarkan hubungan antara tahun dan penjualan. Jika nilai
                                                m positif, itu berarti penjualan meningkat seiring waktu. Sebaliknya,
                                                jika nilai m negatif, penjualan menurun seiring waktu.
                                            </p>

                                            <h5>Intercept (b): <?= $intercept; ?></h5>
                                            <p>
                                                Intercept (b) menggambarkan nilai penjualan pada titik awal (tahun 0
                                                atau titik referensi yang telah ditentukan). Ini memberikan gambaran
                                                tentang seberapa besar penjualan yang diharapkan pada tahun yang dimulai
                                                pada sumbu X (tahun 0). Nilai ini penting untuk memahami konteks awal
                                                dari data yang Anda analisis.
                                            </p>

                                            <p>
                                                Dengan persamaan ini, Anda dapat memprediksi penjualan di masa depan
                                                berdasarkan tahun yang ada. Ini adalah contoh penerapan metode Least
                                                Squares untuk membuat model prediksi berdasarkan data historis yang ada.
                                            </p>
                                        </div>
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
                                            this.textContent =
                                                'Detail lebih lanjut'; // Ubah teks tombol kembali
                                        }
                                    });
                                    </script>


                                    <!-- Pastikan data terisi dengan benar -->
                                    <script>
                                    var years = <?php echo json_encode($years); ?>;
                                    var sales = <?php echo json_encode($sales); ?>;
                                    console.log("Years: ", years);
                                    console.log("Sales: ", sales);
                                    </script>

                                    <script src="https://cdn.jsdelivr.net/npm/chart.js">
                                    </script>
                                    <script>
                                    // Pastikan data tersedia di JavaScript
                                    if (years && sales) {
                                        var ctx = document.getElementById('salesChart')
                                            .getContext(
                                                '2d');
                                        var salesChart = new Chart(ctx, {
                                            type: 'line', // Tipe grafik
                                            data: {
                                                labels: years, // Tahun
                                                datasets: [{
                                                    label: 'Total Penjualan',
                                                    data: sales, // Penjualan
                                                    borderColor: 'rgba(75, 192, 192, 1)',
                                                    fill: false,
                                                    tension: 0.1
                                                }]
                                            },
                                            options: {
                                                scales: {
                                                    x: {
                                                        title: {
                                                            display: true,
                                                            text: 'Tahun'
                                                        }
                                                    },
                                                    y: {
                                                        title: {
                                                            display: true,
                                                            text: 'Penjualan'
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    } else {
                                        console.error("Data grafik tidak ditemukan.");
                                    }
                                    </script>

                                    <?php else: ?>
                                    <div class="alert alert-warning">
                                        Data perhitungan Least Square tidak tersedia.
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    </section>
    <?php $this->load->view('includes/footer'); ?>
    <?php $this->load->view('partials/footer'); ?>

    <script src="<?php echo base_url('assets/vendor/adminlte/plugins/chart.js/Chart.min.js') ?>"></script>
    <script>

    </script>
    <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
</body>

</html>divdivdiv