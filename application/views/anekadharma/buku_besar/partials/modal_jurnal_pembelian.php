<?php
$jurnal_spop_stats = isset($jurnal_pembelian2_spop_stats) ? $jurnal_pembelian2_spop_stats : array('total' => 0, 'sudah' => 0, 'belum' => 0);
$bulan_ns_selected = isset($bulan_ns_selected) ? $bulan_ns_selected : date('Y-m');
$bulan_label = isset($bulan_label) ? $bulan_label : '';
?>
<div class="bb-jp-embed" data-bb-jp-embed="1">
<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }

    :root {
        --jurnal-dt-box-border: #ffb300;
        --jurnal-dt-box-glow: rgba(255, 193, 7, 0.28);
        --jurnal-dt-header-bg: #bbdefb;
    }

    #bbJpTglSPOPFreeze thead th,
    .dataTables_scrollHeadInner table.dataTable#bbJpTglSPOPFreeze thead th {
        background-color: var(--jurnal-dt-header-bg) !important;
        color: #0d47a1;
        font-weight: 700 !important;
    }

    table.dataTable#bbJpTglSPOPFreeze,
    .dataTables_scrollHeadInner table.dataTable#bbJpTglSPOPFreeze,
    .dataTables_scrollBody table.dataTable#bbJpTglSPOPFreeze,
    .dataTables_scrollFootInner table.dataTable#bbJpTglSPOPFreeze {
        border: none !important;
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

    #bbJpTglSPOPFreeze td.col-kode-akun-btn,
    #bbJpTglSPOPFreeze th.col-kode-akun-header {
        text-align: right !important;
    }

    #bbJpTglSPOPFreeze tbody tr.row-jurnal-total td {
        text-align: right !important;
    }

    #bbJpTglSPOPFreeze tbody td.col-jurnal-debet,
    #bbJpTglSPOPFreeze tbody td.col-jurnal-kredit {
        text-align: right !important;
        padding: 8px 12px 8px 8px !important;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
        vertical-align: middle;
    }

    #bbJpTglSPOPFreeze .jurnal-nominal {
        text-align: right;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }

    #bbJpTglSPOPFreeze .jurnal-nominal-total {
        color: #0d47a1;
        font-weight: 700;
    }

    #bbJpTglSPOPFreeze tfoot td,
    .dataTables_scrollFootInner table.dataTable#bbJpTglSPOPFreeze tfoot td {
        background-color: #e3f2fd !important;
        font-weight: 700 !important;
        color: #212529;
        box-sizing: border-box;
        vertical-align: middle;
    }

    #bbJpTglSPOPFreeze tfoot td.col-jurnal-debet,
    #bbJpTglSPOPFreeze tfoot td.col-jurnal-kredit {
        text-align: right !important;
        padding: 8px 12px 8px 8px !important;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }

    #bbJpTglSPOPFreeze tbody td,
    .dataTables_scrollBody table.dataTable#bbJpTglSPOPFreeze tbody td {
        box-sizing: border-box;
        background-color: #fff;
        font-weight: normal;
    }

    table.dataTable#bbJpTglSPOPFreeze,
    .dataTables_scrollHeadInner table.dataTable#bbJpTglSPOPFreeze,
    .dataTables_scrollBody table.dataTable#bbJpTglSPOPFreeze,
    .dataTables_scrollFootInner table.dataTable#bbJpTglSPOPFreeze {
        table-layout: fixed;
        width: 100% !important;
        margin: 0 !important;
    }

    .jurnal-dt-wrapper {
        overflow-x: auto;
        border: 1px solid var(--jurnal-dt-box-border);
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 0 0 1px var(--jurnal-dt-box-glow), 0 2px 8px rgba(255, 179, 0, 0.12);
        padding: 0;
    }

    #bbJpTglSPOPFreeze_wrapper .dataTables_scroll {
        border: none;
        border-radius: 0;
        overflow: hidden;
    }

    .dataTables_scrollHead,
    .dataTables_scrollFoot,
    .dataTables_scrollBody {
        border: none !important;
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

    #bbJpModalJurnalPembelianKodeAkun .modal-dialog {
        max-width: min(1280px, 94vw);
        margin: 1.25rem auto;
    }

    #bbJpModalJurnalPembelianKodeAkun .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.18);
    }

    #bbJpModalJurnalPembelianKodeAkun .modal-header {
        background: linear-gradient(135deg, #1b5e20, #43a047);
        color: #fff;
        border: none;
    }

    #bbJpModalJurnalPembelianKodeAkun .modal-body {
        background: #f8faf8;
    }

    #bbJpModalJurnalPembelianKodeAkun .info-card {
        background: #fff;
        border: 1px solid #e8f5e9;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 16px;
    }

    #bbJpModalJurnalPembelianKodeAkun .info-card .label-muted {
        color: #6c757d;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    #bbJpModalJurnalPembelianKodeAkun .info-card .value-text {
        font-size: 16px;
        font-weight: 700;
        color: #1b4332;
    }

    #bbJpModalJurnalPembelianKodeAkun .detail-section {
        background: #fff;
        border: 1px solid #e8f5e9;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 16px;
        max-height: 380px;
        overflow: auto;
    }

    #bbJpModalJurnalPembelianKodeAkun .detail-section .section-title {
        font-size: 15px;
        font-weight: 700;
        color: #1b5e20;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    #bbJpModalJurnalPembelianKodeAkun .detail-table {
        width: 100%;
        font-size: 15px;
        line-height: 1.45;
        margin-bottom: 0;
    }

    #bbJpModalJurnalPembelianKodeAkun .detail-table thead th {
        background: #e8f5e9;
        color: #1b4332;
        font-weight: 700;
        font-size: 14px;
        white-space: nowrap;
        vertical-align: middle;
        border-color: #c8e6c9;
        padding: 10px 12px;
    }

    #bbJpModalJurnalPembelianKodeAkun .detail-table tbody td {
        vertical-align: middle;
        border-color: #edf7ee;
        padding: 10px 12px;
        font-size: 15px;
    }

    #bbJpModalJurnalPembelianKodeAkun .detail-table tfoot td {
        background: #f1f8e9;
        font-weight: 700;
        font-size: 15px;
        border-color: #c8e6c9;
        padding: 10px 12px;
    }

    #bbJpModalJurnalPembelianKodeAkun .status-lunas {
        color: #1b5e20;
        font-weight: 700;
    }

    #bbJpModalJurnalPembelianKodeAkun .status-hutang {
        color: #c62828;
        font-weight: 700;
    }

    #bbJpModalJurnalPembelianKodeAkun .detail-loading,
    #bbJpModalJurnalPembelianKodeAkun .detail-empty {
        color: #6c757d;
        font-size: 15px;
        text-align: center;
        padding: 22px 8px;
    }

    #bbJpModalJurnalPembelianKodeAkun .modal-kode-akun-hint {
        font-size: 14px;
        line-height: 1.6;
        color: #5f6368;
        margin-top: 8px;
    }

    #bbJpModalJurnalPembelianKodeAkun .modal-kode-akun-hint .hint-highlight {
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

    .jurnal-stats-toolbar {
        margin-bottom: 10px;
        align-items: center;
    }

    .jurnal-spop-stats-box {
        background: #f8faf8;
        border: 1px solid #e8f5e9;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 0;
        font-size: 15px;
        line-height: 1.4;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px 12px;
    }

    .jurnal-spop-stats-box .stats-title {
        font-size: 16px;
        font-weight: 700;
        color: #1b4332;
        display: inline;
        margin-bottom: 0;
        white-space: nowrap;
    }

    .jurnal-spop-stats-box .stats-separator {
        color: #c5c5c5;
        font-weight: 400;
        user-select: none;
    }

    .jurnal-spop-stats-box .stats-item {
        display: inline-flex;
        align-items: center;
        margin: 0;
        white-space: nowrap;
    }

    .jurnal-spop-stats-box .stats-label {
        font-size: 15px;
        color: #37474f;
    }

    .jurnal-spop-stats-box .stats-value {
        font-size: 18px;
        font-weight: 700;
        margin-left: 4px;
    }

    .jurnal-spop-stats-box .stats-filter-note {
        display: inline;
        font-size: 14px;
        margin: 0;
        white-space: nowrap;
    }

    .jurnal-stats-toolbar .btn-cetak-excel-jurnal {
        white-space: nowrap;
    }

    @media (max-width: 991px) {
        .jurnal-stats-toolbar .text-right {
            text-align: left !important;
            margin-top: 8px;
        }
    }

    .jurnal-spop-search-wrap {
        background: #fff;
        border: 1px solid #e8f5e9;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 10px;
    }

    .jurnal-spop-search-wrap .jurnal-spop-search-label {
        font-size: 17px;
        font-weight: 700;
        color: #1b4332;
        margin-bottom: 0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        flex: 0 0 auto;
    }

    .jurnal-spop-search-wrap .jurnal-spop-search-input {
        font-size: 18px;
        height: 46px;
        border: 1px solid #c8e6c9;
        border-radius: 8px;
        flex: 0 1 320px;
        width: 320px;
        max-width: 420px;
        min-width: 180px;
        padding: 8px 12px;
    }

    .jurnal-spop-search-wrap .jurnal-spop-search-field {
        font-size: 18px;
        height: 46px;
        border: 1px solid #c8e6c9;
        border-radius: 8px;
        min-width: 150px;
        max-width: 200px;
        flex: 0 0 175px;
        font-weight: 600;
        color: #1b4332;
        background-color: #f1f8e9;
        padding-left: 10px;
        padding-right: 28px;
    }

    .jurnal-spop-search-wrap .jurnal-spop-search-row {
        display: flex;
        flex-wrap: nowrap;
        gap: 10px;
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

    #bbJpTglSPOPFreeze_wrapper .dataTables_filter {
        display: none !important;
    }

.bb-jp-embed-header{background:linear-gradient(135deg,#1b5e20 0%,#43a047 55%,#66bb6a 100%);color:#fff;border-radius:12px;padding:14px 18px;margin-bottom:12px;box-shadow:0 4px 14px rgba(27,94,32,.25);}
.bb-jp-embed-header h4{margin:0;font-weight:700;font-size:1.15rem;}.bb-jp-embed-header .sub{opacity:.92;font-size:.9rem;margin-top:4px;}
#bbJpModalJurnalPembelianKodeAkun{z-index:1065!important;}
</style>
<div class="bb-jp-embed-header"><h4><i class="fa fa-shopping-cart"></i> Jurnal Pembelian</h4><div class="sub"><?php echo htmlspecialchars($bulan_label, ENT_QUOTES, 'UTF-8'); ?> &mdash; DataTables lengkap dengan tombol ubah kode akun</div></div>
                                <div class="row mb-2 jurnal-stats-toolbar">
                                    <div class="col-md-8 col-lg-9">
                                        <div class="jurnal-spop-stats-box" id="jurnalSpopStatsBox">
                                            <span class="stats-title">Informasi SPOP bulan ini</span>
                                            <span class="stats-separator">|</span>
                                            <span class="stats-item">
                                                <span class="stats-label">Total SPOP:</span>
                                                <strong id="bbJpJurnalSpopStatsTotal" class="stats-value text-primary"><?php echo (int) $jurnal_spop_stats['total']; ?></strong>
                                            </span>
                                            <span class="stats-separator">|</span>
                                            <span class="stats-item">
                                                <span class="stats-label">Sudah setting kode akun:</span>
                                                <strong id="bbJpJurnalSpopStatsSudah" class="stats-value text-success"><?php echo (int) $jurnal_spop_stats['sudah']; ?></strong>
                                            </span>
                                            <span class="stats-separator">|</span>
                                            <span class="stats-item">
                                                <span class="stats-label">Belum setting kode akun:</span>
                                                <strong id="bbJpJurnalSpopStatsBelum" class="stats-value text-danger"><?php echo (int) $jurnal_spop_stats['belum']; ?></strong>
                                            </span>
                                            <span id="bbJpJurnalSpopStatsFilterNote" class="stats-filter-note text-muted"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-3 text-right">
                                        <button type="button" class="btn btn-success btn-flat btn-lg btn-cetak-excel-jurnal" id="bbJpBtnCetakExcelJurnalPembelian">
                                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Cetak ke Excel
                                        </button>
                                    </div>
                                </div>

                                <div class="jurnal-spop-search-wrap">
                                    <div class="jurnal-spop-search-row">
                                        <label for="bbJpJurnalSearchField" class="jurnal-spop-search-label">
                                            <i class="fa fa-search"></i> Cari
                                        </label>
                                        <select id="bbJpJurnalSearchField" class="form-control jurnal-spop-search-field">
                                            <option value="semua" selected>Semua</option>
                                            <option value="spop">SPOP</option>
                                            <option value="supplier">SUPPLIER</option>
                                            <option value="unit">Unit</option>
                                            <option value="kode_akun">Kode Akun</option>
                                            <option value="rekening">Rekening</option>
                                        </select>
                                        <input type="text"
                                            id="bbJpJurnalSearchText"
                                            class="form-control jurnal-spop-search-input"
                                            placeholder="Ketik kata kunci..."
                                            autocomplete="off">
                                    </div>
                                </div>

                                <div class="jurnal-dt-wrapper">

                        <!-- <table id="bbJpTglSPOPFreeze" class="table table-striped dt-responsive w-100 table-bordered display nowrap table-hover mb-0" style="width:100%"> -->
                        <table id="bbJpTglSPOPFreeze" class="display nowrap" style="width:100%">
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
                                    <td class="col-jurnal-debet" id="bbJpFooterTotalDebet">
                                        <span class="jurnal-nominal jurnal-nominal-total">0,00</span>
                                    </td>
                                    <td class="col-jurnal-kredit" id="bbJpFooterTotalKredit">
                                        <span class="jurnal-nominal jurnal-nominal-total">0,00</span>
                                    </td>
                                </tr>
                            </tfoot>

                        </table>
                                </div>


    <div class="modal fade" id="bbJpModalJurnalPembelianKodeAkun" tabindex="-1" role="dialog" aria-labelledby="bbJpModalJurnalPembelianKodeAkunLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <h5 class="modal-title" id="bbJpModalJurnalPembelianKodeAkunLabel">
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
                                <div class="value-text" id="bbJpModalJurnalSpop">-</div>
                            </div>
                            <div class="col-6">
                                <div class="label-muted">Supplier</div>
                                <div class="value-text" id="bbJpModalJurnalSupplier">-</div>
                            </div>
                        </div>
                    </div>
                    <div class="detail-section" id="modalJurnalDetailSection">
                        <div class="section-title"><i class="fa fa-list"></i> Detail Pembelian</div>
                        <div id="bbJpModalJurnalDetailContent" class="detail-loading">
                            <i class="fa fa-spinner fa-spin"></i> Memuat detail pembelian...
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label for="bbJpModalKodeAkunSelect" class="font-weight-bold text-success">
                            <i class="fa fa-list-alt"></i> Pilih Kode Akun (sys_kode_akun)
                        </label>
                        <select id="bbJpModalKodeAkunSelect" class="form-control" style="width:100%;">
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
                            <strong id="bbJpModalJurnalHintSpop" class="hint-highlight">-</strong>
                            dan supplier:
                            <strong id="bbJpModalJurnalHintSupplier" class="hint-highlight">-</strong>
                            yang sama pada bulan ini.
                        </small>
                    </div>
                    <div id="bbJpModalKodeAkunAlert" class="alert d-none mb-0" role="alert"></div>
                </div>
                <div class="modal-footer bg-white border-0 pt-0">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function bbJpInitJurnalPembelianEmbed() {
        var urlUpdateKodeAkun = <?php echo json_encode(isset($url_jurnal_pembelian2_update_kode_akun) ? $url_jurnal_pembelian2_update_kode_akun : site_url('Tbl_pembelian/ajax_jurnal_pembelian2_update_kode_akun')); ?>;
        var urlDetailKodeAkun = <?php echo json_encode(isset($url_jurnal_pembelian2_detail_kode_akun) ? $url_jurnal_pembelian2_detail_kode_akun : site_url('Tbl_pembelian/ajax_jurnal_pembelian2_detail_kode_akun')); ?>;
        var urlExcelJurnalPembelian = <?php echo json_encode(isset($url_excel_jurnal_pembelian2) ? $url_excel_jurnal_pembelian2 : site_url('Tbl_pembelian/excel_jurnal_pembelian2')); ?>;
        var bulanNs = <?php echo json_encode(isset($bulan_ns_selected) ? $bulan_ns_selected : date('Y-m')); ?>;
        var dtJurnalSearchStorageKey = 'bb_jp_jurnal_pembelian2_search_' + bulanNs;
        var dtJurnalSortStorageKey = 'bb_jp_jurnal_pembelian2_sort_' + bulanNs;
        var bbJpJurnalSearchField = 'semua';
        var bbJpJurnalSearchText = '';
        var jurnalSortOrder = [];

        var bbJpJurnalSearchFieldLabels = {
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

            var $wrapper = $('#bbJpTglSPOPFreeze').closest('.dataTables_wrapper');
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

            $('#bbJpJurnalSpopStatsSudah').text(sudah);
            $('#bbJpJurnalSpopStatsBelum').text(belum);
            $('#bbJpJurnalSpopStatsTotal').text(sudah + belum);

            var searchText = $.trim(bbJpJurnalSearchText || '');
            var fieldLabel = bbJpJurnalSearchFieldLabels[bbJpJurnalSearchField] || bbJpJurnalSearchField;
            $('#bbJpJurnalSpopStatsFilterNote').text(searchText !== '' ? '| Menampilkan hasil filter ' + fieldLabel + ': ' + searchText : '');
        }

        function exportJurnalPembelianExcel() {
            var searchText = $.trim(bbJpJurnalSearchText || '');
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = urlExcelJurnalPembelian;
            form.target = '_blank';
            form.style.display = 'none';

            var fields = {
                bulan_ns: bulanNs,
                search_text: searchText,
                search_field: bbJpJurnalSearchField
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
                    field: bbJpJurnalSearchField,
                    text: bbJpJurnalSearchText
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

            if (!bbJpJurnalSearchFieldLabels[savedField]) {
                savedField = 'semua';
            }

            bbJpJurnalSearchField = savedField;
            bbJpJurnalSearchText = (savedText !== undefined && savedText !== null) ? String(savedText) : '';
            $('#bbJpJurnalSearchField').val(bbJpJurnalSearchField);
            $('#bbJpJurnalSearchText').val(bbJpJurnalSearchText);
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
            $('#bbJpJurnalSearchText').attr('placeholder', placeholders[bbJpJurnalSearchField] || 'Ketik kata kunci...');
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
            if (window.bbJpRefreshCallback) { window.bbJpRefreshCallback(); } else { window.location.reload(); }
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

        if (!window.__bbJpJurnalPembelianFieldFilterRegistered) {
            window.__bbJpJurnalPembelianFieldFilterRegistered = true;
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (!settings.nTable || settings.nTable.id !== 'bbJpTglSPOPFreeze') {
                    return true;
                }

                var query = $.trim(bbJpJurnalSearchText || '');
                if (!query) {
                    return true;
                }

                var api = new $.fn.dataTable.Api(settings);
                var rowNode = api.row(dataIndex).node();
                if (!rowNode || $(rowNode).hasClass('row-jurnal-total')) {
                    return false;
                }

                var attrName = 'search-' + bbJpJurnalSearchField.replace(/_/g, '-');
                if (bbJpJurnalSearchField === 'semua') {
                    return rowMatchesJurnalAllFieldsSearch(rowNode, data, query);
                }

                var value = String($(rowNode).attr('data-' + attrName) || '').toLowerCase();
                return value.indexOf(query.toLowerCase()) !== -1;
            });
        }

        var tableJurnalPembelian;
        if ($.fn.DataTable.isDataTable('#bbJpTglSPOPFreeze')) {
            $('#bbJpTglSPOPFreeze').DataTable().destroy();
        }

        tableJurnalPembelian = $('#bbJpTglSPOPFreeze').DataTable({
                "dom": "rt",
                "scrollY": "min(62vh, 520px)",
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

        $('#bbJpJurnalSearchField').on('change', function() {
            bbJpJurnalSearchField = $(this).val() || 'semua';
            updateJurnalSearchPlaceholder();
            saveJurnalSearchState();
            if (tableJurnalPembelian) {
                tableJurnalPembelian.draw();
            }
        });

        $('#bbJpJurnalSearchText').on('input', function() {
            bbJpJurnalSearchText = $(this).val() || '';
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

        $('#bbJpBtnCetakExcelJurnalPembelian').on('click', function() {
            exportJurnalPembelianExcel();
        });

        updateFooterTotals(tableJurnalPembelian);

        $('#bbJpModalKodeAkunSelect').select2({
            theme: 'bootstrap',
            dropdownParent: $('#bbJpModalJurnalPembelianKodeAkun'),
            placeholder: '-- Pilih Kode Akun --',
            allowClear: true,
            width: '100%'
        });

        function showModalAlert(type, message) {
            var $alert = $('#bbJpModalKodeAkunAlert');
            $alert.removeClass('d-none alert-success alert-danger alert-info')
                .addClass('alert-' + type)
                .text(message);
        }

        function hideModalAlert() {
            $('#bbJpModalKodeAkunAlert').addClass('d-none').removeClass('alert-success alert-danger alert-info').text('');
        }

        function escapeHtml(text) {
            return $('<div>').text(text == null ? '' : text).html();
        }

        function renderDetailLoading() {
            $('#bbJpModalJurnalDetailContent')
                .removeClass('detail-empty')
                .addClass('detail-loading')
                .html('<i class="fa fa-spinner fa-spin"></i> Memuat detail pembelian...');
        }

        function renderDetailEmpty(message) {
            $('#bbJpModalJurnalDetailContent')
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

            $('#bbJpModalJurnalDetailContent')
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
            $('#bbJpModalJurnalHintSpop').text(spop || '-');
            $('#bbJpModalJurnalHintSupplier').text(supplier || '-');
        }

        $(document).on('click', '.btn-jurnal-kode-akun', function() {
            var $btn = $(this);
            activeSpop = $btn.data('spop') || '';
            activeUuidSupplier = $btn.data('uuid-supplier') || '';
            activeSupplierName = $btn.data('supplier') || '-';

            $('#bbJpModalJurnalSpop').text(activeSpop);
            $('#bbJpModalJurnalSupplier').text(activeSupplierName);
            updateModalHintText(activeSpop, activeSupplierName);
            hideModalAlert();
            loadModalDetail(activeSpop, activeUuidSupplier);

            var currentKode = $btn.data('kode-akun') || '';
            skipKodeAkunSaveOnce = true;
            $('#bbJpModalKodeAkunSelect').val(currentKode).trigger('change');
            window.setTimeout(function() {
                skipKodeAkunSaveOnce = false;
            }, 200);
            $('#bbJpModalJurnalPembelianKodeAkun').modal('show');
        });

        $('#bbJpModalKodeAkunSelect').on('change', function() {
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
                $('#bbJpModalJurnalPembelianKodeAkun').modal('hide');
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
    }
window.bbJpInitJurnalPembelianEmbed = bbJpInitJurnalPembelianEmbed;
</script>
</div>
