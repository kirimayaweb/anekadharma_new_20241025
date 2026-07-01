<style>
	.neraca-nominal-match {
		color: #28a745 !important;
		font-weight: bold !important;
	}

	.neraca-nominal-mismatch {
		color: #f9032f !important;
		font-weight: bold !important;
	}

	.neraca-calc-display {
		font-weight: bold;
	}
</style>

<script>
(function($) {
	if (!$ || !$.fn) {
		return;
	}

	var neracaKodeAkunUrls = {
		list: <?php echo json_encode(site_url('Tbl_neraca_data/ajax_neraca_setting_kode_akun')); ?>,
		pilih: <?php echo json_encode(site_url('Tbl_neraca_data/ajax_neraca_setting_kode_akun_pilih')); ?>,
		hapus: <?php echo json_encode(site_url('Tbl_neraca_data/ajax_neraca_setting_kode_akun_hapus')); ?>,
		calc: <?php echo json_encode(site_url('Tbl_neraca_data/ajax_neraca_calc_nominal')); ?>
	};
	var neracaFieldLabels = <?php echo json_encode(isset($neraca_field_labels) ? $neraca_field_labels : array()); ?>;
	var neracaSystemTotals = <?php echo json_encode(isset($neraca_system_totals) ? $neraca_system_totals : array()); ?>;
	var neracaTahun = <?php echo json_encode(isset($neraca_tahun_neraca) ? (int) $neraca_tahun_neraca : (int) date('Y')); ?>;
	var neracaBulan = <?php echo json_encode(isset($neraca_bulan_transaksi) ? (int) $neraca_bulan_transaksi : 0); ?>;
	var currentField = '';
	var neracaKodeAkunInitialized = false;

	function escapeHtml(value) {
		return String(value || '')
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;');
	}

	function parseNominal(value) {
		if (value === null || value === undefined) {
			return 0;
		}
		var str = String(value).trim();
		if (str === '') {
			return 0;
		}
		str = str.replace(/\./g, '').replace(',', '.');
		var num = parseFloat(str);
		return isNaN(num) ? 0 : num;
	}

	function formatNominal(num) {
		var n = parseFloat(num);
		if (isNaN(n)) {
			n = 0;
		}
		var parts = n.toFixed(2).split('.');
		parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
		return parts.join(',');
	}

	function destroyDtTable(selector) {
		if (!$.fn.DataTable) {
			return;
		}
		var $table = $(selector);
		if (!$table.length) {
			return;
		}
		if ($.fn.DataTable.isDataTable($table)) {
			try {
				$table.DataTable().clear().destroy();
			} catch (e) {
				try {
					$table.DataTable().destroy();
				} catch (e2) {}
			}
		}
	}

	function destroyDataTables() {
		destroyDtTable('#tblNeracaKodeAkunAvailable');
		destroyDtTable('#tblNeracaKodeAkunSelected');
	}

	function initDataTable(selector) {
		if (!$.fn.DataTable) {
			return null;
		}
		destroyDtTable(selector);
		return $(selector).DataTable({
			pageLength: 10,
			order: [[0, 'asc']],
			destroy: true,
			language: {
				search: 'Cari:',
				lengthMenu: 'Tampilkan _MENU_ baris',
				info: 'Menampilkan _START_ s/d _END_ dari _TOTAL_ data',
				paginate: { previous: 'Sebelum', next: 'Sesudah' },
				zeroRecords: 'Data tidak ditemukan'
			}
		});
	}

	function renderTables(payload) {
		var availableRows = '';
		var selectedRows = '';

		(payload.available || []).forEach(function(row) {
			availableRows += '<tr>' +
				'<td>' + escapeHtml(row.kode_akun) + '</td>' +
				'<td>' + escapeHtml(row.nama_akun) + '</td>' +
				'<td><button type="button" class="btn btn-success btn-xs btn-neraca-pilih-kode" data-kode="' + escapeHtml(row.kode_akun) + '">Pilih</button></td>' +
				'</tr>';
		});

		(payload.terpilih || []).forEach(function(row) {
			selectedRows += '<tr>' +
				'<td>' + escapeHtml(row.kode_akun) + '</td>' +
				'<td>' + escapeHtml(row.nama_akun) + '</td>' +
				'<td><button type="button" class="btn btn-danger btn-xs btn-neraca-hapus-kode" data-kode="' + escapeHtml(row.kode_akun) + '">Hapus</button></td>' +
				'</tr>';
		});

		destroyDataTables();
		$('#tblNeracaKodeAkunAvailable tbody').html(availableRows || '<tr><td colspan="3" class="text-center text-muted">Semua kode akun sudah dipilih.</td></tr>');
		$('#tblNeracaKodeAkunSelected tbody').html(selectedRows || '<tr><td colspan="3" class="text-center text-muted">Belum ada kode akun terpilih.</td></tr>');

		initDataTable('#tblNeracaKodeAkunAvailable');
		initDataTable('#tblNeracaKodeAkunSelected');
	}

	function findCalcDisplay($form) {
		var field = $form.attr('data-field-neraca') || '';
		var $marked = $form.closest('th').find('.neraca-calc-display[data-field-neraca="' + field + '"]');
		if ($marked.length) {
			return $marked;
		}

		var $parentSm = $form.parent('.sm-4');
		if ($parentSm.length && $parentSm.prev('.sm-4').length) {
			return $parentSm.prev('.sm-4');
		}

		return $form.closest('th').find('.row').first().find('.sm-4').first();
	}

	function updateNominalColor($form, systemTotal) {
		if (!$form || !$form.length) {
			return;
		}
		var field = $form.attr('data-field-neraca') || '';
		var manualVal = parseNominal($form.find('input[name="input_box"]').val());
		var systemVal = parseNominal(systemTotal);
		if (systemTotal === undefined || systemTotal === null) {
			if (neracaSystemTotals && neracaSystemTotals[field] !== undefined) {
				systemVal = parseNominal(neracaSystemTotals[field]);
			}
		}

		var $calc = findCalcDisplay($form);
		$calc.addClass('neraca-calc-display').attr('data-field-neraca', field);
		$calc.removeClass('neraca-nominal-match neraca-nominal-mismatch');

		if (Math.abs(systemVal - manualVal) < 0.01) {
			$calc.addClass('neraca-nominal-match');
		} else {
			$calc.addClass('neraca-nominal-mismatch');
		}
	}

	function refreshFieldNominal(fieldName, systemTotal) {
		if (systemTotal !== undefined && systemTotal !== null) {
			neracaSystemTotals[fieldName] = systemTotal;
		}
		var $form = $('form.neraca-kode-akun-form[data-field-neraca="' + fieldName + '"]').first();
		if (!$form.length) {
			return;
		}
		var total = neracaSystemTotals[fieldName];
		var $calc = findCalcDisplay($form);
		$calc.addClass('neraca-calc-display').attr('data-field-neraca', fieldName).text(formatNominal(total));
		updateNominalColor($form, total);
	}

	function refreshAllNominalColors() {
		$('form.neraca-kode-akun-form').each(function() {
			var field = $(this).attr('data-field-neraca') || '';
			if (!field) {
				return;
			}
			refreshFieldNominal(field, neracaSystemTotals[field]);
		});
	}

	function fetchAndRefreshNominal(fieldName) {
		return $.getJSON(neracaKodeAkunUrls.calc + '/' + encodeURIComponent(fieldName), {
			tahun: neracaTahun,
			bulan: neracaBulan
		}).done(function(res) {
			if (res && res.ok) {
				refreshFieldNominal(fieldName, res.system_total);
			}
		});
	}

	function loadNeracaKodeAkun(fieldName) {
		currentField = fieldName;
		$('#neracaKodeAkunFieldName').val(fieldName);

		$.getJSON(neracaKodeAkunUrls.list + '/' + encodeURIComponent(fieldName))
			.done(function(res) {
				if (!res || !res.ok) {
					alert((res && res.message) ? res.message : 'Gagal memuat data kode akun.');
					return;
				}
				$('#neracaKodeAkunFieldLabel, #neracaKodeAkunFieldLabelBottom').text(res.label_neraca || fieldName);
				renderTables(res);
			})
			.fail(function(xhr) {
				alert('Gagal memuat data kode akun. (' + xhr.status + ')');
			});
	}

	function hideNeracaKodeAkunModal() {
		var modalEl = document.getElementById('modalNeracaKodeAkun');
		if (!modalEl) {
			return;
		}

		destroyDataTables();

		if ($.fn.modal) {
			$('#modalNeracaKodeAkun').modal('hide');
		} else {
			modalEl.classList.remove('show');
			modalEl.style.display = 'none';
			modalEl.setAttribute('aria-hidden', 'true');
			document.body.classList.remove('modal-open');
			$('.neraca-kode-akun-backdrop').remove();
		}
	}

	function showNeracaKodeAkunModal() {
		var modalEl = document.getElementById('modalNeracaKodeAkun');
		if (!modalEl) {
			alert('Modal setting kode akun tidak ditemukan.');
			return;
		}

		destroyDataTables();

		if ($.fn.modal) {
			$('#modalNeracaKodeAkun').modal({
				backdrop: 'static',
				keyboard: true,
				show: true
			});
			return;
		}

		modalEl.style.display = 'block';
		modalEl.classList.add('show');
		modalEl.setAttribute('aria-modal', 'true');
		modalEl.removeAttribute('aria-hidden');
		document.body.classList.add('modal-open');
		if (!$('.neraca-kode-akun-backdrop').length) {
			$('<div class="modal-backdrop fade show neraca-kode-akun-backdrop"></div>').appendTo(document.body);
		}
	}

	function openNeracaKodeAkunModal(fieldName, fieldLabel) {
		destroyDataTables();
		$('#neracaKodeAkunFieldLabel, #neracaKodeAkunFieldLabelBottom').text(fieldLabel || fieldName);
		showNeracaKodeAkunModal();
		loadNeracaKodeAkun(fieldName);
	}

	window.neracaOpenSettingKodeAkun = function(btn) {
		var $form = $(btn).closest('form.neraca-kode-akun-form');
		var fieldName = $form.attr('data-field-neraca') || '';
		if (!fieldName) {
			var action = $form.attr('action') || '';
			var parts = action.split('/');
			fieldName = $.trim(parts[parts.length - 1] || '');
		}
		if (!fieldName) {
			alert('Field neraca tidak dikenali.');
			return false;
		}
		var fieldLabel = neracaFieldLabels[fieldName] || fieldName.replace(/_/g, ' ');
		openNeracaKodeAkunModal(fieldName, fieldLabel);
		return false;
	};

	function initNeracaKodeAkunModal() {
		if (neracaKodeAkunInitialized) {
			return;
		}
		if (!$('#modalNeracaKodeAkun').length) {
			return;
		}

		neracaKodeAkunInitialized = true;

		refreshAllNominalColors();

		$(document).on('input change', 'form.neraca-kode-akun-form input[name="input_box"]', function() {
			var $form = $(this).closest('form.neraca-kode-akun-form');
			var field = $form.attr('data-field-neraca') || '';
			updateNominalColor($form, neracaSystemTotals[field]);
		});

		$(document).on('click', '.btn-neraca-get-kode-akun-form', function(e) {
			e.preventDefault();
			e.stopPropagation();
			window.neracaOpenSettingKodeAkun(this);
		});

		$(document).on('click', '.btn-neraca-pilih-kode', function(e) {
			e.preventDefault();
			var kodeAkun = $(this).data('kode');
			$.post(neracaKodeAkunUrls.pilih, {
				field_neraca: currentField,
				kode_akun: kodeAkun
			}).done(function(res) {
				if (!res || !res.ok) {
					alert((res && res.message) ? res.message : 'Gagal memilih kode akun.');
					return;
				}
				loadNeracaKodeAkun(currentField);
				fetchAndRefreshNominal(currentField);
			}).fail(function(xhr) {
				alert('Gagal memilih kode akun. (' + xhr.status + ')');
			});
		});

		$(document).on('click', '.btn-neraca-hapus-kode', function(e) {
			e.preventDefault();
			var kodeAkun = $(this).data('kode');
			$.post(neracaKodeAkunUrls.hapus, {
				field_neraca: currentField,
				kode_akun: kodeAkun
			}).done(function(res) {
				if (!res || !res.ok) {
					alert((res && res.message) ? res.message : 'Gagal menghapus kode akun.');
					return;
				}
				loadNeracaKodeAkun(currentField);
				fetchAndRefreshNominal(currentField);
			}).fail(function(xhr) {
				alert('Gagal menghapus kode akun. (' + xhr.status + ')');
			});
		});

		$(document).on('click', '.neraca-kode-akun-close', function(e) {
			e.preventDefault();
			hideNeracaKodeAkunModal();
		});

		$('#modalNeracaKodeAkun').on('hidden.bs.modal', function() {
			destroyDataTables();
		});
	}

	$(initNeracaKodeAkunModal);
})(window.jQuery);
</script>
