<?php
$bulan_ns_selected = isset($bulan_ns_selected) ? $bulan_ns_selected : date('Y-m');
$bulan_label = isset($bulan_label) ? $bulan_label : '';
?>
<div class="bb-jn-embed" data-bb-jn-embed="1">
<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }

    .nav-tabs.jurnal-penjualan-tabs {
        border-bottom: 2px solid #ffc107;
        margin-bottom: 15px;
    }

    .nav-tabs.jurnal-penjualan-tabs .nav-item {
        margin-bottom: -2px;
    }

    .nav-tabs.jurnal-penjualan-tabs .nav-link {
        background-color: #ffffff;
        border: 2px solid #ffc107;
        border-bottom: none;
        color: #888888;
        font-weight: normal;
        margin-right: 4px;
        border-radius: 4px 4px 0 0;
        opacity: 0.75;
    }

    .nav-tabs.jurnal-penjualan-tabs .nav-link:hover {
        background-color: #f8f9fa;
        color: #666666;
        opacity: 0.9;
    }

    .nav-tabs.jurnal-penjualan-tabs .nav-link.active {
        background-color: #007bff;
        border-color: #ffc107;
        color: #000000;
        font-weight: bold;
        opacity: 1;
    }

    .jurnal-penjualan-unit-block {
        margin-bottom: 40px;
        padding-bottom: 0;
        border-bottom: none;
    }

    .jurnal-penjualan-unit-block:last-child {
        margin-bottom: 0;
    }

    .jurnal-penjualan-unit-toolbar {
        display: grid;
        grid-template-columns: 1fr minmax(200px, auto) 1fr;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        min-height: 44px;
    }

    .jurnal-penjualan-unit-toolbar-spacer {
        grid-column: 1;
    }

    .jurnal-penjualan-unit-title {
        grid-column: 2;
        font-size: 22px;
        font-weight: bold;
        text-align: center;
        margin: 0;
        padding: 6px 16px;
        color: var(--jp-th-text, #4a3728);
        letter-spacing: 0.5px;
        background: var(--jp-th-bg, #e8d4b8);
        border: 2px solid #f5d78e;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        white-space: nowrap;
    }

    .jurnal-penjualan-unit-toolbar-excel {
        grid-column: 3;
        justify-self: end;
        white-space: nowrap;
    }

    .jurnal-penjualan-unit-table-wrap {
        border: 2px solid #f5d78e;
        border-radius: 8px;
        padding: 8px;
        background: #fffdf8;
        box-shadow: 0 2px 8px rgba(245, 215, 142, 0.2);
        width: 100%;
        box-sizing: border-box;
        -webkit-overflow-scrolling: touch;
    }

    .jurnal-penjualan-unit-table-wrap.jp-unit-native-scroll {
        overflow: auto;
        max-height: 480px;
        -webkit-overflow-scrolling: touch;
    }

    .jurnal-penjualan-unit-table-wrap.jp-unit-native-scroll > table.tbl-jurnal-penjualan-per-unit-special {
        margin: 0;
    }
    .jurnal-penjualan-unit-table-wrap::-webkit-scrollbar,
    .jurnal-penjualan-unit-table-wrap .dataTables_scrollBody::-webkit-scrollbar {
        width: 12px;
        height: 12px;
    }

    .jurnal-penjualan-unit-table-wrap::-webkit-scrollbar-thumb,
    .jurnal-penjualan-unit-table-wrap .dataTables_scrollBody::-webkit-scrollbar-thumb {
        background: #f5d78e;
        border-radius: 6px;
    }

    .jurnal-penjualan-unit-table-wrap::-webkit-scrollbar-track,
    .jurnal-penjualan-unit-table-wrap .dataTables_scrollBody::-webkit-scrollbar-track {
        background: #fff8e8;
    }

    .jurnal-penjualan-unit-table-wrap .dataTables_wrapper {
        width: 100%;
        margin: 0;
        font-size: 15px;
    }

    .jurnal-penjualan-unit-table-wrap .dataTables_scroll,
    .jurnal-penjualan-unit-table-wrap .dataTables_scrollHead,
    .jurnal-penjualan-unit-table-wrap .dataTables_scrollBody,
    .jurnal-penjualan-unit-table-wrap .dataTables_scrollFoot {
        width: 100% !important;
    }

    .jurnal-penjualan-unit-table-wrap.jp-unit-dt-scroll .dataTables_scrollBody {
        max-height: 480px !important;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit {
        border-collapse: collapse;
        font-size: 15px;
        line-height: 1.5;
        border: 1px solid #f5d78e;
        width: 100% !important;
        table-layout: fixed;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit-special {
        width: 100% !important;
        min-width: 100%;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit thead th {
        background-color: var(--jp-th-bg, #e8d4b8) !important;
        color: var(--jp-th-text, #4a3728) !important;
        font-weight: 700;
        font-size: 15px;
        border: 1px solid #f5d78e !important;
        padding: 11px 10px;
        white-space: nowrap;
        vertical-align: middle;
        text-align: center;
        box-sizing: border-box;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit tbody td {
        border: 1px solid #f5e6b8;
        padding: 11px 10px;
        background-color: #ffffff;
        vertical-align: middle;
        font-size: 15px;
        white-space: nowrap;
        box-sizing: border-box;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit tbody tr:nth-child(even) td {
        background-color: var(--jp-stripe, #faf7f2);
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit tbody tr:hover td {
        background-color: var(--jp-hover, #f5ebe0);
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit tfoot th {
        background-color: var(--jp-foot, #f0e4d4) !important;
        color: var(--jp-th-text, #4a3728) !important;
        border: 1px solid #f5d78e !important;
        font-weight: bold;
        font-size: 15px;
        padding: 11px 10px;
        white-space: nowrap;
        box-sizing: border-box;
        vertical-align: middle;
        text-align: center;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit tfoot th.jp-cetak-col-amount,
    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit tfoot th.jp-col-amount {
        text-align: right;
    }

    .jurnal-penjualan-tab-table-wrap table.display thead th[data-jp-sort-col],
    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit thead th[data-jp-sort-col] {
        cursor: pointer;
        user-select: none;
    }

    .jurnal-penjualan-tab-table-wrap table.display thead th.jp-sort-asc::after,
    .jurnal-penjualan-tab-table-wrap table.display thead th.jp-sort-desc::after {
        font-size: 11px;
        opacity: 0.85;
    }

    .jurnal-penjualan-tab-table-wrap table.display thead th.jp-sort-asc::after {
        content: ' \25B2';
    }

    .jurnal-penjualan-tab-table-wrap table.display thead th.jp-sort-desc::after {
        content: ' \25BC';
    }

    .jurnal-penjualan-unit-table-wrap.jp-unit-dt-scroll {
        overflow: hidden;
        max-height: none;
        padding: 8px;
    }

    .jurnal-penjualan-unit-table-wrap.jp-unit-dt-scroll .dataTables_scrollBody {
        max-height: 480px !important;
        border-top: 1px solid #f5d78e;
    }

    .jurnal-penjualan-unit-table-wrap.jp-unit-dt-scroll .dataTables_scroll {
        border: 1px solid #f5d78e;
        border-radius: 6px;
        background: #ffffff;
    }

    .jurnal-penjualan-unit-table-wrap.jp-unit-dt-scroll .dataTables_scrollHead {
        background: var(--jp-th-bg, #e8d4b8);
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit thead th[data-jp-sort-col],
    .jurnal-penjualan-unit-table-wrap table.dataTable thead th.sorting,
    .jurnal-penjualan-unit-table-wrap table.dataTable thead th.sorting_asc,
    .jurnal-penjualan-unit-table-wrap table.dataTable thead th.sorting_desc {
        cursor: pointer;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit thead th.jp-sort-asc::after,
    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit thead th.sorting_asc::after {
        content: ' \25B2';
        font-size: 11px;
        opacity: 0.85;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit thead th.jp-sort-desc::after,
    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit thead th.sorting_desc::after {
        content: ' \25BC';
        font-size: 11px;
        opacity: 0.85;
    }

    .jp-unit-theme-0 { --jp-th-bg: #d4a574; --jp-th-group: #c9955e; --jp-th-kode: #bf8a52; --jp-th-text: #3d2817; --jp-stripe: #faf3e8; --jp-hover: #f0e0c8; --jp-foot: #e8cfa8; }
    .jp-unit-theme-1 { --jp-th-bg: #7fd49a; --jp-th-group: #5fc47e; --jp-th-kode: #4db86d; --jp-th-text: #1a4a2e; --jp-stripe: #eef9f1; --jp-hover: #dff3e6; --jp-foot: #c8ebd4; }
    .jp-unit-theme-2 { --jp-th-bg: #7ec8e3; --jp-th-group: #5eb8d8; --jp-th-kode: #4aa8cc; --jp-th-text: #1a4a5c; --jp-stripe: #eef7fb; --jp-hover: #dceef6; --jp-foot: #c5e4f0; }
    .jp-unit-theme-3 { --jp-th-bg: #c9b0e8; --jp-th-group: #b59ad8; --jp-th-kode: #a688cc; --jp-th-text: #3d2d5c; --jp-stripe: #f5f0fb; --jp-hover: #ebe3f6; --jp-foot: #ddd0f0; }
    .jp-unit-theme-4 { --jp-th-bg: #f5c4a0; --jp-th-group: #efb088; --jp-th-kode: #e8a078; --jp-th-text: #5c3018; --jp-stripe: #fff5ee; --jp-hover: #fce8dc; --jp-foot: #f5d4bc; }
    .jp-unit-theme-5 { --jp-th-bg: #8edfb8; --jp-th-group: #6ed4a4; --jp-th-kode: #58c894; --jp-th-text: #1a5c3d; --jp-stripe: #eefaf4; --jp-hover: #ddf3e8; --jp-foot: #c8ebd8; }
    .jp-unit-theme-6 { --jp-th-bg: #f0b0bc; --jp-th-group: #e898a8; --jp-th-kode: #e08898; --jp-th-text: #5c2838; --jp-stripe: #fdf0f3; --jp-hover: #f9e2e8; --jp-foot: #f0ccd4; }
    .jp-unit-theme-7 { --jp-th-bg: #e8dc88; --jp-th-group: #ddd070; --jp-th-kode: #d4c860; --jp-th-text: #4a4418; --jp-stripe: #faf8e8; --jp-hover: #f3efce; --jp-foot: #e8e0b0; }

    .jurnal-penjualan-unit-table-wrap.jp-unit-dt-scroll table.dataTable {
        width: 100% !important;
        table-layout: fixed;
    }

    .jurnal-penjualan-unit-table-wrap.jp-unit-dt-scroll .dataTables_scrollHeadInner,
    .jurnal-penjualan-unit-table-wrap.jp-unit-dt-scroll .dataTables_scrollHeadInner table,
    .jurnal-penjualan-unit-table-wrap.jp-unit-dt-scroll .dataTables_scrollBody table,
    .jurnal-penjualan-unit-table-wrap.jp-unit-dt-scroll .dataTables_scrollFootInner,
    .jurnal-penjualan-unit-table-wrap.jp-unit-dt-scroll .dataTables_scrollFootInner table {
        width: 100% !important;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit-special thead tr.jp-cetak-head-row-group th.jp-cetak-head-debet,
    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit-special thead tr.jp-cetak-head-row-group th.jp-cetak-head-kredit,
    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit-special thead tr.jp-cetak-head-row-group th.jp-cetak-head-argo-parent {
        background-color: var(--jp-th-group, #dcc9ad) !important;
        font-weight: 700;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit-special thead tr.jp-cetak-head-row-argo th,
    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit-special thead tr.jp-cetak-head-row-kode th {
        background-color: var(--jp-th-kode, #e0d0bc) !important;
        font-weight: 700;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit-special.jp-format-pu-fc-gose thead tr.jp-cetak-head-row-argo th {
        background-color: var(--jp-th-kode, #e0d0bc) !important;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit-special thead tr.jp-cetak-head-row-label th.jp-cetak-head-amount {
        text-align: right;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit .jp-col-no,
    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit-special tbody td.jp-cetak-col-no {
        text-align: center;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit .jp-col-text,
    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit-special tbody td.jp-cetak-col-text {
        text-align: left;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit .jp-col-konsumen {
        text-align: left;
        white-space: normal;
        word-break: break-word;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit .jp-col-amount,
    .jurnal-penjualan-unit-table-wrap table.dataTable .jp-col-amount,
    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit-special tbody td.jp-cetak-col-amount,
    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit-special tfoot th.jp-cetak-col-amount {
        text-align: right;
        white-space: nowrap;
        overflow: visible;
        text-overflow: clip;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit-special.jp-format-pu-fc-gose thead tr.jp-cetak-head-row-label th.jp-cetak-head-amount {
        white-space: normal;
        line-height: 1.35;
        font-size: 14px;
    }

    .jurnal-penjualan-tab-table-wrap {
        border: 1px solid #ffc107;
        border-radius: 8px;
        padding: 10px;
        background: #f8fff8;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.12);
    }

    .jurnal-penjualan-tab-table-wrap .dataTables_wrapper {
        width: 100%;
        margin: 0;
    }

    .jurnal-penjualan-tab-table-wrap table.display {
        border-collapse: collapse;
        width: 100% !important;
    }

    .jurnal-penjualan-tab-table-wrap table.display thead th {
        background-color: #d4edda !important;
        color: #2d5a3d !important;
        font-weight: 600;
        border: 1px solid #b8dfc4 !important;
        padding: 10px 8px;
        white-space: nowrap;
        vertical-align: middle;
    }

    .jurnal-penjualan-tab-table-wrap table.display tbody td {
        border: 1px solid #dceee2;
        padding: 8px;
        background-color: #ffffff;
        vertical-align: middle;
    }

    .jurnal-penjualan-tab-table-wrap table.display tbody tr:nth-child(even) td {
        background-color: #f4fbf6;
    }

    .jurnal-penjualan-tab-table-wrap table.display tbody tr:hover td {
        background-color: #e8f5ec;
    }

    .jurnal-penjualan-tab-table-wrap table.display tfoot th {
        background-color: #c3e6cb !important;
        color: #2d5a3d !important;
        border: 1px solid #b8dfc4 !important;
        font-weight: bold;
        padding: 10px 8px;
    }

    .jurnal-penjualan-tab-table-wrap .dataTables_scroll {
        border: 1px solid #b8dfc4;
        border-radius: 4px;
        overflow: hidden;
        background: #ffffff;
    }

    .jurnal-penjualan-tab-table-wrap .dataTables_scrollHead {
        background: #d4edda;
    }

    .jurnal-penjualan-tab-table-wrap .dataTables_scrollHeadInner,
    .jurnal-penjualan-tab-table-wrap .dataTables_scrollHeadInner table {
        width: 100% !important;
    }

    .jurnal-penjualan-tab-table-wrap .dataTables_scrollBody {
        border-top: 1px solid #b8dfc4;
    }

    .jurnal-penjualan-tab-table-wrap .dataTables_scrollBody::-webkit-scrollbar,
    .jurnal-penjualan-tab-table-wrap .dataTables_scrollHead::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }

    .jurnal-penjualan-tab-table-wrap .dataTables_scrollBody::-webkit-scrollbar-thumb,
    .jurnal-penjualan-tab-table-wrap .dataTables_scrollHead::-webkit-scrollbar-thumb {
        background: #b8dfc4;
        border-radius: 6px;
    }

    .jurnal-penjualan-tab-table-wrap .dataTables_scrollBody::-webkit-scrollbar-track,
    .jurnal-penjualan-tab-table-wrap .dataTables_scrollHead::-webkit-scrollbar-track {
        background: #eef8f0;
    }

.bb-jn-embed-header{background:linear-gradient(135deg,#e65100 0%,#fb8c00 55%,#ffb74d 100%);color:#fff;border-radius:12px;padding:14px 18px;margin-bottom:12px;box-shadow:0 4px 14px rgba(230,81,0,.25);}
.bb-jn-embed-header h4{margin:0;font-weight:700;font-size:1.15rem;}.bb-jn-embed-header .sub{opacity:.92;font-size:.9rem;margin-top:4px;}
</style>
<div class="bb-jn-embed-header"><h4><i class="fa fa-line-chart"></i> Jurnal Penjualan</h4><div class="sub"><?php echo htmlspecialchars($bulan_label, ENT_QUOTES, 'UTF-8'); ?> &mdash; Baris, kolom, dan per unit (sama seperti halaman jurnal penjualan)</div></div>
                    <div class="card-body">

                        <ul class="nav nav-tabs jurnal-penjualan-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="bbJnTabJurnalPenjualanBarisTab" data-toggle="tab" href="#bbJnTabJurnalPenjualanBaris" role="tab" aria-controls="bbJnTabJurnalPenjualanBaris" aria-selected="true">
                                    Jurnal penjualan (baris)
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="bbJnTabJurnalPenjualanKolomTab" data-toggle="tab" href="#bbJnTabJurnalPenjualanKolom" role="tab" aria-controls="bbJnTabJurnalPenjualanKolom" aria-selected="false">
                                    Jurnal penjualan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="bbJnTabJurnalPenjualanPerUnitTab" data-toggle="tab" href="#bbJnTabJurnalPenjualanPerUnit" role="tab" aria-controls="bbJnTabJurnalPenjualanPerUnit" aria-selected="false">
                                    Jurnal penjualan per unit
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="bbJnTabJurnalPenjualanBaris" role="tabpanel" aria-labelledby="bbJnTabJurnalPenjualanBarisTab">
                                <div class="row mb-2">
                                    <div class="col-12 text-right">
                                        <?php
                                        $url_excel_jurnal_penjualan_baris = site_url('Tbl_penjualan/excel_jurnal_penjualan2_baris?bulan_ns=' . rawurlencode(isset($bulan_ns_selected) ? $bulan_ns_selected : date('Y-m')));
                                        echo anchor($url_excel_jurnal_penjualan_baris, '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Cetak ke Excel', 'class="btn btn-success btn-flat"');
                                        ?>
                                    </div>
                                </div>

                                <div class="jurnal-penjualan-tab-table-wrap">
                                <table id="bbJnTblJurnalPenjualanBaris" class="display nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" style="text-align:center">No</th>
                                            <th rowspan="2" style="text-align:center">TANGGAL</th>
                                            <th rowspan="2" style="text-align:center">Bukti</th>
                                            <th colspan="3" style="text-align:center">KODE</th>
                                            <th rowspan="2" style="text-align:center">Keterangan</th>
                                            <th rowspan="2" class="jp-col-amount" style="text-align:center">debet</th>
                                            <th rowspan="2" class="jp-col-amount" style="text-align:center">Kredit</th>
                                        </tr>
                                        <tr>
                                            <th style="text-align:center">PL</th>
                                            <th style="text-align:center">Ref</th>
                                            <th style="text-align:center">Rek</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $startBaris = 0;
                                        $TOTAL_debet_baris = 0;
                                        $TOTAL_kredit_baris = 0;
                                        $baris_data = isset($Buku_besar_DATA_baris) ? $Buku_besar_DATA_baris : array();
                                        foreach ($baris_data as $list_data) {
                                            $debet_val = isset($list_data->debet) ? (float) $list_data->debet : 0;
                                            $kredit_val = isset($list_data->kredit) ? (float) $list_data->kredit : 0;
                                            if ($debet_val == 0 && $kredit_val == 0) {
                                                continue;
                                            }

                                            $Get_date = isset($list_data->tanggal) ? date("Y-m-d", strtotime($list_data->tanggal)) : '';
                                            $bukti = isset($list_data->nokirim) ? (string) $list_data->nokirim : '';
                                            $pl = isset($list_data->pl) ? (string) $list_data->pl : '';
                                            $ref = isset($list_data->ref) ? (string) $list_data->ref : (isset($list_data->kode) ? (string) $list_data->kode : '');
                                            $rek = isset($list_data->rek_display) ? trim((string) $list_data->rek_display) : trim((string) (isset($list_data->kode_akun) ? $list_data->kode_akun : ''));
                                            $keterangan = isset($list_data->keterangan_display) ? trim((string) $list_data->keterangan_display) : '';
                                            $keterangan_style = ($kredit_val > 0 && $debet_val == 0) ? 'padding-left:4ch;' : '';

                                            $TOTAL_debet_baris += $debet_val;
                                            $TOTAL_kredit_baris += $kredit_val;
                                        ?>
                                            <tr>
                                                <td align="left"><?php echo ++$startBaris; ?></td>
                                                <td align="left"><?php echo $Get_date; ?></td>
                                                <td align="left"><?php echo htmlspecialchars($bukti, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td align="left"><?php echo htmlspecialchars($pl, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td align="left"><?php echo htmlspecialchars($ref, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td align="left"><?php echo htmlspecialchars($rek, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td align="left" style="<?php echo $keterangan_style; ?>"><?php echo htmlspecialchars($keterangan, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td align="right"><?php echo $debet_val != 0 ? number_format($debet_val, 2, ',', '.') : ''; ?></td>
                                                <td align="right"><?php echo $kredit_val != 0 ? number_format($kredit_val, 2, ',', '.') : ''; ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="7" style="text-align:right"></th>
                                            <th style="text-align:right">
                                                <strong><?php echo number_format($TOTAL_debet_baris, 2, ',', '.'); ?></strong>
                                            </th>
                                            <th style="text-align:right">
                                                <strong><?php echo number_format($TOTAL_kredit_baris, 2, ',', '.'); ?></strong>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="bbJnTabJurnalPenjualanKolom" role="tabpanel" aria-labelledby="bbJnTabJurnalPenjualanKolomTab">
                                <div class="row mb-2">
                                    <div class="col-12 text-right">
                                        <?php
                                        $url_excel_jurnal_penjualan = site_url('Tbl_penjualan/excel_jurnal_penjualan2?bulan_ns=' . rawurlencode(isset($bulan_ns_selected) ? $bulan_ns_selected : date('Y-m')));
                                        echo anchor($url_excel_jurnal_penjualan, '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Cetak ke Excel', 'class="btn btn-success btn-flat"');
                                        ?>
                                    </div>
                                </div>

                                <div class="jurnal-penjualan-tab-table-wrap">
                                <!-- <table id="bbJnTglSPOPFreeze" class="table table-striped dt-responsive w-100 table-bordered display nowrap table-hover mb-0" style="width:100%"> -->
                                <table id="bbJnTglSPOPFreeze" class="display nowrap" style="width:100%">
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
                                    <th rowspan="2" style="text-align:center">11301-UU Dagang</th>
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

                                    <th rowspan="3" style="text-align:left" width="10px">No</th>
                                    <th rowspan="3" style="text-align:left" width="10px">TANGGAL</th>
                                    <th rowspan="3" style="text-align:center">No. INVOICE</th>
                                    <th rowspan="3" style="text-align:center">No. PESAN</th>
                                    <th rowspan="3" style="text-align:center">No. KIRIM</th>
                                    <th rowspan="3" style="text-align:center">KONSUMEN</th>

                                    <th colspan="1" style="text-align:center">DEBET</th>
                                    <th colspan="2" style="text-align:center">KREDIT</th>

                                </tr>
                                <tr>
                                    <th class="jp-col-amount" style="text-align:right">11301</th>
                                    <th class="jp-col-amount" style="text-align:right">41101</th>
                                    <th class="jp-col-amount" style="text-align:right">21201</th>
                                </tr>
                                <tr>
                                    <th class="jp-col-amount" style="text-align:right">Piutang</th>
                                    <th class="jp-col-amount" style="text-align:right">Penjualan DPP</th>
                                    <th class="jp-col-amount" style="text-align:right">Utang PPN</th>
                                <!-- <tr>
                                    <th style="text-align:left">11301-UU</th>
                                    <th style="text-align:right">Jumlah</th>
                                </tr> -->

                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                $TOTAL_debet_11301 = 0;
                                $TOTAL_kredit_41101 = 0;
                                $TOTAL_kredit_21201 = 0;

                                $TOTAL_saldo = 0;
                                $GET_KODE_PL = "";

                                foreach ($Buku_besar_DATA_data as $list_data) {




                                    // BARIS KE 1 ----- PERTAMA

                                    if ($list_data->kode_akun == 11301) {

                                        // CEK dengan SPOP yang sama
                                        // // $Get_kode_akun = 11301;
                                        // $this->db->where('kode_akun', $list_data->kode_akun);
                                        // // $this->db->where('uuid_spop', $list_data->uuid_spop);
                                        // $GET_debet_buku_besar_by_kode_akun_by_spop = $this->db->get('sys_kode_akun')->row()->debet;


                                        $Get_date = date("Y-m-d", strtotime($list_data->tanggal));


                                        $Get_kode_akun = 41101;
                                        $this->db->where('kode_akun', $Get_kode_akun);
                                        $this->db->where('nokirim', $list_data->nokirim);
                                        $this->db->where('tanggal', $Get_date);
                                        $GET_DATA_41101_kredit = $this->db->get('buku_besar')->row()->kredit;

                                        $Get_kode_akun = 21201;
                                        $this->db->where('kode_akun', $Get_kode_akun);
                                        $this->db->where('nokirim', $list_data->nokirim);
                                        $this->db->where('tanggal', $Get_date);
                                        $GET_DATA_21201_kredit = $this->db->get('buku_besar')->row()->kredit;



                                ?>

                                        <tr>
                                            <td align="left">
                                                <?php
                                                echo ++$start;
                                                ?>
                                            </td>

                                            <td align="left">
                                                <?php
                                                // echo "TOTAL";
                                                echo $Get_date;
                                                ?>
                                            </td>

                                            <!-- Nomor Invoice -->
                                            <td align="left">
                                                <?php
                                                // echo $list_data->spop;
                                                // echo "Nomor Invoice";
                                                ?>
                                            </td>

                                            <!-- Nomor Pesan -->
                                            <td align="left">
                                                <?php
                                                // echo $list_data->pl;
                                                // echo $list_data->nokirim;
                                                ?>
                                            </td>

                                            <!-- SUPPLIER -->
                                            <td align="left">
                                                <?php
                                                // echo $list_data->supplier;
                                                // $this->db->where('kode_pl', $GET_KODE_PL);
                                                // $GET_DATA_nama_PL = $this->db->get('sys_kode_pl')->row()->keterangan;

                                                echo $list_data->nokirim;
                                                ?>
                                            </td>

                                            <!-- Konsumen -->
                                            <td align="left">
                                                <?php
                                                // echo $list_data->kode_akun;
                                                echo $list_data->konsumen_nama;
                                                ?>
                                            </td>

                                            <!-- debet 11301 -->
                                            <td align="right">
                                                <?php
                                                echo "<font color='black'><strong>" . number_format($list_data->debet, 2, ',', '.') . "</strong></font>";
                                                $TOTAL_debet_11301 = $TOTAL_debet_11301+$list_data->debet;
                
                                                ?>
                                            </td>

                                            <!-- Jumlah kredit 41101 -->
                                            <td align="right">

                                                <?php
                                                // echo $TOTAL_debet_jumlah;
                                                // $TOTAL_debet_jumlah = $TOTAL_debet_jumlah + $list_data->debet;
                                                echo "<font color='black'><strong>" . number_format($GET_DATA_41101_kredit, 2, ',', '.') . "</strong></font>";

                                                $TOTAL_kredit_41101 = $TOTAL_kredit_41101+$GET_DATA_41101_kredit;
                                                

                                                ?>

                                            </td>


                                            <!-- Jumlah Kredit -->
                                            <td align="right">

                                                <?php
                                                // echo $TOTAL_kredit_11301;
                                                // $TOTAL_kredit_11301 = $TOTAL_kredit_11301 + $GET_DATA_kode_akun_kredit;
                                                echo "<font color='black'><strong>" . number_format($GET_DATA_21201_kredit, 2, ',', '.') . "</strong></font>";

                                                $TOTAL_kredit_21201 = $TOTAL_kredit_21201+$GET_DATA_21201_kredit;

                                                ?>

                                            </td>



                                        </tr>

                                    <?php


                                    


                                    }
                                }
                                ?>



                            </tbody>


                            <tfoot>
                                <tr>

                                    <th style="text-align:left" width="10px"></th>

                                    <!-- tanggal -->
                                    <th style="text-align:center"></th>

                                    <!-- no invoice -->
                                    <th style="text-align:center"></th>

                                    <!-- no pesan -->
                                    <th style="text-align:center"></th>

                                    <!-- no kirim -->
                                    <th style="text-align:center"></th>

                                    <!-- konsumen -->
                                    <th style="text-align:right">
                                        <?php
                                        //echo "<font color='blue'><strong>" . number_format($TOTAL_11301_SEMUA + $TOTAL_debet_jumlah_SEMUA, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </th>

                                    <!-- TOTAL DEBET 11301-->
                                    <th style="text-align:right">
                                        <?php
                                        echo "<font color='blue'><strong>" . number_format($TOTAL_debet_11301, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </th>

                                    <!-- TOTAL kredit 41101 -->
                                    <th style="text-align:right">
                                        <?php
                                        echo "<font color='blue'><strong>" . number_format($TOTAL_kredit_41101, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </th>

                                    <!-- kredit 21201 -->
                                    <th style="text-align:right">
                                        <?php
                                        echo "<font color='blue'><strong>" . number_format($TOTAL_kredit_21201, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </th> <!-- TOTAL JUMLAH -->

                                </tr>

                            </tfoot>







                        </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="bbJnTabJurnalPenjualanPerUnit" role="tabpanel" aria-labelledby="bbJnTabJurnalPenjualanPerUnitTab">
                                <?php
                                $jurnal_penjualan_per_unit_data = isset($jurnal_penjualan_per_unit_data) ? $jurnal_penjualan_per_unit_data : array();
                                $unit_index = 0;
                                foreach ($jurnal_penjualan_per_unit_data as $unit_block) {
                                    $unit_row = isset($unit_block['unit']) ? $unit_block['unit'] : null;
                                    $unit_rows = isset($unit_block['rows']) ? $unit_block['rows'] : array();
                                    $uuid_unit = ($unit_row && isset($unit_row->uuid_unit)) ? (string) $unit_row->uuid_unit : '';
                                    $unit_label = '';
                                    if ($unit_row) {
                                        $unit_label = trim((string) (isset($unit_row->nama_unit) ? $unit_row->nama_unit : ''));
                                        if ($unit_label === '') {
                                            $unit_label = trim((string) (isset($unit_row->kode_unit) ? $unit_row->kode_unit : 'Unit'));
                                        }
                                    } else {
                                        $unit_label = 'Unit';
                                    }
                                    $format_type = isset($unit_block['format_type']) ? (string) $unit_block['format_type'] : 'standard';
                                    $is_special_format = in_array($format_type, array('cetak', 'pu_atk', 'pu_fc_gose'), true);
                                    $is_debet_kredit_format = in_array($format_type, array('cetak', 'pu_atk'), true);
                                    $is_fc_gose_format = ($format_type === 'pu_fc_gose');
                                    $kredit_kode_penjualan = ($format_type === 'pu_atk') ? '41116' : '41101';
                                    $label_penjualan = ($format_type === 'pu_atk') ? 'Penjualan ATK' : 'Penjualan DPP';
                                    $table_id = 'bbJnTblJurnalPenjualanPerUnit' . $unit_index;
                                    $url_excel_per_unit = site_url('Tbl_penjualan/excel_jurnal_penjualan2_per_unit?bulan_ns=' . rawurlencode(isset($bulan_ns_selected) ? $bulan_ns_selected : date('Y-m')) . '&uuid_unit=' . rawurlencode($uuid_unit));

                                    $unit_theme_class = 'jp-unit-theme-' . ($unit_index % 8);
                                    $konsumen_display = function ($row) {
                                        return trim((string) (isset($row->konsumen_nama) ? $row->konsumen_nama : ''));
                                    };
                                    $TOTAL_piutang_unit = 0;
                                    $TOTAL_penjualan_unit = 0;
                                    $TOTAL_utang_ppn_unit = 0;
                                    $TOTAL_jumlah_unit = 0;
                                    $TOTAL_selisih_unit = 0;
                                ?>
                                    <div class="jurnal-penjualan-unit-block <?php echo $unit_theme_class; ?>">
                                        <div class="jurnal-penjualan-unit-toolbar">
                                            <div class="jurnal-penjualan-unit-toolbar-spacer"></div>
                                            <div class="jurnal-penjualan-unit-title"><?php echo htmlspecialchars($unit_label, ENT_QUOTES, 'UTF-8'); ?></div>
                                            <div class="jurnal-penjualan-unit-toolbar-excel">
                                                <?php echo anchor($url_excel_per_unit, '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Cetak ke Excel', 'class="btn btn-success btn-flat"'); ?>
                                            </div>
                                        </div>

                                        <div class="jurnal-penjualan-unit-table-wrap <?php echo $unit_theme_class; ?><?php echo $is_special_format ? ' jp-unit-native-scroll' : ''; ?>">
                                        <table id="<?php echo $table_id; ?>" class="tbl-jurnal-penjualan-per-unit<?php echo $is_special_format ? ' tbl-jurnal-penjualan-per-unit-special' : ' display nowrap'; ?><?php echo $is_fc_gose_format ? ' jp-format-pu-fc-gose' : ''; ?>" data-jp-unit-format="<?php echo htmlspecialchars($format_type, ENT_QUOTES, 'UTF-8'); ?>">
                                            <colgroup>
                                                <?php if ($is_fc_gose_format) { ?>
                                                <col style="width:3.8%">
                                                <col style="width:7.2%">
                                                <col style="width:6.8%">
                                                <col style="width:7.2%">
                                                <col style="width:13%">
                                                <col style="width:8.8%">
                                                <col style="width:8.8%">
                                                <col style="width:8.8%">
                                                <col style="width:8.8%">
                                                <col style="width:7.2%">
                                                <col style="width:8.8%">
                                                <col style="width:9%">
                                                <?php } else { ?>
                                                <col style="width:3.9%">
                                                <col style="width:7.4%">
                                                <col style="width:7%">
                                                <col style="width:7.7%">
                                                <col style="width:7%">
                                                <col style="width:14%">
                                                <col style="width:9.1%">
                                                <col style="width:9.1%">
                                                <col style="width:9.1%">
                                                <col style="width:7.4%">
                                                <col style="width:9.1%">
                                                <col style="width:9.1%">
                                                <?php } ?>
                                            </colgroup>
                                            <thead>
                                                <?php if ($is_debet_kredit_format) { ?>
                                                <tr class="jp-cetak-head-row-group">
                                                    <th rowspan="3" data-jp-sort-col="0">No</th>
                                                    <th rowspan="3" data-jp-sort-col="1">Tanggal</th>
                                                    <th rowspan="3" data-jp-sort-col="2">NO INVOICE</th>
                                                    <th rowspan="3" data-jp-sort-col="3">Nomor Pesan</th>
                                                    <th rowspan="3" data-jp-sort-col="4">Nomor Kirim</th>
                                                    <th rowspan="3" data-jp-sort-col="5">KONSUMEN</th>
                                                    <th class="jp-cetak-head-debet">DEBET</th>
                                                    <th colspan="2" class="jp-cetak-head-kredit">KREDIT</th>
                                                    <th rowspan="3" data-jp-sort-col="9">Tanggal Bayar</th>
                                                    <th rowspan="3" data-jp-sort-col="10">Jumlah</th>
                                                    <th rowspan="3" data-jp-sort-col="11">Selisih</th>
                                                </tr>
                                                <tr class="jp-cetak-head-row-kode">
                                                    <th data-jp-sort-col="6">11301</th>
                                                    <th data-jp-sort-col="7"><?php echo htmlspecialchars($kredit_kode_penjualan, ENT_QUOTES, 'UTF-8'); ?></th>
                                                    <th data-jp-sort-col="8">21201</th>
                                                </tr>
                                                <tr class="jp-cetak-head-row-label">
                                                    <th class="jp-cetak-head-amount" data-jp-sort-col="6">Piutang</th>
                                                    <th class="jp-cetak-head-amount" data-jp-sort-col="7"><?php echo htmlspecialchars($label_penjualan, ENT_QUOTES, 'UTF-8'); ?></th>
                                                    <th class="jp-cetak-head-amount" data-jp-sort-col="8">Utang PPN</th>
                                                </tr>
                                                <?php } elseif ($is_fc_gose_format) { ?>
                                                <tr class="jp-cetak-head-row-group">
                                                    <th rowspan="3" data-jp-sort-col="0">No</th>
                                                    <th rowspan="3" data-jp-sort-col="1">Tanggal</th>
                                                    <th colspan="2" class="jp-cetak-head-argo-parent">ARGO</th>
                                                    <th rowspan="3" data-jp-sort-col="4">KONSUMEN</th>
                                                    <th colspan="2" class="jp-cetak-head-debet">DEBET</th>
                                                    <th colspan="2" class="jp-cetak-head-kredit">KREDIT</th>
                                                    <th rowspan="3" data-jp-sort-col="9">Tanggal Bayar</th>
                                                    <th rowspan="3" data-jp-sort-col="10">Jumlah</th>
                                                    <th rowspan="3" data-jp-sort-col="11">Selisih</th>
                                                </tr>
                                                <tr class="jp-cetak-head-row-argo">
                                                    <th rowspan="2" data-jp-sort-col="2">AWAL</th>
                                                    <th rowspan="2" data-jp-sort-col="3">Akhir</th>
                                                    <th data-jp-sort-col="5">52711</th>
                                                    <th data-jp-sort-col="6">52704</th>
                                                    <th data-jp-sort-col="7">41102</th>
                                                    <th data-jp-sort-col="8">21201</th>
                                                </tr>
                                                <tr class="jp-cetak-head-row-label">
                                                    <th class="jp-cetak-head-amount" data-jp-sort-col="5">BOU- Lain lain</th>
                                                    <th class="jp-cetak-head-amount" data-jp-sort-col="6">BOU- Foto Copy &amp; Cetak</th>
                                                    <th class="jp-cetak-head-amount" data-jp-sort-col="7">Penjualan FC Gose</th>
                                                    <th class="jp-cetak-head-amount" data-jp-sort-col="8">Utang PPN</th>
                                                </tr>
                                                <?php } else { ?>
                                                <tr>
                                                    <th data-jp-sort-col="0">No</th>
                                                    <th data-jp-sort-col="1">Tanggal</th>
                                                    <th data-jp-sort-col="2">NO INVOICE</th>
                                                    <th data-jp-sort-col="3">Nomor Pesan</th>
                                                    <th data-jp-sort-col="4">Nomor Kirim</th>
                                                    <th data-jp-sort-col="5">KONSUMEN</th>
                                                    <th class="jp-col-amount" data-jp-sort-col="6">Piutang</th>
                                                    <th class="jp-col-amount" data-jp-sort-col="7">Penjualan</th>
                                                    <th class="jp-col-amount" data-jp-sort-col="8">Utang PPN</th>
                                                    <th data-jp-sort-col="9">Tanggal Bayar</th>
                                                    <th class="jp-col-amount" data-jp-sort-col="10">Jumlah</th>
                                                    <th class="jp-col-amount" data-jp-sort-col="11">Selisih</th>
                                                </tr>
                                                <?php } ?>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $start_per_unit = 0;
                                                foreach ($unit_rows as $list_data) {
                                                    $piutang_val = isset($list_data->piutang) ? (float) $list_data->piutang : 0;
                                                    $penjualan_val = isset($list_data->penjualan) ? (float) $list_data->penjualan : 0;
                                                    $utang_ppn_val = isset($list_data->utang_ppn) ? (float) $list_data->utang_ppn : 0;
                                                    $jumlah_bayar_val = isset($list_data->jumlah_bayar) ? (float) $list_data->jumlah_bayar : 0;
                                                    $selisih_val = isset($list_data->selisih) ? (float) $list_data->selisih : 0;

                                                    $TOTAL_piutang_unit += $piutang_val;
                                                    $TOTAL_penjualan_unit += $penjualan_val;
                                                    $TOTAL_utang_ppn_unit += $utang_ppn_val;
                                                    $TOTAL_jumlah_unit += $jumlah_bayar_val;
                                                    $TOTAL_selisih_unit += $selisih_val;
                                                ?>
                                                    <tr>
                                                        <?php if ($is_fc_gose_format) { ?>
                                                        <td class="jp-cetak-col-no"><?php echo ++$start_per_unit; ?></td>
                                                        <td class="jp-cetak-col-text"><?php echo htmlspecialchars(isset($list_data->tgl_jual_display) ? $list_data->tgl_jual_display : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="jp-cetak-col-text"><?php echo htmlspecialchars(isset($list_data->no_invoice) ? $list_data->no_invoice : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="jp-cetak-col-text"><?php echo htmlspecialchars(isset($list_data->nmrpesan) ? $list_data->nmrpesan : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="jp-cetak-col-text jp-col-konsumen"><?php echo htmlspecialchars($konsumen_display($list_data), ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="jp-cetak-col-amount"></td>
                                                        <td class="jp-cetak-col-amount"></td>
                                                        <td class="jp-cetak-col-amount"><?php echo number_format($penjualan_val, 2, ',', '.'); ?></td>
                                                        <td class="jp-cetak-col-amount"><?php echo number_format($utang_ppn_val, 2, ',', '.'); ?></td>
                                                        <td class="jp-cetak-col-text"><?php echo htmlspecialchars(isset($list_data->tgl_bayar_display) ? $list_data->tgl_bayar_display : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="jp-cetak-col-amount"><?php echo $jumlah_bayar_val != 0 ? number_format($jumlah_bayar_val, 2, ',', '.') : ''; ?></td>
                                                        <td class="jp-cetak-col-amount"><?php echo $selisih_val != 0 ? number_format($selisih_val, 2, ',', '.') : ''; ?></td>
                                                        <?php } elseif ($is_debet_kredit_format) { ?>
                                                        <td class="jp-cetak-col-no"><?php echo ++$start_per_unit; ?></td>
                                                        <td class="jp-cetak-col-text"><?php echo htmlspecialchars(isset($list_data->tgl_jual_display) ? $list_data->tgl_jual_display : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="jp-cetak-col-text"><?php echo htmlspecialchars(isset($list_data->no_invoice) ? $list_data->no_invoice : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="jp-cetak-col-text"><?php echo htmlspecialchars(isset($list_data->nmrpesan) ? $list_data->nmrpesan : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="jp-cetak-col-text"><?php echo htmlspecialchars(isset($list_data->nmrkirim) ? $list_data->nmrkirim : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="jp-cetak-col-text jp-col-konsumen"><?php echo htmlspecialchars($konsumen_display($list_data), ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="jp-cetak-col-amount"><?php echo number_format($piutang_val, 2, ',', '.'); ?></td>
                                                        <td class="jp-cetak-col-amount"><?php echo number_format($penjualan_val, 2, ',', '.'); ?></td>
                                                        <td class="jp-cetak-col-amount"><?php echo number_format($utang_ppn_val, 2, ',', '.'); ?></td>
                                                        <td class="jp-cetak-col-text"><?php echo htmlspecialchars(isset($list_data->tgl_bayar_display) ? $list_data->tgl_bayar_display : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="jp-cetak-col-amount"><?php echo $jumlah_bayar_val != 0 ? number_format($jumlah_bayar_val, 2, ',', '.') : ''; ?></td>
                                                        <td class="jp-cetak-col-amount"><?php echo $selisih_val != 0 ? number_format($selisih_val, 2, ',', '.') : ''; ?></td>
                                                        <?php } else { ?>
                                                        <td class="jp-col-no"><?php echo ++$start_per_unit; ?></td>
                                                        <td class="jp-col-text"><?php echo htmlspecialchars(isset($list_data->tgl_jual_display) ? $list_data->tgl_jual_display : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="jp-col-text"><?php echo htmlspecialchars(isset($list_data->no_invoice) ? $list_data->no_invoice : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="jp-col-text"><?php echo htmlspecialchars(isset($list_data->nmrpesan) ? $list_data->nmrpesan : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="jp-col-text"><?php echo htmlspecialchars(isset($list_data->nmrkirim) ? $list_data->nmrkirim : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="jp-col-konsumen"><?php echo htmlspecialchars($konsumen_display($list_data), ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="jp-col-amount"><?php echo number_format($piutang_val, 2, ',', '.'); ?></td>
                                                        <td class="jp-col-amount"><?php echo number_format($penjualan_val, 2, ',', '.'); ?></td>
                                                        <td class="jp-col-amount"><?php echo number_format($utang_ppn_val, 2, ',', '.'); ?></td>
                                                        <td class="jp-col-text"><?php echo htmlspecialchars(isset($list_data->tgl_bayar_display) ? $list_data->tgl_bayar_display : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="jp-col-amount"><?php echo $jumlah_bayar_val != 0 ? number_format($jumlah_bayar_val, 2, ',', '.') : ''; ?></td>
                                                        <td class="jp-col-amount"><?php echo $selisih_val != 0 ? number_format($selisih_val, 2, ',', '.') : ''; ?></td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="<?php echo $is_fc_gose_format ? '5' : '6'; ?>" style="text-align:right">TOTAL</th>
                                                    <?php if ($is_fc_gose_format) { ?>
                                                    <th class="jp-col-amount jp-cetak-col-amount"></th>
                                                    <th class="jp-col-amount jp-cetak-col-amount"></th>
                                                    <th class="jp-col-amount jp-cetak-col-amount"><strong><?php echo number_format($TOTAL_penjualan_unit, 2, ',', '.'); ?></strong></th>
                                                    <th class="jp-col-amount jp-cetak-col-amount"><strong><?php echo number_format($TOTAL_utang_ppn_unit, 2, ',', '.'); ?></strong></th>
                                                    <th class="jp-cetak-col-text"></th>
                                                    <th class="jp-col-amount jp-cetak-col-amount"><strong><?php echo number_format($TOTAL_jumlah_unit, 2, ',', '.'); ?></strong></th>
                                                    <th class="jp-col-amount jp-cetak-col-amount"><strong><?php echo number_format($TOTAL_selisih_unit, 2, ',', '.'); ?></strong></th>
                                                    <?php } elseif ($is_debet_kredit_format) { ?>
                                                    <th class="jp-col-amount jp-cetak-col-amount"><strong><?php echo number_format($TOTAL_piutang_unit, 2, ',', '.'); ?></strong></th>
                                                    <th class="jp-col-amount jp-cetak-col-amount"><strong><?php echo number_format($TOTAL_penjualan_unit, 2, ',', '.'); ?></strong></th>
                                                    <th class="jp-col-amount jp-cetak-col-amount"><strong><?php echo number_format($TOTAL_utang_ppn_unit, 2, ',', '.'); ?></strong></th>
                                                    <th></th>
                                                    <th class="jp-col-amount jp-cetak-col-amount"><strong><?php echo number_format($TOTAL_jumlah_unit, 2, ',', '.'); ?></strong></th>
                                                    <th class="jp-col-amount jp-cetak-col-amount"><strong><?php echo number_format($TOTAL_selisih_unit, 2, ',', '.'); ?></strong></th>
                                                    <?php } else { ?>
                                                    <th class="jp-col-amount"><strong><?php echo number_format($TOTAL_piutang_unit, 2, ',', '.'); ?></strong></th>
                                                    <th class="jp-col-amount"><strong><?php echo number_format($TOTAL_penjualan_unit, 2, ',', '.'); ?></strong></th>
                                                    <th class="jp-col-amount"><strong><?php echo number_format($TOTAL_utang_ppn_unit, 2, ',', '.'); ?></strong></th>
                                                    <th></th>
                                                    <th class="jp-col-amount"><strong><?php echo number_format($TOTAL_jumlah_unit, 2, ',', '.'); ?></strong></th>
                                                    <th class="jp-col-amount"><strong><?php echo number_format($TOTAL_selisih_unit, 2, ',', '.'); ?></strong></th>
                                                    <?php } ?>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        </div>
                                    </div>
                                <?php
                                    $unit_index++;
                                }
                                ?>
                            </div>
                        </div>

<script>
function bbJnInitJurnalPenjualanEmbed() {
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

        function updateFooterTotalsBaris(api) {
            var totalDebet = 0;
            var totalKredit = 0;

            api.rows({
                search: 'applied'
            }).every(function() {
                var rowData = this.data();
                totalDebet += parseNominal(rowData[7]);
                totalKredit += parseNominal(rowData[8]);
            });

            $(api.column(7).footer()).html("<strong>" + formatNominal(totalDebet) + "</strong>");
            $(api.column(8).footer()).html("<strong>" + formatNominal(totalKredit) + "</strong>");
        }

        function updateFooterTotals(api) {
            var totalDebet = 0;
            var totalKredit41101 = 0;
            var totalKredit21201 = 0;

            api.rows({
                search: 'applied'
            }).every(function() {
                var rowData = this.data();
                totalDebet += parseNominal(rowData[6]);
                totalKredit41101 += parseNominal(rowData[7]);
                totalKredit21201 += parseNominal(rowData[8]);
            });

            $(api.column(6).footer()).html("<font color='blue'><strong>" + formatNominal(totalDebet) + "</strong></font>");
            $(api.column(7).footer()).html("<font color='blue'><strong>" + formatNominal(totalKredit41101) + "</strong></font>");
            $(api.column(8).footer()).html("<font color='blue'><strong>" + formatNominal(totalKredit21201) + "</strong></font>");
        }

        function updateFooterTotalsPerUnit(api) {
            var formatType = $(api.table().node()).data('jp-unit-format') || 'standard';
            var colPiutang = 6;
            var colPenjualan = 7;
            var colUtangPpn = 8;
            var colJumlah = 10;
            var colSelisih = 11;

            if (formatType === 'pu_fc_gose') {
                colPenjualan = 7;
                colUtangPpn = 8;
                colJumlah = 10;
                colSelisih = 11;
            }

            var totalPiutang = 0;
            var totalPenjualan = 0;
            var totalUtangPpn = 0;
            var totalJumlah = 0;
            var totalSelisih = 0;

            api.rows({
                search: 'applied'
            }).every(function() {
                var rowData = this.data();
                if (formatType !== 'pu_fc_gose') {
                    totalPiutang += parseNominal(rowData[colPiutang]);
                }
                totalPenjualan += parseNominal(rowData[colPenjualan]);
                totalUtangPpn += parseNominal(rowData[colUtangPpn]);
                totalJumlah += parseNominal(rowData[colJumlah]);
                totalSelisih += parseNominal(rowData[colSelisih]);
            });

            if (formatType !== 'pu_fc_gose') {
                $(api.column(colPiutang).footer()).html("<strong>" + formatNominal(totalPiutang) + "</strong>");
            }
            $(api.column(colPenjualan).footer()).html("<strong>" + formatNominal(totalPenjualan) + "</strong>");
            $(api.column(colUtangPpn).footer()).html("<strong>" + formatNominal(totalUtangPpn) + "</strong>");
            $(api.column(colJumlah).footer()).html("<strong>" + formatNominal(totalJumlah) + "</strong>");
            $(api.column(colSelisih).footer()).html("<strong>" + formatNominal(totalSelisih) + "</strong>");
        }

        if (!$.fn.dataTable.ext.type.order['locale-num-pre']) {
            $.fn.dataTable.ext.type.order['locale-num-pre'] = function(data) {
                return parseNominal(data);
            };
            $.fn.dataTable.ext.type.order['locale-num-asc'] = function(a, b) {
                return ((a < b) ? -1 : ((a > b) ? 1 : 0));
            };
            $.fn.dataTable.ext.type.order['locale-num-desc'] = function(a, b) {
                return ((a < b) ? 1 : ((a > b) ? -1 : 0));
            };
        }

        var jpPerUnitAmountColsStandard = [6, 7, 8, 10, 11];
        var jpPerUnitAmountColsFcGose = [7, 8, 10, 11];
        var jpPerUnitColumnWidths = [
            '3.9%', '7.4%', '7%', '7.7%', '7%', '14%',
            '9.1%', '9.1%', '9.1%', '7.4%', '9.1%', '9.1%'
        ];
        var jpPerUnitColumnWidthsFcGose = [
            '3.8%', '7.2%', '6.8%', '7.2%', '13%',
            '8.8%', '8.8%', '8.8%', '8.8%', '7.2%', '8.8%', '9%'
        ];

        function parseDateSortValue(text) {
            var value = $.trim(text);
            var isoMatch = value.match(/^(\d{4})-(\d{2})-(\d{2})$/);
            if (isoMatch) {
                return parseInt(isoMatch[1] + isoMatch[2] + isoMatch[3], 10);
            }

            var idMatch = value.match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/);
            if (idMatch) {
                var dd = ('0' + idMatch[1]).slice(-2);
                var mm = ('0' + idMatch[2]).slice(-2);
                return parseInt(idMatch[3] + mm + dd, 10);
            }

            return value.toLowerCase();
        }

        function isPerUnitAmountColumn(formatType, colIndex) {
            var cols = (formatType === 'pu_fc_gose') ? jpPerUnitAmountColsFcGose : jpPerUnitAmountColsStandard;
            return cols.indexOf(colIndex) !== -1;
        }

        function isPerUnitDateColumn(formatType, colIndex) {
            if (colIndex === 1) {
                return true;
            }
            if (formatType === 'pu_fc_gose') {
                return colIndex === 9;
            }
            return colIndex === 9;
        }

        function getNativeSortValue(text, colIndex, options) {
            options = options || {};
            var amountCols = options.amountCols || [];
            var dateCols = options.dateCols || [];

            if (amountCols.indexOf(colIndex) !== -1) {
                return parseNominal(text);
            }
            if (dateCols.indexOf(colIndex) !== -1) {
                return parseDateSortValue(text);
            }
            if (colIndex === 0) {
                var noVal = parseInt($.trim(text), 10);
                return isNaN(noVal) ? 0 : noVal;
            }

            return $.trim(text).toLowerCase();
        }

        function initNativeTableSort($table, options) {
            options = options || {};
            var amountCols = options.amountCols || [];
            var dateCols = options.dateCols || [];
            var formatType = options.formatType || ($table.data('jp-unit-format') || 'standard');

            $table.off('click.jpNativeSort', 'thead th[data-jp-sort-col]');
            $table.on('click.jpNativeSort', 'thead th[data-jp-sort-col]', function() {
                var $th = $(this);
                var colIndex = parseInt($th.attr('data-jp-sort-col'), 10);
                if (isNaN(colIndex)) {
                    return;
                }

                var lastCol = $table.data('jp-sort-col-active');
                var lastDir = $table.data('jp-sort-dir-active');
                var asc = (lastCol === colIndex) ? (lastDir !== 'asc') : true;
                var nextDir = asc ? 'asc' : 'desc';

                $table.data('jp-sort-col-active', colIndex);
                $table.data('jp-sort-dir-active', nextDir);
                $table.find('thead th[data-jp-sort-col]').removeClass('jp-sort-asc jp-sort-desc');
                $table.find('thead th[data-jp-sort-col="' + colIndex + '"]').addClass(asc ? 'jp-sort-asc' : 'jp-sort-desc');

                var sortOptions = {
                    amountCols: amountCols.length ? amountCols : (formatType ? (function() {
                        var cols = [];
                        $table.find('thead th[data-jp-sort-col]').each(function() {
                            var idx = parseInt($(this).attr('data-jp-sort-col'), 10);
                            if (!isNaN(idx) && isPerUnitAmountColumn(formatType, idx) && cols.indexOf(idx) === -1) {
                                cols.push(idx);
                            }
                        });
                        return cols;
                    })() : []),
                    dateCols: dateCols.length ? dateCols : (formatType ? (function() {
                        var cols = [];
                        $table.find('thead th[data-jp-sort-col]').each(function() {
                            var idx = parseInt($(this).attr('data-jp-sort-col'), 10);
                            if (!isNaN(idx) && isPerUnitDateColumn(formatType, idx) && cols.indexOf(idx) === -1) {
                                cols.push(idx);
                            }
                        });
                        return cols;
                    })() : [1])
                };

                var $tbody = $table.find('tbody');
                var rows = $tbody.find('tr').get();

                rows.sort(function(rowA, rowB) {
                    var aText = $(rowA).children('td').eq(colIndex).text().trim();
                    var bText = $(rowB).children('td').eq(colIndex).text().trim();
                    var aVal = getNativeSortValue(aText, colIndex, sortOptions);
                    var bVal = getNativeSortValue(bText, colIndex, sortOptions);

                    if (aVal < bVal) {
                        return asc ? -1 : 1;
                    }
                    if (aVal > bVal) {
                        return asc ? 1 : -1;
                    }
                    return 0;
                });

                $.each(rows, function(_, row) {
                    $tbody.append(row);
                });
            });
        }

        function initNativePerUnitTableSort($table) {
            var formatType = $table.data('jp-unit-format') || 'standard';
            var amountCols = (formatType === 'pu_fc_gose') ? jpPerUnitAmountColsFcGose : jpPerUnitAmountColsStandard;
            var dateCols = (formatType === 'pu_fc_gose') ? [1, 9] : [1, 9];

            initNativeTableSort($table, {
                formatType: formatType,
                amountCols: amountCols,
                dateCols: dateCols
            });
        }

        function getPerUnitDataTableOptions(formatType) {
            var amountCols = (formatType === 'pu_fc_gose') ? jpPerUnitAmountColsFcGose : jpPerUnitAmountColsStandard;
            var columnWidths = (formatType === 'pu_fc_gose') ? jpPerUnitColumnWidthsFcGose : jpPerUnitColumnWidths;

            return {
                paging: false,
                info: false,
                ordering: true,
                order: [],
                orderCellsTop: false,
                autoWidth: false,
                scrollX: true,
                scrollY: "min(52vh, 420px)",
                scrollCollapse: true,
                columnDefs: [
                    { targets: amountCols, type: 'locale-num', className: 'jp-col-amount' },
                    { targets: [0], className: 'jp-col-no', type: 'num' },
                    { targets: [5], className: 'jp-col-konsumen' },
                    { targets: '_all', orderable: true }
                ],
                columns: $.map(columnWidths, function(width) {
                    return { width: width };
                }),
                footerCallback: function() {
                    updateFooterTotalsPerUnit(this.api());
                },
                initComplete: function() {
                    var api = this.api();
                    api.columns.adjust();
                    updateFooterTotalsPerUnit(api);
                }
            };
        }

        function adjustPerUnitTable($table) {
            var $wrap = $table.closest('.jurnal-penjualan-unit-table-wrap');
            var isSpecial = $table.hasClass('tbl-jurnal-penjualan-per-unit-special');

            if (isSpecial) {
                return;
            }

            if ($.fn.DataTable.isDataTable($table)) {
                var api = $table.DataTable();
                api.columns.adjust();
                updateFooterTotalsPerUnit(api);
            }
        }

        function initJurnalPenjualanPerUnitTables() {
            $('.tbl-jurnal-penjualan-per-unit').each(function() {
                var $table = $(this);
                var $wrap = $table.closest('.jurnal-penjualan-unit-table-wrap');
                var formatType = $table.data('jp-unit-format') || 'standard';
                var isSpecial = $table.hasClass('tbl-jurnal-penjualan-per-unit-special');

                if (isSpecial) {
                    $wrap.removeClass('jp-unit-dt-scroll').addClass('jp-unit-native-scroll');
                    if ($.fn.DataTable.isDataTable($table)) {
                        $table.DataTable().destroy();
                    }
                    initNativePerUnitTableSort($table);
                    return;
                }

                $wrap.removeClass('jp-unit-native-scroll').addClass('jp-unit-dt-scroll');

                if ($.fn.DataTable.isDataTable($table)) {
                    adjustPerUnitTable($table);
                    return;
                }

                $table.DataTable(getPerUnitDataTableOptions(formatType));
            });
        }

        var bbJnTableJurnalPenjualanBaris;
        if ($.fn.DataTable.isDataTable('#bbJnTblJurnalPenjualanBaris')) {
            bbJnTableJurnalPenjualanBaris = $('#bbJnTblJurnalPenjualanBaris').DataTable();
        } else {
            bbJnTableJurnalPenjualanBaris = $('#bbJnTblJurnalPenjualanBaris').DataTable({
                "scrollY": 900,
                "scrollX": true,
                "paging": false,
                "info": false,
                "footerCallback": function() {
                    var api = this.api();
                    updateFooterTotalsBaris(api);
                }
            });
        }

        updateFooterTotalsBaris(bbJnTableJurnalPenjualanBaris);

        var bbJnTableJurnalPenjualan;
        if ($.fn.DataTable.isDataTable('#bbJnTglSPOPFreeze')) {
            bbJnTableJurnalPenjualan = $('#bbJnTglSPOPFreeze').DataTable();
        } else {
            bbJnTableJurnalPenjualan = $('#bbJnTglSPOPFreeze').DataTable({
                "scrollY": 900,
                "scrollX": true,
                "paging": false,
                "info": false,
                "footerCallback": function() {
                    var api = this.api();
                    updateFooterTotals(api);
                }
            });
        }

        updateFooterTotals(bbJnTableJurnalPenjualan);

        initJurnalPenjualanPerUnitTables();

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            if (bbJnTableJurnalPenjualanBaris) {
                bbJnTableJurnalPenjualanBaris.columns.adjust();
                updateFooterTotalsBaris(bbJnTableJurnalPenjualanBaris);
            }
            if (bbJnTableJurnalPenjualan) {
                bbJnTableJurnalPenjualan.columns.adjust();
                updateFooterTotals(bbJnTableJurnalPenjualan);
            }
            if ($(e.target).attr('href') === '#bbJnTabJurnalPenjualanPerUnit') {
                initJurnalPenjualanPerUnitTables();
                setTimeout(function() {
                    $('.tbl-jurnal-penjualan-per-unit').each(function() {
                        adjustPerUnitTable($(this));
                    });
                }, 50);
            }
        });

        
window.bbJnInitJurnalPenjualanEmbed = bbJnInitJurnalPenjualanEmbed;
</script>
</div>
