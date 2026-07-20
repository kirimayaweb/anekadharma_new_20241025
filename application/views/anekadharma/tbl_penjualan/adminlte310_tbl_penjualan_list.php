<div class="content-wrapper">


    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v1</li> -->
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">


        <?php
        // echo $date_awal; 
        // echo "<br/>";

        if (date("Y", strtotime($date_awal)) < 2020) {
            $Get_date_awal = date("d-m-Y");
        } else {
            $Get_date_awal = date("d-m-Y", strtotime($date_awal));
        }

        // echo $Get_date_awal;
        // echo "<br/>";
        // echo "<br/>";


        // echo $date_akhir; 
        // echo "<br/>";

        if (date("Y", strtotime($date_akhir)) < 2020) {
            $Get_date_akhir = date("d-m-Y");
        } else {
            $Get_date_akhir = date("d-m-Y", strtotime($date_akhir));
        }

        // echo $Get_date_akhir;
        // echo "<br/>";
        // echo "<br/>";

        $excel_export_ids = array();
        if (!empty($Tbl_penjualan_data)) {
            foreach ($Tbl_penjualan_data as $row_export) {
                if (!empty($row_export->id)) {
                    $excel_export_ids[] = (int) $row_export->id;
                }
            }
        }
        $excel_export_ids_str = implode(',', $excel_export_ids);

        if (!isset($Tbl_penjualan_data_belum_bayar)) {
            $Tbl_penjualan_data_belum_bayar = array();
        }
        if (!isset($Tbl_penjualan_data_terbayar)) {
            $Tbl_penjualan_data_terbayar = array();
        }
        if (!isset($penjualan_count_belum_bayar)) {
            $penjualan_count_belum_bayar = count($Tbl_penjualan_data_belum_bayar);
        }
        if (!isset($penjualan_count_terbayar)) {
            $penjualan_count_terbayar = count($Tbl_penjualan_data_terbayar);
        }
        if (!isset($penjualan_active_tab) || $penjualan_active_tab === '') {
            $penjualan_active_tab = 'tab-penjualan-semua';
        }

        $penjualan_tabs_list = array(
            array(
                'tab_id' => 'tab-penjualan-semua',
                'link_id' => 'tab-penjualan-semua-link',
                'label' => 'Penjualan',
                'table_id' => 'tglSPOPFreeze',
                'data' => $Tbl_penjualan_data,
                'count' => count($Tbl_penjualan_data),
                'badge_class' => 'badge-secondary',
            ),
            array(
                'tab_id' => 'tab-penjualan-belum-bayar',
                'link_id' => 'tab-penjualan-belum-bayar-link',
                'label' => 'Belum Terbayar ( P )',
                'table_id' => 'tglSPOPFreeze1',
                'data' => $Tbl_penjualan_data_belum_bayar,
                'count' => (int) $penjualan_count_belum_bayar,
                'badge_class' => 'badge-danger',
            ),
            array(
                'tab_id' => 'tab-penjualan-terbayar',
                'link_id' => 'tab-penjualan-terbayar-link',
                'label' => 'Terbayarkan / Proses',
                'table_id' => 'tglSPOPFreeze2',
                'data' => $Tbl_penjualan_data_terbayar,
                'count' => (int) $penjualan_count_terbayar,
                'badge_class' => 'badge-success',
            ),
        );

        ?>



        <!-- DATA PENJUALAN -->

        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="row">
                                    <!-- <div class="col-5" text-align="center"> <strong>DATA PENJUALAN</strong></div> -->
                                    <div class="col-12" text-align="center"> <strong><a href="<?php echo site_url('tbl_penjualan/create'); ?>" id="btn-input-penjualan-baru" class="btn btn-danger">Input PENJUALAN BARU</a></strong></div>

                                </div>


                            </div>
                            <div class="col-md-5">

                                <?php
                                // $action_cari_between_date="cari_between_date" ;
                                $action_cari_between_date = site_url('Tbl_penjualan/cari_between_date');

                                ?>

                                <form id="form-cari-penjualan" action="<?php echo $action_cari_between_date; ?>" method="post">
                                    <input type="hidden" name="penjualan_active_tab" id="penjualan_active_tab_input" value="<?php echo htmlspecialchars($penjualan_active_tab, ENT_QUOTES, 'UTF-8'); ?>" />
                                    <div class="row">

                                        <div class="col-md-4" text-align="right">
                                            <div class="input-group date" id="tgl_awal" name="tgl_awal" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#tgl_awal" id="tgl_awal" name="tgl_awal" value="<?php echo $Get_date_awal; ?>" required />
                                                <div class="input-group-append" data-target="#tgl_awal" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-1" text-align="center" align="center">s/d</div>

                                        <div class="col-md-4" text-align="left" align="left">
                                            <div class="input-group date" id="tgl_akhir" name="tgl_akhir" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#tgl_akhir" id="tgl_akhir" name="tgl_akhir" value="<?php echo $Get_date_akhir; ?>" required />
                                                <div class="input-group-append" data-target="#tgl_akhir" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3" text-align="left" align="left">
                                            <strong>
                                                <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in" aria-hidden="true"></i> Cari</button>
                                            </strong>
                                        </div>

                                    </div>
                                </form>

                            </div>

                            <div class="col-md-2">
                                <?php //echo anchor(site_url('tbl_penjualan/RekapPenjualanPerBarang'), 'Rekap Penjualan Per Barang', 'class="btn btn-success"'); ?>


                            </div>

                            <div class="col-md-2">
                                <?php //echo anchor(site_url('tbl_penjualan/RekapPenjualanPerKonsumen'), 'Rekap Penjualan Per Konsumen', 'class="btn btn-success"'); ?>
                                
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-xl-select-unit">
                                    REKAP DATA
                                </button>

                            </div>

                            <div class="col-md-1">
                                <input type="hidden" id="excel-export-source" value="tbl_penjualan" />
                                <input type="hidden" id="excel-export-ids" value="<?php echo htmlspecialchars($excel_export_ids_str, ENT_QUOTES, 'UTF-8'); ?>" />
                                <button type="button" class="btn btn-success btn-block" onclick="cetakExcelPenjualan(); return false;">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Cetak ke Excel (.xlsx)
                                </button>
                            </div>


                        </div>




                    </div>




                    <div class="card-body">

                        <style>
                            .col-tgl-jual-penjualan {
                                white-space: nowrap;
                                vertical-align: top;
                            }
                            .penjualan-badge-bayar-p {
                                display: block;
                                font-size: 1.45rem;
                                font-weight: 800;
                                color: #dc3545;
                                line-height: 1.05;
                                margin-top: 0.15rem;
                                letter-spacing: 0.02em;
                            }
                            .penjualan-badge-bayar-l {
                                display: block;
                                font-size: 1.2rem;
                                font-weight: 600;
                                color: #20a070;
                                line-height: 1.05;
                                margin-top: 0.15rem;
                                opacity: 0.92;
                            }
                            #penjualan-proses-bayar-tabs {
                                border-bottom: 1px solid #dee2e6;
                            }
                            #penjualan-proses-bayar-tabs .nav-link {
                                color: #212529;
                                font-style: italic;
                                font-weight: 500;
                                background: #f8f9fa;
                                border: 1px solid #52b788;
                                border-bottom: none;
                                margin-right: 0.35rem;
                                margin-bottom: -1px;
                                border-radius: 0.4rem 0.4rem 0 0;
                                transition: color 0.2s ease, background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
                            }
                            #penjualan-proses-bayar-tabs .nav-link:hover:not(.active) {
                                background: #eef9f1;
                                color: #1a1a1a;
                            }
                            #penjualan-proses-bayar-tabs .nav-link.active {
                                color: #fff !important;
                                font-style: normal;
                                font-weight: 700;
                                background: linear-gradient(180deg, #2b7cff 0%, #0056d6 100%) !important;
                                border: 2px solid #ffc107 !important;
                                box-shadow: 0 0 10px rgba(255, 193, 7, 0.85), 0 2px 8px rgba(0, 86, 214, 0.35);
                            }
                            #penjualan-proses-bayar-tabs .nav-link.active .badge-count {
                                background: rgba(255, 255, 255, 0.28);
                                color: #fff;
                            }
                            #penjualan-proses-bayar-tabs .badge-count {
                                font-size: 0.72rem;
                                margin-left: 0.25rem;
                            }
                        </style>

                        <ul class="nav nav-tabs" id="penjualan-proses-bayar-tabs" role="tablist">
                            <?php foreach ($penjualan_tabs_list as $tab_cfg) :
                                $tab_nav_active = ($penjualan_active_tab === $tab_cfg['tab_id']) ? ' active' : '';
                            ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_nav_active; ?>" id="<?php echo htmlspecialchars($tab_cfg['link_id'], ENT_QUOTES, 'UTF-8'); ?>" data-toggle="tab" href="#<?php echo htmlspecialchars($tab_cfg['tab_id'], ENT_QUOTES, 'UTF-8'); ?>" role="tab" data-table-id="<?php echo htmlspecialchars($tab_cfg['table_id'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($tab_cfg['label'], ENT_QUOTES, 'UTF-8'); ?> <span class="badge <?php echo htmlspecialchars($tab_cfg['badge_class'], ENT_QUOTES, 'UTF-8'); ?> badge-count"><?php echo (int) $tab_cfg['count']; ?></span></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="tab-content pt-3" id="penjualan-proses-bayar-tabs-content">
                            <?php
                            foreach ($penjualan_tabs_list as $tab_cfg) :
                                $Tbl_penjualan_tab_data = $tab_cfg['data'];
                                $penjualan_table_id = $tab_cfg['table_id'];
                                $tab_active_class = ($penjualan_active_tab === $tab_cfg['tab_id']) ? ' show active' : '';
                            ?>
                            <div class="tab-pane fade<?php echo $tab_active_class; ?>" id="<?php echo htmlspecialchars($tab_cfg['tab_id'], ENT_QUOTES, 'UTF-8'); ?>" role="tabpanel">
                                <?php include __DIR__ . '/_adminlte310_tbl_penjualan_list_table_fragment.php'; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </section>
</div>



<!-- TAMBAH BARANG MODAL EXTRA LARGE -->
<form action="<?php //echo $action_simpan_bahan; 
                ?>" method="post">
    <div class="modal fade" id="modal-xl-select-unit">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">REKAP DATA</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">


                        <p class="text-muted small mb-2" id="rekap-modal-periode-info">Periode mengikuti tanggal awal dan tanggal akhir di atas.</p>
                        <div class="row">
                            <div class="col-4">
                                <a href="#" class="btn btn-success btn-block btn-rekap-penjualan" data-field="nama_barang" target="_blank">Rekap Per Barang</a>
                            </div>
                            <div class="col-4">
                                <a href="#" class="btn btn-success btn-block btn-rekap-penjualan" data-field="konsumen_nama" target="_blank">Rekap Per Konsumen</a>
                            </div>
                            <div class="col-4">
                                <a href="#" class="btn btn-success btn-block btn-rekap-penjualan" data-field="unit" target="_blank">Rekap Per Unit</a>
                            </div>

                          

                        </div>



                    </div>

                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <!-- <button type="button" class="btn btn-primary">Simpan</button> -->
                    <!-- <button type="submit" class="btn btn-primary">Proses</button> -->
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
<!-- END OF MODAL EXTRA LARGE -->




<script>
    function getActivePenjualanTableId() {
        var activePane = document.querySelector('#penjualan-proses-bayar-tabs-content .tab-pane.active');
        if (activePane) {
            var tableEl = activePane.querySelector('table.penjualan-list-table');
            if (tableEl && tableEl.id) {
                return tableEl.id;
            }
        }
        var activeTab = document.querySelector('#penjualan-proses-bayar-tabs .nav-link.active');
        if (activeTab) {
            var tableId = activeTab.getAttribute('data-table-id');
            if (tableId) {
                return tableId;
            }
        }
        return 'tglSPOPFreeze';
    }

    function isDataTablePenjualanAktif() {
        if (!window.jQuery || !jQuery.fn.DataTable) {
            return false;
        }
        var tableId = getActivePenjualanTableId();
        return jQuery.fn.DataTable.isDataTable('#' + tableId);
    }

    function ambilIdDariBarisPenjualan(tr) {
        if (!tr) {
            return 0;
        }
        var rawId = tr.getAttribute('data-penjualan-id');
        if (!rawId && tr.id) {
            var clone = document.getElementById(tr.id);
            if (clone) {
                rawId = clone.getAttribute('data-penjualan-id');
            }
        }
        var id = parseInt(rawId, 10);
        return (!isNaN(id) && id > 0) ? id : 0;
    }

    function kumpulkanIdPenjualanDariDataTable() {
        var ids = [];
        if (!isDataTablePenjualanAktif()) {
            return ids;
        }

        var tableId = getActivePenjualanTableId();
        var table = jQuery('#' + tableId).DataTable();
        var nodes = table.rows({ search: 'applied', order: 'applied', page: 'all' }).nodes();
        for (var i = 0; i < nodes.length; i++) {
            var tr = nodes[i];
            var id = ambilIdDariBarisPenjualan(tr);
            if (id > 0) {
                ids.push(id);
            }
        }

        if (!ids.length) {
            table.rows({ search: 'applied', order: 'applied', page: 'all' }).every(function() {
                var id = ambilIdDariBarisPenjualan(this.node());
                if (id > 0) {
                    ids.push(id);
                }
            });
        }

        return ids;
    }

    function kumpulkanIdPenjualanDariDomUrutanTabel() {
        var ids = [];
        var tableId = getActivePenjualanTableId();
        var tbody = document.querySelector('#' + tableId + ' tbody');
        if (!tbody) {
            return ids;
        }
        Array.prototype.forEach.call(tbody.querySelectorAll('tr.row-penjualan-data'), function(tr) {
            var id = ambilIdDariBarisPenjualan(tr);
            if (id > 0) {
                ids.push(id);
            }
        });
        return ids;
    }

    function cetakExcelPenjualan() {
        var tglAwalEl = document.querySelector('#form-cari-penjualan input[name="tgl_awal"]');
        var tglAkhirEl = document.querySelector('#form-cari-penjualan input[name="tgl_akhir"]');
        var tglAwal = tglAwalEl ? tglAwalEl.value : '';
        var tglAkhir = tglAkhirEl ? tglAkhirEl.value : '';
        if (!tglAwal || !tglAkhir) {
            alert('Pilih tanggal awal dan tanggal akhir terlebih dahulu.');
            return;
        }

        var ids = kumpulkanIdPenjualanDariDataTable();
        if (!ids.length && !isDataTablePenjualanAktif()) {
            var idsEl = document.getElementById('excel-export-ids');
            if (idsEl && idsEl.value) {
                ids = idsEl.value.split(',').map(function(v) {
                    return parseInt(v, 10);
                }).filter(function(v) {
                    return !isNaN(v) && v > 0;
                });
            }
            if (!ids.length) {
                ids = kumpulkanIdPenjualanDariDomUrutanTabel();
            }
        }

        if (!ids.length) {
            alert('Tidak ada data penjualan untuk diekspor. Periksa filter/search DataTable atau rentang tanggal.');
            return;
        }

        var seenId = {};
        ids = ids.filter(function(id) {
            if (seenId[id]) {
                return false;
            }
            seenId[id] = true;
            return true;
        });

        var sourceEl = document.getElementById('excel-export-source');
        var source = sourceEl ? sourceEl.value : 'tbl_penjualan';
        var url = <?php echo json_encode(site_url('Tbl_penjualan/excel')); ?>
            + '?source=' + encodeURIComponent(source)
            + '&from_datatable=1'
            + '&ids=' + encodeURIComponent(ids.join(','))
            + '&tgl_awal=' + encodeURIComponent(tglAwal)
            + '&tgl_akhir=' + encodeURIComponent(tglAkhir);
        window.location.href = url;
    }

(function() {
    var baseRekapUrl = <?php echo json_encode(site_url('Tbl_penjualan/RekapData/')); ?>;
    var FILTER_STORAGE_KEY = 'anekadharma_tbl_penjualan_list_state';
    var filterRestoreAttempted = false;
    var skipFilterRestore = <?php echo (isset($skip_filter_restore) && $skip_filter_restore) ? 'true' : 'false'; ?>;

    function parseTanggalInputKey(val) {
        if (!val) {
            return null;
        }
        var parts = String(val).trim().split(/[-/.]/);
        if (parts.length !== 3) {
            return null;
        }
        var d = parseInt(parts[0], 10);
        var m = parseInt(parts[1], 10);
        var y = parseInt(parts[2], 10);
        if (isNaN(d) || isNaN(m) || isNaN(y) || d <= 0 || m <= 0) {
            return null;
        }
        if (y < 100) {
            y += 2000;
        }
        return (y * 10000) + (m * 100) + d;
    }

    function tanggalInputSama(a, b) {
        var keyA = parseTanggalInputKey(a);
        var keyB = parseTanggalInputKey(b);
        return keyA !== null && keyB !== null && keyA === keyB;
    }

    function getActiveTabIdFromDom() {
        var activeTab = document.querySelector('#penjualan-proses-bayar-tabs .nav-link.active');
        if (activeTab) {
            var href = activeTab.getAttribute('href') || '';
            if (href.charAt(0) === '#') {
                return href.substring(1);
            }
        }
        var tabInput = document.getElementById('penjualan_active_tab_input');
        return tabInput && tabInput.value ? tabInput.value : 'tab-penjualan-semua';
    }

    function setPenjualanActiveTabInput(tabId) {
        var tabInput = document.getElementById('penjualan_active_tab_input');
        if (tabInput && tabId) {
            tabInput.value = tabId;
        }
    }

    function savePenjualanListState(awal, akhir, activeTab) {
        if (!window.sessionStorage) {
            return;
        }
        var tgl = getTanggalFilterPenjualan();
        var tabId = activeTab || getActiveTabIdFromDom();
        var tglAwal = awal || tgl.awal;
        var tglAkhir = akhir || tgl.akhir;
        if (!tglAwal || !tglAkhir) {
            return;
        }
        try {
            sessionStorage.setItem(FILTER_STORAGE_KEY, JSON.stringify({
                tgl_awal: tglAwal,
                tgl_akhir: tglAkhir,
                active_tab: tabId
            }));
        } catch (eStorage) {}
    }

    function loadPenjualanListState() {
        if (!window.sessionStorage) {
            return null;
        }
        try {
            var raw = sessionStorage.getItem(FILTER_STORAGE_KEY);
            if (!raw) {
                return null;
            }
            var parsed = JSON.parse(raw);
            if (parsed && parsed.tgl_awal && parsed.tgl_akhir) {
                return parsed;
            }
        } catch (eParse) {}
        return null;
    }

    function restorePenjualanListStateDariSession() {
        if (filterRestoreAttempted || skipFilterRestore) {
            return;
        }
        filterRestoreAttempted = true;

        var stored = loadPenjualanListState();
        if (!stored) {
            return;
        }

        var inpAwal = document.querySelector('#form-cari-penjualan input[name="tgl_awal"]');
        var inpAkhir = document.querySelector('#form-cari-penjualan input[name="tgl_akhir"]');
        var form = document.getElementById('form-cari-penjualan');
        if (!inpAwal || !inpAkhir || !form) {
            return;
        }

        var tanggalBerbeda = !tanggalInputSama(inpAwal.value, stored.tgl_awal)
            || !tanggalInputSama(inpAkhir.value, stored.tgl_akhir);

        if (tanggalBerbeda) {
            inpAwal.value = stored.tgl_awal;
            inpAkhir.value = stored.tgl_akhir;
            if (stored.active_tab) {
                setPenjualanActiveTabInput(stored.active_tab);
            }
            form.submit();
            return;
        }

        if (stored.active_tab) {
            setPenjualanActiveTabInput(stored.active_tab);
            restorePenjualanActiveTab(stored.active_tab);
        }
    }

    function restorePenjualanActiveTab(tabId) {
        if (!window.jQuery || !tabId) {
            return;
        }
        var link = document.querySelector('#penjualan-proses-bayar-tabs a[href="#' + tabId + '"]');
        if (link && !link.classList.contains('active')) {
            jQuery(link).tab('show');
        }
    }

    function getTanggalFilterPenjualan() {
        var tglAwal = document.querySelector('#form-cari-penjualan input[name="tgl_awal"]');
        var tglAkhir = document.querySelector('#form-cari-penjualan input[name="tgl_akhir"]');
        return {
            awal: tglAwal ? tglAwal.value : '',
            akhir: tglAkhir ? tglAkhir.value : ''
        };
    }

    function buildUrlInputPenjualanBaru() {
        var baseCreate = <?php echo json_encode(site_url('tbl_penjualan/create')); ?>;
        var tgl = getTanggalFilterPenjualan();
        if (tgl.awal && tgl.akhir) {
            return baseCreate
                + '?tgl_awal=' + encodeURIComponent(tgl.awal)
                + '&tgl_akhir=' + encodeURIComponent(tgl.akhir);
        }
        return baseCreate;
    }

    function initLinkInputPenjualanBaru() {
        var btn = document.getElementById('btn-input-penjualan-baru');
        if (!btn) {
            return;
        }
        btn.href = buildUrlInputPenjualanBaru();
        btn.addEventListener('click', function(e) {
            btn.href = buildUrlInputPenjualanBaru();
            var tgl = getTanggalFilterPenjualan();
            if (!tgl.awal || !tgl.akhir) {
                e.preventDefault();
                alert('Pilih tanggal awal dan tanggal akhir terlebih dahulu.');
            }
        });
    }

    window.buildRekapPenjualanUrl = function(field) {
        var tgl = getTanggalFilterPenjualan();
        var url = baseRekapUrl + field;
        if (tgl.awal && tgl.akhir) {
            url += '?tgl_awal=' + encodeURIComponent(tgl.awal) + '&tgl_akhir=' + encodeURIComponent(tgl.akhir);
        }
        return url;
    };

    function updateRekapModalLinks() {
        var btnCreate = document.getElementById('btn-input-penjualan-baru');
        if (btnCreate && typeof buildUrlInputPenjualanBaru === 'function') {
            btnCreate.href = buildUrlInputPenjualanBaru();
        }

        var tgl = getTanggalFilterPenjualan();
        var info = document.getElementById('rekap-modal-periode-info');
        if (info) {
            if (tgl.awal && tgl.akhir) {
                info.textContent = 'Periode: ' + tgl.awal + ' s/d ' + tgl.akhir;
            } else {
                info.textContent = 'Pilih tanggal awal dan tanggal akhir terlebih dahulu.';
            }
        }
        document.querySelectorAll('.btn-rekap-penjualan').forEach(function(btn) {
            var field = btn.getAttribute('data-field');
            if (!field) {
                return;
            }
            if (tgl.awal && tgl.akhir) {
                btn.href = buildRekapPenjualanUrl(field);
                btn.classList.remove('disabled');
                btn.setAttribute('aria-disabled', 'false');
            } else {
                btn.href = '#';
                btn.classList.add('disabled');
                btn.setAttribute('aria-disabled', 'true');
            }
        });
    }

    document.querySelectorAll('.btn-rekap-penjualan').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            var tgl = getTanggalFilterPenjualan();
            if (!tgl.awal || !tgl.akhir) {
                e.preventDefault();
                alert('Pilih tanggal awal dan tanggal akhir terlebih dahulu.');
                return;
            }
            var field = btn.getAttribute('data-field');
            btn.href = buildRekapPenjualanUrl(field);
        });
    });

    if (window.jQuery) {
        jQuery('#modal-xl-select-unit').on('show.bs.modal', updateRekapModalLinks);
    }

    var submitTimer = null;
    function submitCariPenjualanOtomatis() {
        clearTimeout(submitTimer);
        submitTimer = setTimeout(function() {
            var form = document.getElementById('form-cari-penjualan');
            if (!form) {
                return;
            }
            var tgl = getTanggalFilterPenjualan();
            if (tgl.awal && tgl.akhir) {
                savePenjualanListState(tgl.awal, tgl.akhir, getActiveTabIdFromDom());
                form.submit();
            }
        }, 400);
    }

    function initAutoCariPenjualan() {
        var form = document.getElementById('form-cari-penjualan');
        if (!form) {
            return;
        }
        form.querySelectorAll('input[name="tgl_awal"], input[name="tgl_akhir"]').forEach(function(el) {
            el.addEventListener('change', function() {
                updateRekapModalLinks();
                var tgl = getTanggalFilterPenjualan();
                if (tgl.awal && tgl.akhir) {
                    savePenjualanListState(tgl.awal, tgl.akhir, getActiveTabIdFromDom());
                }
                submitCariPenjualanOtomatis();
            });
        });
        if (window.jQuery) {
            jQuery('#tgl_awal, #tgl_akhir').on('change.datetimepicker hide.datetimepicker', function() {
                updateRekapModalLinks();
                var tgl = getTanggalFilterPenjualan();
                if (tgl.awal && tgl.akhir) {
                    savePenjualanListState(tgl.awal, tgl.akhir, getActiveTabIdFromDom());
                }
                submitCariPenjualanOtomatis();
            });
        }
        var tglInit = getTanggalFilterPenjualan();
        if (tglInit.awal && tglInit.akhir) {
            savePenjualanListState(tglInit.awal, tglInit.akhir, getActiveTabIdFromDom());
        }
        restorePenjualanListStateDariSession();
        updateRekapModalLinks();
    }

    function sesuaikanDataTablePenjualanAktif() {
        if (!window.jQuery || !jQuery.fn.DataTable) {
            return;
        }
        var tableId = getActivePenjualanTableId();
        if (!jQuery.fn.DataTable.isDataTable('#' + tableId)) {
            return;
        }
        var table = jQuery('#' + tableId).DataTable();
        table.columns.adjust();
        if (table.fixedColumns && typeof table.fixedColumns === 'function') {
            try {
                table.fixedColumns().relayout();
            } catch (ignoreFc) {}
        }
        table.draw(false);
    }

    function initPenjualanProsesBayarTabs() {
        if (!window.jQuery) {
            return;
        }
        jQuery('#penjualan-proses-bayar-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            var href = e.target.getAttribute('href') || '';
            var tabId = href.charAt(0) === '#' ? href.substring(1) : href;
            setPenjualanActiveTabInput(tabId);
            savePenjualanListState(null, null, tabId);
            setTimeout(sesuaikanDataTablePenjualanAktif, 80);
        });
    }

    var formCariPenjualan = document.getElementById('form-cari-penjualan');
    if (formCariPenjualan) {
        formCariPenjualan.addEventListener('submit', function() {
            var tgl = getTanggalFilterPenjualan();
            if (tgl.awal && tgl.akhir) {
                savePenjualanListState(tgl.awal, tgl.akhir, getActiveTabIdFromDom());
            }
        });
    }

    if (document.readyState === 'complete') {
        initAutoCariPenjualan();
        initLinkInputPenjualanBaru();
        initPenjualanProsesBayarTabs();
    } else {
        window.addEventListener('load', function() {
            initAutoCariPenjualan();
            initLinkInputPenjualanBaru();
            initPenjualanProsesBayarTabs();
        });
    }
})();
</script>
