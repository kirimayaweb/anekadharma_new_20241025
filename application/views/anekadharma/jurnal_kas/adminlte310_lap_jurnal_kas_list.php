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
                                <a href="<?php echo htmlspecialchars($url_lap_jurnal_kas_excel, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-success" id="btn-lap-jurnal-kas-excel">
                                    <i class="fa fa-file-excel-o"></i> Cetak Buku Kas Ke Excel
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
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
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style type="text/css">
    #lap-jurnal-kas-table-wrap.lap-jk-dt-box {
        border: 2px solid #ffe082;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 1px 4px rgba(255, 193, 7, 0.15);
        padding: 0;
    }
    #lap-jurnal-kas-table-wrap .lap-jk-dt-responsive {
        overflow-x: auto;
    }
    #lap-jurnal-kas-table-wrap .jurnal-kas-grid-table {
        table-layout: fixed;
        width: 100%;
        min-width: 1680px;
        font-size: 30px;
        color: #212529;
        border-color: #dee2e6 !important;
    }
    #lap-jurnal-kas-table-wrap col.lap-jk-w-no { width: 72px; }
    #lap-jurnal-kas-table-wrap col.lap-jk-w-tanggal { width: 260px; }
    #lap-jurnal-kas-table-wrap col.lap-jk-w-bukti { width: 220px; }
    #lap-jurnal-kas-table-wrap col.lap-jk-w-keterangan { width: auto; }
    #lap-jurnal-kas-table-wrap col.lap-jk-w-debet { width: 320px; }
    #lap-jurnal-kas-table-wrap col.lap-jk-w-kredit { width: 320px; }
    #lap-jurnal-kas-table-wrap table.dataTable thead th:nth-child(2),
    #lap-jurnal-kas-table-wrap table.dataTable tbody td:nth-child(2),
    #lap-jurnal-kas-table-wrap .lap-jk-summary-table td:nth-child(2) {
        min-width: 260px;
        white-space: nowrap;
    }
    #lap-jurnal-kas-table-wrap table.dataTable thead th:nth-child(3),
    #lap-jurnal-kas-table-wrap table.dataTable tbody td:nth-child(3),
    #lap-jurnal-kas-table-wrap .lap-jk-summary-table td:nth-child(3) {
        min-width: 220px;
        white-space: nowrap;
    }
    #lap-jurnal-kas-table-wrap table.dataTable thead th:nth-child(5),
    #lap-jurnal-kas-table-wrap table.dataTable tbody td:nth-child(5),
    #lap-jurnal-kas-table-wrap .lap-jk-summary-table td:nth-child(5) {
        min-width: 320px;
        white-space: nowrap;
    }
    #lap-jurnal-kas-table-wrap table.dataTable thead th:nth-child(6),
    #lap-jurnal-kas-table-wrap table.dataTable tbody td:nth-child(6),
    #lap-jurnal-kas-table-wrap .lap-jk-summary-table td:nth-child(6) {
        min-width: 320px;
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
        padding: 16px 18px;
    }
    #lap-jurnal-kas-table-wrap table.dataTable tbody td {
        border: 1px solid #dee2e6 !important;
        vertical-align: middle;
        padding: 14px 18px;
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
        font-size: 28px;
        color: #212529;
    }
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .row:first-child {
        margin: 0;
        padding: 16px 18px 12px;
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
        font-size: 28px;
        padding: 8px 14px;
        height: auto;
        border: 1px solid #ced4da;
        border-radius: 4px;
        background: #fff;
    }
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_filter input {
        min-width: 320px;
    }
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_info,
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_paginate {
        padding: 16px 18px;
        font-size: 28px;
    }
    #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 8px 16px;
        font-size: 28px;
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
        padding-right: 36px !important;
        white-space: nowrap;
    }
    #lap-jurnal-kas-table-wrap table.dataTable thead th:nth-child(5),
    #lap-jurnal-kas-table-wrap table.dataTable thead th:nth-child(6) {
        padding-right: 36px !important;
    }
    #lap-jurnal-kas-table-wrap table.dataTable tbody td.sorting_1,
    #lap-jurnal-kas-table-wrap table.dataTable tbody td.sorting_2,
    #lap-jurnal-kas-table-wrap table.dataTable tbody td.sorting_3 {
        background-color: inherit;
    }
    #lap-jurnal-kas-table-wrap .lap-jk-summary-wrap {
        border-top: 2px solid #ffe082;
    }
    #lap-jurnal-kas-table-wrap .lap-jk-summary-table td {
        border: 1px solid #dee2e6 !important;
        font-size: 30px;
        padding: 14px 18px;
    }
    .jk-summary-row td {
        background: #fff8e1;
        font-weight: 700;
        font-size: 30px;
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

            var lapColWidths = ['72px', '260px', '220px', 'auto', '320px', '320px'];

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

            function syncLapJurnalKasTableWidths() {
                var $mainTable = $('#lapJurnalKasMainTable');
                var $summaryTable = $('.lap-jk-summary-table');
                if (!$mainTable.length) {
                    return;
                }
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
                $mainTable.css('width', '100%');
                if ($summaryTable.length) {
                    $summaryTable.css('width', $mainTable.outerWidth() + 'px');
                }
            }

            var lapDt = $table.DataTable({
                paging: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
                searching: true,
                ordering: true,
                order: [[0, 'asc']],
                orderClasses: false,
                autoWidth: false,
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
                    { targets: 0, width: lapColWidths[0], orderable: true, className: 'text-center' },
                    { targets: 1, width: lapColWidths[1], orderable: true },
                    { targets: 2, width: lapColWidths[2], orderable: true },
                    { targets: 3, width: lapColWidths[3], orderable: true },
                    { targets: 4, width: lapColWidths[4], orderable: true, className: 'text-right' },
                    { targets: 5, width: lapColWidths[5], orderable: true, className: 'text-right' }
                ],
                drawCallback: function() {
                    syncLapJurnalKasTableWidths();
                    updateLapSummaryTotals(this.api());
                },
                initComplete: function() {
                    syncLapJurnalKasTableWidths();
                    updateLapSummaryTotals(this.api());
                }
            });

            lapDt.on('search.dt draw.dt order.dt column-visibility.dt page.dt length.dt', function() {
                setTimeout(function() {
                    syncLapJurnalKasTableWidths();
                    updateLapSummaryTotals(lapDt);
                }, 0);
            });
            $(window).on('resize.lapJurnalKasDt', function() {
                syncLapJurnalKasTableWidths();
            });
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
