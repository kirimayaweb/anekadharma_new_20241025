<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-theme@0.1.0-beta.10/dist/select2-bootstrap.min.css">
<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }

    #tglSPOPFreeze,
    #tglSPOPFreeze thead th,
    #tglSPOPFreeze tbody td,
    #tglSPOPFreeze tfoot th,
    #tglSPOPFreeze tfoot td {
        border: 1px solid #f5e6a8 !important;
    }

    #tglSPOPFreeze thead th {
        background-color: #d8f3dc !important;
        color: #1b4332;
        font-weight: 600;
    }

    #tglSPOPFreeze.dataTable thead th,
    #tglSPOPFreeze.dataTable thead td {
        border-bottom: 1px solid #f5e6a8 !important;
    }

    #tglSPOPFreeze.dataTable.no-footer {
        border-bottom: 1px solid #f5e6a8;
    }

    table.dataTable#tglSPOPFreeze {
        border-collapse: collapse !important;
    }

    .btn-jurnal-kode-akun {
        min-width: 88px;
        font-weight: 600;
        border-radius: 20px;
        padding: 4px 12px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        transition: all .2s ease;
        display: inline-block;
    }

    #tglSPOPFreeze td.col-kode-akun-btn,
    #tglSPOPFreeze th.col-kode-akun-header {
        text-align: right !important;
    }

    #tglSPOPFreeze tbody tr.row-jurnal-total td {
        text-align: right !important;
    }

    #tglSPOPFreeze tbody td.col-jurnal-debet,
    #tglSPOPFreeze tbody td.col-jurnal-kredit {
        text-align: right !important;
        padding: 8px 12px 8px 8px !important;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
        vertical-align: middle;
    }

    #tglSPOPFreeze .jurnal-nominal {
        text-align: right;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }

    #tglSPOPFreeze .jurnal-nominal-total {
        color: #0d47a1;
        font-weight: 700;
    }

    #tglSPOPFreeze tfoot td {
        background-color: #f1f8e9;
        font-weight: 700;
        border: 1px solid #f5e6a8 !important;
        box-sizing: border-box;
        vertical-align: middle;
    }

    #tglSPOPFreeze tfoot td.col-jurnal-debet,
    #tglSPOPFreeze tfoot td.col-jurnal-kredit {
        text-align: right !important;
        padding: 8px 12px 8px 8px !important;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }

    #tglSPOPFreeze tbody td {
        box-sizing: border-box;
    }

    table.dataTable#tglSPOPFreeze,
    .dataTables_scrollHeadInner table.dataTable#tglSPOPFreeze,
    .dataTables_scrollBody table.dataTable#tglSPOPFreeze,
    .dataTables_scrollFootInner table.dataTable#tglSPOPFreeze {
        table-layout: fixed;
        width: 100% !important;
        margin: 0 !important;
    }

    .jurnal-dt-wrapper {
        overflow-x: auto;
    }

    .dataTables_scrollFoot {
        border-top: 1px solid #f5e6a8 !important;
        overflow: hidden !important;
    }

    .dataTables_scrollBody {
        border-bottom: none !important;
    }

    .btn-jurnal-kode-akun:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
    }

    .btn-jurnal-kode-akun.btn-kode-kosong {
        background: #e57373;
        border: 1px solid #ef5350;
        color: #fff;
    }

    .btn-jurnal-kode-akun.btn-kode-kosong:hover {
        background: #ef5350;
        border-color: #e53935;
        color: #fff;
    }

    .btn-jurnal-kode-akun.btn-kode-ada,
    .btn-jurnal-kode-akun.btn-kode-inherited {
        background: #c8e6c9;
        border: 1px solid #a5d6a7;
        color: #1b5e20;
    }

    .btn-jurnal-kode-akun.btn-kode-ada:hover,
    .btn-jurnal-kode-akun.btn-kode-inherited:hover {
        background: #a5d6a7;
        border-color: #81c784;
        color: #1b4332;
    }

    #modalJurnalPembelianKodeAkun .modal-dialog {
        max-width: min(1280px, 94vw);
        margin: 1.25rem auto;
    }

    #modalJurnalPembelianKodeAkun .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.18);
    }

    #modalJurnalPembelianKodeAkun .modal-header {
        background: linear-gradient(135deg, #1b5e20, #43a047);
        color: #fff;
        border: none;
    }

    #modalJurnalPembelianKodeAkun .modal-body {
        background: #f8faf8;
    }

    #modalJurnalPembelianKodeAkun .info-card {
        background: #fff;
        border: 1px solid #e8f5e9;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 16px;
    }

    #modalJurnalPembelianKodeAkun .info-card .label-muted {
        color: #6c757d;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    #modalJurnalPembelianKodeAkun .info-card .value-text {
        font-size: 16px;
        font-weight: 700;
        color: #1b4332;
    }

    #modalJurnalPembelianKodeAkun .detail-section {
        background: #fff;
        border: 1px solid #e8f5e9;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 16px;
        max-height: 380px;
        overflow: auto;
    }

    #modalJurnalPembelianKodeAkun .detail-section .section-title {
        font-size: 15px;
        font-weight: 700;
        color: #1b5e20;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    #modalJurnalPembelianKodeAkun .detail-table {
        width: 100%;
        font-size: 15px;
        line-height: 1.45;
        margin-bottom: 0;
    }

    #modalJurnalPembelianKodeAkun .detail-table thead th {
        background: #e8f5e9;
        color: #1b4332;
        font-weight: 700;
        font-size: 14px;
        white-space: nowrap;
        vertical-align: middle;
        border-color: #c8e6c9;
        padding: 10px 12px;
    }

    #modalJurnalPembelianKodeAkun .detail-table tbody td {
        vertical-align: middle;
        border-color: #edf7ee;
        padding: 10px 12px;
        font-size: 15px;
    }

    #modalJurnalPembelianKodeAkun .detail-table tfoot td {
        background: #f1f8e9;
        font-weight: 700;
        font-size: 15px;
        border-color: #c8e6c9;
        padding: 10px 12px;
    }

    #modalJurnalPembelianKodeAkun .status-lunas {
        color: #1b5e20;
        font-weight: 700;
    }

    #modalJurnalPembelianKodeAkun .status-hutang {
        color: #c62828;
        font-weight: 700;
    }

    #modalJurnalPembelianKodeAkun .detail-loading,
    #modalJurnalPembelianKodeAkun .detail-empty {
        color: #6c757d;
        font-size: 15px;
        text-align: center;
        padding: 22px 8px;
    }

    #modalJurnalPembelianKodeAkun .modal-kode-akun-hint {
        font-size: 14px;
        line-height: 1.6;
        color: #5f6368;
        margin-top: 8px;
    }

    #modalJurnalPembelianKodeAkun .modal-kode-akun-hint .hint-highlight {
        font-size: 17px;
        font-weight: 700;
        color: #1b5e20;
    }

    .nav-tabs.jurnal-pembelian-tabs {
        border-bottom: 2px solid #ffc107;
        margin-bottom: 15px;
    }

    .nav-tabs.jurnal-pembelian-tabs .nav-link {
        background-color: #ffffff;
        border: 2px solid #ffc107;
        border-bottom: none;
        color: #888888;
        margin-right: 4px;
        border-radius: 4px 4px 0 0;
        opacity: 0.75;
    }

    .nav-tabs.jurnal-pembelian-tabs .nav-link.active {
        background-color: #007bff;
        border-color: #ffc107;
        color: #000000;
        font-weight: bold;
        opacity: 1;
    }

    .jurnal-spop-stats-box {
        background: #f8faf8;
        border: 1px solid #e8f5e9;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 14px;
        font-size: 18px;
        line-height: 1.7;
    }

    .jurnal-spop-stats-box .stats-title {
        font-size: 20px;
        font-weight: 700;
        color: #1b4332;
        display: block;
        margin-bottom: 8px;
    }

    .jurnal-spop-stats-box .stats-item {
        display: inline-block;
        margin-right: 18px;
        margin-bottom: 4px;
    }

    .jurnal-spop-stats-box .stats-label {
        font-size: 17px;
        color: #37474f;
    }

    .jurnal-spop-stats-box .stats-value {
        font-size: 24px;
        font-weight: 700;
        margin-left: 4px;
    }

    .jurnal-spop-stats-box .stats-filter-note {
        display: block;
        font-size: 15px;
        margin-top: 6px;
    }

    .jurnal-spop-search-wrap {
        background: #fff;
        border: 1px solid #e8f5e9;
        border-radius: 8px;
        padding: 8px 12px;
        margin-bottom: 10px;
    }

    .jurnal-spop-search-wrap .jurnal-spop-search-label {
        font-size: 14px;
        font-weight: 700;
        color: #1b4332;
        margin-bottom: 0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        flex: 0 0 auto;
    }

    .jurnal-spop-search-wrap .jurnal-spop-search-input {
        font-size: 14px;
        height: 36px;
        border: 1px solid #c8e6c9;
        border-radius: 6px;
        flex: 0 1 260px;
        width: 260px;
        max-width: 320px;
        min-width: 140px;
    }

    .jurnal-spop-search-wrap .jurnal-spop-search-field {
        font-size: 14px;
        height: 36px;
        border: 1px solid #c8e6c9;
        border-radius: 6px;
        min-width: 120px;
        max-width: 150px;
        flex: 0 0 135px;
        font-weight: 600;
        color: #1b4332;
        background-color: #f1f8e9;
        padding-left: 8px;
        padding-right: 24px;
    }

    .jurnal-spop-search-wrap .jurnal-spop-search-row {
        display: flex;
        flex-wrap: nowrap;
        gap: 8px;
        align-items: center;
    }

    @media (max-width: 767px) {
        .jurnal-spop-search-wrap .jurnal-spop-search-row {
            flex-wrap: wrap;
        }

        .jurnal-spop-search-wrap .jurnal-spop-search-input {
            flex: 1 1 100%;
            width: 100%;
            max-width: 100%;
        }
    }

    #tglSPOPFreeze_wrapper .dataTables_filter {
        display: none !important;
    }

    .compare-dt-wrap {
        width: 100%;
        overflow: auto;
        max-height: 420px;
    }

    .compare-toolbar-row .compare-toolbar-control {
        width: 110px;
        min-width: 110px;
    }

    #compare_tabel_pembelian.compare-toolbar-tabel {
        width: 360px;
        min-width: 270px;
        max-width: 480px;
    }

    .compare-csv-file-wrap {
        max-width: 520px;
        min-width: 280px;
        flex: 0 1 520px;
    }
</style>

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

        $Get_month_from_date = $month_selected;
        $Get_year_Tahun_ini = $year_selected;
        $Get_year_Setahun_lalu = date("Y", strtotime('-1 year'));




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

        function bulan_teks($angka_bulan)
        {
            if ($angka_bulan == 1) {
                $bulan_teks = "Januari";
            } elseif ($angka_bulan == 2) {
                $bulan_teks = "Februari";
            } elseif ($angka_bulan == 3) {
                $bulan_teks = "Maret";
            } elseif ($angka_bulan == 4) {
                $bulan_teks = "April";
            } elseif ($angka_bulan == 5) {
                $bulan_teks = "Mei";
            } elseif ($angka_bulan == 6) {
                $bulan_teks = "Juni";
            } elseif ($angka_bulan == 7) {
                $bulan_teks = "Juli";
            } elseif ($angka_bulan == 8) {
                $bulan_teks = "Agustus";
            } elseif ($angka_bulan == 9) {
                $bulan_teks = "September";
            } elseif ($angka_bulan == 10) {
                $bulan_teks = "Oktober";
            } elseif ($angka_bulan == 11) {
                $bulan_teks = "November";
            } elseif ($angka_bulan == 12) {
                $bulan_teks = "Desember";
            } else {
                $bulan_teks = "";
            }
            return $bulan_teks;
        }

        function jurnal_pembelian2_normalize_pl_nama($nama_pl)
        {
            $nama = trim((string) $nama_pl);
            if ($nama !== '' && preg_match('/^perdagangan\s+umum\s*\(/iu', $nama)) {
                return 'perdagangan umum';
            }
            return $nama;
        }

        $jurnal_spop_stats = isset($jurnal_pembelian2_spop_stats) ? $jurnal_pembelian2_spop_stats : array('sudah' => 0, 'belum' => 0, 'total' => 0);
        $active_tab = isset($active_tab) ? $active_tab : 'jurnal';
        $tab_jurnal_active = ($active_tab !== 'compare');
        $tab_compare_active = ($active_tab === 'compare');
        $url_excel_jurnal_pembelian = isset($url_excel_jurnal_pembelian2) ? $url_excel_jurnal_pembelian2 : site_url('Tbl_pembelian/excel_jurnal_pembelian2');

        ?>


        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4" align="left">
                                        <div class="col-12" text-align="center"> <strong>JURNAL PEMBELIAN</strong> <?php echo bulan_teks($Get_month_from_date) . " " . $Get_year_Tahun_ini ?></div>
                                    </div>

                                    <div class="col-6" align="left">




                                        <?php
                                        $action_cari_between_date = site_url('Tbl_pembelian/jurnal_pembelian2');
                                        ?>

                                        <form id="formFilterBulanJurnalPembelian" action="<?php echo $action_cari_between_date; ?>" method="post">
                                            <div class="row">
                                                <div class="col-md-4" text-align="right" align="right">
                                                    <input type="month" id="bulan_ns" name="bulan_ns" value="<?php echo isset($bulan_ns_selected) ? $bulan_ns_selected : date('Y-m'); ?>">
                                                </div>
                                                <div class="col-md-2" text-align="left" align="left">
                                                    <button type="submit" id="btnCariBulanJurnalPembelian" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in" aria-hidden="true"></i> Cari</button>
                                                </div>

                                            </div>
                                        </form>

                                    </div>
                                    <div class="col-2" align="right">
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>



                    <div class="card-body">

                        <ul class="nav nav-tabs jurnal-pembelian-tabs" id="jurnal-pembelian-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_jurnal_active ? ' active' : ''; ?>" id="tab-jurnal-datatable" data-toggle="pill" href="#panel-jurnal-datatable" role="tab">Jurnal Pembelian</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_compare_active ? ' active' : ''; ?>" id="tab-compare-manual-online" data-toggle="pill" href="#panel-compare-manual-online" role="tab">Compare manual-online</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="jurnal-pembelian-tabs-content">
                            <div class="tab-pane fade<?php echo $tab_jurnal_active ? ' show active' : ''; ?>" id="panel-jurnal-datatable" role="tabpanel">

                                <div class="row mb-2">
                                    <div class="col-md-8">
                                        <div class="jurnal-spop-stats-box" id="jurnalSpopStatsBox">
                                            <span class="stats-title">Informasi SPOP bulan ini</span>
                                            <span class="stats-item">
                                                <span class="stats-label">Total SPOP:</span>
                                                <strong id="jurnalSpopStatsTotal" class="stats-value text-primary"><?php echo (int) $jurnal_spop_stats['total']; ?></strong>
                                            </span>
                                            <span class="stats-item">
                                                <span class="stats-label">Sudah setting kode akun:</span>
                                                <strong id="jurnalSpopStatsSudah" class="stats-value text-success"><?php echo (int) $jurnal_spop_stats['sudah']; ?></strong>
                                            </span>
                                            <span class="stats-item">
                                                <span class="stats-label">Belum setting kode akun:</span>
                                                <strong id="jurnalSpopStatsBelum" class="stats-value text-danger"><?php echo (int) $jurnal_spop_stats['belum']; ?></strong>
                                            </span>
                                            <span id="jurnalSpopStatsFilterNote" class="stats-filter-note text-muted"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <button type="button" class="btn btn-success btn-flat btn-lg" id="btnCetakExcelJurnalPembelian">
                                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Cetak ke Excel
                                        </button>
                                    </div>
                                </div>

                                <div class="jurnal-spop-search-wrap">
                                    <div class="jurnal-spop-search-row">
                                        <label for="jurnalSearchField" class="jurnal-spop-search-label">
                                            <i class="fa fa-search"></i> Cari
                                        </label>
                                        <select id="jurnalSearchField" class="form-control jurnal-spop-search-field">
                                            <option value="semua" selected>Semua</option>
                                            <option value="spop">SPOP</option>
                                            <option value="supplier">SUPPLIER</option>
                                            <option value="unit">Unit</option>
                                            <option value="kode_akun">Kode Akun</option>
                                            <option value="rekening">Rekening</option>
                                        </select>
                                        <input type="text"
                                            id="jurnalSearchText"
                                            class="form-control jurnal-spop-search-input"
                                            placeholder="Ketik kata kunci..."
                                            autocomplete="off">
                                    </div>
                                </div>

                                <div class="jurnal-dt-wrapper">

                        <!-- <table id="tglSPOPFreeze" class="table table-striped dt-responsive w-100 table-bordered display nowrap table-hover mb-0" style="width:100%"> -->
                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <!-- <tr>

                                    <th rowspan="3" style="text-align:left" width="10px">Tanggal</th>
                                    <th rowspan="3" style="text-align:center">Kode Akun</th>
                                    <th rowspan="3" style="text-align:center">No. Bukti BKM</th>
                                    <th rowspan="3" style="text-align:center">PL</th>
                                    <th rowspan="3" style="text-align:center">KETERANGAN</th>

                                    <th colspan="3" style="text-align:center">Debet</th>


                                    <th colspan="1" style="text-align:center">Kredit</th>
                                </tr>
                                <tr>
                                    <th rowspan="2" style="text-align:center">21101-UU Dagang</th>
                                    <th colspan="2" style="text-align:center">Serba-Serbi</th>
                                    <th rowspan="2" style="text-align:right">11101-Kas Besar</th>
                                </tr>


                                <tr>
                                    <th rowspan="2" style="text-align:left">No. Rek</th>
                                    <th rowspan="2" style="text-align:right">Jumlah</th>

                                </tr>
                                 -->
                                <!-- ============================================ -->

                                <tr>

                                    <th rowspan="2" style="text-align:left" width="10px">No</th>
                                    <th rowspan="2" style="text-align:left" width="10px">TANGGAL</th>
                                    <th rowspan="2" style="text-align:center">No. SPOP</th>
                                    <!-- <th rowspan="3" style="text-align:center">VVVVVVV</th> -->
                                    <th rowspan="2" style="text-align:center">PL</th>
                                    <th rowspan="2" style="text-align:center">SUPPLIER</th>

                                    <th colspan="3" style="text-align:center">DEBET</th>
                                    <th colspan="1" style="text-align:center">KREDIT</th>

                                </tr>
                                <tr>
                                    <th style="text-align:right" class="col-kode-akun-header">No. Rek</th>
                                    <th style="text-align:left">Rekening</th>
                                    <th style="text-align:right">Jumlah</th>
                                    <th style="text-align:right">21101-UU</th>
                                </tr>
                                <!-- <tr>
                                    <th style="text-align:left">21101-UU</th>
                                    <th style="text-align:right">Jumlah</th>
                                </tr> -->

                            </thead>
                            <colgroup>
                                <col style="width:4%;">
                                <col style="width:8%;">
                                <col style="width:10%;">
                                <col style="width:5%;">
                                <col style="width:22%;">
                                <col style="width:10%;">
                                <col style="width:18%;">
                                <col class="col-jurnal-debet-width" style="width:11%;">
                                <col class="col-jurnal-kredit-width" style="width:12%;">
                            </colgroup>
                            <tbody>
                                <?php
                                $jurnal_rows = isset($jurnal_pembelian2_rows) ? $jurnal_pembelian2_rows : array();
                                $jurnal_totals = isset($jurnal_pembelian2_totals) ? $jurnal_pembelian2_totals : array('debet' => 0, 'kredit' => 0);

                                foreach ($jurnal_rows as $row) {
                                    $is_total = !empty($row['is_total']);
                                    $tr_style = $is_total ? ' style="background-color:yellow;"' : '';
                                    $row_class = $is_total ? ' class="row-jurnal-total"' : ' class="row-jurnal-data"';
                                    $row_data_attrs = '';
                                    if (!$is_total) {
                                        $row_data_attrs .= ' data-search-spop="' . htmlspecialchars(isset($row['spop']) ? $row['spop'] : '', ENT_QUOTES, 'UTF-8') . '"';
                                        $row_data_attrs .= ' data-search-supplier="' . htmlspecialchars(isset($row['supplier']) ? $row['supplier'] : '', ENT_QUOTES, 'UTF-8') . '"';
                                        $row_data_attrs .= ' data-search-unit="' . htmlspecialchars(isset($row['unit']) ? $row['unit'] : '', ENT_QUOTES, 'UTF-8') . '"';
                                        $row_data_attrs .= ' data-search-kode-akun="' . htmlspecialchars(isset($row['kode_akun']) ? $row['kode_akun'] : '', ENT_QUOTES, 'UTF-8') . '"';
                                        $row_data_attrs .= ' data-search-rekening="' . htmlspecialchars(isset($row['nama_akun']) ? $row['nama_akun'] : '', ENT_QUOTES, 'UTF-8') . '"';
                                    }
                                    ?>
                                    <tr<?php echo $tr_style . $row_class . $row_data_attrs; ?>>
                                        <td<?php echo $is_total ? ' align="right"' : ' align="left"'; ?>><?php echo (int) $row['no']; ?></td>
                                        <td<?php echo $is_total ? ' align="right"' : ' align="left"'; ?>><?php echo htmlspecialchars($row['tanggal'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td<?php echo $is_total ? ' align="right"' : ' align="left"'; ?>><?php echo htmlspecialchars($row['spop'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td<?php echo $is_total ? ' align="right"' : ' align="left"'; ?>><?php echo htmlspecialchars($row['kode_pl'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td<?php echo $is_total ? ' align="right"' : ' align="left"'; ?>><?php echo htmlspecialchars($row['supplier'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td align="right" class="col-kode-akun-btn">
                                            <?php if (!$is_total) { ?>
                                                <?php
                                                $btn_class = 'btn-kode-kosong';
                                                $btn_label = 'Pilih Akun';
                                                if (!empty($row['kode_akun'])) {
                                                    $btn_label = $row['kode_akun'];
                                                    $btn_class = !empty($row['kode_akun_inherited']) ? 'btn-kode-inherited' : 'btn-kode-ada';
                                                }
                                                ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-jurnal-kode-akun <?php echo $btn_class; ?>"
                                                    data-spop="<?php echo htmlspecialchars($row['spop'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    data-uuid-supplier="<?php echo htmlspecialchars($row['uuid_supplier'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    data-supplier="<?php echo htmlspecialchars($row['supplier'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    data-kode-akun="<?php echo htmlspecialchars($row['kode_akun'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    data-nama-akun="<?php echo htmlspecialchars($row['nama_akun'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    title="<?php echo !empty($row['kode_akun_inherited']) ? 'Kode akun dari bulan sebelumnya (supplier sama)' : 'Klik untuk ubah kode akun'; ?>">
                                                    <i class="fa fa-book"></i> <?php echo htmlspecialchars($btn_label, ENT_QUOTES, 'UTF-8'); ?>
                                                </button>
                                            <?php } ?>
                                        </td>
                                        <td<?php echo $is_total ? ' align="right"' : ' align="left"'; ?> class="col-nama-akun"><?php echo $is_total ? '' : htmlspecialchars($row['nama_akun'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td align="right" class="col-jurnal-debet">
                                            <?php
                                            $debet_fmt = number_format((float) $row['debet'], 2, ',', '.');
                                            $debet_class = $is_total ? 'jurnal-nominal jurnal-nominal-total' : 'jurnal-nominal';
                                            echo '<span class="' . $debet_class . '">' . $debet_fmt . '</span>';
                                            ?>
                                        </td>
                                        <td align="right" class="col-jurnal-kredit">
                                            <?php
                                            $kredit_fmt = number_format((float) $row['kredit'], 2, ',', '.');
                                            $kredit_class = $is_total ? 'jurnal-nominal jurnal-nominal-total' : 'jurnal-nominal';
                                            echo '<span class="' . $kredit_class . '">' . $kredit_fmt . '</span>';
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="7" align="right"><strong>GRAND TOTAL</strong></td>
                                    <td class="col-jurnal-debet" id="footerTotalDebet">
                                        <span class="jurnal-nominal jurnal-nominal-total">0,00</span>
                                    </td>
                                    <td class="col-jurnal-kredit" id="footerTotalKredit">
                                        <span class="jurnal-nominal jurnal-nominal-total">0,00</span>
                                    </td>
                                </tr>
                            </tfoot>

                        </table>
                                </div>

                            </div><!-- /.tab-pane jurnal -->

                            <div class="tab-pane fade<?php echo $tab_compare_active ? ' show active' : ''; ?>" id="panel-compare-manual-online" role="tabpanel">
                                <?php $this->load->view('anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_compare_manual_online_panel'); ?>
                            </div><!-- /.tab-pane compare -->

                        </div><!-- /.tab-content -->
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalJurnalPembelianKodeAkun" tabindex="-1" role="dialog" aria-labelledby="modalJurnalPembelianKodeAkunLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <h5 class="modal-title" id="modalJurnalPembelianKodeAkunLabel">
                        <i class="fa fa-cogs"></i> Setting Kode Akun Jurnal Pembelian
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="info-card">
                        <div class="row">
                            <div class="col-6">
                                <div class="label-muted">SPOP</div>
                                <div class="value-text" id="modalJurnalSpop">-</div>
                            </div>
                            <div class="col-6">
                                <div class="label-muted">Supplier</div>
                                <div class="value-text" id="modalJurnalSupplier">-</div>
                            </div>
                        </div>
                    </div>
                    <div class="detail-section" id="modalJurnalDetailSection">
                        <div class="section-title"><i class="fa fa-list"></i> Detail Pembelian</div>
                        <div id="modalJurnalDetailContent" class="detail-loading">
                            <i class="fa fa-spinner fa-spin"></i> Memuat detail pembelian...
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label for="modalJurnalKodeAkunSelect" class="font-weight-bold text-success">
                            <i class="fa fa-list-alt"></i> Pilih Kode Akun (sys_kode_akun)
                        </label>
                        <select id="modalJurnalKodeAkunSelect" class="form-control" style="width:100%;">
                            <option value="">-- Pilih Kode Akun --</option>
                            <?php if (!empty($sys_kode_akun_list)) { ?>
                                <?php foreach ($sys_kode_akun_list as $akun) { ?>
                                    <option value="<?php echo htmlspecialchars($akun->kode_akun, ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo htmlspecialchars($akun->kode_akun . ' — ' . $akun->nama_akun, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <small class="form-text modal-kode-akun-hint">
                            Pilih kode akun untuk mengupdate semua record dengan SPOP =
                            <strong id="modalJurnalHintSpop" class="hint-highlight">-</strong>
                            dan supplier:
                            <strong id="modalJurnalHintSupplier" class="hint-highlight">-</strong>
                            yang sama pada bulan ini.
                        </small>
                    </div>
                    <div id="modalJurnalKodeAkunAlert" class="alert d-none mb-0" role="alert"></div>
                </div>
                <div class="modal-footer bg-white border-0 pt-0">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        var urlUpdateKodeAkun = <?php echo json_encode(isset($url_jurnal_pembelian2_update_kode_akun) ? $url_jurnal_pembelian2_update_kode_akun : site_url('Tbl_pembelian/ajax_jurnal_pembelian2_update_kode_akun')); ?>;
        var urlDetailKodeAkun = <?php echo json_encode(isset($url_jurnal_pembelian2_detail_kode_akun) ? $url_jurnal_pembelian2_detail_kode_akun : site_url('Tbl_pembelian/ajax_jurnal_pembelian2_detail_kode_akun')); ?>;
        var urlExcelJurnalPembelian = <?php echo json_encode(isset($url_excel_jurnal_pembelian2) ? $url_excel_jurnal_pembelian2 : site_url('Tbl_pembelian/excel_jurnal_pembelian2')); ?>;
        var bulanNs = <?php echo json_encode(isset($bulan_ns_selected) ? $bulan_ns_selected : date('Y-m')); ?>;
        var dtJurnalSearchStorageKey = 'jurnal_pembelian2_search_' + bulanNs;
        var dtJurnalSortStorageKey = 'jurnal_pembelian2_sort_' + bulanNs;
        var jurnalSearchField = 'semua';
        var jurnalSearchText = '';
        var jurnalSortOrder = [];

        var jurnalSearchFieldLabels = {
            semua: 'Semua',
            spop: 'SPOP',
            supplier: 'SUPPLIER',
            unit: 'Unit',
            kode_akun: 'Kode Akun',
            rekening: 'Rekening'
        };
        var activeSpop = '';
        var activeUuidSupplier = '';
        var activeSupplierName = '';
        var isSavingKodeAkun = false;
        var skipKodeAkunSaveOnce = false;
        var detailRequest = null;

        function parseNominal(value) {
            if (typeof value === 'number') {
                return value;
            }

            var text = $('<div>').html(value).text();
            text = text.replace(/\./g, '').replace(',', '.').replace(/[^0-9.-]/g, '');
            var number = parseFloat(text);
            return isNaN(number) ? 0 : number;
        }

        function formatNominal(value) {
            return value.toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function formatFooterNominal(value) {
            return '<span class="jurnal-nominal jurnal-nominal-total">' + formatNominal(value) + '</span>';
        }

        function syncJurnalTableLayout() {
            if (!tableJurnalPembelian) {
                return;
            }

            tableJurnalPembelian.columns.adjust();

            var $wrapper = $('#tglSPOPFreeze').closest('.dataTables_wrapper');
            var $headInner = $wrapper.find('.dataTables_scrollHeadInner');
            var $footInner = $wrapper.find('.dataTables_scrollFootInner');
            var $bodyInner = $wrapper.find('.dataTables_scrollBody');
            var $headTable = $headInner.find('table');
            var $footTable = $footInner.find('table');
            var $bodyTable = $bodyInner.find('table');

            if (!$headTable.length || !$footTable.length) {
                return;
            }

            var headTableWidth = $headTable.outerWidth();
            $footInner.width(headTableWidth);
            $footTable.width(headTableWidth);

            if ($bodyTable.length) {
                $bodyTable.width(headTableWidth);
            }

            var $headCols = $headTable.find('thead tr').last().find('th');
            var $footCells = $footTable.find('tfoot tr').first().find('td');
            var $refCells = $bodyTable.find('tbody tr:first td');

            if (!$refCells.length) {
                $refCells = $headCols;
            }

            $refCells.each(function(index) {
                var colWidth = $(this).outerWidth();
                var $footCell = $footCells.eq(index);
                if (!$footCell.length || !colWidth) {
                    return;
                }

                $footCell.css({
                    width: colWidth + 'px',
                    minWidth: colWidth + 'px',
                    maxWidth: colWidth + 'px'
                });
            });

            if ($bodyInner.length) {
                var scrollBarWidth = $bodyInner[0].offsetWidth - $bodyInner[0].clientWidth;
                $wrapper.find('.dataTables_scrollFoot').css('padding-right', scrollBarWidth > 0 ? scrollBarWidth + 'px' : '0px');
            }
        }

        function updateFooterTotals(api) {
            var totalDebet = 0;
            var totalKredit = 0;

            api.rows({
                search: 'applied'
            }).every(function() {
                var rowNode = this.node();
                if (!rowNode || $(rowNode).hasClass('row-jurnal-total')) {
                    return;
                }

                var $td = $(rowNode).find('td');
                totalDebet += parseNominal($td.eq(7).text());
                totalKredit += parseNominal($td.eq(8).text());
            });

            $(api.column(7).footer()).html(formatFooterNominal(totalDebet));
            $(api.column(8).footer()).html(formatFooterNominal(totalKredit));
            updateJurnalSpopStats(api);
            syncJurnalTableLayout();
        }

        function updateJurnalSpopStats(api) {
            var sudah = 0;
            var belum = 0;

            api.rows({
                search: 'applied'
            }).every(function() {
                var rowNode = this.node();
                if (!rowNode || $(rowNode).hasClass('row-jurnal-total')) {
                    return;
                }

                if ($(rowNode).find('.btn-kode-kosong').length) {
                    belum++;
                } else {
                    sudah++;
                }
            });

            $('#jurnalSpopStatsSudah').text(sudah);
            $('#jurnalSpopStatsBelum').text(belum);
            $('#jurnalSpopStatsTotal').text(sudah + belum);

            var searchText = $.trim(jurnalSearchText || '');
            var fieldLabel = jurnalSearchFieldLabels[jurnalSearchField] || jurnalSearchField;
            $('#jurnalSpopStatsFilterNote').text(searchText !== '' ? 'Menampilkan hasil filter ' + fieldLabel + ': ' + searchText : '');
        }

        function exportJurnalPembelianExcel() {
            var searchText = $.trim(jurnalSearchText || '');
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = urlExcelJurnalPembelian;
            form.target = '_blank';
            form.style.display = 'none';

            var fields = {
                bulan_ns: bulanNs,
                search_text: searchText,
                search_field: jurnalSearchField
            };

            $.each(fields, function(key, value) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        function saveJurnalSearchState() {
            try {
                localStorage.setItem(dtJurnalSearchStorageKey, JSON.stringify({
                    field: jurnalSearchField,
                    text: jurnalSearchText
                }));
            } catch (e) {}
        }

        function loadJurnalSearchState() {
            var savedField = 'semua';
            var savedText = '';

            try {
                var raw = localStorage.getItem(dtJurnalSearchStorageKey);
                if (raw) {
                    try {
                        var parsed = JSON.parse(raw);
                        if (parsed && typeof parsed === 'object') {
                            savedField = parsed.field || 'semua';
                            savedText = (parsed.text !== undefined && parsed.text !== null) ? String(parsed.text) : '';
                        } else {
                            savedText = String(raw);
                        }
                    } catch (parseErr) {
                        savedText = String(raw);
                    }
                }
            } catch (e) {
                savedField = 'semua';
                savedText = '';
            }

            if (!jurnalSearchFieldLabels[savedField]) {
                savedField = 'semua';
            }

            jurnalSearchField = savedField;
            jurnalSearchText = (savedText !== undefined && savedText !== null) ? String(savedText) : '';
            $('#jurnalSearchField').val(jurnalSearchField);
            $('#jurnalSearchText').val(jurnalSearchText);
            updateJurnalSearchPlaceholder();
        }

        function normalizeJurnalSortOrder(order) {
            var valid = [];
            var i;
            var col;
            var dir;

            if (!Array.isArray(order)) {
                return [];
            }

            for (i = 0; i < order.length; i++) {
                if (!Array.isArray(order[i]) || order[i].length < 2) {
                    continue;
                }
                col = parseInt(order[i][0], 10);
                dir = String(order[i][1] || '').toLowerCase();
                if (col >= 0 && col <= 8 && (dir === 'asc' || dir === 'desc')) {
                    valid.push([col, dir]);
                }
            }

            return valid;
        }

        function loadJurnalSortState() {
            jurnalSortOrder = [];

            try {
                var raw = localStorage.getItem(dtJurnalSortStorageKey);
                if (raw) {
                    jurnalSortOrder = normalizeJurnalSortOrder(JSON.parse(raw));
                }
            } catch (e) {
                jurnalSortOrder = [];
            }
        }

        function saveJurnalSortState() {
            try {
                if (tableJurnalPembelian) {
                    jurnalSortOrder = normalizeJurnalSortOrder(tableJurnalPembelian.order());
                }
                localStorage.setItem(dtJurnalSortStorageKey, JSON.stringify(jurnalSortOrder));
            } catch (e) {}
        }

        function getJurnalDataTableOrder() {
            return jurnalSortOrder.slice();
        }

        function updateJurnalSearchPlaceholder() {
            var placeholders = {
                semua: 'Cari di semua kolom...',
                spop: 'No. SPOP...',
                supplier: 'Nama supplier...',
                unit: 'Unit / PL...',
                kode_akun: 'No. Rek / kode akun...',
                rekening: 'Nama rekening...'
            };
            $('#jurnalSearchText').attr('placeholder', placeholders[jurnalSearchField] || 'Ketik kata kunci...');
        }

        function rowMatchesJurnalAllFieldsSearch(rowNode, data, query) {
            var i;
            var cellText;
            var extraAttrs = ['search-unit', 'search-spop', 'search-supplier', 'search-kode-akun', 'search-rekening'];

            query = String(query || '').toLowerCase();
            if (!query) {
                return true;
            }

            for (i = 0; i < data.length; i++) {
                cellText = $('<div>').html(String(data[i] || '')).text().toLowerCase();
                if (cellText.indexOf(query) !== -1) {
                    return true;
                }
            }

            for (i = 0; i < extraAttrs.length; i++) {
                cellText = String($(rowNode).attr('data-' + extraAttrs[i]) || '').toLowerCase();
                if (cellText.indexOf(query) !== -1) {
                    return true;
                }
            }

            return false;
        }

        function refreshJurnalPage() {
            saveJurnalSearchState();
            saveJurnalSortState();
            window.location.reload();
        }

        function showKodeAkunSuccessAlert(res) {
            var kodeAkun = (res && res.kode_akun) ? res.kode_akun : '';
            var namaAkun = (res && res.nama_akun) ? res.nama_akun : '';
            var kodeAkunLabel = kodeAkun;
            if (namaAkun) {
                kodeAkunLabel += ' — ' + namaAkun;
            }

            var htmlMessage = '<div style="text-align:left;font-size:15px;line-height:1.7;">' +
                '<div><strong>SPOP:</strong> ' + escapeHtml(activeSpop || '-') + '</div>' +
                '<div><strong>Supplier:</strong> ' + escapeHtml(activeSupplierName || '-') + '</div>' +
                '<div><strong>Kode Akun:</strong> ' + escapeHtml(kodeAkunLabel || '-') + '</div>' +
                '</div>';

            Swal.fire({
                icon: 'success',
                title: 'Kode Akun Berhasil Disimpan',
                html: htmlMessage,
                confirmButtonText: 'OK',
                timer: 2000,
                timerProgressBar: true,
                allowOutsideClick: false,
                customClass: {
                    popup: 'swal-jurnal-kode-akun-success'
                }
            }).then(function() {
                refreshJurnalPage();
            });
        }

        loadJurnalSearchState();
        loadJurnalSortState();

        if (!window.__jurnalPembelianFieldFilterRegistered) {
            window.__jurnalPembelianFieldFilterRegistered = true;
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (!settings.nTable || settings.nTable.id !== 'tglSPOPFreeze') {
                    return true;
                }

                var query = $.trim(jurnalSearchText || '');
                if (!query) {
                    return true;
                }

                var api = new $.fn.dataTable.Api(settings);
                var rowNode = api.row(dataIndex).node();
                if (!rowNode || $(rowNode).hasClass('row-jurnal-total')) {
                    return false;
                }

                var attrName = 'search-' + jurnalSearchField.replace(/_/g, '-');
                if (jurnalSearchField === 'semua') {
                    return rowMatchesJurnalAllFieldsSearch(rowNode, data, query);
                }

                var value = String($(rowNode).attr('data-' + attrName) || '').toLowerCase();
                return value.indexOf(query.toLowerCase()) !== -1;
            });
        }

        var tableJurnalPembelian;
        if ($.fn.DataTable.isDataTable('#tglSPOPFreeze')) {
            $('#tglSPOPFreeze').DataTable().destroy();
        }

        tableJurnalPembelian = $('#tglSPOPFreeze').DataTable({
                "dom": "rt",
                "scrollY": 900,
                "scrollCollapse": true,
                "paging": false,
                "info": false,
                "searching": true,
                "order": getJurnalDataTableOrder(),
                "autoWidth": false,
                "columnDefs": [
                    { "targets": 7, "className": "col-jurnal-debet text-right", "width": "11%" },
                    { "targets": 8, "className": "col-jurnal-kredit text-right", "width": "12%" }
                ],
                "footerCallback": function() {
                    var api = this.api();
                    updateFooterTotals(api);
                },
                "initComplete": function() {
                    var api = this.api();
                    window.setTimeout(function() {
                        syncJurnalTableLayout();
                        updateFooterTotals(api);
                    }, 0);
                    window.setTimeout(syncJurnalTableLayout, 120);
                },
                "drawCallback": function() {
                    window.setTimeout(syncJurnalTableLayout, 0);
                }
            });

        tableJurnalPembelian.on('draw.dt', function() {
            updateFooterTotals(tableJurnalPembelian);
            syncJurnalTableLayout();
        });

        tableJurnalPembelian.on('order.dt', function() {
            saveJurnalSortState();
        });

        $('#jurnalSearchField').on('change', function() {
            jurnalSearchField = $(this).val() || 'semua';
            updateJurnalSearchPlaceholder();
            saveJurnalSearchState();
            if (tableJurnalPembelian) {
                tableJurnalPembelian.draw();
            }
        });

        $('#jurnalSearchText').on('input', function() {
            jurnalSearchText = $(this).val() || '';
            saveJurnalSearchState();
            if (tableJurnalPembelian) {
                tableJurnalPembelian.draw();
            }
        });

        $(window).on('resize', function() {
            syncJurnalTableLayout();
        });

        $(window).on('beforeunload', function() {
            saveJurnalSearchState();
            saveJurnalSortState();
        });

        $('#btnCetakExcelJurnalPembelian').on('click', function() {
            exportJurnalPembelianExcel();
        });

        updateFooterTotals(tableJurnalPembelian);

        $('#modalJurnalKodeAkunSelect').select2({
            theme: 'bootstrap',
            dropdownParent: $('#modalJurnalPembelianKodeAkun'),
            placeholder: '-- Pilih Kode Akun --',
            allowClear: true,
            width: '100%'
        });

        function showModalAlert(type, message) {
            var $alert = $('#modalJurnalKodeAkunAlert');
            $alert.removeClass('d-none alert-success alert-danger alert-info')
                .addClass('alert-' + type)
                .text(message);
        }

        function hideModalAlert() {
            $('#modalJurnalKodeAkunAlert').addClass('d-none').removeClass('alert-success alert-danger alert-info').text('');
        }

        function escapeHtml(text) {
            return $('<div>').text(text == null ? '' : text).html();
        }

        function renderDetailLoading() {
            $('#modalJurnalDetailContent')
                .removeClass('detail-empty')
                .addClass('detail-loading')
                .html('<i class="fa fa-spinner fa-spin"></i> Memuat detail pembelian...');
        }

        function renderDetailEmpty(message) {
            $('#modalJurnalDetailContent')
                .removeClass('detail-loading')
                .addClass('detail-empty')
                .text(message || 'Detail pembelian tidak ditemukan.');
        }

        function renderDetailRows(rows, grandTotalFmt) {
            if (!rows || !rows.length) {
                renderDetailEmpty();
                return;
            }

            var html = '<div class="table-responsive"><table class="table table-bordered detail-table">';
            html += '<thead><tr>';
            html += '<th>Tgl PO</th>';
            html += '<th>Supplier</th>';
            html += '<th>Unit / Konsumen</th>';
            html += '<th>Kode Barang</th>';
            html += '<th>Nama Barang</th>';
            html += '<th class="text-right">Jumlah</th>';
            html += '<th class="text-right">Harga Satuan</th>';
            html += '<th class="text-right">Harga Total</th>';
            html += '<th class="text-center">Lunas?</th>';
            html += '</tr></thead><tbody>';

            $.each(rows, function(_, row) {
                var statusClass = (row.status_lunas === 'Lunas') ? 'status-lunas' : 'status-hutang';
                html += '<tr>';
                html += '<td>' + escapeHtml(row.tgl_po) + '</td>';
                html += '<td>' + escapeHtml(row.supplier_nama) + '</td>';
                html += '<td>' + escapeHtml(row.unit_konsumen) + '</td>';
                html += '<td>' + escapeHtml(row.kode_barang) + '</td>';
                html += '<td>' + escapeHtml(row.nama_barang) + '</td>';
                html += '<td class="text-right">' + escapeHtml(row.jumlah_fmt) + '</td>';
                html += '<td class="text-right">' + escapeHtml(row.harga_satuan_fmt) + '</td>';
                html += '<td class="text-right">' + escapeHtml(row.harga_total_fmt) + '</td>';
                html += '<td class="text-center"><span class="' + statusClass + '">' + escapeHtml(row.status_lunas) + '</span></td>';
                html += '</tr>';
            });

            html += '</tbody>';
            if (grandTotalFmt) {
                html += '<tfoot><tr>';
                html += '<td colspan="7" class="text-right">Grand Total</td>';
                html += '<td class="text-right">' + escapeHtml(grandTotalFmt) + '</td>';
                html += '<td></td>';
                html += '</tr></tfoot>';
            }
            html += '</table></div>';

            $('#modalJurnalDetailContent')
                .removeClass('detail-loading detail-empty')
                .html(html);
        }

        function loadModalDetail(spop, uuidSupplier) {
            if (detailRequest) {
                detailRequest.abort();
                detailRequest = null;
            }

            renderDetailLoading();

            detailRequest = $.ajax({
                url: urlDetailKodeAkun,
                type: 'POST',
                dataType: 'json',
                data: {
                    spop: spop,
                    uuid_supplier: uuidSupplier,
                    bulan_ns: bulanNs
                }
            }).done(function(res) {
                if (!res || !res.ok) {
                    renderDetailEmpty((res && res.message) ? res.message : 'Gagal memuat detail pembelian.');
                    return;
                }

                renderDetailRows(res.rows || [], res.grand_total_fmt || '');
            }).fail(function(xhr, status) {
                if (status === 'abort') {
                    return;
                }
                renderDetailEmpty('Terjadi kesalahan koneksi saat memuat detail pembelian.');
            }).always(function() {
                detailRequest = null;
            });
        }

        function updateModalHintText(spop, supplier) {
            $('#modalJurnalHintSpop').text(spop || '-');
            $('#modalJurnalHintSupplier').text(supplier || '-');
        }

        $(document).on('click', '.btn-jurnal-kode-akun', function() {
            var $btn = $(this);
            activeSpop = $btn.data('spop') || '';
            activeUuidSupplier = $btn.data('uuid-supplier') || '';
            activeSupplierName = $btn.data('supplier') || '-';

            $('#modalJurnalSpop').text(activeSpop);
            $('#modalJurnalSupplier').text(activeSupplierName);
            updateModalHintText(activeSpop, activeSupplierName);
            hideModalAlert();
            loadModalDetail(activeSpop, activeUuidSupplier);

            var currentKode = $btn.data('kode-akun') || '';
            skipKodeAkunSaveOnce = true;
            $('#modalJurnalKodeAkunSelect').val(currentKode).trigger('change');
            window.setTimeout(function() {
                skipKodeAkunSaveOnce = false;
            }, 200);
            $('#modalJurnalPembelianKodeAkun').modal('show');
        });

        $('#modalJurnalKodeAkunSelect').on('change', function() {
            if (skipKodeAkunSaveOnce) {
                return;
            }

            var kodeAkun = $(this).val();
            if (!kodeAkun || isSavingKodeAkun || !activeSpop || !activeUuidSupplier) {
                return;
            }

            isSavingKodeAkun = true;
            showModalAlert('info', 'Menyimpan kode akun...');

            $.ajax({
                url: urlUpdateKodeAkun,
                type: 'POST',
                dataType: 'json',
                data: {
                    spop: activeSpop,
                    uuid_supplier: activeUuidSupplier,
                    bulan_ns: bulanNs,
                    kode_akun: kodeAkun
                }
            }).done(function(res) {
                if (!res || !res.ok) {
                    showModalAlert('danger', (res && res.message) ? res.message : 'Gagal menyimpan kode akun.');
                    return;
                }

                hideModalAlert();
                $('#modalJurnalPembelianKodeAkun').modal('hide');
                showKodeAkunSuccessAlert(res);
            }).fail(function() {
                showModalAlert('danger', 'Terjadi kesalahan koneksi saat menyimpan kode akun.');
            }).always(function() {
                isSavingKodeAkun = false;
            });
        });

        $('#bulan_ns').on('change', function() {
            if ($(this).val()) {
                saveJurnalSearchState();
                saveJurnalSortState();
                $('#formFilterBulanJurnalPembelian').trigger('submit');
            }
        });
    });
</script>
<?php $this->load->view('anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_compare_manual_online_script'); ?>
