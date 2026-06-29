<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Buku Kas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('Dashboard'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Lap Buku Kas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <?php
        $Get_month_from_date = (int) $month_selected;
        ?>

        <div class="box box-warning box-solid">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <strong>LAPORAN BUKU KAS</strong>
                                <?php echo htmlspecialchars($bulan_label, ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                            <div class="col-md-4">
                                <div class="lap-jurnal-kas-month-filter">
                                    <input type="month" class="form-control" id="bulan_ns_lap" name="bulan_ns_lap"
                                        value="<?php echo htmlspecialchars($bulan_ns_value, ENT_QUOTES, 'UTF-8'); ?>"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4 text-right">
                                <?php if (!empty($is_published)) { ?>
                                <a href="<?php echo htmlspecialchars($url_lap_jurnal_kas_excel, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-success" id="btn-lap-jurnal-kas-excel">
                                    <i class="fa fa-file-excel-o"></i> Cetak Buku Kas Ke Excel
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="card-body lap-jk-card-body">
                        <?php if (!empty($not_published)) { ?>
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-info-circle"></i>
                            Laporan Buku Kas <strong><?php echo htmlspecialchars($bulan_label, ENT_QUOTES, 'UTF-8'); ?></strong> belum dipublish.
                            Silakan publish dari halaman <strong>Jurnal Kas → Compare Data Manual - Online (Tab 2)</strong> terlebih dahulu.
                        </div>
                        <?php } ?>
                        <div id="lap-jurnal-kas-table-wrap" class="lap-jk-dt-box">
                            <div class="lap-jk-dt-responsive">
                                <table id="lapJurnalKasMainTable" class="table table-bordered table-striped jurnal-kas-grid-table mb-0 w-100">
                                    <colgroup>
                                        <col class="lap-jk-w-no">
                                        <col class="lap-jk-w-tanggal">
                                        <col class="lap-jk-w-bukti">
                                        <col class="lap-jk-w-keterangan">
                                        <col class="lap-jk-w-debet">
                                        <col class="lap-jk-w-kredit">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Bukti</th>
                                            <th>Keterangan</th>
                                            <th>Debet</th>
                                            <th>Kredit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($not_published)) { ?>
                                            <tr class="lap-jk-empty-row">
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    Data belum tersedia — bulan ini belum dipublish ke Laporan Buku Kas.
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php foreach ((array) $report_rows as $row) {
                                            $tanggal = isset($row['tanggal']) ? (string) $row['tanggal'] : '';
                                            $tanggal_order = '';
                                            if (preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $tanggal, $m_tgl)) {
                                                $tanggal_order = $m_tgl[3] . $m_tgl[2] . $m_tgl[1];
                                            }
                                            $debet_raw = ($row['debet'] !== null && $row['debet'] !== '') ? (float) $row['debet'] : 0;
                                            $kredit_raw = ($row['kredit'] !== null && $row['kredit'] !== '') ? (float) $row['kredit'] : 0;
                                            $row_type = isset($row['type']) ? (string) $row['type'] : 'data';
                                        ?>
                                            <tr data-row-type="<?php echo htmlspecialchars($row_type, ENT_QUOTES, 'UTF-8'); ?>" data-debet="<?php echo $debet_raw; ?>" data-kredit="<?php echo $kredit_raw; ?>">
                                                <td data-order="<?php echo isset($row['no']) ? (int) $row['no'] : 0; ?>"><?php echo isset($row['no']) ? (int) $row['no'] : ''; ?></td>
                                                <td data-order="<?php echo htmlspecialchars($tanggal_order, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($tanggal, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(isset($row['bukti']) ? $row['bukti'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(isset($row['keterangan']) ? $row['keterangan'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-right" data-order="<?php echo $debet_raw; ?>">
                                                    <?php
                                                    if ($row['debet'] !== null && $row['debet'] !== '') {
                                                        echo number_format((float) $row['debet'], 2, ',', '.');
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-right" data-order="<?php echo $kredit_raw; ?>">
                                                    <?php
                                                    if ($row['kredit'] !== null && $row['kredit'] !== '') {
                                                        echo number_format((float) $row['kredit'], 2, ',', '.');
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if (!empty($is_published)) { ?>
                            <div class="lap-jk-summary-scroll" id="lap-jk-summary-scroll">
                            <div class="jurnal-kas-summary-wrap lap-jk-summary-wrap">
                                <table class="table table-bordered jurnal-kas-grid-table jurnal-kas-summary-table lap-jk-summary-table mb-0">
                                    <colgroup>
                                        <col class="lap-jk-w-no">
                                        <col class="lap-jk-w-tanggal">
                                        <col class="lap-jk-w-bukti">
                                        <col class="lap-jk-w-keterangan">
                                        <col class="lap-jk-w-debet">
                                        <col class="lap-jk-w-kredit">
                                    </colgroup>
                                    <tbody>
                                        <tr class="jk-summary-row">
                                            <td colspan="4" class="jk-summary-label text-center font-weight-bold">JUMLAH DEBET / KREDIT</td>
                                            <td class="text-right font-weight-bold" id="lap-jk-summary-total-debet"><?php echo number_format((float) $TOTAL_debet, 2, ',', '.'); ?></td>
                                            <td class="text-right font-weight-bold" id="lap-jk-summary-total-kredit"><?php echo number_format((float) $TOTAL_kredit, 2, ',', '.'); ?></td>
                                        </tr>
                                        <tr class="jk-summary-row jk-summary-row-saldo">
                                            <td colspan="4" class="jk-summary-label text-center font-weight-bold">Saldo akhir Kas Bulan <?php echo jurnal_kas_bulan_teks($Get_month_from_date); ?></td>
                                            <td class="text-right font-weight-bold"></td>
                                            <td class="text-right font-weight-bold" id="lap-jk-summary-saldo-akhir"><?php echo number_format((float) $SALDO_AKHIR, 2, ',', '.'); ?></td>
                                        </tr>
                                        <tr class="jk-summary-row jk-summary-row-last">
                                            <td colspan="4" class="jk-summary-label text-center font-weight-bold">JUMLAH SEIMBANG</td>
                                            <td class="text-right font-weight-bold" id="lap-jk-summary-seimbang-debet">
                                                <?php
                                                if ($SALDO_AKHIR >= 0) {
                                                    echo number_format((float) $TOTAL_debet, 2, ',', '.');
                                                } else {
                                                    echo number_format((float) $SALDO_AKHIR, 2, ',', '.');
                                                }
                                                ?>
                                            </td>
                                            <td class="text-right font-weight-bold" id="lap-jk-summary-seimbang-kredit">
                                                <?php
                                                if ($SALDO_AKHIR >= 0) {
                                                    echo number_format((float) $TOTAL_debet, 2, ',', '.');
                                                } else {
                                                    echo number_format((float) $SALDO_AKHIR, 2, ',', '.');
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style type="text/css">
    #lap-jurnal-kas-table-wrap.lap-jk-dt-box {
        --lap-jk-font-base: clamp(14px, 0.55vw + 12px, 30px);
        --lap-jk-font-control: clamp(13px, 0.5vw + 11px, 28px);
        --lap-jk-pad-y: clamp(8px, 0.45vw + 6px, 16px);
        --lap-jk-pad-x: clamp(8px, 0.55vw + 6px, 18px);
        --lap-jk-viewport-h: clamp(320px, calc(100vh - 280px), 900px);
        --lap-jk-col-no: clamp(52px, 4.5vw, 80px);
        --lap-jk-col-tanggal: clamp(130px, 11vw, 280px);
        --lap-jk-col-bukti: clamp(110px, 9vw, 240px);
        --lap-jk-col-keterangan: clamp(220px, 22vw, 520px);
        --lap-jk-col-debet: clamp(150px, 13vw, 340px);
        --lap-jk-col-kredit: clamp(150px, 13vw, 340px);
        border: 2px solid #ffe082;
        border-radius: 8px;
        overflow: visible;
        background: #fff;
        box-shadow: 0 1px 4px rgba(255, 193, 7, 0.15);
        padding: 0;
    }
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_scroll {
        border-bottom: 1px solid #dee2e6;
    }
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_scrollHead {
        overflow: hidden !important;
        background: #fff;
    }
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_scrollBody {
        max-height: var(--lap-jk-viewport-h) !important;
        overflow-x: auto !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch;
    }
    #lap-jurnal-kas-table-wrap .lap-jk-summary-scroll {
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        border-top: 2px solid #ffe082;
    }
    #lap-jurnal-kas-table-wrap .lap-jk-dt-responsive {
        width: 100%;
    }
    #lap-jurnal-kas-table-wrap .jurnal-kas-grid-table {
        table-layout: auto;
        width: 100%;
        min-width: calc(
            var(--lap-jk-col-no) + var(--lap-jk-col-tanggal) + var(--lap-jk-col-bukti) +
            var(--lap-jk-col-keterangan) + var(--lap-jk-col-debet) + var(--lap-jk-col-kredit)
        );
        font-size: var(--lap-jk-font-base);
        color: #212529;
        border-color: #dee2e6 !important;
    }
    #lap-jurnal-kas-table-wrap col.lap-jk-w-no { width: var(--lap-jk-col-no); min-width: var(--lap-jk-col-no); }
    #lap-jurnal-kas-table-wrap col.lap-jk-w-tanggal { width: var(--lap-jk-col-tanggal); min-width: var(--lap-jk-col-tanggal); }
    #lap-jurnal-kas-table-wrap col.lap-jk-w-bukti { width: var(--lap-jk-col-bukti); min-width: var(--lap-jk-col-bukti); }
    #lap-jurnal-kas-table-wrap col.lap-jk-w-keterangan { width: var(--lap-jk-col-keterangan); min-width: var(--lap-jk-col-keterangan); }
    #lap-jurnal-kas-table-wrap col.lap-jk-w-debet { width: var(--lap-jk-col-debet); min-width: var(--lap-jk-col-debet); }
    #lap-jurnal-kas-table-wrap col.lap-jk-w-kredit { width: var(--lap-jk-col-kredit); min-width: var(--lap-jk-col-kredit); }
    #lap-jurnal-kas-table-wrap table.dataTable thead th:nth-child(1),
    #lap-jurnal-kas-table-wrap table.dataTable tbody td:nth-child(1) {
        min-width: var(--lap-jk-col-no);
        width: var(--lap-jk-col-no);
        white-space: nowrap;
        text-align: center;
    }
    #lap-jurnal-kas-table-wrap table.dataTable thead th:nth-child(2),
    #lap-jurnal-kas-table-wrap table.dataTable tbody td:nth-child(2) {
        min-width: var(--lap-jk-col-tanggal);
        white-space: nowrap;
    }
    #lap-jurnal-kas-table-wrap table.dataTable thead th:nth-child(3),
    #lap-jurnal-kas-table-wrap table.dataTable tbody td:nth-child(3) {
        min-width: var(--lap-jk-col-bukti);
        white-space: nowrap;
    }
    #lap-jurnal-kas-table-wrap table.dataTable thead th:nth-child(4),
    #lap-jurnal-kas-table-wrap table.dataTable tbody td:nth-child(4) {
        min-width: var(--lap-jk-col-keterangan);
        white-space: normal;
        word-wrap: break-word;
        overflow-wrap: anywhere;
        line-height: 1.35;
    }
    #lap-jurnal-kas-table-wrap table.dataTable thead th:nth-child(5),
    #lap-jurnal-kas-table-wrap table.dataTable tbody td:nth-child(5) {
        min-width: var(--lap-jk-col-debet);
        white-space: nowrap;
    }
    #lap-jurnal-kas-table-wrap table.dataTable thead th:nth-child(6),
    #lap-jurnal-kas-table-wrap table.dataTable tbody td:nth-child(6) {
        min-width: var(--lap-jk-col-kredit);
        white-space: nowrap;
    }
    #lap-jurnal-kas-table-wrap table.dataTable {
        border-collapse: collapse !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        border-color: #dee2e6 !important;
    }
    #lap-jurnal-kas-table-wrap table.dataTable thead th,
    #lap-jurnal-kas-table-wrap table.dataTable thead td {
        background-color: #cce5ff !important;
        color: #212529 !important;
        border: 1px solid #dee2e6 !important;
        font-weight: 600;
        vertical-align: middle;
        padding: var(--lap-jk-pad-y) var(--lap-jk-pad-x);
    }
    #lap-jurnal-kas-table-wrap table.dataTable tbody td {
        border: 1px solid #dee2e6 !important;
        vertical-align: middle;
        padding: var(--lap-jk-pad-y) var(--lap-jk-pad-x);
        background-color: #fff;
    }
    #lap-jurnal-kas-table-wrap table.dataTable tbody tr:nth-child(even) td {
        background-color: #f8f9fa;
    }
    #lap-jurnal-kas-table-wrap table.dataTable tbody tr:hover td {
        background-color: #eef6ff;
    }
    #lap-jurnal-kas-table-wrap table.dataTable.no-footer {
        border-bottom: 1px solid #dee2e6 !important;
    }
    #lap-jurnal-kas-table-wrap .dataTables_wrapper {
        width: 100%;
        padding: 0;
        font-size: var(--lap-jk-font-control);
        color: #212529;
    }
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .row:first-child {
        margin: 0;
        padding: var(--lap-jk-pad-y) var(--lap-jk-pad-x);
        background: #fffef5;
        border-bottom: 1px solid #ffe082;
    }
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_length {
        float: left;
        text-align: left;
        padding: 0;
    }
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_filter {
        float: right;
        text-align: right;
        padding: 0;
    }
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_length label,
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_filter label {
        font-weight: 500;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_length select,
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_filter input {
        font-size: var(--lap-jk-font-control);
        padding: clamp(6px, 0.4vw + 4px, 10px) clamp(8px, 0.5vw + 6px, 14px);
        height: auto;
        border: 1px solid #ced4da;
        border-radius: 4px;
        background: #fff;
    }
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_filter input {
        min-width: clamp(180px, 22vw, 360px);
        max-width: 100%;
    }
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_info,
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_paginate {
        padding: var(--lap-jk-pad-y) var(--lap-jk-pad-x);
        font-size: var(--lap-jk-font-control);
    }
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: clamp(4px, 0.35vw + 3px, 8px) clamp(8px, 0.6vw + 4px, 16px);
        font-size: var(--lap-jk-font-control);
        border-radius: 4px;
        border: 1px solid #dee2e6 !important;
        background: #fff !important;
        color: #212529 !important;
        margin-left: 2px;
    }
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #cce5ff !important;
        border-color: #99caff !important;
        color: #212529 !important;
    }
    #lap-jurnal-kas-table-wrap table.dataTable thead .sorting,
    #lap-jurnal-kas-table-wrap table.dataTable thead .sorting_asc,
    #lap-jurnal-kas-table-wrap table.dataTable thead .sorting_desc {
        cursor: pointer;
        position: relative;
        padding-right: clamp(24px, 2vw, 36px) !important;
        white-space: nowrap;
    }
    #lap-jurnal-kas-table-wrap table.dataTable thead th:nth-child(5),
    #lap-jurnal-kas-table-wrap table.dataTable thead th:nth-child(6) {
        padding-right: clamp(24px, 2vw, 36px) !important;
    }
    #lap-jurnal-kas-table-wrap table.dataTable tbody td.sorting_1,
    #lap-jurnal-kas-table-wrap table.dataTable tbody td.sorting_2,
    #lap-jurnal-kas-table-wrap table.dataTable tbody td.sorting_3 {
        background-color: inherit;
    }
    #lap-jurnal-kas-table-wrap .lap-jk-summary-wrap {
        border-top: none;
    }
    #lap-jurnal-kas-table-wrap .lap-jk-summary-table {
        table-layout: auto;
        width: 100%;
        min-width: calc(
            var(--lap-jk-col-no) + var(--lap-jk-col-tanggal) + var(--lap-jk-col-bukti) +
            var(--lap-jk-col-keterangan) + var(--lap-jk-col-debet) + var(--lap-jk-col-kredit)
        );
    }
    #lap-jurnal-kas-table-wrap .lap-jk-summary-table td {
        border: 1px solid #dee2e6 !important;
        font-size: var(--lap-jk-font-base);
        padding: var(--lap-jk-pad-y) var(--lap-jk-pad-x);
        white-space: nowrap;
    }
    .jk-summary-row td {
        background: #fff8e1;
        font-weight: 700;
        font-size: var(--lap-jk-font-base);
    }
    .lap-jk-card-body {
        overflow-x: auto;
    }
    @media (max-width: 991px) {
        #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_length label,
        #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_filter label {
            flex-wrap: wrap;
        }
    }
</style>

<script>
window.addEventListener('load', function() {
    if (window.jQuery && jQuery.fn.DataTable) {
        var $ = jQuery;
        var $table = $('#lapJurnalKasMainTable');
        if ($table.length) {
            if ($.fn.DataTable.isDataTable($table)) {
                $table.DataTable().destroy();
            }

            var lapColWidths = null;

            function readLapColWidths() {
                var root = document.getElementById('lap-jurnal-kas-table-wrap');
                if (!root || !window.getComputedStyle) {
                    return ['52px', '130px', '110px', '220px', '150px', '150px'];
                }
                var cs = window.getComputedStyle(root);
                return [
                    cs.getPropertyValue('--lap-jk-col-no').trim() || '52px',
                    cs.getPropertyValue('--lap-jk-col-tanggal').trim() || '130px',
                    cs.getPropertyValue('--lap-jk-col-bukti').trim() || '110px',
                    cs.getPropertyValue('--lap-jk-col-keterangan').trim() || '220px',
                    cs.getPropertyValue('--lap-jk-col-debet').trim() || '150px',
                    cs.getPropertyValue('--lap-jk-col-kredit').trim() || '150px'
                ];
            }

            function getLapScrollBody() {
                var $wrap = $table.closest('.dataTables_wrapper');
                var $body = $wrap.find('.dataTables_scrollBody');
                return $body.length ? $body : $wrap.find('.dataTables_scroll').find('.dataTables_scrollBody');
            }

            function bindLapSummaryScrollSync() {
                var $scrollBody = getLapScrollBody();
                var $summaryScroll = $('#lap-jk-summary-scroll');
                if (!$scrollBody.length || !$summaryScroll.length) {
                    return;
                }
                $scrollBody.off('scroll.lapJkSync').on('scroll.lapJkSync', function() {
                    $summaryScroll.scrollLeft($scrollBody.scrollLeft());
                });
                $summaryScroll.off('scroll.lapJkSync').on('scroll.lapJkSync', function() {
                    $scrollBody.scrollLeft($summaryScroll.scrollLeft());
                });
            }

            function fmtLapAmount(value) {
                var num = parseFloat(value);
                if (isNaN(num)) {
                    num = 0;
                }
                var parts = num.toFixed(2).split('.');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                return parts.join(',');
            }

            function updateLapSummaryTotals(dt) {
                var totalDebet = 0;
                var totalKredit = 0;

                dt.rows({ search: 'applied' }).every(function() {
                    var node = this.node();
                    if (!node) {
                        return;
                    }
                    var $row = $(node);
                    totalDebet += parseFloat($row.attr('data-debet') || 0) || 0;
                    totalKredit += parseFloat($row.attr('data-kredit') || 0) || 0;
                });

                var saldoAkhir = totalDebet - totalKredit;
                var seimbangVal = saldoAkhir >= 0 ? totalDebet : saldoAkhir;

                $('#lap-jk-summary-total-debet').text(fmtLapAmount(totalDebet));
                $('#lap-jk-summary-total-kredit').text(fmtLapAmount(totalKredit));
                $('#lap-jk-summary-saldo-akhir').text(fmtLapAmount(saldoAkhir));
                $('#lap-jk-summary-seimbang-debet').text(fmtLapAmount(seimbangVal));
                $('#lap-jk-summary-seimbang-kredit').text(fmtLapAmount(seimbangVal));
            }

            function syncLapJurnalKasTableWidths(dt) {
                lapColWidths = readLapColWidths();
                var $mainTable = $('#lapJurnalKasMainTable');
                var $summaryTable = $('.lap-jk-summary-table');
                if (!$mainTable.length) {
                    return;
                }
                if (dt && dt.columns) {
                    dt.columns.adjust();
                }
                var $scrollBody = getLapScrollBody();
                var $innerTable = $scrollBody.length ? $scrollBody.find('table').first() : $mainTable;
                var tableWidth = Math.max(
                    ($innerTable[0] && $innerTable[0].scrollWidth) || 0,
                    $mainTable[0].scrollWidth,
                    $mainTable.outerWidth()
                );
                $mainTable.find('colgroup col').each(function(i) {
                    if (lapColWidths[i]) {
                        $(this).css('width', lapColWidths[i]);
                    }
                });
                $summaryTable.find('colgroup col').each(function(i) {
                    if (lapColWidths[i]) {
                        $(this).css('width', lapColWidths[i]);
                    }
                });
                $summaryTable.css('width', tableWidth + 'px');
                bindLapSummaryScrollSync();
            }

            var lapDt = null;
            lapDt = $table.DataTable({
                paging: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
                searching: true,
                ordering: true,
                order: [[0, 'asc']],
                orderClasses: false,
                autoWidth: true,
                scrollX: true,
                scrollY: 'var(--lap-jk-viewport-h, calc(100vh - 320px))',
                scrollCollapse: true,
                info: true,
                dom: 'lfrtip',
                language: {
                    lengthMenu: 'Tampilkan _MENU_ record',
                    search: 'Cari:',
                    searchPlaceholder: 'Ketik kata kunci...',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ record',
                    infoEmpty: 'Menampilkan 0 sampai 0 dari 0 record',
                    infoFiltered: '(difilter dari _MAX_ total record)',
                    zeroRecords: 'Tidak ada data yang cocok',
                    paginate: {
                        first: 'Awal',
                        last: 'Akhir',
                        next: 'Next',
                        previous: 'Prev'
                    }
                },
                columnDefs: [
                    { targets: 0, orderable: true, className: 'text-center' },
                    { targets: 1, orderable: true },
                    { targets: 2, orderable: true },
                    { targets: 3, orderable: true },
                    { targets: 4, orderable: true, className: 'text-right' },
                    { targets: 5, orderable: true, className: 'text-right' }
                ],
                drawCallback: function() {
                    syncLapJurnalKasTableWidths(this.api());
                    updateLapSummaryTotals(this.api());
                },
                initComplete: function() {
                    syncLapJurnalKasTableWidths(this.api());
                    updateLapSummaryTotals(this.api());
                    bindLapSummaryScrollSync();
                }
            });

            function lapDtReflow() {
                if (!lapDt) {
                    return;
                }
                syncLapJurnalKasTableWidths(lapDt);
                updateLapSummaryTotals(lapDt);
            }

            lapDt.on('search.dt draw.dt order.dt column-visibility.dt page.dt length.dt', function() {
                setTimeout(lapDtReflow, 0);
            });
            $(window).on('resize.lapJurnalKasDt orientationchange.lapJurnalKasDt', function() {
                setTimeout(lapDtReflow, 100);
            });
            if (window.visualViewport) {
                window.visualViewport.addEventListener('resize', function() {
                    setTimeout(lapDtReflow, 100);
                });
            }
            if (window.ResizeObserver) {
                var lapResizeObs = new ResizeObserver(function() {
                    lapDtReflow();
                });
                var lapWrapEl = document.getElementById('lap-jurnal-kas-table-wrap');
                if (lapWrapEl) {
                    lapResizeObs.observe(lapWrapEl);
                }
            }
        }
    }

    var urlCariBase = <?php echo json_encode($url_cari_lap_jurnal_kas); ?>;
    var STORAGE_KEY = 'anekadharma_lap_jurnal_kas_bulan_terpilih';

    function parseMonthValue(val) {
        if (!val || !/^\d{4}-\d{2}$/.test(val)) return null;
        var parts = val.split('-');
        return { year: parseInt(parts[0], 10), month: parseInt(parts[1], 10) };
    }

    function saveMonthChoice(val) {
        try { sessionStorage.setItem(STORAGE_KEY, val); } catch (e) {}
    }

    function bindMonthPicker() {
        var bulanNs = document.getElementById('bulan_ns_lap');
        if (!bulanNs) return;
        if (bulanNs.value) saveMonthChoice(bulanNs.value);
        var lastValue = bulanNs.value || '';
        bulanNs.addEventListener('change', function() {
            var val = bulanNs.value || '';
            if (!val || val === lastValue) return;
            lastValue = val;
            saveMonthChoice(val);
            var parsed = parseMonthValue(val);
            if (parsed) {
                window.location.href = urlCariBase + '/' + parsed.year + '/' + parsed.month;
            }
        });
    }

    bindMonthPicker();
});
</script>
