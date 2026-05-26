<!doctype html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Recalculate Penjualan → Persediaan <?php echo htmlspecialchars($bulan_label); ?></title>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<style>
		body { font-family: Arial, sans-serif; margin: 20px; background: #f4f6f9; color: #333; }
		.card { background: #fff; border-radius: 8px; padding: 16px 20px; margin-bottom: 16px; box-shadow: 0 1px 3px rgba(0,0,0,.12); }
		h3 { margin-top: 0; }
		#hasil-proses { display: none; }
		#hasil-proses.selesai { display: block; }
		table { width: 100%; border-collapse: collapse; font-size: 13px; }
		th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
		th { background: #17a2b8; color: #fff; position: sticky; top: 0; }
		tr.ok td { background: #e8f5e9; }
		tr.skip td { background: #fff8e1; }
		.summary-box { background: #e3f2fd; padding: 12px; border-radius: 6px; margin-bottom: 12px; }
		pre.ringkasan { background: #263238; color: #aed581; padding: 12px; border-radius: 6px; overflow: auto; font-size: 12px; }
		.progress-bar-wrap { height: 8px; background: #e9ecef; border-radius: 4px; margin: 10px 0; overflow: hidden; }
		.progress-bar-fill { height: 100%; background: #17a2b8; width: 0%; transition: width 0.25s ease; }
	</style>
</head>
<body>

<div class="card">
	<h3>Recalculate Penjualan → Persediaan</h3>
	<p><strong>Bulan:</strong> <?php echo htmlspecialchars($bulan_label); ?> (<?php echo htmlspecialchars($bulan); ?>)</p>
	<p><strong>tanggal_beli persediaan:</strong> <?php echo htmlspecialchars($tanggal_beli); ?></p>
	<p><strong>Rentang tgl_jual:</strong> <?php echo htmlspecialchars($tgl_awal); ?> s/d <?php echo htmlspecialchars($tgl_akhir); ?></p>
	<p><strong>Record persediaan bulan ini:</strong> <?php echo (int) $total_persediaan; ?></p>
	<p><strong>Record tbl_penjualan bulan ini:</strong> <?php echo (int) $total_penjualan; ?></p>
	<p><em>Langkah: reset kolom penjualan + unit → baca penjualan → cocokkan uuid_persediaan/uuid_barang + satuan + hpp → update kolom unit &amp; penjualan.</em></p>
</div>

<div id="hasil-proses" class="card">
	<h3>Hasil Proses</h3>
	<div id="ringkasan-akhir" class="summary-box"></div>
	<div style="max-height: 70vh; overflow: auto;">
		<table id="tabel-hasil">
			<thead>
				<tr>
					<th>No</th>
					<th>Status</th>
					<th>ID Jual</th>
					<th>ID Persediaan</th>
					<th>Nama Barang</th>
					<th>Jumlah</th>
					<th>Unit / Kolom</th>
					<th>Keterangan</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
	<pre id="print-r-ringkasan" class="ringkasan"></pre>
	<p><a href="<?php echo site_url('Persediaan/recalculate_data_persediaan'); ?>">Recalculate bulan lain</a> |
		<a href="<?php echo site_url('persediaan'); ?>">Kembali ke Data Persediaan</a></p>
</div>

<script>
(function () {
	var ajaxUrl = <?php echo json_encode($ajax_url); ?>;
	var batchLimit = 50;
	var offset = 0;
	var allItems = [];

	function escapeHtml(s) {
		if (s == null) return '';
		return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
	}

	function updateSwalProgress(data) {
		var pct = data.total_penjualan > 0
			? Math.min(100, Math.round((data.offset_selesai / data.total_penjualan) * 100))
			: 100;
		var html = '<p style="margin:0 0 8px;"><strong>Memproses penjualan ' + data.offset_selesai + ' / ' + data.total_penjualan + '</strong></p>'
			+ '<div class="progress-bar-wrap"><div class="progress-bar-fill" style="width:' + pct + '%"></div></div>';
		if (Swal.isVisible()) {
			Swal.update({ html: html });
		} else {
			Swal.fire({
				title: 'Recalculate Penjualan',
				html: html,
				allowOutsideClick: false,
				showConfirmButton: false,
				didOpen: function () { Swal.showLoading(); }
			});
		}
	}

	function runBatch() {
		var url = ajaxUrl + (ajaxUrl.indexOf('?') >= 0 ? '&' : '?')
			+ 'ajax=1&offset=' + offset + '&limit=' + batchLimit;

		fetch(url, { credentials: 'same-origin' })
			.then(function (r) { return r.json(); })
			.then(function (data) {
				if (!data.ok) {
					Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Proses gagal' });
					return;
				}

				if (data.reset && data.reset.record_direset > 0) {
					Swal.fire({
						icon: 'info',
						title: 'Reset kolom penjualan',
						html: 'Reset <strong>' + data.reset.record_direset + '</strong> record persediaan '
							+ '(penjualan + kolom unit = 0).',
						timer: 3500,
						showConfirmButton: true
					});
				}

				if (data.items && data.items.length) {
					allItems = allItems.concat(data.items);
				}

				updateSwalProgress(data);
				offset = data.offset_selesai;

				if (!data.done) {
					setTimeout(runBatch, 80);
					return;
				}

				Swal.close();
				tampilkanHasil(data.summary);
				Swal.fire({
					icon: 'success',
					title: 'Selesai',
					html: 'Berhasil: <strong>' + data.summary.total_ok + '</strong><br/>'
						+ 'Skip: <strong>' + data.summary.total_skip + '</strong><br/>'
						+ 'Update persediaan: <strong>' + data.summary.total_update_persediaan + '</strong>',
					timer: 4000,
					showConfirmButton: true
				});
			})
			.catch(function (err) {
				Swal.fire({ icon: 'error', title: 'Error', text: String(err) });
			});
	}

	function tampilkanHasil(summary) {
		var wrap = document.getElementById('hasil-proses');
		var tbody = document.querySelector('#tabel-hasil tbody');
		var ringkasan = document.getElementById('ringkasan-akhir');
		var pre = document.getElementById('print-r-ringkasan');

		ringkasan.innerHTML = '<strong>SELESAI</strong><br/>'
			+ 'Bulan: ' + escapeHtml(summary.bulan_label) + '<br/>'
			+ 'Penjualan diproses: ' + summary.total_penjualan + '<br/>'
			+ 'Berhasil cocok &amp; update: ' + summary.total_ok + '<br/>'
			+ 'Skip (tidak cocok): ' + summary.total_skip + '<br/>'
			+ 'Baris persediaan di-update: ' + summary.total_update_persediaan;

		var html = '';
		for (var i = 0; i < allItems.length; i++) {
			var it = allItems[i];
			var trClass = it.status === 'OK' ? 'ok' : 'skip';
			html += '<tr class="' + trClass + '">'
				+ '<td>' + (i + 1) + '</td>'
				+ '<td>' + escapeHtml(it.status) + '</td>'
				+ '<td>' + escapeHtml(it.id_penjualan) + '</td>'
				+ '<td>' + escapeHtml(it.id_persediaan || '') + '</td>'
				+ '<td>' + escapeHtml(it.namabarang || it.nama_barang || '') + '</td>'
				+ '<td>' + escapeHtml(it.jumlah || '') + '</td>'
				+ '<td>' + escapeHtml((it.unit || '') + (it.kolom_unit ? ' → ' + it.kolom_unit : '')) + '</td>'
				+ '<td><small>' + escapeHtml(it.keterangan || '') + '</small></td>'
				+ '</tr>';
		}
		tbody.innerHTML = html;
		pre.textContent = JSON.stringify(summary, null, 2);
		wrap.classList.add('selesai');
		window.scrollTo(0, wrap.offsetTop - 10);
	}

	updateSwalProgress({
		total_penjualan: <?php echo (int) $total_penjualan; ?>,
		offset_selesai: 0
	});
	runBatch();
})();
</script>
</body>
</html>
