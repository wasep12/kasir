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
                                                    <th>\( \Sigma X^2 \)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $y_sum = 0; // To store ΣY (total penjualan)
                                                $x_sum = 0;
                                                $xy_sum = 0;
                                                $x2_sum = 0; // To store ΣX^2
                                                $x = 1; // X starts from 1
                                                ?>
                                                <?php foreach ($data_tahun as $index => $row): ?>
                                                    <tr>
                                                        <td><?= $row['tahun']; ?></td>
                                                        <td><?= number_format($row['penjualan']); ?></td>
                                                        <td><?= $x; ?></td>
                                                        <td>
                                                            <?php
                                                            $x_sum += $x; // Akumulasi ΣX
                                                            echo number_format($x_sum); // Display ΣX running total
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $xy_sum += $x * $row['penjualan']; // Akumulasi ΣXY
                                                            echo number_format($xy_sum); // Display ΣXY running total
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $x2_sum += $x * $x; // Akumulasi ΣX²
                                                            echo number_format($x2_sum); // Display ΣX² running total
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $y_sum += $row['penjualan']; // Akumulasi ΣY (total penjualan)
                                                    $x++; // Increment X for the next row
                                                    endforeach; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="1" class="text-end"><strong>Total \( \Sigma \)</strong>
                                                    </td>
                                                    <td><strong><?= number_format($y_sum); ?></strong></td>
                                                    <td></td> <!-- Leave X empty in the footer -->
                                                    <td><strong><?= number_format($x_sum); ?></strong></td>
                                                    <!-- Total ΣX -->
                                                    <td><strong><?= number_format($xy_sum); ?></strong></td>
                                                    <!-- Total ΣXY -->
                                                    <td><strong><?= number_format($x2_sum); ?></strong></td>
                                                    <!-- Total ΣX² -->
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <div class="container">
                                            <!-- Tombol untuk menampilkan penjelasan lebih lanjut -->
                                            <button class="btn btn-primary my-4" id="toggleDetailsBtn">Detail lebih
                                                lanjut</button>

                                            <!-- Penjelasan rinci yang disembunyikan -->
                                            <div id="details" style="display:none;">
                                                <!-- Tampilkan persamaan Least Square -->
                                                <div class="mb-4">
                                                    <h4 class="mt-3">Persamaan Least Square: <strong>y = <?= $slope; ?>x +
                                                            <?= $intercept; ?></strong></h4>
                                                    <p>Persamaan Least Square yang dihitung adalah <strong>y = mx +
                                                            b</strong>, di mana:</p>
                                                    <ul>
                                                        <li><strong>y</strong> adalah nilai prediksi (output) berdasarkan
                                                            tahun (x) yang kita masukkan ke dalam persamaan.</li>
                                                        <li><strong>x</strong> adalah tahun yang digunakan dalam
                                                            perhitungan, yang diambil dari data penjualan per tahun.</li>
                                                        <li><strong>m</strong> adalah <strong>slope (kemiringan)</strong>
                                                            yang menunjukkan seberapa besar perubahan nilai y (penjualan)
                                                            setiap kali ada perubahan 1 unit pada x (tahun).</li>
                                                        <li><strong>b</strong> adalah <strong>intercept (potongan sumbu
                                                                Y)</strong>, yaitu nilai y saat x = 0. Intercept ini
                                                            memberikan nilai awal yang mungkin terjadi jika tahun (x)
                                                            dimulai dari titik tertentu.</li>
                                                    </ul>
                                                </div>

                                                <!-- Slope dan Intercept -->
                                                <div class="mb-4">
                                                    <h5>Slope (m): <?= $slope; ?></h5>
                                                    <p>Slope (m) menggambarkan hubungan antara tahun dan penjualan. Jika
                                                        nilai m positif, itu berarti penjualan meningkat seiring waktu.
                                                        Sebaliknya, jika nilai m negatif, penjualan menurun seiring waktu.
                                                    </p>
                                                </div>

                                                <div class="mb-4">
                                                    <h5>Intercept (b): <?= $intercept; ?></h5>
                                                    <p>Intercept (b) menggambarkan nilai penjualan pada titik awal (tahun 0
                                                        atau titik referensi yang telah ditentukan). Ini memberikan gambaran
                                                        tentang seberapa besar penjualan yang diharapkan pada tahun yang
                                                        dimulai pada sumbu X (tahun 0). Nilai ini penting untuk memahami
                                                        konteks awal dari data yang Anda analisis.</p>
                                                </div>

                                                <!-- Informasi Penjualan Terendah dan Tertinggi -->
                                                <div class="mb-4">
                                                    <h5>Penjualan Tertinggi dan Terendah</h5>
                                                    <p><strong>Tahun dengan Penjualan Terendah:</strong>
                                                        <?= $lowest_year_data['tahun']; ?> dengan jumlah penjualan
                                                        <?= $lowest_year_data['penjualan']; ?>
                                                    </p>
                                                    <p><strong>Tahun dengan Penjualan Tertinggi:</strong>
                                                        <?= $highest_year_data['tahun']; ?> dengan jumlah penjualan
                                                        <?= $highest_year_data['penjualan']; ?>
                                                    </p>
                                                </div>

                                                <p>
                                                    Dengan persamaan ini, Anda dapat memprediksi penjualan di masa depan
                                                    berdasarkan tahun yang ada. Ini adalah contoh penerapan metode Least
                                                    Squares untuk membuat model prediksi berdasarkan data historis yang ada.
                                                </p>

                                                <!-- Tabel Data Penjualan per Tahun -->
                                                <div class="mb-4">
                                                    <h5>Data Penjualan per Tahun</h5>
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Tahun</th>
                                                                <th>Penjualan (Qty)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($data_tahun as $row): ?>
                                                                <tr>
                                                                    <td><?= $row['tahun']; ?></td>
                                                                    <td><?= $row['penjualan']; ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            document.getElementById("toggleDetailsBtn").addEventListener("click", function () {
                                                var details = document.getElementById("details");
                                                details.style.display = details.style.display === "none" ? "block" :
                                                    "none";
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

</html>