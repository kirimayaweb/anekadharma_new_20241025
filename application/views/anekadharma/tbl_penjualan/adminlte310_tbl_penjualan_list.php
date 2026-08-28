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
        if (!isset($Tbl_penjualan_data_belum_persediaan)) {
            $Tbl_penjualan_data_belum_persediaan = array();
        }
        if (!isset($Tbl_penjualan_data_persediaan_manual)) {
            $Tbl_penjualan_data_persediaan_manual = array();
        }
        if (!isset($Tbl_penjualan_data_persediaan_otomatis)) {
            $Tbl_penjualan_data_persediaan_otomatis = array();
        }
        if (!isset($penjualan_count_belum_bayar)) {
            $penjualan_count_belum_bayar = count($Tbl_penjualan_data_belum_bayar);
        }
        if (!isset($penjualan_count_terbayar)) {
            $penjualan_count_terbayar = count($Tbl_penjualan_data_terbayar);
        }
        if (!isset($penjualan_count_belum_persediaan)) {
            $penjualan_count_belum_persediaan = count($Tbl_penjualan_data_belum_persediaan);
        }
        if (!isset($penjualan_count_persediaan_manual)) {
            $penjualan_count_persediaan_manual = count($Tbl_penjualan_data_persediaan_manual);
        }
        if (!isset($penjualan_count_persediaan_otomatis)) {
            $penjualan_count_persediaan_otomatis = count($Tbl_penjualan_data_persediaan_otomatis);
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
            array(
                'tab_id' => 'tab-penjualan-belum-persediaan',
                'link_id' => 'tab-penjualan-belum-persediaan-link',
                'label' => 'Belum ke Persediaan',
                'table_id' => 'tglSPOPFreezeBelumPersediaan',
                'data' => $Tbl_penjualan_data_belum_persediaan,
                'count' => (int) $penjualan_count_belum_persediaan,
                'badge_class' => 'badge-warning',
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
                                    <div class="row mb-1">
                                        <div class="col-12">
                                            <small class="text-muted">Halaman ini menampilkan <strong>penjualan barang</strong> saja (bukan jasa). Data jasa diproses di menu Penjualan Jasa.</small>
                                        </div>
                                    </div>
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
                                <?php //echo anchor(site_url('tbl_penjualan/RekapPenjualanPerBarang'), 'Rekap Penjualan Per Barang', 'class="btn btn-success"'); 
                                ?>


                            </div>

                            <div class="col-md-2">
                                <?php //echo anchor(site_url('tbl_penjualan/RekapPenjualanPerKonsumen'), 'Rekap Penjualan Per Konsumen', 'class="btn btn-success"'); 
                                ?>

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

                            #penjualan-persediaan-subtabs .nav-link {
                                color: #212529;
                                font-weight: 500;
                                background: #f8f9fa;
                                border: 1px solid #adb5bd;
                                border-radius: 0.4rem;
                                margin-right: 0.4rem;
                                margin-bottom: 0.35rem;
                                transition: color 0.2s ease, background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
                            }

                            #penjualan-persediaan-subtabs .nav-link:hover:not(.active) {
                                background: #e9ecef;
                                color: #1a1a1a;
                                border-color: #868e96;
                            }

                            #penjualan-persediaan-subtabs .nav-link.active {
                                color: #fff !important;
                                font-weight: 700;
                                background: #0b3d91 !important;
                                border: 2px solid #ffc107 !important;
                                box-shadow: 0 0 8px rgba(255, 193, 7, 0.55);
                            }

                            table.penjualan-persediaan-dt-table tfoot .pj-persediaan-total-row th {
                                background-color: #fff3cd;
                                font-weight: 700;
                                border-top: 2px solid #ffc107;
                            }

                            table.penjualan-persediaan-dt-table tfoot .pj-persediaan-foot-jumlah,
                            table.penjualan-persediaan-dt-table tfoot .pj-persediaan-foot-harga-total {
                                color: #c82333;
                                font-family: Consolas, Monaco, monospace;
                            }

                            #modal-pj-isi-jumlah-refered {
                                z-index: 1065 !important;
                            }
                            .modal-backdrop.pj-refered-jumlah-backdrop {
                                z-index: 1060 !important;
                            }
                            #modal-pj-isi-jumlah-refered .pj-isi-jumlah-detail-table th {
                                width: 38%;
                                background: #f8f9fa;
                                font-weight: 600;
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
                                    <?php if ($tab_cfg['tab_id'] === 'tab-penjualan-belum-persediaan') : ?>
                                        <div class="alert alert-warning py-2 px-3 small mb-3">
                                            <strong>Verifikasi Persediaan</strong> — pilih sub-tab:
                                            <strong>Belum Terverifikasi</strong> (<code>verified_persediaan</code> kosong),
                                            <strong>Terverifikasi Manual</strong> (<code>refered manual</code>), atau
                                            <strong>Verifikasi Otomatis</strong> (<code>refered</code>).
                                            Badge tab utama = jumlah belum terverifikasi.
                                            <?php
                                            if (!empty($penjualan_verified_sync) && is_array($penjualan_verified_sync) && !empty($penjualan_verified_sync['ok'])) {
                                                echo ' <span class="text-muted">Last sync otomatis: refered='
                                                    . (int) (isset($penjualan_verified_sync['refered']) ? $penjualan_verified_sync['refered'] : 0)
                                                    . ', belum='
                                                    . (int) (isset($penjualan_verified_sync['belum']) ? $penjualan_verified_sync['belum'] : 0)
                                                    . '.</span>';
                                            }
                                            ?>
                                        </div>
                                        <?php include __DIR__ . '/_adminlte310_tbl_penjualan_belum_persediaan_fragment.php'; ?>
                                    <?php else : ?>
                                        <?php include __DIR__ . '/_adminlte310_tbl_penjualan_list_table_fragment.php'; ?>
                                    <?php endif; ?>
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

<!-- Modal Referensi Persediaan (tab Belum ke Persediaan) -->
<div class="modal fade" id="modal-pj-referensi-persediaan" tabindex="-1" role="dialog" aria-labelledby="modalPjReferensiPersediaanLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width:96%;">
        <div class="modal-content">
            <div class="modal-header bg-info text-white py-2">
                <h5 class="modal-title" id="modalPjReferensiPersediaanLabel"><i class="fas fa-link"></i> Referensi Persediaan</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body pb-2">
                <div id="pj-referensi-alert" class="mb-2"></div>
                <div id="pj-referensi-meta" class="mb-3 px-3 py-2 border rounded bg-light" style="font-size:1.15rem; line-height:1.45;"></div>
                <div id="pj-referensi-loading" class="text-center py-4 text-muted d-none"><i class="fas fa-spinner fa-spin"></i> Memuat persediaan bulan terpilih...</div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-striped mb-0" id="tbl-pj-referensi-persediaan" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th>Referensi</th>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>HPP</th>
                                <th>SA</th>
                                <th>Beli</th>
                                <th>Penjualan</th>
                                <th>Total 10</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Isi Jumlah Refered (detail persediaan + qty) -->
<div class="modal fade" id="modal-pj-isi-jumlah-refered" tabindex="-1" role="dialog" aria-labelledby="modalPjIsiJumlahReferedLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-isi-jumlah-barang" role="document">
        <div class="modal-content">
            <form id="form-pj-isi-jumlah-refered" action="javascript:void(0);" method="post">
                <div class="modal-header bg-primary text-white py-2">
                    <h4 class="modal-title" id="modalPjIsiJumlahReferedLabel">Referensi Persediaan — Isi Jumlah</h4>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="pj-isi-jumlah-alert" class="mb-2"></div>
                    <p class="mb-2 text-dark">Detail barang persediaan yang akan dijadikan referensi:</p>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-bordered mb-0 pj-isi-jumlah-detail-table">
                            <tbody>
                                <tr><th>ID Persediaan</th><td id="pj-isi-jumlah-d-id"></td></tr>
                                <tr><th>Nama Barang</th><td id="pj-isi-jumlah-d-nama"></td></tr>
                                <tr><th>Kode / SPOP</th><td id="pj-isi-jumlah-d-kode"></td></tr>
                                <tr><th>Satuan persediaan</th><td id="pj-isi-jumlah-d-satuan"></td></tr>
                                <tr><th>HPP</th><td id="pj-isi-jumlah-d-hpp"></td></tr>
                                <tr><th>SA / Beli / Penjualan</th><td id="pj-isi-jumlah-d-mutasi"></td></tr>
                                <tr><th>Total 10 (stok)</th><td id="pj-isi-jumlah-d-total10"></td></tr>
                                <tr><th>UUID</th><td id="pj-isi-jumlah-d-uuid"><small></small></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group d-none">
                        <label>Barang (persediaan terpilih)</label>
                        <input type="text" class="form-control" id="pj-isi-jumlah-nama" value="" disabled>
                    </div>
                    <div class="form-group d-none">
                        <label>Satuan</label>
                        <input type="text" class="form-control" id="pj-isi-jumlah-satuan" value="" disabled>
                    </div>
                    <div class="alert alert-light border py-2 px-3 mb-3" id="pj-isi-jumlah-meta-qty"></div>
                    <div class="form-group mb-0">
                        <label class="penjualan-label-info-jumlah d-block" for="pj-isi-jumlah-input" id="pj-isi-jumlah-label">
                            Jumlah (default = qty penjualan)
                        </label>
                        <input type="number" class="form-control form-control-lg" id="pj-isi-jumlah-input" name="jumlah" min="1" step="1" placeholder="Isi jumlah barang" required>
                        <small class="form-text text-muted">Jika tidak diubah, jumlah = qty penjualan lalu klik SIMPAN.</small>
                    </div>
                    <p class="small text-info mt-2 mb-0">SIMPAN meng-update tabel <strong>persediaan</strong> (<code>penjualan += jumlah</code>, <code>total_10 -= jumlah</code>, kolom unit sesuai konsumen/unit) dan menandai penjualan <code>verified_persediaan = refered manual</code> (pindah ke tab Terverifikasi Manual).</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden" id="pj-isi-jumlah-id-persediaan" value="">
                    <input type="hidden" id="pj-isi-jumlah-id-penjualan" value="">
                    <input type="hidden" id="pj-isi-jumlah-bulan" value="">
                    <input type="hidden" id="pj-isi-jumlah-force" value="0">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-pj-isi-jumlah-simpan">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
</div>



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
        var nodes = table.rows({
            search: 'applied',
            order: 'applied',
            page: 'all'
        }).nodes();
        for (var i = 0; i < nodes.length; i++) {
            var tr = nodes[i];
            var id = ambilIdDariBarisPenjualan(tr);
            if (id > 0) {
                ids.push(id);
            }
        }

        if (!ids.length) {
            table.rows({
                search: 'applied',
                order: 'applied',
                page: 'all'
            }).every(function() {
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
        var url = <?php echo json_encode(site_url('Tbl_penjualan/excel')); ?> +
            '?source=' + encodeURIComponent(source) +
            '&from_datatable=1' +
            '&ids=' + encodeURIComponent(ids.join(',')) +
            '&tgl_awal=' + encodeURIComponent(tglAwal) +
            '&tgl_akhir=' + encodeURIComponent(tglAkhir);
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

            var tanggalBerbeda = !tanggalInputSama(inpAwal.value, stored.tgl_awal) ||
                !tanggalInputSama(inpAkhir.value, stored.tgl_akhir);

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
                return baseCreate +
                    '?tgl_awal=' + encodeURIComponent(tgl.awal) +
                    '&tgl_akhir=' + encodeURIComponent(tgl.akhir);
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
            initBelumPersediaanReferensi();
        } else {
            window.addEventListener('load', function() {
                initAutoCariPenjualan();
                initLinkInputPenjualanBaru();
                initPenjualanProsesBayarTabs();
                initBelumPersediaanReferensi();
            });
        }

        function escapeHtmlPj(s) {
            return String(s == null ? '' : s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function initBelumPersediaanReferensi() {
            if (!window.jQuery || !jQuery.fn.DataTable) {
                return;
            }

            var urlList = <?php echo json_encode(isset($url_penjualan_referensi_list) ? $url_penjualan_referensi_list : site_url('Tbl_penjualan/ajax_referensi_persediaan_list')); ?>;
            var urlApply = <?php echo json_encode(isset($url_penjualan_referensi_apply) ? $url_penjualan_referensi_apply : site_url('Tbl_penjualan/ajax_referensi_persediaan_apply')); ?>;
            var bulanDefault = <?php echo json_encode(isset($penjualan_bulan_referensi) ? $penjualan_bulan_referensi : ''); ?>;

            var tableSel = '#tglSPOPFreezeBelumPersediaan';
            jQuery('#modal-pj-referensi-persediaan, #modal-pj-isi-jumlah-refered').appendTo('body');

            function normSatuanPj(s) {
                return String(s == null ? '' : s).toLowerCase().replace(/\s+/g, ' ').trim();
            }
            function satuanCocokPj(a, b) {
                a = normSatuanPj(a);
                b = normSatuanPj(b);
                if (a === '' || b === '') {
                    return true;
                }
                if (a === b) {
                    return true;
                }
                var n = Math.min(a.length, b.length);
                if (n >= 3 && a.slice(0, n) === b.slice(0, n)) {
                    return true;
                }
                if (a.indexOf(b) === 0 || b.indexOf(a) === 0) {
                    return true;
                }
                return false;
            }
            function parseAngkaPjRef(v) {
                var s = String(v == null ? '' : v).trim();
                if (!s || s === '-') {
                    return 0;
                }
                if (s.indexOf(',') >= 0) {
                    s = s.replace(/\./g, '').replace(',', '.');
                } else if (/^\d{1,3}(\.\d{3})+$/.test(s)) {
                    s = s.replace(/\./g, '');
                }
                s = s.replace(/[^0-9.\-]/g, '');
                var n = parseFloat(s);
                return isNaN(n) ? 0 : n;
            }
            function stokPersediaanPjRef(row) {
                if (!row) {
                    return 0;
                }
                if (row.total_10_stok != null && row.total_10_stok !== '') {
                    var nStok = parseFloat(row.total_10_stok);
                    if (!isNaN(nStok)) {
                        return nStok;
                    }
                }
                return parseAngkaPjRef(row.total_10);
            }
            function notifyPjReferensi(msg, icon) {
                icon = icon || 'warning';
                var cls = (icon === 'error') ? 'danger' : 'warning';
                jQuery('#pj-referensi-alert').html('<div class="alert alert-' + cls + ' py-2 mb-0">' + escapeHtmlPj(msg) + '</div>');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: icon, title: (icon === 'error' ? 'Gagal' : 'Perhatian'), text: msg });
                    setTimeout(function() {
                        jQuery('.swal2-container').css('z-index', 20000);
                    }, 30);
                } else {
                    window.alert(msg);
                }
            }
            function showPjIsiJumlahModal() {
                var $m = jQuery('#modal-pj-isi-jumlah-refered');
                if ($m.length && $m.parent()[0] !== document.body) {
                    $m.appendTo('body');
                }
                $m.css('z-index', 1065).modal('show');
            }

            function parseAngkaPersediaanDt(val) {
                if (val == null || val === '') {
                    return 0;
                }
                var s = String(val).replace(/<[^>]+>/g, '').trim();
                if (s === '') {
                    return 0;
                }
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
                if (dec === 0 || !parts[1]) {
                    return intPart;
                }
                var frac = (parts[1] || '').slice(0, dec);
                while (frac.length < dec) {
                    frac += '0';
                }
                return intPart + ',' + frac;
            }

            function buildPersediaanFooterCallback(hasReferensi) {
                return function() {
                    var api = this.api();
                    var totalJumlah = 0;
                    var totalHarga = 0;
                    api.rows({ search: 'applied' }).every(function() {
                        var node = this.node();
                        if (!node) {
                            return;
                        }
                        var $row = jQuery(node);
                        var jNum = $row.find('td.pj-persediaan-col-jumlah').attr('data-num');
                        var hNum = $row.find('td.pj-persediaan-col-harga-total').attr('data-num');
                        totalJumlah += parseAngkaPersediaanDt(jNum != null ? jNum : $row.find('td.pj-persediaan-col-jumlah').text());
                        totalHarga += parseAngkaPersediaanDt(hNum != null ? hNum : $row.find('td.pj-persediaan-col-harga-total').text());
                    });
                    var jDec = (Math.abs(totalJumlah - Math.round(totalJumlah)) < 0.0001) ? 0 : 2;
                    jQuery(api.table().footer()).find('.pj-persediaan-foot-jumlah').html(formatAngkaPersediaanDt(totalJumlah, jDec));
                    jQuery(api.table().footer()).find('.pj-persediaan-foot-harga-total').html(formatAngkaPersediaanDt(totalHarga, 2));
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
                var colJumlah = hasReferensi ? 5 : 4;
                var colHargaTotal = hasReferensi ? 7 : 6;
                var dtOpts = {
                    scrollX: true,
                    pageLength: 10,
                    order: [[hasReferensi ? 2 : 1, 'asc']],
                    language: {
                        emptyTable: 'Tidak ada data'
                    },
                    footerCallback: buildPersediaanFooterCallback(hasReferensi),
                    columnDefs: [
                        { targets: [colJumlah, colHargaTotal], className: 'text-right' }
                    ]
                };
                if (hasReferensi) {
                    dtOpts.columnDefs.push({ targets: [1], orderable: false });
                    dtOpts.language.emptyTable = 'Semua penjualan sudah terverifikasi ke persediaan';
                }
                jQuery(sel).DataTable(dtOpts);
            }

            function initVisiblePersediaanSubtabDt() {
                var $activePane = jQuery('#penjualan-persediaan-subtabs-content .tab-pane.active');
                if (!$activePane.length) {
                    initPersediaanDtTable(tableSel);
                    return;
                }
                var $tbl = $activePane.find('table.penjualan-persediaan-dt-table').first();
                if ($tbl.length && $tbl.attr('id')) {
                    initPersediaanDtTable('#' + $tbl.attr('id'));
                }
            }

            initVisiblePersediaanSubtabDt();

            try {
                var savedSub = sessionStorage.getItem('pj_persediaan_subtab');
                if (savedSub) {
                    sessionStorage.removeItem('pj_persediaan_subtab');
                    jQuery('#penjualan-proses-bayar-tabs a[href="#tab-penjualan-belum-persediaan"]').tab('show');
                    setTimeout(function() {
                        jQuery('#penjualan-persediaan-subtabs a[href="#' + savedSub + '"]').tab('show');
                    }, 250);
                }
            } catch (eSub) {}

            jQuery('#penjualan-persediaan-subtabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                var href = jQuery(e.target).attr('href') || '';
                if (href.charAt(0) === '#') {
                    var $tbl = jQuery(href).find('table.penjualan-persediaan-dt-table').first();
                    if ($tbl.length && $tbl.attr('id')) {
                        setTimeout(function() {
                            initPersediaanDtTable('#' + $tbl.attr('id'));
                        }, 50);
                    }
                }
            });

            jQuery('#penjualan-proses-bayar-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                var href = jQuery(e.target).attr('href') || '';
                if (href === '#tab-penjualan-belum-persediaan') {
                    setTimeout(initVisiblePersediaanSubtabDt, 80);
                }
            });

            var refState = { idPenjualan: 0, bulanKey: '', meta: null, dt: null, persediaanMap: {} };

            function resolveBulanReferensi() {
                if (bulanDefault && /^\d{4}-\d{2}$/.test(bulanDefault)) {
                    return bulanDefault;
                }
                var tgl = typeof getTanggalFilterPenjualan === 'function' ? getTanggalFilterPenjualan() : { awal: '' };
                var raw = (tgl && tgl.awal) ? String(tgl.awal) : '';
                var m = raw.match(/(\d{4})[-\/](\d{1,2})/);
                if (m) {
                    return m[1] + '-' + ('0' + m[2]).slice(-2);
                }
                // d-m-Y
                var m2 = raw.match(/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/);
                if (m2) {
                    return m2[3] + '-' + ('0' + m2[2]).slice(-2);
                }
                return '';
            }

            function updateBelumPersediaanBadge(count) {
                var $badge = jQuery('#tab-penjualan-belum-persediaan-link .badge-count');
                if ($badge.length) {
                    $badge.text(String(count));
                }
            }

            function removeBelumPersediaanRow(idPenjualan) {
                if (!jQuery.fn.DataTable.isDataTable(tableSel)) {
                    jQuery(tableSel + ' tr[data-penjualan-id="' + idPenjualan + '"]').remove();
                    updateBelumPersediaanBadge(jQuery(tableSel + ' tbody tr[data-penjualan-id]').length);
                    return;
                }
                var dt = jQuery(tableSel).DataTable();
                var removed = false;
                dt.rows().every(function() {
                    var node = this.node();
                    if (!node) {
                        return;
                    }
                    var id = parseInt(jQuery(node).attr('data-penjualan-id'), 10) || 0;
                    if (id === idPenjualan) {
                        dt.row(node).remove();
                        removed = true;
                    }
                });
                if (removed) {
                    dt.draw(false);
                }
                updateBelumPersediaanBadge(dt.rows().count());
            }

            function openPjReferensiModal(idPenjualan, meta) {
                var bulanKey = resolveBulanReferensi();
                if (!bulanKey) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Bulan tidak dikenali', text: 'Set tanggal filter penjualan dulu.' });
                    } else {
                        alert('Bulan tidak dikenali. Set tanggal filter penjualan dulu.');
                    }
                    return;
                }
                refState.idPenjualan = idPenjualan;
                refState.bulanKey = bulanKey;
                refState.meta = meta || {};
                jQuery('#pj-referensi-alert').empty();
                jQuery('#pj-referensi-meta').html(
                    '<div class="text-dark">'
                    + 'Penjualan ID <strong>' + idPenjualan + '</strong> — '
                    + '<strong style="font-size:1.35rem;">' + escapeHtmlPj(refState.meta.nama || '') + '</strong>'
                    + ' / ' + escapeHtmlPj(refState.meta.satuan || '')
                    + ' qty <strong>' + escapeHtmlPj(String(refState.meta.jumlah || '')) + '</strong>'
                    + ' &nbsp;|&nbsp; Persediaan bulan <strong>' + escapeHtmlPj(bulanKey) + '</strong>'
                    + '</div>'
                    + '<div class="small mt-1 text-muted">'
                    + 'Klik <strong>Refered</strong> untuk membuka modal isi jumlah. '
                    + 'Warna kolom <strong>Satuan</strong> &amp; <strong>Total 10</strong>: '
                    + '<span style="color:#006400;font-weight:600;">hijau tua</span> = cocok/cukup, '
                    + '<span style="color:#d4a017;font-weight:600;">kuning</span> = perlu dicek (modal tetap dibuka).'
                    + '</div>'
                );
                jQuery('#pj-referensi-loading').removeClass('d-none');
                if (refState.dt && jQuery.fn.DataTable.isDataTable('#tbl-pj-referensi-persediaan')) {
                    try { jQuery('#tbl-pj-referensi-persediaan').DataTable().destroy(); } catch (eD) {}
                    refState.dt = null;
                }
                jQuery('#tbl-pj-referensi-persediaan tbody').empty();
                jQuery('#modal-pj-referensi-persediaan').modal('show');

                var qtyRef = parseAngkaPjRef(refState.meta.jumlah);
                var satRef = normSatuanPj(refState.meta.satuan);
                refState.persediaanMap = {};

                jQuery.ajax({
                    url: urlList,
                    type: 'POST',
                    dataType: 'json',
                    data: { bulan: bulanKey, id_penjualan: idPenjualan }
                }).done(function(res) {
                    jQuery('#pj-referensi-loading').addClass('d-none');
                    if (!res || !res.ok) {
                        jQuery('#pj-referensi-alert').html('<div class="alert alert-danger py-2 mb-0">' + escapeHtmlPj((res && res.message) ? res.message : 'Gagal memuat persediaan.') + '</div>');
                        return;
                    }
                    var rows = (res.rows || []).map(function(r) {
                        var idp = parseInt(r.id, 10) || 0;
                        if (idp > 0) {
                            refState.persediaanMap[idp] = r;
                        }
                        var total10 = stokPersediaanPjRef(r);
                        var satuanOk = satuanCocokPj(satRef, r.satuan);
                        var stokOk = (total10 >= qtyRef && qtyRef > 0);
                        var boleh = satuanOk && stokOk;
                        var colorYellow = '#d4a017';
                        var colorGreen = '#006400';
                        var colorSatuan = satuanOk ? colorGreen : colorYellow;
                        var colorTotal = stokOk ? colorGreen : colorYellow;
                        var btnClass = boleh ? 'btn-success' : 'btn-warning';
                        var btnTitle = boleh
                            ? 'Satuan cocok dan total_10 cukup — klik untuk isi jumlah'
                            : 'Klik untuk lihat detail dan isi jumlah';
                        return [
                            '<button type="button" class="btn btn-xs ' + btnClass + ' btn-pj-refered"'
                                + ' data-id-persediaan="' + idp + '"'
                                + ' title="' + escapeHtmlPj(btnTitle) + '"'
                                + ' style="color:#fff;">Refered</button>',
                            idp,
                            '<span style="color:#212529;font-weight:600;">' + escapeHtmlPj(r.namabarang || '') + '</span>',
                            '<span style="color:' + colorSatuan + ';font-weight:700;">' + escapeHtmlPj(r.satuan || '') + '</span>',
                            escapeHtmlPj(r.hpp || ''),
                            escapeHtmlPj(r.sa || ''),
                            escapeHtmlPj(r.beli || ''),
                            escapeHtmlPj(r.penjualan || ''),
                            '<span style="color:' + colorTotal + ';font-weight:700;">' + escapeHtmlPj(r.total_10 || '') + '</span>'
                        ];
                    });
                    refState.dt = jQuery('#tbl-pj-referensi-persediaan').DataTable({
                        data: rows,
                        pageLength: 10,
                        scrollX: true,
                        order: [[2, 'asc']],
                        columnDefs: [{ targets: [0], orderable: false }],
                        language: {
                            search: 'Cari:',
                            lengthMenu: 'Tampil _MENU_',
                            info: 'Menampilkan _START_–_END_ dari _TOTAL_',
                            paginate: { previous: 'Prev', next: 'Next' },
                            emptyTable: 'Tidak ada data persediaan di bulan ini'
                        }
                    });
                }).fail(function() {
                    jQuery('#pj-referensi-loading').addClass('d-none');
                    jQuery('#pj-referensi-alert').html('<div class="alert alert-danger py-2 mb-0">Gagal menghubungi server.</div>');
                });
            }

            jQuery(document).on('click', '.btn-pj-referensi-persediaan', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $btn = jQuery(this);
                var idPj = parseInt($btn.attr('data-id-penjualan'), 10) || 0;
                if (idPj < 1) {
                    notifyPjReferensi('ID penjualan tidak valid.', 'error');
                    return;
                }
                openPjReferensiModal(idPj, {
                    nama: $btn.attr('data-nama-barang') || '',
                    satuan: $btn.attr('data-satuan') || '',
                    jumlah: $btn.attr('data-jumlah') || '',
                    konsumen: $btn.attr('data-konsumen') || '',
                    unit: $btn.attr('data-unit') || ''
                });
            });

            jQuery(document).on('click', '.btn-pj-refered', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $btnClick = jQuery(this);
                var idPers = parseInt($btnClick.attr('data-id-persediaan'), 10) || 0;
                var idPj = refState.idPenjualan || 0;
                var bulanKey = refState.bulanKey || resolveBulanReferensi();
                if (idPers < 1 || idPj < 1 || !bulanKey) {
                    notifyPjReferensi('Data referensi tidak lengkap (ID persediaan / penjualan / bulan). Buka ulang tombol Referensi.', 'error');
                    return;
                }

                var rowPers = refState.persediaanMap[idPers] || {};
                var qtyRef = parseAngkaPjRef(refState.meta && refState.meta.jumlah);
                var satRef = (refState.meta && refState.meta.satuan) ? refState.meta.satuan : '';
                var satPers = rowPers.satuan || $btnClick.attr('data-satuan') || '';
                var total10 = stokPersediaanPjRef(rowPers);
                if (!total10 && $btnClick.attr('data-total10')) {
                    total10 = parseAngkaPjRef($btnClick.attr('data-total10'));
                }
                var satuanOk = satuanCocokPj(satRef, satPers);
                var namaPers = rowPers.namabarang || $btnClick.attr('data-nama-barang') || '';
                var defaultJumlah = qtyRef > 0 ? qtyRef : 1;
                if (defaultJumlah < 1) {
                    defaultJumlah = 1;
                }
                var maxJumlah = qtyRef > 0 ? qtyRef : (total10 > 0 ? total10 : defaultJumlah);

                var warnings = [];
                if (!satuanOk) {
                    warnings.push('Satuan penjualan ("' + satRef + '") berbeda dari persediaan ("' + satPers + '").');
                }
                if (total10 <= 0) {
                    warnings.push('total_10 persediaan kosong / 0 — simpan akan ditolak sampai stok cukup.');
                } else if (qtyRef > 0 && total10 < qtyRef) {
                    warnings.push('total_10 (' + total10 + ') lebih kecil dari qty jual (' + qtyRef + '). Kurangi jumlah sebelum simpan.');
                }

                jQuery('#pj-isi-jumlah-alert').html(
                    warnings.length
                        ? '<div class="alert alert-warning py-2 mb-0">' + escapeHtmlPj(warnings.join(' ')) + '</div>'
                        : ''
                );
                jQuery('#pj-isi-jumlah-nama').val(namaPers);
                jQuery('#pj-isi-jumlah-satuan').val(satPers);
                jQuery('#pj-isi-jumlah-d-id').text(String(idPers));
                jQuery('#pj-isi-jumlah-d-nama').html('<strong>' + escapeHtmlPj(namaPers) + '</strong>');
                jQuery('#pj-isi-jumlah-d-kode').text((rowPers.kode_barang || '-') + ' / ' + (rowPers.spop || '-'));
                jQuery('#pj-isi-jumlah-d-satuan').text(satPers || '-');
                jQuery('#pj-isi-jumlah-d-hpp').text(rowPers.hpp || '-');
                jQuery('#pj-isi-jumlah-d-mutasi').text(
                    'SA ' + (rowPers.sa || '0')
                    + ' | Beli ' + (rowPers.beli || '0')
                    + ' | Penjualan ' + (rowPers.penjualan || '0')
                    + (rowPers.pecah_satuan ? (' | Pecah ' + rowPers.pecah_satuan) : '')
                    + (rowPers.bahan_produksi ? (' | Produksi ' + rowPers.bahan_produksi) : '')
                );
                jQuery('#pj-isi-jumlah-d-total10').html('<strong>' + escapeHtmlPj(String(rowPers.total_10 != null ? rowPers.total_10 : total10)) + '</strong>');
                jQuery('#pj-isi-jumlah-d-uuid').html('<small>' + escapeHtmlPj(rowPers.uuid_persediaan || '-') + '</small>');
                jQuery('#pj-isi-jumlah-label').text('Jumlah (qty jual = ' + qtyRef + ' , total_10 = ' + total10 + ')');
                jQuery('#pj-isi-jumlah-input').attr('max', maxJumlah).attr('min', 1).val(defaultJumlah);
                jQuery('#pj-isi-jumlah-meta-qty').html(
                    '<div><strong>Penjualan</strong> ID ' + idPj
                    + ' — <strong>' + escapeHtmlPj((refState.meta && refState.meta.nama) ? refState.meta.nama : '') + '</strong>'
                    + ' / ' + escapeHtmlPj(satRef)
                    + ' — qty jual <strong style="font-size:1.15rem;">' + qtyRef + '</strong></div>'
                    + '<div class="mt-1">Konsumen: <strong>' + escapeHtmlPj((refState.meta && refState.meta.konsumen) ? refState.meta.konsumen : '-') + '</strong>'
                    + ' &nbsp;|&nbsp; Unit: <strong>' + escapeHtmlPj((refState.meta && refState.meta.unit) ? refState.meta.unit : '-') + '</strong>'
                    + ' &nbsp;|&nbsp; Bulan: <strong>' + escapeHtmlPj(bulanKey) + '</strong></div>'
                    + '<div class="mt-1 text-muted">Default jumlah = qty penjualan. Ubah jika perlu, lalu SIMPAN.</div>'
                );
                jQuery('#pj-isi-jumlah-id-persediaan').val(String(idPers));
                jQuery('#pj-isi-jumlah-id-penjualan').val(String(idPj));
                jQuery('#pj-isi-jumlah-bulan').val(bulanKey);
                jQuery('#pj-isi-jumlah-force').val('0');
                jQuery('#btn-pj-isi-jumlah-simpan').prop('disabled', false);
                showPjIsiJumlahModal();
            });

            function submitPjIsiJumlahRefered(forceFlag) {
                var idPers = parseInt(jQuery('#pj-isi-jumlah-id-persediaan').val(), 10) || 0;
                var idPj = parseInt(jQuery('#pj-isi-jumlah-id-penjualan').val(), 10) || 0;
                var bulanKey = jQuery('#pj-isi-jumlah-bulan').val() || '';
                var jumlah = parseFloat(jQuery('#pj-isi-jumlah-input').val()) || 0;
                var maxJumlah = parseFloat(jQuery('#pj-isi-jumlah-input').attr('max')) || 0;
                if (idPers < 1 || idPj < 1 || !bulanKey) {
                    return;
                }
                if (jumlah < 1 || (maxJumlah > 0 && jumlah > maxJumlah)) {
                    jQuery('#pj-isi-jumlah-alert').html('<div class="alert alert-warning py-2 mb-0">Jumlah harus antara 1 dan ' + maxJumlah + '.</div>');
                    return;
                }

                var $btnSimpan = jQuery('#btn-pj-isi-jumlah-simpan').prop('disabled', true);
                jQuery.ajax({
                    url: urlApply,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        bulan: bulanKey,
                        id_penjualan: idPj,
                        id_persediaan: idPers,
                        jumlah: jumlah,
                        force: forceFlag ? '1' : '0'
                    }
                }).done(function(res) {
                    if (res && res.need_confirm_uuid) {
                        $btnSimpan.prop('disabled', false);
                        var msg = (res && res.message) ? res.message : 'Ada uuid sinkron di pembelian &amp; persediaan.';
                        var sid = parseInt(res.suggested_id_persediaan, 10) || 0;
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'UUID sinkron ditemukan',
                                html: escapeHtmlPj(msg)
                                    + (sid > 0 ? '<br/><br/>Sarankan persediaan ID: <strong>' + sid + '</strong>' : '')
                                    + '<br/><small>Batal lalu pilih record yang disarankan, atau lanjut paksa ke record ini.</small>',
                                showCancelButton: true,
                                confirmButtonText: 'Lanjut paksa SIMPAN',
                                cancelButtonText: 'Batal / pilih ulang'
                            }).then(function(result) {
                                if (result.isConfirmed) {
                                    jQuery('#pj-isi-jumlah-force').val('1');
                                    submitPjIsiJumlahRefered(true);
                                }
                            });
                        } else {
                            if (window.confirm(msg + '\n\nOK = lanjut paksa, Cancel = batal')) {
                                submitPjIsiJumlahRefered(true);
                            }
                        }
                        jQuery('#pj-isi-jumlah-alert').html('<div class="alert alert-warning py-2 mb-0">' + escapeHtmlPj(msg) + '</div>');
                        return;
                    }
                    if (!res || !res.ok) {
                        jQuery('#pj-isi-jumlah-alert').html('<div class="alert alert-danger py-2 mb-0">' + escapeHtmlPj((res && res.message) ? res.message : 'Gagal simpan.') + '</div>');
                        $btnSimpan.prop('disabled', false);
                        return;
                    }
                    jQuery('#modal-pj-isi-jumlah-refered').modal('hide');
                    jQuery('#modal-pj-referensi-persediaan').modal('hide');
                    $btnSimpan.prop('disabled', false);
                    var doneMsg = res.message || ('Persediaan id=' + idPers + ' di-update sejumlah ' + jumlah);
                    if (typeof Swal !== 'undefined') {
                        try { sessionStorage.setItem('pj_persediaan_subtab', 'subtab-persediaan-manual'); } catch (eSs) {}
                        Swal.fire({
                            icon: 'success',
                            title: 'Refered berhasil',
                            text: doneMsg
                        }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        try { sessionStorage.setItem('pj_persediaan_subtab', 'subtab-persediaan-manual'); } catch (eSs2) {}
                        alert(doneMsg);
                        window.location.reload();
                    }
                }).fail(function() {
                    jQuery('#pj-isi-jumlah-alert').html('<div class="alert alert-danger py-2 mb-0">Gagal menghubungi server.</div>');
                    $btnSimpan.prop('disabled', false);
                });
            }

            jQuery('#form-pj-isi-jumlah-refered').on('submit', function(e) {
                e.preventDefault();
                submitPjIsiJumlahRefered(jQuery('#pj-isi-jumlah-force').val() === '1');
            });

            jQuery('#modal-pj-referensi-persediaan').on('shown.bs.modal', function() {
                if (refState.dt) {
                    try { refState.dt.columns.adjust(); } catch (eAdj) {}
                }
            });

            jQuery('#modal-pj-isi-jumlah-refered').on('shown.bs.modal', function() {
                jQuery('.modal-backdrop').last().addClass('pj-refered-jumlah-backdrop').css('z-index', 1060);
                jQuery('#pj-isi-jumlah-input').trigger('focus').select();
            });

            jQuery(document).on('click', '.btn-cetak-excel-persediaan-section', function(e) {
                e.preventDefault();
                var tableSelExport = jQuery(this).data('table') || tableSel;
                var filename = jQuery(this).data('filename') || 'Penjualan_Persediaan';
                var $table = jQuery(tableSelExport);
                if (!$table.length) {
                    return;
                }
                var headers = [];
                $table.find('thead tr:first th').each(function() {
                    headers.push(jQuery(this).text().trim());
                });
                var rows = [];
                if (jQuery.fn.DataTable.isDataTable(tableSelExport)) {
                    jQuery(tableSelExport).DataTable().rows({ search: 'applied' }).every(function() {
                        var d = this.data();
                        var line = [];
                        for (var i = 0; i < headers.length; i++) {
                            var cell = d[i];
                            line.push(cell == null ? '' : String(cell).replace(/<[^>]+>/g, '').trim());
                        }
                        rows.push(line);
                    });
                }
                function escXml(s) {
                    return String(s == null ? '' : s)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;');
                }
                var xml = '<' + '?xml version="1.0"?>' + '<' + '?mso-application progid="Excel.Sheet"?>'
                    + '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"'
                    + ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">'
                    + '<Worksheet ss:Name="Data"><Table>';
                xml += '<Row>';
                headers.forEach(function(h) {
                    xml += '<Cell><Data ss:Type="String">' + escXml(h) + '</Data></Cell>';
                });
                xml += '</Row>';
                rows.forEach(function(line) {
                    xml += '<Row>';
                    line.forEach(function(c) {
                        xml += '<Cell><Data ss:Type="String">' + escXml(c) + '</Data></Cell>';
                    });
                    xml += '</Row>';
                });
                var $footCells = $table.find('tfoot tr:first th');
                if ($footCells.length) {
                    xml += '<Row>';
                    $footCells.each(function() {
                        xml += '<Cell><Data ss:Type="String">' + escXml(jQuery(this).text().trim()) + '</Data></Cell>';
                    });
                    xml += '</Row>';
                }
                xml += '</Table></Worksheet></Workbook>';
                var blob = new Blob([xml], { type: 'application/vnd.ms-excel' });
                var link = document.createElement('a');
                var objectUrl = URL.createObjectURL(blob);
                link.href = objectUrl;
                link.download = filename + '_' + (new Date().toISOString().slice(0, 19).replace(/[:T]/g, '-')) + '.xls';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(objectUrl);
            });
        }
    })();
</script>