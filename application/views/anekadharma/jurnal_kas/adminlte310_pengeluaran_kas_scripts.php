<script>
window.PENGELUARAN_KAS_CFG = {
    urlExcel: <?php echo json_encode(isset($url_pengeluaran_kas_excel) ? $url_pengeluaran_kas_excel : site_url('Jurnal_kas/excel_pengeluaran_kas')); ?>,
    urlList: <?php echo json_encode(isset($url_ajax_pengeluaran_kas_list) ? $url_ajax_pengeluaran_kas_list : site_url('Jurnal_kas/ajax_pengeluaran_kas_list')); ?>,
    urlGet: <?php echo json_encode(isset($url_ajax_pengeluaran_kas_get) ? $url_ajax_pengeluaran_kas_get : site_url('Jurnal_kas/ajax_pengeluaran_kas_get')); ?>,
    urlSave: <?php echo json_encode(isset($url_ajax_pengeluaran_kas_save) ? $url_ajax_pengeluaran_kas_save : site_url('Jurnal_kas/ajax_pengeluaran_kas_save')); ?>,
    urlUpdate: <?php echo json_encode(isset($url_ajax_pengeluaran_kas_update) ? $url_ajax_pengeluaran_kas_update : site_url('Jurnal_kas/ajax_pengeluaran_kas_update')); ?>,
    urlDelete: <?php echo json_encode(isset($url_ajax_pengeluaran_kas_delete) ? $url_ajax_pengeluaran_kas_delete : site_url('Jurnal_kas/ajax_pengeluaran_kas_delete')); ?>,
    namaBulan: <?php echo json_encode(isset($nama_bulan_id) ? $nama_bulan_id : array()); ?>,
    bulanNsServer: <?php echo json_encode(isset($bulan_ns_value) ? $bulan_ns_value : date('Y-m')); ?>,
    canManage: <?php echo !empty($can_input_pengeluaran_kas) ? 'true' : 'false'; ?>,
    modalTanggalDefault: <?php echo json_encode(isset($modal_pgk_tanggal_default) ? $modal_pgk_tanggal_default : date('d-m-Y')); ?>
};
</script>

<script>
(function() {
    var cfg = window.PENGELUARAN_KAS_CFG || {};
    var submitTimer = null;
    var pageReady = false;
    var loading = false;
    var lastMonth = '';
    var pgkDt = null;

    function parseMonthValue(val) {
        if (!val || !/^\d{4}-\d{2}$/.test(val)) return null;
        var parts = val.split('-');
        var year = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10);
        if (!year || month < 1 || month > 12) return null;
        return { year: year, month: month };
    }

    function daysInMonth(year, month) {
        return new Date(year, month, 0).getDate();
    }

    function pad2(n) {
        return String(n).padStart(2, '0');
    }

    function buildRangeLabel(parsed) {
        var awal = '01-' + pad2(parsed.month) + '-' + parsed.year;
        var akhir = pad2(daysInMonth(parsed.year, parsed.month)) + '-' + pad2(parsed.month) + '-' + parsed.year;
        return awal + ' s/d ' + akhir;
    }

    function getSelectedMonthValue() {
        var el = document.getElementById('pgk_bulan_ns');
        return el ? String(el.value || '').trim() : '';
    }

    function showMonthLoading(show) {
        var elLoading = document.getElementById('pengeluaran-kas-month-loading');
        var elWrap = document.getElementById('pengeluaran-kas-table-wrap');
        if (elLoading) elLoading.classList.toggle('d-none', !show);
        if (elWrap) {
            elWrap.style.opacity = show ? '0.45' : '1';
            elWrap.style.pointerEvents = show ? 'none' : '';
        }
    }

    function syncLabels(bulanLabel, rangeText) {
        if (bulanLabel) {
            jQuery('#pengeluaran-kas-label-periode').text('(' + bulanLabel + ')');
            jQuery('#tab-pengeluaran-data').text('Data Jurnal Pengeluaran Kas (' + bulanLabel + ')');
            jQuery('#compare-pengeluaran-label-bulan').text(bulanLabel);
        }
        if (rangeText) {
            jQuery('#pengeluaran-kas-label-range').text(rangeText);
        }
    }

    function syncCompareBulanTahunFromMonth(monthValue) {
        var parsed = parseMonthValue(monthValue);
        if (!parsed) return;

        var $bulan = jQuery('#compare_bulan_pengeluaran');
        var $tahun = jQuery('#compare_tahun_pengeluaran');
        if ($bulan.length) $bulan.val(String(parsed.month));
        if ($tahun.length) {
            if ($tahun.find('option[value="' + parsed.year + '"]').length === 0) {
                $tahun.prepend(jQuery('<option>', { value: parsed.year, text: parsed.year }));
            }
            $tahun.val(String(parsed.year));
        }

        var namaBulan = cfg.namaBulan || {};
        var bulanLabel = (namaBulan[parsed.month]) ? namaBulan[parsed.month] + ' ' + parsed.year : (parsed.month + ' ' + parsed.year);
        syncLabels(bulanLabel, buildRangeLabel(parsed));

        if (typeof window.toggleBtnsPengeluaranKas === 'function') {
            window.toggleBtnsPengeluaranKas();
        }
        if (typeof window.validateTabelPengeluaranForImport === 'function') {
            window.validateTabelPengeluaranForImport();
        }
    }

    function fmtAmount(val, alwaysShow) {
        val = parseFloat(val || 0);
        if (!val && !alwaysShow) return '';
        return val.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    function escHtml(v) {
        return jQuery('<span>').text(v == null ? '' : String(v)).html();
    }

    function buildRowHtml(row) {
        var actions = '';
        if (cfg.canManage) {
            actions = '<div class="pgk-row-actions">' +
                '<button type="button" class="btn btn-xs btn-warning btn-pgk-action btn-pgk-ubah" data-pk="' + escHtml(row.pk) + '"><i class="fa fa-pencil"></i> Ubah</button> ' +
                '<button type="button" class="btn btn-xs btn-danger btn-pgk-action btn-pgk-hapus" data-pk="' + escHtml(row.pk) + '"><i class="fa fa-trash"></i> Hapus</button>' +
                '</div>';
        }
        return '<tr data-pk="' + escHtml(row.pk) + '">' +
            '<td>' + escHtml(row.no) + '</td>' +
            '<td class="pgk-cell-tanggal"><div class="pgk-tanggal-text">' + escHtml(row.tanggal) + '</div>' + actions + '</td>' +
            '<td>' + escHtml(row.nomor_bukti_bkk) + '</td>' +
            '<td>' + escHtml(row.pl) + '</td>' +
            '<td>' + escHtml(row.keterangan) + '</td>' +
            '<td class="text-right">' + escHtml(fmtAmount(row.debet_21101uu_dagang)) + '</td>' +
            '<td>' + escHtml(row.serba_serbi_nomor_rekening) + '</td>' +
            '<td class="text-right">' + escHtml(fmtAmount(row.serba_serbi_jumlah)) + '</td>' +
            '<td class="text-right">' + escHtml(fmtAmount(row.kredit_11101_kas_besar)) + '</td>' +
            '</tr>';
    }

    function updateTotals(totals) {
        totals = totals || {};
        var deb = parseFloat(totals.debet_21101 || 0);
        var jml = parseFloat(totals.serba_serbi_jumlah || 0);
        var kre = parseFloat(totals.kredit_kas || 0);
        var combined = totals.combined_debet_21101 != null ? parseFloat(totals.combined_debet_21101) : (deb + jml);

        jQuery('#pgk-total-debet').text(fmtAmount(deb, true));
        jQuery('#pgk-total-jumlah').text(fmtAmount(jml, true));
        jQuery('#pgk-total-kredit').text(fmtAmount(kre, true));
        jQuery('#pgk-total-combined-debet').text(fmtAmount(combined, true));
        jQuery('#pgk-total-balance-kredit').text(fmtAmount(kre, true));
    }

    function renderTableRows(rows) {
        var html = (rows || []).map(buildRowHtml).join('');
        jQuery('#pengeluaran-kas-tbody').html(html);
    }

    function initDataTable() {
        if (!window.jQuery || !jQuery.fn.DataTable) return;
        var $table = jQuery('#table-pengeluaran-kas');
        if (!$table.length) return;

        if (jQuery.fn.DataTable.isDataTable($table)) {
            $table.DataTable().clear().destroy();
            $table.find('tfoot').appendTo($table);
        }

        jQuery('.DTFC_Cloned').remove();
        jQuery('.DTFC_LeftWrapper, .DTFC_RightWrapper').remove();

        pgkDt = $table.DataTable({
            scrollX: true,
            scrollY: '550px',
            scrollCollapse: true,
            paging: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
            ordering: false,
            searching: true,
            info: true,
            autoWidth: false,
            columnDefs: [{ orderable: false, targets: '_all' }],
            language: { url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json' },
            drawCallback: function() {
                if (pgkDt) pgkDt.columns.adjust();
            },
            initComplete: function() {
                if (pgkDt) pgkDt.columns.adjust();
            }
        });
    }

    window.reloadPengeluaranKasTable = function(forceMonth) {
        var monthValue = forceMonth || getSelectedMonthValue();
        var parsed = parseMonthValue(monthValue);
        if (!parsed) return;

        loading = true;
        showMonthLoading(true);
        jQuery.ajax({
            url: cfg.urlList,
            type: 'POST',
            dataType: 'json',
            data: { bulan_ns: monthValue }
        }).done(function(res) {
            if (!res || !res.ok) {
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Gagal Memuat', text: (res && res.message) || 'Gagal memuat data.' });
                return;
            }
            renderTableRows(res.rows || []);
            updateTotals(res.totals || {});
            if (res.bulan_label) syncLabels(res.bulan_label, res.periode_label);
            if (res.bulan_ns) {
                lastMonth = res.bulan_ns;
                var el = document.getElementById('pgk_bulan_ns');
                if (el && el.value !== res.bulan_ns) el.value = res.bulan_ns;
                syncCompareBulanTahunFromMonth(res.bulan_ns);
            }
            initDataTable();
        }).fail(function() {
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Gagal Memuat', text: 'Tidak dapat menghubungi server.' });
        }).always(function() {
            loading = false;
            showMonthLoading(false);
        });
    };

    function reloadByMonth(monthValue, force) {
        if (loading) return;
        var parsed = parseMonthValue(monthValue);
        if (!parsed) {
            if (force) alert('Pilih bulan terlebih dahulu.');
            return;
        }
        if (!force && !pageReady) return;
        if (!force && monthValue === lastMonth) return;

        lastMonth = monthValue;
        syncCompareBulanTahunFromMonth(monthValue);
        window.reloadPengeluaranKasTable(monthValue);
    }

    function scheduleReloadByMonth(monthValue) {
        clearTimeout(submitTimer);
        submitTimer = setTimeout(function() {
            reloadByMonth(monthValue, false);
        }, 300);
    }

    function exportExcel() {
        var monthValue = getSelectedMonthValue();
        if (!parseMonthValue(monthValue)) {
            alert('Pilih bulan terlebih dahulu.');
            return;
        }
        var f = document.createElement('form');
        f.method = 'post';
        f.action = cfg.urlExcel;
        f.target = '_blank';
        f.style.display = 'none';
        var inpBulan = document.createElement('input');
        inpBulan.type = 'hidden';
        inpBulan.name = 'bulan_ns';
        inpBulan.value = monthValue;
        f.appendChild(inpBulan);
        document.body.appendChild(f);
        f.submit();
        document.body.removeChild(f);
    }

    function initMonthFilter() {
        var form = document.getElementById('form-cari-pengeluaran-kas');
        var bulanNs = document.getElementById('pgk_bulan_ns');
        if (!form || !bulanNs) return;

        if (!bulanNs.value && cfg.bulanNsServer) {
            bulanNs.value = cfg.bulanNsServer;
        }
        lastMonth = bulanNs.value || cfg.bulanNsServer || '';
        syncCompareBulanTahunFromMonth(lastMonth);

        var btnExcel = document.getElementById('btn-pengeluaran-kas-excel');
        if (btnExcel) btnExcel.addEventListener('click', exportExcel);

        var btnCari = document.getElementById('btn-cari-pengeluaran-kas-bulan');
        if (btnCari) {
            btnCari.addEventListener('click', function() {
                reloadByMonth(getSelectedMonthValue(), true);
            });
        }

        function handleMonthChange() {
            var val = bulanNs.value || '';
            if (!val || val === lastMonth) return;
            scheduleReloadByMonth(val);
        }

        bulanNs.addEventListener('change', handleMonthChange);
        bulanNs.addEventListener('input', handleMonthChange);

        setTimeout(function() {
            pageReady = true;
        }, 600);
    }

    function showProcessAlert(title, html) {
        if (typeof Swal === 'undefined') return;
        Swal.fire({
            title: title,
            html: html,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: function() { Swal.showLoading(); }
        });
    }

    function closeProcessAlert() {
        if (typeof Swal !== 'undefined' && Swal.isVisible()) Swal.close();
    }

    function showSuccessAlert(message, callback) {
        if (typeof Swal === 'undefined') {
            alert(message);
            if (callback) callback();
            return;
        }
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            html: '<div style="font-size:15px;">' + message + '</div>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#28a745',
            timer: 2000,
            timerProgressBar: true,
            allowOutsideClick: false
        }).then(function() {
            if (callback) callback();
        });
        setTimeout(function() {
            if (typeof Swal !== 'undefined' && Swal.isVisible()) {
                Swal.close();
                if (callback) callback();
            }
        }, 2100);
    }

    function initCrudModal() {
        if (!cfg.canManage || !window.jQuery) return;

        var $modal = jQuery('#modal-pengeluaran-kas-form');
        var $form = jQuery('#form-pengeluaran-kas-modal');
        var $btnSimpan = jQuery('#btn-pengeluaran-kas-modal-simpan');
        var modalMode = 'create';
        var modalPluginsReady = false;
        var saving = false;

        function showModalError(msg) {
            var $el = jQuery('#pengeluaran-kas-modal-errors');
            if (!msg) { $el.addClass('d-none').text(''); return; }
            $el.removeClass('d-none').html(msg);
        }

        function resetForm() {
            modalMode = 'create';
            jQuery('#modal_pgk_pk').val('');
            jQuery('#modal-pengeluaran-kas-form-title').html('<i class="fa fa-plus"></i> Input Pengeluaran Kas');
            $btnSimpan.html('<i class="fa fa-save"></i> Simpan');
            $form[0].reset();
            jQuery('#modal_pgk_tanggal').val(cfg.modalTanggalDefault || '');
            jQuery('.modal-pgk-select2').val('').trigger('change');
            showModalError('');
        }

        function initModalPlugins() {
            jQuery('.modal-pgk-select2').each(function() {
                var $sel = jQuery(this);
                if ($sel.hasClass('select2-hidden-accessible')) $sel.select2('destroy');
                $sel.select2({ dropdownParent: $modal, width: '100%', placeholder: 'Pilih' });
            });
            if (!jQuery('#modal_pgk_tanggal_wrap').data('datetimepicker')) {
                jQuery('#modal_pgk_tanggal_wrap').datetimepicker({ format: 'D-M-YYYY' });
            }
            modalPluginsReady = true;
        }

        function fillForm(data) {
            data = data || {};
            jQuery('#modal_pgk_pk').val(data.pk || '');
            jQuery('#modal_pgk_tanggal').val(data.tanggal || cfg.modalTanggalDefault || '');
            jQuery('#modal_pgk_bukti').val(data.nomor_bukti_bkk || '');
            jQuery('#modal_pgk_pl').val(data.pl || '').trigger('change');
            jQuery('#modal_pgk_keterangan').val(data.keterangan || '');
            jQuery('#modal_pgk_debet').val(data.debet_21101uu_dagang ? fmtAmount(data.debet_21101uu_dagang) : '');
            jQuery('#modal_pgk_rekening').val(data.serba_serbi_nomor_rekening || '');
            jQuery('#modal_pgk_jumlah').val(data.serba_serbi_jumlah ? fmtAmount(data.serba_serbi_jumlah) : '');
            jQuery('#modal_pgk_kredit').val(data.kredit_11101_kas_besar ? fmtAmount(data.kredit_11101_kas_besar) : '');
        }

        $modal.on('show.bs.modal', function(e) {
            if (!modalPluginsReady) initModalPlugins();
            if (!jQuery(e.relatedTarget).is('#btn-pengeluaran-kas-input-data')) {
                return;
            }
            resetForm();
        });

        jQuery(document).on('click', '.btn-pgk-ubah', function() {
            var pk = jQuery(this).data('pk');
            if (!pk) return;
            if (!modalPluginsReady) initModalPlugins();
            resetForm();
            modalMode = 'edit';
            jQuery('#modal-pengeluaran-kas-form-title').html('<i class="fa fa-pencil"></i> Ubah Pengeluaran Kas');
            $btnSimpan.html('<i class="fa fa-save"></i> Ubah');
            showProcessAlert('Memuat Data...', 'Mohon tunggu.');
            jQuery.ajax({
                url: cfg.urlGet, type: 'POST', dataType: 'json', data: { pk: pk }
            }).done(function(res) {
                closeProcessAlert();
                if (!res || !res.ok || !res.data) {
                    if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Gagal', text: (res && res.message) || 'Data tidak ditemukan.' });
                    return;
                }
                fillForm(res.data);
                $modal.modal('show');
            }).fail(function() {
                closeProcessAlert();
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memuat data.' });
            });
        });

        jQuery(document).on('click', '.btn-pgk-hapus', function() {
            var pk = jQuery(this).data('pk');
            if (!pk) return;
            var doDelete = function() {
                showProcessAlert('Menghapus Data...', 'Mohon tunggu, sedang memproses hapus data.');
                jQuery.ajax({
                    url: cfg.urlDelete, type: 'POST', dataType: 'json', data: { pk: pk }
                }).done(function(res) {
                    closeProcessAlert();
                    if (!res || !res.ok) {
                        if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Gagal Hapus', text: (res && res.message) || 'Gagal menghapus data.' });
                        return;
                    }
                    showSuccessAlert(res.message || 'Data berhasil dihapus.', function() {
                        window.reloadPengeluaranKasTable();
                    });
                }).fail(function() {
                    closeProcessAlert();
                    if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Kesalahan Server', text: 'Tidak dapat menghubungi server.' });
                });
            };
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Hapus Data?',
                    text: 'Data yang dihapus tidak dapat dikembalikan.',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Ya, Hapus'
                }).then(function(r) { if (r.isConfirmed) doDelete(); });
            } else if (confirm('Hapus data ini?')) {
                doDelete();
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            if (saving) return;
            showModalError('');
            saving = true;
            $btnSimpan.prop('disabled', true);
            var isEdit = modalMode === 'edit';
            showProcessAlert(isEdit ? 'Mengubah Data...' : 'Menyimpan Data...', 'Mohon tunggu, sedang memproses.');
            jQuery.ajax({
                url: isEdit ? cfg.urlUpdate : cfg.urlSave,
                type: 'POST',
                dataType: 'json',
                data: $form.serialize()
            }).done(function(res) {
                closeProcessAlert();
                if (!res || !res.ok) {
                    showModalError((res && res.message) || 'Proses gagal.');
                    if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Gagal', text: (res && res.message) || 'Proses gagal.' });
                    return;
                }
                $modal.modal('hide');
                showSuccessAlert(res.message || 'Proses berhasil.', function() {
                    window.reloadPengeluaranKasTable();
                });
            }).fail(function() {
                closeProcessAlert();
                showModalError('Tidak dapat menghubungi server.');
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Kesalahan Server', text: 'Tidak dapat menghubungi server.' });
            }).always(function() {
                saving = false;
                $btnSimpan.prop('disabled', false);
            });
        });
    }

    function boot() {
        initMonthFilter();
        initDataTable();
        initCrudModal();
    }

    if (document.readyState === 'complete') boot();
    else window.addEventListener('load', boot);
})();
</script>
