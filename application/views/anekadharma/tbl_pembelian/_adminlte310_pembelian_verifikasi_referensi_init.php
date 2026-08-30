<?php
if (!isset($pem_ref_cfg) || !is_array($pem_ref_cfg)) {
	$pem_ref_cfg = array();
}
$pem_ref_defaults = array(
	'table_sel' => '#tglSPOPFreezeBelumPersediaan',
	'subtabs_content' => '#pembelian-persediaan-subtabs-content',
	'subtabs_ul' => '#pembelian-persediaan-subtabs',
	'main_tab_href' => '#tab-pembelian-verifikasi',
	'main_tabs' => '#pembelian-main-tabs',
	'badge_link' => '#tab-pembelian-verifikasi-link',
	'main_data_tab_href' => '#tab-pembelian-data',
	'session_subtab_key' => 'pem_persediaan_subtab',
	'subtab_manual_id' => 'pembelian-persediaan-manual',
	'tabel' => isset($pembelian_tabel_referensi) ? $pembelian_tabel_referensi : 'tbl_pembelian',
	'want_jasa' => !empty($pembelian_want_jasa_referensi) ? '1' : '0',
);
$pem_ref_cfg = array_merge($pem_ref_defaults, $pem_ref_cfg);
?>
<script>
(function() {
	var cfg = <?php echo json_encode($pem_ref_cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

	function escapeHtmlPem(s) {
		return String(s == null ? '' : s)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;');
	}

	function initPembelianVerifikasiReferensi() {
		if (!window.jQuery || !jQuery.fn.DataTable) {
			return;
		}

		var urlList = <?php echo json_encode(isset($url_pembelian_referensi_list) ? $url_pembelian_referensi_list : site_url('Tbl_pembelian/ajax_pembelian_referensi_persediaan_list')); ?>;
		var urlApply = <?php echo json_encode(isset($url_pembelian_referensi_apply) ? $url_pembelian_referensi_apply : site_url('Tbl_pembelian/ajax_pembelian_referensi_persediaan_apply')); ?>;
		var bulanDefault = <?php echo json_encode(isset($pembelian_bulan_referensi) ? $pembelian_bulan_referensi : ''); ?>;
		var tableSel = cfg.table_sel;

		jQuery('#modal-pem-referensi-persediaan, #modal-pem-isi-jumlah-refered').appendTo('body');

		function normSatuanPem(s) {
			return String(s == null ? '' : s).toLowerCase().replace(/\s+/g, ' ').trim();
		}
		function satuanCocokPem(a, b) {
			a = normSatuanPem(a);
			b = normSatuanPem(b);
			if (a === '' || b === '') return true;
			if (a === b) return true;
			var n = Math.min(a.length, b.length);
			if (n >= 3 && a.slice(0, n) === b.slice(0, n)) return true;
			if (a.indexOf(b) === 0 || b.indexOf(a) === 0) return true;
			return false;
		}
		function parseAngkaPemRef(v) {
			var s = String(v == null ? '' : v).trim();
			if (!s || s === '-') return 0;
			if (s.indexOf(',') >= 0) {
				s = s.replace(/\./g, '').replace(',', '.');
			} else if (/^\d{1,3}(\.\d{3})+$/.test(s)) {
				s = s.replace(/\./g, '');
			}
			s = s.replace(/[^0-9.\-]/g, '');
			var n = parseFloat(s);
			return isNaN(n) ? 0 : n;
		}
		function notifyPemReferensi(msg, icon) {
			icon = icon || 'warning';
			var cls = (icon === 'error') ? 'danger' : 'warning';
			jQuery('#pem-referensi-alert').html('<div class="alert alert-' + cls + ' py-2 mb-0">' + escapeHtmlPem(msg) + '</div>');
			if (typeof Swal !== 'undefined') {
				Swal.fire({ icon: icon, title: (icon === 'error' ? 'Gagal' : 'Perhatian'), text: msg });
				setTimeout(function() { jQuery('.swal2-container').css('z-index', 20000); }, 30);
			} else {
				window.alert(msg);
			}
		}

		function parseAngkaPersediaanDt(val) {
			if (val == null || val === '') return 0;
			var s = String(val).replace(/<[^>]+>/g, '').trim();
			if (s.indexOf(',') >= 0) {
				s = s.replace(/\./g, '').replace(/,/g, '.');
			} else {
				s = s.replace(/[^\d.-]/g, '');
			}
			var n = parseFloat(s);
			return isNaN(n) ? 0 : n;
		}
		function formatAngkaPersediaanDt(n, maxDec) {
			var dec = (typeof maxDec === 'number') ? maxDec : 2;
			var rounded = Math.round(n * Math.pow(10, dec)) / Math.pow(10, dec);
			var parts = String(rounded).split('.');
			var intPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
			if (dec === 0 || !parts[1]) return intPart;
			var frac = (parts[1] || '').slice(0, dec);
			while (frac.length < dec) frac += '0';
			return intPart + ',' + frac;
		}

		function buildPembelianFooterCallback(hasReferensi) {
			return function() {
				var api = this.api();
				var totalJumlah = 0;
				var totalHarga = 0;
				api.rows({ search: 'applied' }).every(function() {
					var node = this.node();
					if (!node) return;
					var $row = jQuery(node);
					totalJumlah += parseAngkaPersediaanDt($row.find('td.pem-persediaan-col-jumlah').attr('data-num'));
					totalHarga += parseAngkaPersediaanDt($row.find('td.pem-persediaan-col-harga-total').attr('data-num'));
				});
				var jDec = (Math.abs(totalJumlah - Math.round(totalJumlah)) < 0.0001) ? 0 : 2;
				jQuery(api.table().footer()).find('.pem-persediaan-foot-jumlah').html(formatAngkaPersediaanDt(totalJumlah, jDec));
				jQuery(api.table().footer()).find('.pem-persediaan-foot-harga-total').html(formatAngkaPersediaanDt(totalHarga, 2));
			};
		}

		function initPersediaanDtTable(sel) {
			if (!sel || !jQuery(sel).length || jQuery.fn.DataTable.isDataTable(sel)) {
				if (sel && jQuery.fn.DataTable.isDataTable(sel)) {
					try { jQuery(sel).DataTable().columns.adjust(); } catch (eAdj) {}
				}
				return;
			}
			var hasReferensi = jQuery(sel + ' thead th').filter(function() {
				return jQuery(this).text().trim() === 'Referensi';
			}).length > 0;
			var colJumlah = hasReferensi ? 6 : 5;
			var colHargaTotal = hasReferensi ? 8 : 7;
			var dtOpts = {
				scrollX: true,
				pageLength: 10,
				order: [[hasReferensi ? 2 : 1, 'asc']],
				language: { emptyTable: 'Tidak ada data' },
				footerCallback: buildPembelianFooterCallback(hasReferensi),
				columnDefs: [{ targets: [colJumlah, colHargaTotal], className: 'text-right' }]
			};
			if (hasReferensi) {
				dtOpts.columnDefs.push({ targets: [1], orderable: false });
				dtOpts.language.emptyTable = 'Semua pembelian sudah terverifikasi ke persediaan';
			}
			jQuery(sel).DataTable(dtOpts);
		}

		function initVisiblePersediaanSubtabDt() {
			var $activePane = jQuery(cfg.subtabs_content + ' .tab-pane.active');
			if (!$activePane.length) {
				initPersediaanDtTable(tableSel);
				return;
			}
			var $tbl = $activePane.find('table.pembelian-persediaan-dt-table').first();
			if ($tbl.length && $tbl.attr('id')) {
				initPersediaanDtTable('#' + $tbl.attr('id'));
			}
		}

		initVisiblePersediaanSubtabDt();

		try {
			var savedSub = sessionStorage.getItem(cfg.session_subtab_key);
			if (savedSub) {
				sessionStorage.removeItem(cfg.session_subtab_key);
				jQuery(cfg.main_tabs + ' a[href="' + cfg.main_tab_href + '"]').tab('show');
				setTimeout(function() {
					jQuery(cfg.subtabs_ul + ' a[href="#' + savedSub + '"]').tab('show');
				}, 250);
			}
		} catch (eSub) {}

		jQuery(cfg.subtabs_ul + ' a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			var href = jQuery(e.target).attr('href') || '';
			if (href.charAt(0) === '#') {
				var $tbl = jQuery(href).find('table.pembelian-persediaan-dt-table').first();
				if ($tbl.length && $tbl.attr('id')) {
					setTimeout(function() { initPersediaanDtTable('#' + $tbl.attr('id')); }, 50);
				}
			}
		});

		jQuery(cfg.main_tabs + ' a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			var href = jQuery(e.target).attr('href') || '';
			if (href === cfg.main_tab_href) {
				setTimeout(initVisiblePersediaanSubtabDt, 80);
			}
			if (cfg.main_data_tab_href && href === cfg.main_data_tab_href && jQuery.fn.DataTable.isDataTable('#tglSPOPFreeze')) {
				setTimeout(function() {
					try { jQuery('#tglSPOPFreeze').DataTable().columns.adjust(); } catch (eMain) {}
				}, 80);
			}
			if (cfg.main_data_tab_href && href === cfg.main_data_tab_href && jQuery.fn.DataTable.isDataTable('#tblPembelianJasaList')) {
				setTimeout(function() {
					try { jQuery('#tblPembelianJasaList').DataTable().columns.adjust(); } catch (eJasa) {}
				}, 80);
			}
		});

		var refState = { idPembelian: 0, bulanKey: '', meta: null, dt: null, persediaanMap: {} };

		function resolveBulanReferensi() {
			if (bulanDefault && /^\d{4}-\d{2}$/.test(bulanDefault)) return bulanDefault;
			var tglAwal = jQuery('input[name="tgl_awal"]').val() || '';
			var m = String(tglAwal).match(/(\d{4})[-\/](\d{1,2})/);
			if (m) return m[1] + '-' + ('0' + m[2]).slice(-2);
			var m2 = String(tglAwal).match(/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/);
			if (m2) return m2[3] + '-' + ('0' + m2[2]).slice(-2);
			return '';
		}

		function openPemReferensiModal(idPembelian, meta) {
			var bulanKey = resolveBulanReferensi();
			if (!bulanKey) {
				notifyPemReferensi('Bulan tidak dikenali. Set tanggal filter pembelian dulu.', 'error');
				return;
			}
			refState.idPembelian = idPembelian;
			refState.bulanKey = bulanKey;
			refState.meta = meta || {};
			jQuery('#pem-referensi-alert').empty();
			jQuery('#pem-referensi-meta').html(
				'<div class="text-dark">Pembelian ID <strong>' + idPembelian + '</strong> — '
				+ '<strong style="font-size:1.35rem;">' + escapeHtmlPem(refState.meta.uraian || '') + '</strong>'
				+ ' / ' + escapeHtmlPem(refState.meta.satuan || '')
				+ ' qty <strong>' + escapeHtmlPem(String(refState.meta.jumlah || '')) + '</strong>'
				+ ' &nbsp;|&nbsp; Persediaan bulan <strong>' + escapeHtmlPem(bulanKey) + '</strong></div>'
				+ '<div class="small mt-1 text-muted">Pembelian menambah stok. Klik <strong>Refered</strong> untuk isi jumlah.</div>'
			);
			jQuery('#pem-referensi-loading').removeClass('d-none');
			if (refState.dt && jQuery.fn.DataTable.isDataTable('#tbl-pem-referensi-persediaan')) {
				try { jQuery('#tbl-pem-referensi-persediaan').DataTable().destroy(); } catch (eD) {}
				refState.dt = null;
			}
			jQuery('#tbl-pem-referensi-persediaan tbody').empty();
			jQuery('#modal-pem-referensi-persediaan').modal('show');

			var satRef = normSatuanPem(refState.meta.satuan);
			refState.persediaanMap = {};

			jQuery.ajax({
				url: urlList,
				type: 'POST',
				dataType: 'json',
				data: { bulan: bulanKey, want_jasa: cfg.want_jasa, id_pembelian: idPembelian }
			}).done(function(res) {
				jQuery('#pem-referensi-loading').addClass('d-none');
				if (!res || !res.ok) {
					jQuery('#pem-referensi-alert').html('<div class="alert alert-danger py-2 mb-0">' + escapeHtmlPem((res && res.message) ? res.message : 'Gagal memuat persediaan.') + '</div>');
					return;
				}
				var rows = (res.rows || []).map(function(r) {
					var idp = parseInt(r.id, 10) || 0;
					if (idp > 0) refState.persediaanMap[idp] = r;
					var satuanOk = satuanCocokPem(satRef, r.satuan);
					var colorSatuan = satuanOk ? '#006400' : '#d4a017';
					var btnClass = satuanOk ? 'btn-success' : 'btn-warning';
					return [
						'<button type="button" class="btn btn-xs ' + btnClass + ' btn-pem-refered" data-id-persediaan="' + idp + '">Refered</button>',
						idp,
						'<span style="font-weight:600;">' + escapeHtmlPem(r.namabarang || '') + '</span>',
						'<span style="color:' + colorSatuan + ';font-weight:700;">' + escapeHtmlPem(r.satuan || '') + '</span>',
						escapeHtmlPem(r.hpp || ''),
						escapeHtmlPem(r.sa || ''),
						escapeHtmlPem(r.beli || ''),
						escapeHtmlPem(r.penjualan || ''),
						escapeHtmlPem(r.total_10 || '')
					];
				});
				refState.dt = jQuery('#tbl-pem-referensi-persediaan').DataTable({
					data: rows,
					pageLength: 10,
					scrollX: true,
					order: [[2, 'asc']],
					columnDefs: [{ targets: [0], orderable: false }],
					language: { emptyTable: 'Tidak ada data persediaan di bulan ini' }
				});
			}).fail(function() {
				jQuery('#pem-referensi-loading').addClass('d-none');
				jQuery('#pem-referensi-alert').html('<div class="alert alert-danger py-2 mb-0">Gagal menghubungi server.</div>');
			});
		}

		jQuery(document).on('click', '.btn-pem-referensi-persediaan', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var $btn = jQuery(this);
			var idPem = parseInt($btn.attr('data-id-pembelian'), 10) || 0;
			if (idPem < 1) {
				notifyPemReferensi('ID pembelian tidak valid.', 'error');
				return;
			}
			openPemReferensiModal(idPem, {
				uraian: $btn.attr('data-uraian') || '',
				satuan: $btn.attr('data-satuan') || '',
				jumlah: $btn.attr('data-jumlah') || '',
				spop: $btn.attr('data-spop') || '',
				supplier: $btn.attr('data-supplier') || ''
			});
		});

		jQuery(document).on('click', '.btn-pem-refered', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var idPers = parseInt(jQuery(this).attr('data-id-persediaan'), 10) || 0;
			var idPem = refState.idPembelian || 0;
			var bulanKey = refState.bulanKey || resolveBulanReferensi();
			if (idPers < 1 || idPem < 1 || !bulanKey) {
				notifyPemReferensi('Data referensi tidak lengkap.', 'error');
				return;
			}
			var rowPers = refState.persediaanMap[idPers] || {};
			var qtyRef = parseAngkaPemRef(refState.meta && refState.meta.jumlah);
			var satRef = (refState.meta && refState.meta.satuan) ? refState.meta.satuan : '';
			var satPers = rowPers.satuan || '';
			var satuanOk = satuanCocokPem(satRef, satPers);
			var namaPers = rowPers.namabarang || '';
			var defaultJumlah = qtyRef > 0 ? qtyRef : 1;
			if (defaultJumlah < 1) defaultJumlah = 1;

			jQuery('#pem-isi-jumlah-alert').html(
				!satuanOk
					? '<div class="alert alert-warning py-2 mb-0">Satuan pembelian ("' + escapeHtmlPem(satRef) + '") berbeda dari persediaan ("' + escapeHtmlPem(satPers) + '").</div>'
					: ''
			);
			jQuery('#pem-isi-jumlah-d-id').text(String(idPers));
			jQuery('#pem-isi-jumlah-d-nama').html('<strong>' + escapeHtmlPem(namaPers) + '</strong>');
			jQuery('#pem-isi-jumlah-d-kode').text((rowPers.kode_barang || '-') + ' / ' + (rowPers.spop || '-'));
			jQuery('#pem-isi-jumlah-d-satuan').text(satPers || '-');
			jQuery('#pem-isi-jumlah-d-hpp').text(rowPers.hpp || '-');
			jQuery('#pem-isi-jumlah-d-mutasi').text('SA ' + (rowPers.sa || '0') + ' | Beli ' + (rowPers.beli || '0') + ' | Penjualan ' + (rowPers.penjualan || '0'));
			jQuery('#pem-isi-jumlah-d-total10').html('<strong>' + escapeHtmlPem(String(rowPers.total_10 != null ? rowPers.total_10 : '-')) + '</strong>');
			jQuery('#pem-isi-jumlah-d-uuid').html('<small>' + escapeHtmlPem(rowPers.uuid_persediaan || '-') + '</small>');
			jQuery('#pem-isi-jumlah-label').text('Jumlah (qty pembelian = ' + qtyRef + ')');
			jQuery('#pem-isi-jumlah-input').attr('min', 1).val(defaultJumlah);
			jQuery('#pem-isi-jumlah-meta-qty').html(
				'<div><strong>Pembelian</strong> ID ' + idPem
				+ ' — <strong>' + escapeHtmlPem((refState.meta && refState.meta.uraian) ? refState.meta.uraian : '') + '</strong>'
				+ ' / ' + escapeHtmlPem(satRef)
				+ ' — qty <strong>' + qtyRef + '</strong></div>'
				+ '<div class="mt-1">SPOP: <strong>' + escapeHtmlPem((refState.meta && refState.meta.spop) ? refState.meta.spop : '-') + '</strong>'
				+ ' &nbsp;|&nbsp; Bulan: <strong>' + escapeHtmlPem(bulanKey) + '</strong></div>'
			);
			jQuery('#pem-isi-jumlah-id-persediaan').val(String(idPers));
			jQuery('#pem-isi-jumlah-id-pembelian').val(String(idPem));
			jQuery('#pem-isi-jumlah-bulan').val(bulanKey);
			jQuery('#pem-isi-jumlah-force').val('0');
			jQuery('#pem-isi-jumlah-tabel').val(cfg.tabel);
			jQuery('#btn-pem-isi-jumlah-simpan').prop('disabled', false);
			jQuery('#modal-pem-isi-jumlah-refered').css('z-index', 1065).modal('show');
		});

		function submitPemIsiJumlahRefered(forceFlag) {
			var idPers = parseInt(jQuery('#pem-isi-jumlah-id-persediaan').val(), 10) || 0;
			var idPem = parseInt(jQuery('#pem-isi-jumlah-id-pembelian').val(), 10) || 0;
			var bulanKey = jQuery('#pem-isi-jumlah-bulan').val() || '';
			var jumlah = parseFloat(jQuery('#pem-isi-jumlah-input').val()) || 0;
			var tabel = jQuery('#pem-isi-jumlah-tabel').val() || cfg.tabel;
			if (idPers < 1 || idPem < 1 || !bulanKey) return;
			if (jumlah < 1) {
				jQuery('#pem-isi-jumlah-alert').html('<div class="alert alert-warning py-2 mb-0">Jumlah minimal 1.</div>');
				return;
			}
			var $btnSimpan = jQuery('#btn-pem-isi-jumlah-simpan').prop('disabled', true);
			jQuery.ajax({
				url: urlApply,
				type: 'POST',
				dataType: 'json',
				data: {
					bulan: bulanKey,
					id_pembelian: idPem,
					id_persediaan: idPers,
					jumlah: jumlah,
					force: forceFlag ? '1' : '0',
					tabel: tabel
				}
			}).done(function(res) {
				if (res && res.need_confirm_uuid) {
					$btnSimpan.prop('disabled', false);
					var msg = (res && res.message) ? res.message : 'Ada uuid sinkron di pembelian & persediaan.';
					if (typeof Swal !== 'undefined') {
						Swal.fire({
							icon: 'warning',
							title: 'UUID sinkron ditemukan',
							html: escapeHtmlPem(msg),
							showCancelButton: true,
							confirmButtonText: 'Lanjut paksa SIMPAN',
							cancelButtonText: 'Batal'
						}).then(function(result) {
							if (result.isConfirmed) submitPemIsiJumlahRefered(true);
						});
					} else if (window.confirm(msg)) {
						submitPemIsiJumlahRefered(true);
					}
					return;
				}
				if (!res || !res.ok) {
					jQuery('#pem-isi-jumlah-alert').html('<div class="alert alert-danger py-2 mb-0">' + escapeHtmlPem((res && res.message) ? res.message : 'Gagal simpan.') + '</div>');
					$btnSimpan.prop('disabled', false);
					return;
				}
				jQuery('#modal-pem-isi-jumlah-refered').modal('hide');
				jQuery('#modal-pem-referensi-persediaan').modal('hide');
				$btnSimpan.prop('disabled', false);
				var doneMsg = res.message || ('Persediaan di-update sejumlah ' + jumlah);
				try { sessionStorage.setItem(cfg.session_subtab_key, cfg.subtab_manual_id); } catch (eSs) {}
				if (typeof Swal !== 'undefined') {
					Swal.fire({ icon: 'success', title: 'Refered berhasil', text: doneMsg }).then(function() {
						window.location.reload();
					});
				} else {
					alert(doneMsg);
					window.location.reload();
				}
			}).fail(function() {
				jQuery('#pem-isi-jumlah-alert').html('<div class="alert alert-danger py-2 mb-0">Gagal menghubungi server.</div>');
				$btnSimpan.prop('disabled', false);
			});
		}

		jQuery('#form-pem-isi-jumlah-refered').on('submit', function(e) {
			e.preventDefault();
			submitPemIsiJumlahRefered(jQuery('#pem-isi-jumlah-force').val() === '1');
		});

		jQuery(document).on('click', '.btn-cetak-excel-pembelian-persediaan-section', function(e) {
			e.preventDefault();
			var tableSelExport = jQuery(this).data('table');
			var filename = jQuery(this).data('filename') || 'Pembelian_Persediaan';
			var $table = jQuery(tableSelExport);
			if (!$table.length) return;
			var headers = [];
			$table.find('thead tr:first th').each(function() { headers.push(jQuery(this).text().trim()); });
			var rows = [];
			if (jQuery.fn.DataTable.isDataTable(tableSelExport)) {
				jQuery(tableSelExport).DataTable().rows({ search: 'applied' }).every(function() {
					var d = this.data();
					var line = [];
					for (var i = 0; i < headers.length; i++) {
						line.push(d[i] == null ? '' : String(d[i]).replace(/<[^>]+>/g, '').trim());
					}
					rows.push(line);
				});
			}
			function escXml(s) {
				return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
			}
			var xml = '<' + '?xml version="1.0"?>' + '<' + '?mso-application progid="Excel.Sheet"?><Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"><Worksheet ss:Name="Data"><Table>';
			xml += '<Row>'; headers.forEach(function(h) { xml += '<Cell><Data ss:Type="String">' + escXml(h) + '</Data></Cell>'; }); xml += '</Row>';
			rows.forEach(function(line) {
				xml += '<Row>'; line.forEach(function(c) { xml += '<Cell><Data ss:Type="String">' + escXml(c) + '</Data></Cell>'; }); xml += '</Row>';
			});
			xml += '</Table></Worksheet></Workbook>';
			var blob = new Blob([xml], { type: 'application/vnd.ms-excel' });
			var link = document.createElement('a');
			link.href = URL.createObjectURL(blob);
			link.download = filename + '_' + (new Date().toISOString().slice(0, 19).replace(/[:T]/g, '-')) + '.xls';
			document.body.appendChild(link);
			link.click();
			document.body.removeChild(link);
		});
	}

	if (document.readyState === 'complete') {
		initPembelianVerifikasiReferensi();
	} else {
		window.addEventListener('load', initPembelianVerifikasiReferensi);
	}
})();
</script>
