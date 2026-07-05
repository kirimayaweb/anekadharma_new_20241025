<?php
if (!isset($data_list)) {
    $data_list = array();
}
if (!isset($data_kode_akun)) {
    $data_kode_akun = array();
}
if (!isset($tbl_source_options)) {
    $tbl_source_options = array();
}
$url_create = isset($url_create) ? $url_create : site_url('Setting_kode_akun/create_action_ajax');
$url_update = isset($url_update) ? $url_update : site_url('Setting_kode_akun/update_action_ajax');
$url_delete = isset($url_delete) ? $url_delete : site_url('Setting_kode_akun/delete_action_ajax');
$url_excel = isset($url_excel) ? $url_excel : site_url('Setting_kode_akun/excel');
$url_reload_panel = isset($url_reload_panel) ? $url_reload_panel : site_url('Buku_besar/ajax_setting_kode_akun_panel');
$url_fields = isset($url_fields) ? $url_fields : site_url('Setting_kode_akun/ajax_source_fields');
$url_values = isset($url_values) ? $url_values : site_url('Setting_kode_akun/ajax_source_field_values');
$tab_setting_ska_active = (isset($active_tab) && $active_tab === 'setting');
?>
<div class="tab-pane fade<?php echo $tab_setting_ska_active ? ' show active' : ''; ?>" id="panel-bb-setting-ska" role="tabpanel">
    <div id="bb-unit-ska-panel-root">
        <?php $this->load->view('anekadharma/buku_besar/partials/setting_kode_akun_unit_panel', get_defined_vars()); ?>
    </div>
</div>

<div id="bb-unit-ska-modal-wrap">
    <?php $this->load->view('anekadharma/buku_besar/partials/setting_kode_akun_unit_modal', get_defined_vars()); ?>
</div>

<style>
    #bbUnitSkaModal { z-index: 1060 !important; }
    .modal-backdrop.bb-unit-ska-backdrop { z-index: 1055 !important; }
    .bb-unit-ska-modal-content { border-radius: 12px; overflow: hidden; }
    #bbUnitSkaTable tbody tr:hover { background-color: #f0f7ff !important; }
    .bb-unit-ska-embed .btn-xs { padding: 0.2rem 0.45rem; font-size: 0.78rem; }

    .bb-unit-ska-btn-tambah {
        background: linear-gradient(135deg, #0a3d91 0%, #1565c0 45%, #1e88e5 100%) !important;
        border: 1px solid #ffeb3b !important;
        box-shadow: 0 0 10px rgba(255, 235, 59, 0.55), 0 2px 12px rgba(10, 61, 145, 0.45) !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        text-shadow: 0 0 8px rgba(255, 255, 255, 0.45);
        transition: all 0.25s ease;
    }
    .bb-unit-ska-btn-tambah:hover,
    .bb-unit-ska-btn-tambah:focus {
        background: linear-gradient(135deg, #1565c0 0%, #1976d2 50%, #42a5f5 100%) !important;
        border-color: #fff176 !important;
        box-shadow: 0 0 14px rgba(255, 235, 59, 0.85), 0 4px 16px rgba(13, 71, 161, 0.55) !important;
        color: #ffffff !important;
    }
    .bb-unit-ska-btn-tambah i { color: #ffffff !important; }

    .bb-unit-ska-select2-dropdown .select2-results__options {
        max-height: 280px !important;
        overflow-y: auto !important;
    }
    .bb-unit-ska-select2-dropdown .select2-search__field {
        border-radius: 6px !important;
        padding: 6px 10px !important;
    }
    #bbUnitSkaModal .select2-container--bootstrap4 .select2-selection {
        min-height: 36px;
        border-radius: 6px;
    }
    #bbUnitSkaModal .select2-container--bootstrap4 .select2-selection__rendered {
        line-height: 24px;
        padding-top: 4px;
    }
</style>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/AdminLTE310/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<script type="text/javascript">
window.bbUnitSkaConfig = {
    urlCreate: <?php echo json_encode($url_create); ?>,
    urlUpdate: <?php echo json_encode($url_update); ?>,
    urlDelete: <?php echo json_encode($url_delete); ?>,
    urlReloadPanel: <?php echo json_encode($url_reload_panel); ?>,
    urlFields: <?php echo json_encode($url_fields); ?>,
    urlValues: <?php echo json_encode($url_values); ?>,
    valueLabels: {
        tbl_penjualan: 'Unit',
        tbl_pembelian: 'Konsumen',
        jurnal_kas: 'Keterangan'
    }
};

window.bbUnitSkaReloadPanel = window.bbUnitSkaReloadPanel || function() {
    if (!window.jQuery) return;
    var cfg = window.bbUnitSkaConfig || {};
    jQuery.ajax({
        url: cfg.urlReloadPanel,
        type: 'POST',
        dataType: 'json'
    }).done(function(res) {
        if (res && res.ok && res.html) {
            if (window.bbUnitSkaDestroyDt) window.bbUnitSkaDestroyDt();
            jQuery('#bb-unit-ska-panel-root').html(res.html);
            if (typeof window.bbUnitSkaInitPanel === 'function') window.bbUnitSkaInitPanel(true);
        }
    });
};

window.bbUnitSkaInitPanel = function() {
    if (!window.jQuery) return;

    var cfg = window.bbUnitSkaConfig || {};
    var $modal = jQuery('#bbUnitSkaModal');
    if (!jQuery('#bb-unit-ska-panel-root').length || !$modal.length) return;

    if (!$modal.data('bb-appended')) {
        $modal.appendTo('body');
        $modal.data('bb-appended', true);
    }

    if (!window._bbUnitSkaBound) {
        window._bbUnitSkaBound = true;
        window._bbUnitSkaMode = 'create';
        window._bbUnitSkaEditState = null;

        function valueLabel(tbl) {
            tbl = tbl || jQuery('#bb_unit_ska_tbl_source').val() || '';
            return (cfg.valueLabels && cfg.valueLabels[tbl]) ? cfg.valueLabels[tbl] : 'Nilai Sumber';
        }

        function destroySelect2El($el) {
            if ($el && $el.length && $el.data('select2')) {
                try { $el.select2('destroy'); } catch (e) {}
            }
        }

        function initSelect2El($el, placeholder) {
            if (!jQuery.fn.select2 || !$el || !$el.length) return;
            destroySelect2El($el);
            $el.select2({
                theme: 'bootstrap4',
                dropdownParent: $modal,
                width: '100%',
                placeholder: placeholder || 'Ketik untuk mencari...',
                allowClear: true,
                dropdownCssClass: 'bb-unit-ska-select2-dropdown',
                language: {
                    noResults: function() { return 'Data tidak ditemukan'; },
                    searching: function() { return 'Mencari...'; },
                    inputTooShort: function() { return 'Ketik kata kunci pencarian'; }
                }
            });
        }

        function initSelect2($scope) {
            if (!jQuery.fn.select2) return;
            var map = {
                '#bb_unit_ska_tbl_source': 'Cari / pilih tabel sumber...',
                '#bb_unit_ska_source_field': 'Cari / pilih field...',
                '#bb_unit_ska_source_value': 'Cari / pilih ' + valueLabel().toLowerCase() + '...',
                '#bb_unit_ska_kode_akun': 'Cari / pilih kode akun...'
            };
            var $targets = $scope ? $scope : jQuery('#bbUnitSkaModal .bb-unit-ska-select2');
            $targets.each(function() {
                var $el = jQuery(this);
                var ph = map['#' + $el.attr('id')] || 'Ketik untuk mencari...';
                initSelect2El($el, ph);
            });
        }

        function refreshFieldSelect2() {
            initSelect2El(jQuery('#bb_unit_ska_source_field'), 'Cari / pilih field...');
        }

        function refreshValueSelect2() {
            initSelect2El(jQuery('#bb_unit_ska_source_value'), 'Cari / pilih ' + valueLabel().toLowerCase() + '...');
        }

        function clearSourceSelection() {
            jQuery('#bb_unit_ska_uuid_unit, #bb_unit_ska_kode_unit, #bb_unit_ska_nama_unit, #bb_unit_ska_source_value_hidden').val('');
        }

        function applySourceItem($opt) {
            if (!$opt || !$opt.length) {
                clearSourceSelection();
                return;
            }
            jQuery('#bb_unit_ska_uuid_unit').val($opt.data('uuid') || '');
            jQuery('#bb_unit_ska_kode_unit').val($opt.data('kode') || '');
            jQuery('#bb_unit_ska_nama_unit').val($opt.data('nama') || $opt.val() || '');
            jQuery('#bb_unit_ska_source_value_hidden').val($opt.val() || '');
        }

        function updateValueLabel(tbl, custom) {
            var lbl = custom || valueLabel(tbl);
            jQuery('#bb_unit_ska_source_value_label').html('<i class="fas fa-list text-primary mr-1"></i> ' + lbl + ' <span class="text-danger">*</span>');
        }

        function loadFields(tbl, selectedField, callback) {
            var $field = jQuery('#bb_unit_ska_source_field');
            var $val = jQuery('#bb_unit_ska_source_value');
            destroySelect2El($field);
            destroySelect2El($val);
            $field.prop('disabled', true).html('<option value="">Memuat field...</option>');
            $val.prop('disabled', true).html('<option value="">-- Pilih field --</option>');
            clearSourceSelection();
            if (!tbl) {
                $field.html('<option value="">-- Pilih field --</option>');
                refreshFieldSelect2();
                refreshValueSelect2();
                return jQuery.Deferred().resolve().promise();
            }
            return jQuery.ajax({
                url: cfg.urlFields,
                type: 'POST',
                dataType: 'json',
                data: { tbl_source: tbl }
            }).done(function(res) {
                var html = '<option value=""></option>';
                if (res && res.ok && res.fields) {
                    res.fields.forEach(function(f) {
                        html += '<option value="' + f.name + '">' + f.name + '</option>';
                    });
                }
                $field.html(html).prop('disabled', false);
                var defField = selectedField || (res && res.default_field) || '';
                if (defField) $field.val(defField);
                updateValueLabel(tbl, res && res.value_label ? res.value_label : null);
                refreshFieldSelect2();
                refreshValueSelect2();
                if (typeof callback === 'function') callback();
            }).fail(function() {
                $field.html('<option value="">Gagal memuat field</option>');
                refreshFieldSelect2();
            });
        }

        function loadValues(tbl, field, selectedValue, callback) {
            var $val = jQuery('#bb_unit_ska_source_value');
            destroySelect2El($val);
            $val.prop('disabled', true).html('<option value="">Memuat data...</option>');
            clearSourceSelection();
            if (!tbl || !field) {
                $val.html('<option value="">-- Pilih tabel sumber & field --</option>');
                refreshValueSelect2();
                return jQuery.Deferred().resolve().promise();
            }
            return jQuery.ajax({
                url: cfg.urlValues,
                type: 'POST',
                dataType: 'json',
                data: { tbl_source: tbl, source_field: field }
            }).done(function(res) {
                var html = '<option value=""></option>';
                if (res && res.ok && res.items) {
                    res.items.forEach(function(it) {
                        var uuid = it.uuid || '';
                        var kode = it.kode || it.value || '';
                        var nama = it.nama || it.value || '';
                        var label = it.label || it.value || '';
                        html += '<option value="' + jQuery('<div>').text(it.value).html() + '"'
                            + ' data-uuid="' + jQuery('<div>').text(uuid).html() + '"'
                            + ' data-kode="' + jQuery('<div>').text(kode).html() + '"'
                            + ' data-nama="' + jQuery('<div>').text(nama).html() + '">'
                            + jQuery('<div>').text(label).html() + '</option>';
                    });
                }
                $val.html(html).prop('disabled', false);
                if (selectedValue) $val.val(selectedValue);
                applySourceItem($val.find('option:selected'));
                refreshValueSelect2();
                if (typeof callback === 'function') callback();
            }).fail(function() {
                $val.html('<option value="">Gagal memuat data</option>');
                refreshValueSelect2();
            });
        }

        function resetForm() {
            destroySelect2El(jQuery('#bb_unit_ska_tbl_source'));
            destroySelect2El(jQuery('#bb_unit_ska_source_field'));
            destroySelect2El(jQuery('#bb_unit_ska_source_value'));
            destroySelect2El(jQuery('#bb_unit_ska_kode_akun'));
            jQuery('#bbUnitSkaForm')[0].reset();
            jQuery('#bb_unit_ska_id').val('');
            jQuery('#bb_unit_ska_mutiply').val('1');
            jQuery('#bb_unit_ska_source_field').html('<option value="">-- Pilih field --</option>').prop('disabled', true);
            jQuery('#bb_unit_ska_source_value').html('<option value="">-- Pilih tabel sumber & field --</option>').prop('disabled', true);
            clearSourceSelection();
            updateValueLabel('');
            window._bbUnitSkaEditState = null;
        }

        function showSwal(type, title, text) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: type, title: title, text: text, confirmButtonColor: '#007bff' });
            } else alert(title + (text ? '\n' + text : ''));
        }

        window.bbUnitSkaDestroyDt = function() {
            var $t = jQuery('#bbUnitSkaTable');
            if ($t.length && jQuery.fn.DataTable && jQuery.fn.DataTable.isDataTable($t)) {
                $t.DataTable().destroy();
            }
        };

        window.bbUnitSkaInitTable = function() {
            if (!jQuery.fn.DataTable) return;
            var $t = jQuery('#bbUnitSkaTable');
            if (!$t.length) return;
            window.bbUnitSkaDestroyDt();
            $t.DataTable({
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
                order: [[2, 'asc']],
                scrollX: true,
                columnDefs: [{ orderable: false, targets: [1] }],
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    zeroRecords: 'Data tidak ditemukan',
                    paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' }
                }
            });
        };

        jQuery(document).on('click.bbUnitSka', '#bb-unit-ska-btn-tambah', function() {
            window._bbUnitSkaMode = 'create';
            resetForm();
            jQuery('#bbUnitSkaModalTitle').html('<i class="fas fa-plus-circle mr-2"></i>Tambah Setting Kode Akun');
            jQuery('#bbUnitSkaBtnSubmit').removeClass('btn-warning').addClass('btn-primary').html('<i class="fas fa-save"></i> Simpan');
            $modal.modal('show');
            setTimeout(function() { initSelect2(); }, 150);
        });

        jQuery(document).on('click.bbUnitSka', '.bb-unit-ska-btn-edit', function() {
            var $b = jQuery(this);
            window._bbUnitSkaMode = 'update';
            resetForm();
            var tbl = $b.data('tbl-source') || 'tbl_penjualan';
            var field = $b.data('source-field') || '';
            var srcVal = $b.data('source-value') || $b.data('nama-unit') || '';
            window._bbUnitSkaEditState = { tbl: tbl, field: field, value: srcVal };

            jQuery('#bb_unit_ska_id').val($b.data('id'));
            jQuery('#bb_unit_ska_kode_akun').val($b.data('kode-akun'));
            jQuery('#bb_unit_ska_mutiply').val($b.data('mutiply'));
            jQuery('#bb_unit_ska_keterangan').val($b.data('keterangan'));
            jQuery('#bb_unit_ska_uuid_unit').val($b.data('uuid-unit') || '');
            jQuery('#bb_unit_ska_kode_unit').val($b.data('kode-unit') || '');
            jQuery('#bb_unit_ska_nama_unit').val($b.data('nama-unit') || '');
            jQuery('#bb_unit_ska_source_value_hidden').val(srcVal);

            jQuery('#bb_unit_ska_tbl_source').val(tbl);
            loadFields(tbl, field, function() {
                loadValues(tbl, jQuery('#bb_unit_ska_source_field').val(), srcVal, function() {
                    initSelect2();
                });
            });

            jQuery('#bbUnitSkaModalTitle').html('<i class="fas fa-edit mr-2"></i>Ubah Setting Kode Akun');
            jQuery('#bbUnitSkaBtnSubmit').removeClass('btn-primary').addClass('btn-warning').html('<i class="fas fa-save"></i> Update');
            $modal.modal('show');
        });

        jQuery(document).on('change.bbUnitSka', '#bb_unit_ska_tbl_source', function() {
            var tbl = jQuery(this).val() || '';
            updateValueLabel(tbl);
            loadFields(tbl, '', function() {
                var field = jQuery('#bb_unit_ska_source_field').val();
                if (field) loadValues(tbl, field);
                initSelect2();
            });
        });

        jQuery(document).on('change.bbUnitSka', '#bb_unit_ska_source_field', function() {
            var tbl = jQuery('#bb_unit_ska_tbl_source').val() || '';
            var field = jQuery(this).val() || '';
            loadValues(tbl, field, function() { initSelect2(); });
        });

        jQuery(document).on('change.bbUnitSka', '#bb_unit_ska_source_value', function() {
            applySourceItem(jQuery(this).find('option:selected'));
        });

        jQuery(document).on('select2:select select2:clear', '#bb_unit_ska_source_value', function() {
            applySourceItem(jQuery(this).find('option:selected'));
        });

        jQuery(document).on('click.bbUnitSka', '.bb-unit-ska-btn-delete', function() {
            var id = jQuery(this).data('id');
            var label = jQuery(this).data('label');
            var doDelete = function() {
                jQuery.ajax({ url: cfg.urlDelete, type: 'POST', dataType: 'json', data: { id: id } })
                    .done(function(res) {
                        if (res && res.success) {
                            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'success', title: 'Berhasil', timer: 1500, showConfirmButton: false });
                            window.bbUnitSkaReloadPanel();
                        } else showSwal('error', 'Gagal', (res && res.message) ? res.message : 'Gagal hapus.');
                    })
                    .fail(function() { showSwal('error', 'Gagal', 'Tidak dapat menghubungi server.'); });
            };
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning', title: 'Konfirmasi Hapus',
                    html: 'Yakin hapus data <strong>' + label + '</strong>?',
                    showCancelButton: true, confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal'
                }).then(function(r) { if (r.isConfirmed) doDelete(); });
            } else if (confirm('Hapus data?')) doDelete();
        });

        jQuery(document).on('submit.bbUnitSka', '#bbUnitSkaForm', function(e) {
            e.preventDefault();
            if (!jQuery.trim(jQuery('#bb_unit_ska_tbl_source').val())) {
                showSwal('warning', 'Perhatian', 'Tabel sumber wajib dipilih.');
                return;
            }
            if (!jQuery.trim(jQuery('#bb_unit_ska_source_field').val())) {
                showSwal('warning', 'Perhatian', 'Field wajib dipilih.');
                return;
            }
            if (!jQuery.trim(jQuery('#bb_unit_ska_nama_unit').val())) {
                showSwal('warning', 'Perhatian', valueLabel() + ' wajib dipilih.');
                return;
            }
            var url = (window._bbUnitSkaMode === 'update') ? cfg.urlUpdate : cfg.urlCreate;
            jQuery('#bbUnitSkaBtnSubmit').prop('disabled', true);
            jQuery.ajax({ url: url, type: 'POST', dataType: 'json', data: jQuery(this).serialize() })
                .done(function(res) {
                    jQuery('#bbUnitSkaBtnSubmit').prop('disabled', false);
                    if (res && res.success) {
                        $modal.modal('hide');
                        if (typeof Swal !== 'undefined') Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Data disimpan.', timer: 1500, showConfirmButton: false });
                        window.bbUnitSkaReloadPanel();
                    } else showSwal('error', 'Gagal', (res && res.message) ? res.message : 'Gagal simpan.');
                })
                .fail(function() {
                    jQuery('#bbUnitSkaBtnSubmit').prop('disabled', false);
                    showSwal('error', 'Gagal', 'Tidak dapat menghubungi server.');
                });
        });

        $modal.on('shown.bs.modal.bbUnitSka', function() {
            initSelect2();
        });
    }

    window.bbUnitSkaInitTable();
};

window.bbUnitSkaBoot = window.bbUnitSkaBoot || function() {
    if (typeof window.bbUnitSkaInitPanel === 'function') window.bbUnitSkaInitPanel();
};
</script>
