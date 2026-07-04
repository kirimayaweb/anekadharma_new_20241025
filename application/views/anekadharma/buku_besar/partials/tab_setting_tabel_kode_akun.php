<?php
if (!isset($bb_ska_selected_tables) || !is_array($bb_ska_selected_tables)) {
    $bb_ska_selected_tables = array();
}
if (!isset($list_kode_akun)) {
    $list_kode_akun = array();
}
$url_bb_ska_list_tables = isset($url_bb_ska_list_tables) ? $url_bb_ska_list_tables : site_url('Buku_besar/ajax_bb_ska_list_tables');
$url_bb_ska_save_tables = isset($url_bb_ska_save_tables) ? $url_bb_ska_save_tables : site_url('Buku_besar/ajax_bb_ska_save_tables');
$url_bb_ska_table_records = isset($url_bb_ska_table_records) ? $url_bb_ska_table_records : site_url('Buku_besar/ajax_bb_ska_table_records');
$url_bb_ska_save_record = isset($url_bb_ska_save_record) ? $url_bb_ska_save_record : site_url('Buku_besar/ajax_bb_ska_save_record_kode_akun');
$url_bb_ska_selected_tables = isset($url_bb_ska_selected_tables) ? $url_bb_ska_selected_tables : site_url('Buku_besar/ajax_bb_ska_selected_tables');
$url_bb_ska_module = isset($url_bb_ska_module) ? $url_bb_ska_module : site_url('Buku_besar_setting_kode_akun');
$tab_setting_ska_active = (isset($active_tab) && $active_tab === 'setting');
?>
<div class="tab-pane fade<?php echo $tab_setting_ska_active ? ' show active' : ''; ?>" id="panel-bb-setting-ska" role="tabpanel">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 bb-ska-toolbar">
        <div>
            <h5 class="mb-0 text-primary"><strong>Setting Tabel dan Kode Akun</strong></h5>
            <small class="text-muted">
                Record ditampilkan sesuai bulan di header Buku Besar.
                Periode: <strong id="bb-ska-label-bulan">—</strong>
                — nilai agregat di <a href="<?php echo htmlspecialchars($url_bb_ska_module, ENT_QUOTES, 'UTF-8'); ?>" target="_blank">Buku_besar_setting_kode_akun</a>.
            </small>
        </div>
        <div class="mt-2 mt-md-0">
            <button type="button" class="btn btn-warning btn-sm" id="btn-bb-ska-pilih-tabel">
                <i class="fa fa-cog"></i> Setting / Pilih Tabel
            </button>
            <button type="button" class="btn btn-danger btn-sm" id="btn-bb-ska-muat-ulang">
                <i class="fa fa-refresh"></i> Muat Ulang Data
            </button>
            <a href="<?php echo htmlspecialchars($url_bb_ska_module, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-info btn-sm" target="_blank">
                <i class="fa fa-external-link"></i> Lihat Nilai Kode Akun
            </a>
        </div>
    </div>

    <ul class="nav nav-tabs bb-ska-table-tabs" id="bb-ska-table-tabs" role="tablist"></ul>
    <div class="tab-content mt-2" id="bb-ska-table-tabs-content">
        <div class="alert alert-secondary py-3" id="bb-ska-empty-msg">
            Belum ada tabel terpilih. Klik <strong>Setting / Pilih Tabel</strong> untuk memilih tabel dari database.
        </div>
    </div>
</div>

<div class="modal fade" id="modal-bb-ska-pilih-tabel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning py-2">
                <h5 class="modal-title"><i class="fa fa-database"></i> Pilih Tabel Database</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted">Centang tabel yang akan ditampilkan sebagai tab di Setting Tabel dan Kode Akun. Setting disimpan ke database.</p>
                <table id="table-bb-ska-pilih-tabel" class="table table-bordered table-sm table-striped" style="width:100%;">
                    <thead>
                        <tr>
                            <th style="width:50px;">Pilih</th>
                            <th>Nama Tabel</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success btn-sm" id="btn-bb-ska-simpan-tabel"><i class="fa fa-save"></i> Simpan Pilihan Tabel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-bb-ska-kode-akun" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title"><i class="fa fa-book"></i> Setting Kode Akun Record</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="bb-ska-edit-table" value="">
                <input type="hidden" id="bb-ska-edit-record-id" value="">
                <p class="small mb-2">Tabel: <strong id="bb-ska-edit-table-label">—</strong> | Record ID: <strong id="bb-ska-edit-record-label">—</strong></p>
                <div class="form-group mb-0">
                    <label for="bb-ska-edit-kode-akun">Kode Akun</label>
                    <select id="bb-ska-edit-kode-akun" class="form-control form-control-sm">
                        <option value="">— Pilih kode akun —</option>
                        <?php foreach ($list_kode_akun as $ka) { ?>
                        <option value="<?php echo htmlspecialchars($ka->kode_akun, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($ka->kode_akun, ENT_QUOTES, 'UTF-8'); ?> — <?php echo htmlspecialchars($ka->nama_akun, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn-bb-ska-simpan-kode-akun"><i class="fa fa-save"></i> Simpan</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
window.bbSkaBoot = window.bbSkaBoot || function() {
    if (window._bbSkaBooted) return;
    window._bbSkaBooted = true;

    var urlListTables = <?php echo json_encode($url_bb_ska_list_tables); ?>;
    var urlSaveTables = <?php echo json_encode($url_bb_ska_save_tables); ?>;
    var urlTableRecords = <?php echo json_encode($url_bb_ska_table_records); ?>;
    var urlSaveRecord = <?php echo json_encode($url_bb_ska_save_record); ?>;
    var urlSelectedTables = <?php echo json_encode($url_bb_ska_selected_tables); ?>;
    var initialTables = <?php echo json_encode($bb_ska_selected_tables); ?>;

    var pilihDt = null;
    var recordDts = {};
    var loadedTables = {};
    var currentTables = [];
    var pilihTabelSelection = {};
    (initialTables || []).forEach(function(t) {
        var name = t.nama_tabel || t;
        if (name) pilihTabelSelection[name] = true;
    });

    function bbSkaSlug(name) {
        return String(name || '').replace(/[^a-zA-Z0-9_]/g, '_');
    }

    function bbSkaEscape(s) {
        return jQuery('<div>').text(s == null ? '' : String(s)).html();
    }

    function bbSkaBulanNs() {
        return jQuery('#bulan_ns').val() || '';
    }

    function bbSkaUpdateBulanLabel(label) {
        if (label) {
            jQuery('#bb-ska-label-bulan').text(label);
            return;
        }
        var ns = bbSkaBulanNs();
        if (/^\d{4}-\d{2}$/.test(ns)) {
            var p = ns.split('-');
            var bulanNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            jQuery('#bb-ska-label-bulan').text((bulanNames[parseInt(p[1], 10)] || p[1]) + ' ' + p[0]);
        } else {
            jQuery('#bb-ska-label-bulan').text('—');
        }
    }

    function destroyRecordDt(table) {
        var slug = bbSkaSlug(table);
        var sel = '#table-bb-ska-records-' + slug;
        if (recordDts[slug] && jQuery.fn.DataTable && jQuery.fn.DataTable.isDataTable(sel)) {
            recordDts[slug].destroy();
            delete recordDts[slug];
        }
    }

    function resetRecordPane(table) {
        var slug = bbSkaSlug(table);
        var $pane = jQuery('#panel-bb-ska-' + slug);
        destroyRecordDt(table);
        $pane.find('.bb-ska-alert').remove();
        $pane.find('.bb-ska-records-wrap').addClass('d-none');
        $pane.find('.bb-ska-loading').removeClass('d-none').html('<i class="fa fa-spinner fa-spin"></i> Memuat record bulan ' + bbSkaEscape(jQuery('#bb-ska-label-bulan').text()) + '...');
    }

    function buildRecordTableHead(displayColumns) {
        var html = '<tr><th>No</th><th>Record ID</th>';
        (displayColumns || []).forEach(function(col) {
            html += '<th>' + bbSkaEscape(col) + '</th>';
        });
        html += '<th>Kode Akun</th><th>Nama Akun</th><th class="text-right">Nilai</th><th>Aksi</th></tr>';
        return html;
    }

    function buildRecordTableBody(table, rows, displayColumns) {
        var html = '';
        (rows || []).forEach(function(r) {
            html += '<tr data-record-id="' + bbSkaEscape(r.record_id) + '">';
            html += '<td>' + bbSkaEscape(r.no) + '</td>';
            html += '<td>' + bbSkaEscape(r.record_id) + '</td>';
            (displayColumns || []).forEach(function(col) {
                var val = (r.columns && r.columns[col] !== undefined) ? r.columns[col] : '';
                html += '<td>' + bbSkaEscape(val) + '</td>';
            });
            html += '<td class="bb-ska-cell-kode">' + bbSkaEscape(r.kode_akun) + '</td>';
            html += '<td class="bb-ska-cell-nama">' + bbSkaEscape(r.nama_akun) + '</td>';
            html += '<td class="text-right">' + bbSkaEscape(r.nominal_formatted) + '</td>';
            html += '<td><button type="button" class="btn btn-warning btn-xs btn-bb-ska-setting-ka" data-table="' + bbSkaEscape(table) + '" data-record-id="' + bbSkaEscape(r.record_id) + '" data-kode-akun="' + bbSkaEscape(r.kode_akun) + '"><i class="fa fa-cog"></i> Setting Kode Akun</button></td>';
            html += '</tr>';
        });
        return html;
    }

    function loadTableRecords(table, force) {
        if (!table) return;
        var slug = bbSkaSlug(table);
        var cacheKey = table + '|' + bbSkaBulanNs();
        if (!force && loadedTables[cacheKey]) {
            return;
        }

        resetRecordPane(table);
        var $pane = jQuery('#panel-bb-ska-' + slug);

        jQuery.ajax({
            url: urlTableRecords,
            type: 'POST',
            dataType: 'json',
            data: { nama_tabel: table, bulan_ns: bbSkaBulanNs() }
        }).done(function(res) {
            $pane.find('.bb-ska-loading').addClass('d-none');
            if (!res || !res.ok) {
                $pane.append('<div class="alert alert-danger py-2 bb-ska-alert">' + bbSkaEscape((res && res.message) ? res.message : 'Gagal memuat data.') + '</div>');
                return;
            }

            if (res.bulan_label) {
                jQuery('#bb-ska-label-bulan').text(res.bulan_label);
            }

            var rows = res.rows || [];
            var displayColumns = res.display_columns || [];
            var $wrap = $pane.find('.bb-ska-records-wrap');
            var $table = $wrap.find('table');

            $table.find('thead').html(buildRecordTableHead(displayColumns));
            $table.find('tbody').html(buildRecordTableBody(table, rows, displayColumns));
            $wrap.removeClass('d-none');

            var infoHtml = '<div class="alert alert-light border py-2 mb-2 bb-ska-alert">';
            infoHtml += '<strong>' + bbSkaEscape(table) + '</strong> — ';
            infoHtml += '<strong>' + rows.length + '</strong> record';
            if (res.bulan_label) {
                infoHtml += ' | Periode: <strong>' + bbSkaEscape(res.bulan_label) + '</strong>';
            }
            if (res.kolom_tanggal) {
                infoHtml += ' | Filter kolom: <code>' + bbSkaEscape(res.kolom_tanggal) + '</code>';
            } else if (res.message) {
                infoHtml += ' | <span class="text-warning">' + bbSkaEscape(res.message) + '</span>';
            }
            infoHtml += '</div>';
            $pane.prepend(infoHtml);

            destroyRecordDt(table);
            if (jQuery.fn.DataTable) {
                recordDts[slug] = $table.DataTable({
                    pageLength: 25,
                    order: [[0, 'asc']],
                    scrollX: true,
                    language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json' }
                });
            }

            loadedTables[cacheKey] = true;
        }).fail(function() {
            $pane.find('.bb-ska-loading').html('<span class="text-danger">Gagal memuat data dari server.</span>');
        });
    }

    function loadActiveTableTab(force) {
        var $active = jQuery('#bb-ska-table-tabs .nav-link.active');
        if ($active.length) {
            loadTableRecords($active.data('table') || $active.attr('data-table'), force);
        }
    }

    window.bbSkaReloadAll = function(force) {
        bbSkaUpdateBulanLabel();
        loadedTables = {};
        if (!currentTables.length) {
            fetchSelectedTablesAndRender(force);
            return;
        }
        if (force) {
            currentTables.forEach(function(t) {
                var tbl = t.nama_tabel || t;
                loadTableRecords(tbl, true);
            });
        } else {
            loadActiveTableTab(true);
        }
    };

    function renderTableTabs(tables) {
        var $tabs = jQuery('#bb-ska-table-tabs');
        var $content = jQuery('#bb-ska-table-tabs-content');
        Object.keys(recordDts).forEach(function(k) {
            try { if (recordDts[k]) recordDts[k].destroy(); } catch (e) {}
        });
        recordDts = {};
        loadedTables = {};
        currentTables = tables || [];
        $tabs.empty();
        $content.find('.bb-ska-table-pane').remove();

        if (!tables || !tables.length) {
            jQuery('#bb-ska-empty-msg').removeClass('d-none');
            return;
        }
        jQuery('#bb-ska-empty-msg').addClass('d-none');
        bbSkaUpdateBulanLabel();

        tables.forEach(function(t, idx) {
            var tbl = t.nama_tabel || t;
            var slug = bbSkaSlug(tbl);
            var tabId = 'tab-bb-ska-' + slug;
            var panelId = 'panel-bb-ska-' + slug;
            var active = idx === 0 ? ' active' : '';
            var show = idx === 0 ? ' show active' : '';

            $tabs.append(
                '<li class="nav-item">'
                + '<a class="nav-link' + active + '" id="' + tabId + '" data-toggle="pill" href="#' + panelId + '" data-table="' + bbSkaEscape(tbl) + '" role="tab">' + bbSkaEscape(tbl) + '</a>'
                + '</li>'
            );

            $content.append(
                '<div class="tab-pane fade bb-ska-table-pane' + show + '" id="' + panelId + '" role="tabpanel" data-table="' + bbSkaEscape(tbl) + '">'
                + '<div class="text-center text-muted py-3 bb-ska-loading"><i class="fa fa-spinner fa-spin"></i> Memuat record...</div>'
                + '<div class="table-responsive bb-ska-records-wrap d-none">'
                + '<table id="table-bb-ska-records-' + slug + '" class="table table-bordered table-sm table-striped bb-ska-records-dt" style="width:100%;">'
                + '<thead></thead><tbody></tbody></table></div></div>'
            );
        });

        jQuery('#bb-ska-table-tabs a[data-toggle="pill"]').off('shown.bs.tab.bbSka').on('shown.bs.tab.bbSka', function() {
            loadTableRecords(jQuery(this).data('table') || jQuery(this).attr('data-table'), true);
        });

        tables.forEach(function(t, idx) {
            var tbl = t.nama_tabel || t;
            if (idx === 0) {
                loadTableRecords(tbl, true);
            }
        });
    }

    function syncPilihTabelSelectionFromServer(rows) {
        (rows || []).forEach(function(row) {
            pilihTabelSelection[row.nama_tabel] = !!row.selected;
        });
    }

    function applyPilihTabelCheckboxes() {
        jQuery('#table-bb-ska-pilih-tabel tbody .bb-ska-chk-tabel').each(function() {
            var val = jQuery(this).val();
            jQuery(this).prop('checked', !!pilihTabelSelection[val]);
        });
    }

    function collectSelectedTablesFromModal() {
        var selected = [];
        Object.keys(pilihTabelSelection).forEach(function(tbl) {
            if (pilihTabelSelection[tbl]) {
                selected.push(tbl);
            }
        });
        selected.sort();
        return selected;
    }

    function fetchSelectedTablesAndRender(forceReload) {
        jQuery.ajax({
            url: urlSelectedTables,
            type: 'POST',
            dataType: 'json'
        }).done(function(res) {
            if (res && res.ok && res.tables && res.tables.length) {
                renderTableTabs(res.tables);
            } else if (initialTables && initialTables.length) {
                renderTableTabs(initialTables);
                initialTables = null;
            }
            if (forceReload && currentTables.length) {
                window.bbSkaReloadAll(true);
            }
        });
    }

    function loadPilihTabelModal() {
        jQuery('#modal-bb-ska-pilih-tabel').modal('show');
        jQuery.ajax({
            url: urlListTables,
            type: 'POST',
            dataType: 'json'
        }).done(function(res) {
            if (!res || !res.ok) return;
            syncPilihTabelSelectionFromServer(res.tables || []);
            var html = '';
            (res.tables || []).forEach(function(row) {
                var checked = pilihTabelSelection[row.nama_tabel] ? ' checked' : '';
                html += '<tr data-tabel="' + bbSkaEscape(row.nama_tabel) + '"><td class="text-center"><input type="checkbox" class="bb-ska-chk-tabel" value="' + bbSkaEscape(row.nama_tabel) + '"' + checked + '></td>'
                    + '<td>' + bbSkaEscape(row.nama_tabel) + '</td></tr>';
            });
            jQuery('#table-bb-ska-pilih-tabel tbody').html(html);
            if (pilihDt && jQuery.fn.DataTable && jQuery.fn.DataTable.isDataTable('#table-bb-ska-pilih-tabel')) {
                pilihDt.destroy();
                pilihDt = null;
            }
            if (jQuery.fn.DataTable) {
                pilihDt = jQuery('#table-bb-ska-pilih-tabel').DataTable({
                    pageLength: 25,
                    order: [[1, 'asc']],
                    columnDefs: [{ orderable: false, targets: 0 }],
                    language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json' }
                });
            }
        });
    }

    jQuery(document).on('change', '.bb-ska-chk-tabel', function() {
        pilihTabelSelection[jQuery(this).val()] = jQuery(this).is(':checked');
    });

    function savePilihTabel() {
        applyPilihTabelCheckboxes();
        var selected = collectSelectedTablesFromModal();
        jQuery.ajax({
            url: urlSaveTables,
            type: 'POST',
            dataType: 'json',
            traditional: true,
            data: {
                tables_json: JSON.stringify(selected),
                'tables[]': selected
            }
        }).done(function(res) {
            if (!res || !res.ok) {
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Gagal', text: (res && res.message) ? res.message : 'Gagal menyimpan.' });
                return;
            }
            jQuery('#modal-bb-ska-pilih-tabel').modal('hide');
            renderTableTabs(res.tables || []);
            var tabCount = (res.tables || []).length;
            var msg = res.message || ('Setting tabel disimpan (' + tabCount + ' tabel).');
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'success', title: 'Tersimpan', text: msg, timer: 2200, showConfirmButton: false });
        });
    }

    jQuery('#btn-bb-ska-pilih-tabel').on('click', loadPilihTabelModal);
    jQuery('#btn-bb-ska-simpan-tabel').on('click', savePilihTabel);
    jQuery('#btn-bb-ska-muat-ulang').on('click', function() { window.bbSkaReloadAll(true); });

    jQuery(document).on('click', '.btn-bb-ska-setting-ka', function() {
        var $btn = jQuery(this);
        jQuery('#bb-ska-edit-table').val($btn.data('table') || '');
        jQuery('#bb-ska-edit-record-id').val($btn.data('record-id') || '');
        jQuery('#bb-ska-edit-table-label').text($btn.data('table') || '—');
        jQuery('#bb-ska-edit-record-label').text($btn.data('record-id') || '—');
        jQuery('#bb-ska-edit-kode-akun').val($btn.data('kode-akun') || '');
        jQuery('#modal-bb-ska-kode-akun').modal('show');
    });

    jQuery('#btn-bb-ska-simpan-kode-akun').on('click', function() {
        var table = jQuery('#bb-ska-edit-table').val();
        var recordId = jQuery('#bb-ska-edit-record-id').val();
        var kodeAkun = jQuery('#bb-ska-edit-kode-akun').val() || '';
        jQuery.ajax({
            url: urlSaveRecord,
            type: 'POST',
            dataType: 'json',
            data: { nama_tabel: table, record_id: recordId, kode_akun: kodeAkun }
        }).done(function(res) {
            if (!res || !res.ok) {
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Gagal', text: (res && res.message) ? res.message : 'Gagal menyimpan kode akun.' });
                return;
            }
            jQuery('#modal-bb-ska-kode-akun').modal('hide');
            var slug = bbSkaSlug(table);
            var $tr = jQuery('#table-bb-ska-records-' + slug + ' tbody tr[data-record-id="' + recordId + '"]');
            $tr.find('.bb-ska-cell-kode').text(res.kode_akun || '');
            $tr.find('.bb-ska-cell-nama').text(res.nama_akun || '');
            $tr.find('.btn-bb-ska-setting-ka').data('kode-akun', res.kode_akun || '');
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'success', title: 'Tersimpan', timer: 1500, showConfirmButton: false });
        });
    });

    function initSettingTab() {
        bbSkaUpdateBulanLabel();
        if (currentTables.length || jQuery('#bb-ska-table-tabs .nav-item').length) {
            window.bbSkaReloadAll(true);
            return;
        }
        if (initialTables && initialTables.length) {
            renderTableTabs(initialTables);
            initialTables = null;
            return;
        }
        fetchSelectedTablesAndRender(false);
    }

    jQuery('#tab-bb-setting-ska').on('shown.bs.tab', initSettingTab);
    if (jQuery('#tab-bb-setting-ska').hasClass('active')) {
        initSettingTab();
    }
};
</script>
