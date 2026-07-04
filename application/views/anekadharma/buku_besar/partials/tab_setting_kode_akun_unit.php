<?php
if (!isset($data_list)) {
    $data_list = array();
}
if (!isset($data_unit)) {
    $data_unit = array();
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
    #bbUnitSkaModal + .modal-backdrop { z-index: 1055 !important; }
    #bbUnitSkaTable tbody tr:hover { background-color: #f0f7ff !important; }
    .bb-unit-ska-embed .btn-xs { padding: 0.2rem 0.45rem; font-size: 0.78rem; }
</style>

<script type="text/javascript">
window.bbUnitSkaConfig = {
    urlCreate: <?php echo json_encode($url_create); ?>,
    urlUpdate: <?php echo json_encode($url_update); ?>,
    urlDelete: <?php echo json_encode($url_delete); ?>,
    urlReloadPanel: <?php echo json_encode($url_reload_panel); ?>
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
            var $root = jQuery('#bb-unit-ska-panel-root');
            if (window.bbUnitSkaDestroyDt) {
                window.bbUnitSkaDestroyDt();
            }
            $root.html(res.html);
            if (typeof window.bbUnitSkaInitPanel === 'function') {
                window.bbUnitSkaInitPanel(true);
            }
        }
    });
};

window.bbUnitSkaInitPanel = function(reinitTableOnly) {
    if (!window.jQuery) return;

    var cfg = window.bbUnitSkaConfig || {};
    var $root = jQuery('#bb-unit-ska-panel-root');
    var $modal = jQuery('#bbUnitSkaModal');
    if (!$root.length || !$modal.length) return;

    if (!$modal.data('bb-appended')) {
        $modal.appendTo('body');
        $modal.data('bb-appended', true);
    }

    if (!window._bbUnitSkaBound) {
        window._bbUnitSkaBound = true;
        window._bbUnitSkaMode = 'create';

        function fillUnitFields() {
            var $sel = jQuery('#bb_unit_ska_uuid_unit option:selected');
            jQuery('#bb_unit_ska_kode_unit').val($sel.data('kode') || '');
            jQuery('#bb_unit_ska_nama_unit').val($sel.data('nama') || '');
        }

        function initSelect2() {
            if (!jQuery.fn.select2) return;
            jQuery('.bb-unit-ska-select2').each(function() {
                var $el = jQuery(this);
                if ($el.data('select2')) {
                    $el.select2('destroy');
                }
                $el.select2({
                    theme: 'bootstrap4',
                    dropdownParent: $modal,
                    width: '100%'
                });
            });
        }

        function resetForm() {
            jQuery('#bbUnitSkaForm')[0].reset();
            jQuery('#bb_unit_ska_id').val('');
            jQuery('#bb_unit_ska_uuid_unit, #bb_unit_ska_kode_akun, #bb_unit_ska_tbl_source').val('').trigger('change');
            jQuery('#bb_unit_ska_tbl_source').val('tbl_penjualan').trigger('change');
            jQuery('#bb_unit_ska_mutiply').val('1');
            fillUnitFields();
        }

        function showSwal(type, title, text) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: type, title: title, text: text, confirmButtonColor: '#007bff' });
            } else {
                alert(title + (text ? '\n' + text : ''));
            }
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
                order: [[3, 'asc']],
                scrollX: true,
                columnDefs: [{ orderable: false, targets: [1] }],
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
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
            initSelect2();
            $modal.modal('show');
        });

        jQuery(document).on('click.bbUnitSka', '.bb-unit-ska-btn-edit', function() {
            var $b = jQuery(this);
            window._bbUnitSkaMode = 'update';
            resetForm();
            jQuery('#bb_unit_ska_id').val($b.data('id'));
            jQuery('#bb_unit_ska_uuid_unit').val($b.data('uuid-unit')).trigger('change');
            fillUnitFields();
            jQuery('#bb_unit_ska_kode_unit').val($b.data('kode-unit'));
            jQuery('#bb_unit_ska_nama_unit').val($b.data('nama-unit'));
            jQuery('#bb_unit_ska_kode_akun').val($b.data('kode-akun')).trigger('change');
            jQuery('#bb_unit_ska_tbl_source').val($b.data('tbl-source') || 'tbl_penjualan').trigger('change');
            jQuery('#bb_unit_ska_mutiply').val($b.data('mutiply'));
            jQuery('#bb_unit_ska_keterangan').val($b.data('keterangan'));
            jQuery('#bbUnitSkaModalTitle').html('<i class="fas fa-edit mr-2"></i>Ubah Setting Kode Akun');
            jQuery('#bbUnitSkaBtnSubmit').removeClass('btn-primary').addClass('btn-warning').html('<i class="fas fa-save"></i> Update');
            initSelect2();
            $modal.modal('show');
        });

        jQuery(document).on('change.bbUnitSka', '#bb_unit_ska_uuid_unit', fillUnitFields);

        jQuery(document).on('click.bbUnitSka', '.bb-unit-ska-btn-delete', function() {
            var id = jQuery(this).data('id');
            var label = jQuery(this).data('label');
            var doDelete = function() {
                jQuery.ajax({
                    url: cfg.urlDelete,
                    type: 'POST',
                    dataType: 'json',
                    data: { id: id }
                }).done(function(res) {
                    if (res && res.success) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Data dihapus.', timer: 1500, showConfirmButton: false });
                        }
                        window.bbUnitSkaReloadPanel();
                    } else {
                        showSwal('error', 'Gagal', (res && res.message) ? res.message : 'Gagal hapus.');
                    }
                }).fail(function() {
                    showSwal('error', 'Gagal', 'Tidak dapat menghubungi server.');
                });
            };
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Konfirmasi Hapus',
                    html: 'Yakin hapus data <strong>' + label + '</strong>?',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then(function(r) { if (r.isConfirmed) doDelete(); });
            } else if (confirm('Hapus data?')) {
                doDelete();
            }
        });

        jQuery(document).on('submit.bbUnitSka', '#bbUnitSkaForm', function(e) {
            e.preventDefault();
            if (!jQuery.trim(jQuery('#bb_unit_ska_tbl_source').val())) {
                showSwal('warning', 'Perhatian', 'Tabel sumber wajib dipilih.');
                return;
            }
            var url = (window._bbUnitSkaMode === 'update') ? cfg.urlUpdate : cfg.urlCreate;
            jQuery('#bbUnitSkaBtnSubmit').prop('disabled', true);
            jQuery.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: jQuery(this).serialize()
            }).done(function(res) {
                jQuery('#bbUnitSkaBtnSubmit').prop('disabled', false);
                if (res && res.success) {
                    $modal.modal('hide');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Data disimpan.', timer: 1500, showConfirmButton: false });
                    }
                    window.bbUnitSkaReloadPanel();
                } else {
                    showSwal('error', 'Gagal', (res && res.message) ? res.message : 'Gagal simpan.');
                }
            }).fail(function() {
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
    if (typeof window.bbUnitSkaInitPanel === 'function') {
        window.bbUnitSkaInitPanel(false);
    }
};
</script>
