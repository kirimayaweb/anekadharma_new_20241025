<!doctype html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Generate Persediaan Bulan <?php echo htmlspecialchars($bulan_target); ?></title>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<style>
		body { font-family: Arial, sans-serif; margin: 20px; background: #f4f6f9; color: #333; }
		.card { background: #fff; border-radius: 8px; padding: 16px 20px; margin-bottom: 16px; box-shadow: 0 1px 3px rgba(0,0,0,.12); }
		h3 { margin-top: 0; }
		#hasil-proses { display: none; }
		#hasil-proses.selesai { display: block; }
		table { width: 100%; border-collapse: collapse; font-size: 13px; }
		th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
		th { background: #007bff; color: #fff; position: sticky; top: 0; }
		tr.insert td { background: #e8f5e9; }
		tr.update td { background: #e3f2fd; }
		tr.skip td { background: #fff8e1; }
		.badge-insert { color: #1b5e20; font-weight: bold; }
		.badge-update { color: #0d47a1; font-weight: bold; }
		.badge-skip { color: #e65100; font-weight: bold; }
		.summary-box { background: #e3f2fd; padding: 12px; border-radius: 6px; margin-bottom: 12px; }
		pre.ringkasan { background: #263238; color: #aed581; padding: 12px; border-radius: 6px; overflow: auto; font-size: 12px; }
		.swal-line-item {
			text-align: left;
			font-size: 12px;
			padding: 6px 8px;
			margin: 4px 0;
			border-left: 3px solid #007bff;
			background: #f8f9fa;
			animation: slideIn 0.35s ease-out;
		}
		.swal-line-item.insert { border-left-color: #28a745; }
		.swal-line-item.skip { border-left-color: #ff9800; }
		@keyframes slideIn {
			from { opacity: 0; transform: translateX(-12px); }
			to { opacity: 1; transform: translateX(0); }
		}
		.progress-bar-wrap { height: 8px; background: #e9ecef; border-radius: 4px; margin: 10px 0; overflow: hidden; }
		.progress-bar-fill { height: 100%; background: #007bff; width: 0%; transition: width 0.25s ease; }
	</style>
</head>
<body>

<div class="card">
	<h3>Generate Persediaan Bulan</h3>
	<p><strong>Bulan target:</strong> <?php echo htmlspecialchars($bulan_target); ?></p>
	<p><strong>tanggal_beli baru:</strong> <?php echo htmlspecialchars($tanggal_beli_target); ?></p>
	<p><strong>Salin dari tanggal_beli:</strong> <?php echo htmlspecialchars($tanggal_beli_sumber); ?></p>
	<p><strong>Total data sumber:</strong> <?php echo (int) $total_sumber; ?></p>
	<p><em>sa = total_10 - penjualan - pecah_satuan - bahan_produksi (bulan sumber)</em></p>
	<p><em>beli record baru = 0 (tidak dari bulan sumber)</em></p>
	<p><em>total_10 = sa + beli | nilai_persediaan = total_10 × hpp | tuj = sa + beli</em></p>
	<p><em>Kolom setelah tuj sampai sebelum total_10 = 0 (tidak disalin dari bulan sumber)</em></p>
	<p><em>Awal proses: (1) isi <code>uuid_barang</code> kosong di bulan sumber (uuid baru unik tiap baris); (2) perbaiki uuid ganda; (3) kosongkan bulan target; (4) salin semua record (INSERT).</em></p>
	<p><em>Record di <code>tbl_pembelian</code> / <code>tbl_pembelian_jasa</code> tetap di-copy; kolom <strong>beli</strong> diisi dari pembelian bulan target (0 jika tidak ada).</em></p>
	<p><em>Total record bulan target setelah selesai = total bulan sumber (<?php echo (int) $total_sumber; ?>).</em></p>
</div>

<div id="hasil-proses" class="card">
	<h3>Hasil Proses Lengkap</h3>
	<div id="ringkasan-akhir" class="summary-box"></div>
	<div style="max-height: 70vh; overflow: auto;">
		<table id="tabel-hasil">
			<thead>
				<tr>
					<th>No</th>
					<th>Aksi</th>
					<th>ID</th>
					<th>UUID</th>
					<th>Nama Barang</th>
					<th>Satuan</th>
					<th>HPP</th>
					<th>SA</th>
					<th>Beli</th>
					<th>Total_10</th>
					<th>Nilai Persediaan</th>
					<th>Tuj</th>
					<th>Keterangan</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
	<pre id="print-r-ringkasan" class="ringkasan"></pre>
	<p><a href="<?php echo site_url('persediaan'); ?>">Kembali ke Data Persediaan</a></p>
</div>

<script>
(function () {
	var ajaxUrl = <?php echo json_encode($ajax_url); ?>;
	var batchLimit = 25;
	var offset = 0;
	var allItems = [];
	var summaryAcc = { total_insert: 0, total_skip: 0 };

	function escapeHtml(s) {
		if (s == null) return '';
		return String(s)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;');
	}

	function formatSwalLines(items) {
		if (!items || !items.length) {
			return '<div class="swal-line-item">Menunggu data...</div>';
		}
		return items.map(function (it) {
			var cls = it.aksi === 'INSERT' ? 'insert' : (it.aksi === 'UPDATE' ? 'update' : 'skip');
			var line = '<strong>' + escapeHtml(it.aksi) + '</strong> | '
				+ escapeHtml(it.namabarang) + ' | ' + escapeHtml(it.satuan)
				+ ' | hpp: ' + escapeHtml(it.hpp);
			if (it.sa != null && it.sa !== '') {
				line += ' | sa: ' + escapeHtml(it.sa);
			}
			if (it.beli != null && it.beli !== '') {
				line += ' | beli: ' + escapeHtml(it.beli);
			}
			if (it.total_10 != null && it.total_10 !== '') {
				line += ' | total_10: ' + escapeHtml(it.total_10);
			}
			if (it.nilai_persediaan != null && it.nilai_persediaan !== '') {
				line += ' | nilai: ' + escapeHtml(it.nilai_persediaan);
			}
			if (it.tuj != null && it.tuj !== '') {
				line += ' | tuj: ' + escapeHtml(it.tuj);
			}
			if (it.keterangan) {
				line += '<br/><small>' + escapeHtml(it.keterangan) + '</small>';
			}
			return '<div class="swal-line-item ' + cls + '">' + line + '</div>';
		}).join('');
	}

	function updateSwalProgress(data) {
		var pct = data.total_sumber > 0
			? Math.min(100, Math.round((data.offset_selesai / data.total_sumber) * 100))
			: 0;
		var html = '<p style="margin:0 0 8px;"><strong>Memproses ' + data.offset_selesai + ' / ' + data.total_sumber + '</strong></p>'
			+ '<div class="progress-bar-wrap"><div class="progress-bar-fill" style="width:' + pct + '%"></div></div>'
			+ '<p style="font-size:12px;margin:8px 0 4px;">5 record terakhir yang diproses:</p>'
			+ formatSwalLines(data.last_five);

		if (Swal.isVisible()) {
			Swal.update({ html: html });
		} else {
			Swal.fire({
				title: 'Generate Persediaan',
				html: html,
				allowOutsideClick: false,
				allowEscapeKey: false,
				showConfirmButton: false,
				didOpen: function () { Swal.showLoading(); }
			});
		}
	}

	function parseJsonResponse(r) {
		return r.text().then(function (text) {
			var trimmed = (text || '').trim();
			if (!trimmed) {
				throw new Error('Respon server kosong.');
			}
			if (trimmed.charAt(0) === '<') {
				throw new Error('Server mengembalikan HTML, bukan JSON. Periksa error PHP atau sesi login.');
			}
			try {
				return JSON.parse(trimmed);
			} catch (e) {
				throw new Error('Respon tidak valid JSON: ' + trimmed.substring(0, 180));
			}
		});
	}

	function runBatch() {
		var url = ajaxUrl + (ajaxUrl.indexOf('?') >= 0 ? '&' : '?')
			+ 'ajax=1&offset=' + offset + '&limit=' + batchLimit;

		fetch(url, { credentials: 'same-origin' })
			.then(function (r) { return parseJsonResponse(r); })
			.then(function (data) {
				if (!data.ok) {
					Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Proses gagal' });
					return;
				}

				if (data.fixuuid && data.fixuuid.record_kosong > 0) {
					var fmsg = (data.fixuuid.pesan || '')
						+ '<br/><small>Diperbaiki: ' + data.fixuuid.record_diperbaiki + ' record</small>';
					if (data.fixuuid.rekap_penyebab && data.fixuuid.rekap_penyebab.length) {
						fmsg += '<br/><small><strong>Penyebab:</strong> ';
						fmsg += data.fixuuid.rekap_penyebab.map(function (r) {
							return (r.label || r.kode) + ' (' + r.jumlah + ')';
						}).join(', ');
						fmsg += '</small>';
					}
					Swal.fire({
						icon: 'info',
						title: 'Perbaikan uuid_barang kosong',
						html: fmsg,
						timer: 5000,
						showConfirmButton: true
					});
				}

				if (data.dedup && data.dedup.grup_duplikat > 0) {
					var dmsg = (data.dedup.pesan || '')
						+ '<br/><small>Grup duplikat: ' + data.dedup.grup_duplikat
						+ ', diperbaiki: ' + data.dedup.record_diperbaiki + '</small>';
					Swal.fire({
						icon: 'info',
						title: 'Perbaikan uuid_barang ganda',
						html: dmsg,
						timer: 4500,
						showConfirmButton: true
					});
				}

				if (data.reset_target && data.reset_target.dihapus > 0) {
					Swal.fire({
						icon: 'info',
						title: 'Reset bulan target',
						html: 'Menghapus <strong>' + data.reset_target.dihapus + '</strong> record lama '
							+ '(tanggal_beli ' + escapeHtml(data.reset_target.tanggal_beli) + ') sebelum salin ulang.',
						timer: 4000,
						showConfirmButton: true
					});
				}

				if (data.items && data.items.length) {
					allItems = allItems.concat(data.items);
				}
				summaryAcc.total_insert += data.batch_insert || 0;
				summaryAcc.total_skip += data.batch_skip || 0;

				updateSwalProgress(data);
				offset = data.offset_selesai;

				if (!data.done) {
					setTimeout(runBatch, 80);
					return;
				}

				Swal.close();
				tampilkanHasilLengkap(data.summary);
				Swal.fire({
					icon: 'success',
					title: 'Selesai',
					html: 'Total insert: <strong>' + data.summary.total_insert + '</strong><br/>'
						+ 'Total record bulan target: <strong>' + (data.summary.total_target_akhir || data.summary.total_insert) + '</strong>'
						+ ' (harus = sumber ' + data.summary.total_sumber + ')<br/>'
						+ 'Total skip: <strong>' + data.summary.total_skip + '</strong><br/>'
						+ '<small>Daftar lengkap ditampilkan di bawah.</small>',
					timer: 3500,
					showConfirmButton: true,
					confirmButtonText: 'OK'
				});
			})
			.catch(function (err) {
				Swal.fire({ icon: 'error', title: 'Error', text: String(err) });
			});
	}

	function tampilkanHasilLengkap(summary) {
		var wrap = document.getElementById('hasil-proses');
		var tbody = document.querySelector('#tabel-hasil tbody');
		var ringkasan = document.getElementById('ringkasan-akhir');
		var pre = document.getElementById('print-r-ringkasan');

		ringkasan.innerHTML = '<strong>SELESAI PROSES</strong><br/>'
			+ 'Bulan target: ' + escapeHtml(summary.bulan_target) + '<br/>'
			+ 'tanggal_beli target: ' + escapeHtml(summary.tanggal_beli_target) + '<br/>'
			+ 'tanggal_beli sumber: ' + escapeHtml(summary.tanggal_beli_sumber) + '<br/>'
			+ 'Total sumber: ' + summary.total_sumber + '<br/>'
			+ 'Total insert: ' + summary.total_insert + '<br/>'
			+ 'Total record bulan target: <strong>' + (summary.total_target_akhir || summary.total_insert) + '</strong>'
			+ ' (harus sama dengan sumber)<br/>'
			+ 'Total skip: ' + summary.total_skip;
		if (summary.reset_target && summary.reset_target.dihapus > 0) {
			ringkasan.innerHTML += '<br/><strong>Reset bulan target:</strong> '
				+ summary.reset_target.dihapus + ' record lama dihapus.';
		}
		if (summary.fixuuid && summary.fixuuid.record_kosong > 0) {
			ringkasan.innerHTML += '<br/><strong>Perbaikan uuid_barang kosong (bulan sumber):</strong> '
				+ summary.fixuuid.record_diperbaiki + ' / ' + summary.fixuuid.record_kosong + ' record.';
			if (summary.fixuuid.rekap_penyebab && summary.fixuuid.rekap_penyebab.length) {
				ringkasan.innerHTML += ' Penyebab: ';
				ringkasan.innerHTML += summary.fixuuid.rekap_penyebab.map(function (r) {
					return (r.label || r.kode) + ' (' + r.jumlah + ')';
				}).join(', ');
				ringkasan.innerHTML += '.';
			}
		}
		if (summary.dedup) {
			ringkasan.innerHTML += '<br/><strong>Perbaikan uuid ganda (bulan sumber):</strong> '
				+ summary.dedup.grup_duplikat + ' grup, '
				+ summary.dedup.record_diperbaiki + ' record uuid baru, '
				+ summary.dedup.record_tetap + ' record tetap.';
		}
		if (summary.uuid_kosong_target_akhir !== undefined) {
			ringkasan.innerHTML += '<br/><strong>uuid_barang kosong di bulan target setelah generate:</strong> '
				+ summary.uuid_kosong_target_akhir;
		}

		var html = '';
		for (var i = 0; i < allItems.length; i++) {
			var it = allItems[i];
			var trClass = it.aksi === 'INSERT' ? 'insert' : (it.aksi === 'UPDATE' ? 'update' : 'skip');
			var badgeCls = it.aksi === 'INSERT' ? 'badge-insert' : (it.aksi === 'UPDATE' ? 'badge-update' : 'badge-skip');
			html += '<tr class="' + trClass + '">'
				+ '<td>' + (i + 1) + '</td>'
				+ '<td class="' + badgeCls + '">' + escapeHtml(it.aksi) + '</td>'
				+ '<td>' + escapeHtml(it.id) + '</td>'
				+ '<td style="font-size:11px;word-break:break-all;">' + escapeHtml(it.uuid_persediaan) + '</td>'
				+ '<td>' + escapeHtml(it.namabarang) + '</td>'
				+ '<td>' + escapeHtml(it.satuan) + '</td>'
				+ '<td>' + escapeHtml(it.hpp) + '</td>'
				+ '<td>' + escapeHtml(it.sa) + '</td>'
				+ '<td>' + escapeHtml(it.beli) + '</td>'
				+ '<td>' + escapeHtml(it.total_10) + '</td>'
				+ '<td>' + escapeHtml(it.nilai_persediaan) + '</td>'
				+ '<td>' + escapeHtml(it.tuj) + '</td>'
				+ '<td><small>' + escapeHtml(it.keterangan) + '</small></td>'
				+ '</tr>';
		}
		tbody.innerHTML = html;
		pre.textContent = JSON.stringify(summary, null, 2);
		wrap.classList.add('selesai');
		window.scrollTo(0, document.getElementById('hasil-proses').offsetTop - 10);
	}

	updateSwalProgress({
		total_sumber: <?php echo (int) $total_sumber; ?>,
		offset_selesai: 0,
		last_five: []
	});
	runBatch();
})();
</script>
</body>
</html>
