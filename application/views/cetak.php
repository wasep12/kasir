<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Cetak Nota Transaksi</title>
	<!-- Link to Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		body {
			font-family: Arial, sans-serif;
		}

		.container {
			width: 600px;
			margin: 0 auto;
			padding: 20px;
			border: 1px solid #ddd;
			background-color: #f9f9f9;
		}

		.center {
			text-align: center;
		}

		/* Mengatur tabel untuk menggunakan seluruh lebar kertas */
		.table {
			width: 100%;
			table-layout: fixed;
			/* Membuat lebar tabel otomatis sesuai dengan kertas */
			border-collapse: collapse;
		}

		.table td,
		.table th {
			vertical-align: middle;
			text-align: left;
			border: 1px solid #ddd;
			padding: 8px;
		}

		.table td.right,
		.table th.right {
			text-align: right;
		}

		hr {
			border: 1px solid #ddd;
			margin: 20px 0;
		}

		.total {
			font-weight: bold;
		}

		/* Print-specific styles */
		@media print {
			.container {
				width: 100%;
				margin: 0;
				padding: 0;
			}

			body {
				margin: 0;
				padding: 0;
			}

			.nota {
				align-items: center;
				justify-content: center;
				width: 100%;
			}

			/* Mengatur ukuran halaman A4 */
			@page {
				size: A4;
				margin: 20mm;
			}

			/* Mengatur konten agar tercetak dengan benar di tengah */
			.nota {
				text-align: center;
				width: 100%;
			}

			/* Mengatur tabel untuk mencetak dengan seluruh lebar kertas */
			.table {
				width: 100%;
				table-layout: fixed;
				/* Agar kolom memenuhi lebar kertas */
			}

			/* Menyesuaikan lebar kolom jika diperlukan */
			.table th,
			.table td {
				width: 25%;
				/* Anda bisa sesuaikan persen ini untuk kolom berbeda */
			}
		}
	</style>


</head>

<body>
	<div class="container">
		<div class="center">
			<h3><?php echo $this->session->userdata('toko')->nama; ?></h3>
			<p><?php echo $this->session->userdata('toko')->alamat; ?></p>
			<p><strong>Nota:</strong> <?php echo $nota ?></p>
			<p><strong>Tanggal:</strong> <?php echo $tanggal ?></p>
			<p>Kasir: <?= isset($kasirNama) ? htmlspecialchars($kasirNama) : 'Tidak tersedia'; ?></p>



			<hr>

			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Produk</th>
						<th class="text-end">Qty</th>
						<th class="text-end">Harga</th>
						<th class="text-end">Total</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($produk as $key): ?>
						<tr>
							<td><?php echo $key->nama_produk ?></td>
							<td class="text-end"><?php echo $key->qty ?></td>
							<td class="text-end"><?php echo number_format($key->harga, 2) ?></td>
							<td class="text-end"><?php echo number_format($key->total, 2) ?></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>

			<hr>

			<table class="table table-bordered">
				<tr>
					<td class="text-start" colspan="3"><strong>Total Harga Jual:</strong></td>
					<td class="text-end"><?php echo number_format($total_bayar, 2); ?></td>
				</tr>
				<tr>
					<td class="text-start" colspan="3"><strong>Diskon:</strong></td>
					<td class="text-end"><?php echo number_format($diskon, 2) ?></td>
				</tr>
				<tr>
					<td class="text-start" colspan="3"><strong>Total Bayar:</strong></td>
					<td class="text-end"><?php echo number_format((float) $total_bayar, 2); ?></td>

				</tr>
				<tr>
					<td class="text-start" colspan="3"><strong>Jumlah Uang:</strong></td>
					<td class="text-end">
						<?php echo isset($bayar) ? number_format($bayar, 2) : '0.00'; ?>
					</td>

					</td>

				</tr>
				<tr>
					<td class="text-start" colspan="3"><strong>Kembalian:</strong></td>
					<td class="text-end"><?php echo number_format($kembalian, 2) ?></td>
				</tr>
			</table>

			<hr>

			<p>Terima Kasih atas kunjungan Anda!</p>
			<p><?php echo $this->session->userdata('toko')->nama; ?></p>

		</div>
	</div>

	<!-- Link to Bootstrap JS (optional, but useful for Bootstrap components) -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

	<script>
		window.print();
	</script>
</body>

</html>