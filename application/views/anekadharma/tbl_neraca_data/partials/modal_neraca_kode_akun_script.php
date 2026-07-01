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

	function initDataTable(selector, extraOptions) {
		if (!$.fn.DataTable) {
			return null;
		}
		destroyDtTable(selector);
		var options = $.extend(true, {
			pageLength: 10,
			order: [[0, 'asc']],
			destroy: true,
			scrollX: true,
			scrollY: '300px',
			scrollCollapse: true,
			autoWidth: false,
			language: {
				search: 'Cari:',
				lengthMenu: 'Tampilkan _MENU_ baris',
				info: 'Menampilkan _START_ s/d _END_ dari _TOTAL_ data',
				paginate: { previous: 'Sebelum', next: 'Sesudah' },
				zeroRecords: 'Data tidak ditemukan'
			}
		}, extraOptions || {});
		return $(selector).DataTable(options);
	}

	function getSettingButtonLabel(fieldName) {
		var label = neracaFieldLabels[fieldName] || String(fieldName || '').replace(/_/g, ' ');
		return 'Setting Kode Akun ' + label;
	}

	function updateSettingButtonLabels() {
		$('.btn-neraca-get-kode-akun-form').each(function() {
			var $btn = $(this);
			var $block = $btn.closest('.neraca-field-block');
			var field = ($btn.attr('data-field-neraca')
				|| $block.attr('data-field-neraca')
				|| $block.find('form.neraca-kode-akun-form').attr('data-field-neraca')
				|| $btn.closest('tr').find('form.neraca-kode-akun-form').first().attr('data-field-neraca')
				|| '').trim();
			if (!field) {
				return;
			}
			$btn.attr('data-field-neraca', field);
			$btn.html('<i class="fa fa-cog"></i> ' + escapeHtml(getSettingButtonLabel(field)));
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
				'<td class="text-right">' + escapeHtml(row.nominal_formatted || formatNominal(row.nominal || 0)) + '</td>' +
				'<td><button type="button" class="btn btn-danger btn-xs btn-neraca-hapus-kode" data-kode="' + escapeHtml(row.kode_akun) + '">Hapus</button></td>' +
				'</tr>';
		});

		destroyDataTables();
		$('#tblNeracaKodeAkunAvailable tbody').html(availableRows || '<tr><td colspan="3" class="text-center text-muted">Semua kode akun sudah dipilih.</td></tr>');
		$('#tblNeracaKodeAkunSelected tbody').html(selectedRows || '<tr><td colspan="4" class="text-center text-muted">Belum ada kode akun terpilih.</td></tr>');
		$('#neracaKodeAkunSelectedTotal').text(payload.total_terpilih_formatted || formatNominal(payload.total_terpilih || 0));

		initDataTable('#tblNeracaKodeAkunAvailable');
		initDataTable('#tblNeracaKodeAkunSelected', {
			order: [[0, 'asc']],
			columnDefs: [
				{ targets: 2, className: 'text-right' }
			]
		});
	}

	function findCalcSource($form) {
		var $parentSm = $form.closest('.sm-4');
		if ($parentSm.length && $parentSm.prev('.sm-4').length) {
			return $parentSm.prev('.sm-4');
		}

		var $th = $form.closest('th');
		var $rows = $th.find('> .row');
		if ($rows.length >= 2) {
			return $rows.first().find('.sm-4').first();
		}
		if ($rows.length === 1) {
			return $rows.first().find('.sm-4').first();
		}
		return $();
	}

	function findCalcDisplay($form) {
		var field = $form.attr('data-field-neraca') || '';
		var $block = $form.closest('.neraca-field-block');
		if ($block.length) {
			if (field) {
				var $marked = $block.find('.neraca-calc-display[data-field-neraca="' + field + '"]');
				if ($marked.length) {
					return $marked;
				}
			}
			return $block.find('.neraca-calc-display').first();
		}

		var $th = $form.closest('th');
		if (field) {
			var $markedTh = $th.find('.neraca-calc-display[data-field-neraca="' + field + '"]');
			if ($markedTh.length) {
				return $markedTh;
			}
		}

		var $source = findCalcSource($form);
		if ($source.length) {
			return $source;
		}

		return $th.find('.row').first().find('.sm-4').first();
	}

	function buildNeracaRowCalc($calcSpan) {
		var $rowCalc = $('<div class="neraca-row-calc"></div>');
		var $colNominal = $('<div class="neraca-col-nominal"></div>');
		$colNominal.append($calcSpan);
		$rowCalc.append($colNominal);
		return $rowCalc;
	}

	function buildNeracaRowInput($input, $simpanBtn) {
		var $rowInput = $('<div class="neraca-row-input"></div>');
		var $colInput = $('<div class="neraca-col-input-wrap"></div>');
		var $colSimpan = $('<div class="neraca-col-simpan"></div>');

		if ($input && $input.length) {
			$input.removeAttr('style');
			$colInput.append($input);
		}
		if ($simpanBtn && $simpanBtn.length) {
			$simpanBtn.removeAttr('style');
			$colSimpan.append($simpanBtn);
		} else {
			$colSimpan.append($('<button type="submit" class="btn btn-success btn-xs">Simpan</button>'));
		}

		$rowInput.append($colInput).append($colSimpan);
		return $rowInput;
	}

	function readCalcTextFromTh($th) {
		var $existing = $th.find('.neraca-calc-display').first();
		if ($existing.length) {
			return $.trim($existing.text());
		}

		var $legacyCalc = $th.find('.neraca-calc-legacy').first();
		if ($legacyCalc.length) {
			return $.trim($legacyCalc.text());
		}

		var $legacyRow = $th.find('> .row').first().find('.sm-4, > div').first();
		if ($legacyRow.length) {
			return $.trim($legacyRow.text());
		}

		return '';
	}

	function findLabelThForInput($inputTh) {
		var $rp = $inputTh.prev('th');
		if ($rp.length && ($rp.hasClass('neraca-col-rp') || $rp.attr('colspan') === '25')) {
			var $label = $rp.prev('th');
			if ($label.length && ($label.hasClass('neraca-col-label') || $label.attr('colspan') === '250')) {
				return $label;
			}
		}
		return $inputTh.prevAll('th.neraca-col-label, th[colspan="250"]').first();
	}

	function ensureLabelWithSetting($labelTh, $settingBtn, field) {
		if (!$labelTh || !$labelTh.length) {
			return;
		}

		if (field && !$settingBtn.length) {
			var $existingBtn = $labelTh.find('.neraca-label-wrap .btn-neraca-get-kode-akun-form[data-field-neraca="' + field + '"]');
			if ($existingBtn.length) {
				$labelTh.addClass('neraca-label-layout-done');
				if (field) {
					$labelTh.attr('data-field-neraca', field);
				}
				return;
			}
		}

		var labelText = (field && neracaFieldLabels[field])
			? neracaFieldLabels[field]
			: $.trim($labelTh.find('.neraca-label-text').first().text());
		if (!labelText) {
			labelText = $.trim($labelTh.text());
		}

		if (field) {
			$labelTh.attr('data-field-neraca', field);
		}

		if (!$settingBtn || !$settingBtn.length) {
			$settingBtn = $labelTh.find('.btn-neraca-get-kode-akun-form').first();
		}
		if (!$settingBtn.length) {
			$settingBtn = $(
				'<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form"></button>'
			);
		}

		$settingBtn.removeAttr('style').removeAttr('onclick');
		if (field) {
			$settingBtn.attr('data-field-neraca', field);
		}
		$settingBtn.html('<i class="fa fa-cog"></i> ' + escapeHtml(getSettingButtonLabel(field)));
		$settingBtn = $settingBtn.detach();

		$labelTh.empty();
		var $wrap = $('<div class="neraca-label-wrap"></div>');
		$wrap.append($('<span class="neraca-label-text"></span>').text(labelText));
		var $settingSpan = $('<span class="neraca-label-setting"></span>');
		$settingSpan.append($settingBtn);
		$wrap.append($settingSpan);
		$labelTh.append($wrap);
		$labelTh.addClass('neraca-label-layout-done');
	}

	function ensureNeracaFieldLayout() {
		$('#customers.neraca-form-table th.neraca-col-input, #customers.neraca-form-table th[colspan="195"]').each(function() {
			var $th = $(this);
			var $form = $th.find('form.neraca-kode-akun-form').first();
			if (!$form.length) {
				return;
			}

			var field = $form.attr('data-field-neraca') || '';
			var calcText = readCalcTextFromTh($th);

			var $settingBtn = $form.find('.btn-neraca-get-kode-akun-form').first().detach();
			if (!$settingBtn.length) {
				$settingBtn = $th.find('.btn-neraca-get-kode-akun-form').first().detach();
			}
			$form.find('.btn-neraca-get-kode-akun-form').remove();
			$th.find('.btn-neraca-get-kode-akun-form').remove();

			var $simpanBtn = $form.find('button[type="submit"]').first().detach();
			var $input = $form.find('input[name="input_box"], input[type="tel"]').first().detach();

			if (!$input.length) {
				return;
			}

			var $calcSpan = $('<span class="neraca-calc-display neraca-nominal-mismatch"></span>');
			if (field) {
				$calcSpan.attr('data-field-neraca', field);
			}
			$calcSpan.text(calcText);

			var $block = $('<div class="neraca-field-block"></div>');
			if (field) {
				$block.attr('data-field-neraca', field);
			}

			ensureLabelWithSetting(findLabelThForInput($th), $settingBtn, field);

			$form.empty();
			$form.append(buildNeracaRowInput($input, $simpanBtn));
			$block.append(buildNeracaRowCalc($calcSpan));
			$block.append($form);

			$th.empty().append($block);
			$th.addClass('neraca-layout-done');
		});
	}

	function restructureNeracaFieldForms() {
		ensureNeracaFieldLayout();
	}

	window.restructureNeracaFieldForms = restructureNeracaFieldForms;

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

		$.getJSON(neracaKodeAkunUrls.list + '/' + encodeURIComponent(fieldName), {
			tahun: neracaTahun,
			bulan: neracaBulan
		})
			.done(function(res) {
				if (!res || !res.ok) {
					alert((res && res.message) ? res.message : 'Gagal memuat data kode akun.');
					return;
				}
				var fieldLabel = res.label_neraca || fieldName;
				$('#neracaKodeAkunFieldLabelBottom').text(fieldLabel);
				$('#neracaKodeAkunModalTitle').text('Setting Kode Akun ' + fieldLabel);
				$('#neracaKodeAkunPeriodInfo').text(res.periode_label || ('Neraca saldo tahun ' + neracaTahun));
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
		$('#neracaKodeAkunFieldLabelBottom').text(fieldLabel || fieldName);
		$('#neracaKodeAkunModalTitle').text('Setting Kode Akun ' + (fieldLabel || fieldName));
		showNeracaKodeAkunModal();
		loadNeracaKodeAkun(fieldName);
	}

	window.neracaOpenSettingKodeAkun = function(btn) {
		var $btn = $(btn);
		var fieldName = ($btn.attr('data-field-neraca') || '').trim();
		var $form = $btn.closest('form.neraca-kode-akun-form');
		if (!$form.length) {
			$form = $btn.closest('tr').find('form.neraca-kode-akun-form').first();
		}
		if (!$form.length) {
			$form = $btn.closest('.neraca-field-block').find('form.neraca-kode-akun-form');
		}
		if (!fieldName && $form.length) {
			fieldName = $form.attr('data-field-neraca') || '';
		}
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

	function setupNeracaNominalInputs() {
		$('form.neraca-kode-akun-form input[type="tel"], form.neraca-kode-akun-form input[name="input_box"], #customers.neraca-form-table .neraca-row-input input[type="tel"], #customers.neraca-form-table input[type="tel"]').each(function() {
			$(this)
				.attr('maxlength', 26)
				.attr('size', 24)
				.removeAttr('pattern')
				.css({
					'font-size': '0.86rem',
					'font-weight': 'bold'
				});
		});
	}

	function initNeracaKodeAkunModal() {
		restructureNeracaFieldForms();
		updateSettingButtonLabels();
		setupNeracaNominalInputs();
		refreshAllNominalColors();

		if (neracaKodeAkunInitialized) {
			return;
		}
		if (!$('#modalNeracaKodeAkun').length) {
			return;
		}

		neracaKodeAkunInitialized = true;

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
