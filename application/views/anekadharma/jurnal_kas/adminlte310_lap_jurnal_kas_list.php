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
        <div class="box box-warning box-solid">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <strong>LAPORAN BUKU KAS</strong>
                                <span id="lap-jk-bulan-label"><?php echo htmlspecialchars($bulan_label, ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            <div class="col-md-4">
                                <div class="lap-jurnal-kas-month-filter d-flex align-items-center flex-nowrap">
                                    <input type="month" class="form-control" id="bulan_ns_lap" name="bulan_ns_lap"
                                        value="<?php echo htmlspecialchars($bulan_ns_value, ENT_QUOTES, 'UTF-8'); ?>"
                                        autocomplete="off">
                                    <button type="button" class="btn btn-danger btn-sm btn-flat ml-2 flex-shrink-0" id="btn-cari-lap-jurnal-kas">
                                        <i class="fa fa-search" aria-hidden="true"></i> Cari
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="<?php echo htmlspecialchars($url_lap_jurnal_kas_excel, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-success<?php echo empty($is_published) ? ' d-none' : ''; ?>" id="btn-lap-jurnal-kas-excel">
                                    <i class="fa fa-file-excel-o"></i> Cetak Buku Kas Ke Excel
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body lap-jk-card-body">
                        <div id="lap-jk-alert-wrap">
                        <?php $this->load->view('anekadharma/jurnal_kas/partials/lap_jurnal_kas_not_published_alert'); ?>
                        </div>
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
                                    <tbody id="lap-jk-tbody">
                                        <?php $this->load->view('anekadharma/jurnal_kas/partials/lap_jurnal_kas_tbody'); ?>
                                    </tbody>
                                </table>
                            </div>

                            <div id="lap-jk-summary-wrap">
                            <?php $this->load->view('anekadharma/jurnal_kas/partials/lap_jurnal_kas_summary'); ?>
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
    .lap-jurnal-kas-month-filter .form-control {
        min-width: 0;
    }
    #lap-jurnal-kas-table-wrap.lap-jk-loading {
        opacity: 0.55;
        pointer-events: none;
    }
    @media (max-width: 991px) {
        #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_length label,
        #lap-jurnal-kas-table-wrap .dataTables_wrapper .dataTables_filter label {
            flex-wrap: wrap;
        }
    }
</style>

<script>
(function() {
    var urlAjaxRefresh = <?php echo json_encode(isset($url_ajax_refresh) ? $url_ajax_refresh : site_url('Lap_Jurnal_kas/ajax_refresh_datatable')); ?>;
    var urlCariBase = <?php echo json_encode($url_cari_lap_jurnal_kas); ?>;
    var STORAGE_KEY = 'anekadharma_lap_jurnal_kas_bulan_terpilih';
    var serverBulanNs = <?php echo json_encode($bulan_ns_value); ?>;
    var lastLoadedBulanNs = serverBulanNs || '';
    var lapDt = null;
    var lapRefreshing = false;
    var lapMonthDebounce = null;
    var lapLibsReady = false;

    function parseMonthValue(val) {
        if (!val || !/^\d{4}-\d{2}$/.test(val)) return null;
        var parts = val.split('-');
        return { year: parseInt(parts[0], 10), month: parseInt(parts[1], 10) };
    }

    function saveMonthChoice(val) {
        try { sessionStorage.setItem(STORAGE_KEY, val); } catch (e) {}
    }

    function hasDataTable() {
        return !!(window.jQuery && jQuery.fn && (jQuery.fn.dataTable || jQuery.fn.DataTable));
    }

    function waitForLibs(cb) {
        if (hasDataTable()) {
            cb();
            return;
        }
        setTimeout(function() { waitForLibs(cb); }, 50);
    }

    function getBulanNsValue() {
        var el = document.getElementById('bulan_ns_lap');
        if (!el) return '';
        var val = (el.value || '').trim();
        if (!/^\d{4}-\d{2}$/.test(val)) {
            val = lastLoadedBulanNs || serverBulanNs || '';
            if (val) el.value = val;
        }
        return val;
    }

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

    var lapColWidths = null;

    function getLapScrollBody() {
        var $table = getLapMainTable();
        if (!$table || !$table.length) return jQuery();
        var $wrap = $table.closest('.dataTables_wrapper');
        var $body = $wrap.find('.dataTables_scrollBody');
        return $body.length ? $body : $wrap.find('.dataTables_scroll').find('.dataTables_scrollBody');
    }

    function bindLapSummaryScrollSync() {
        var $scrollBody = getLapScrollBody();
        var $summaryScroll = jQuery('#lap-jk-summary-scroll');
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
        if (!dt) return;
        var totalDebet = 0;
        var totalKredit = 0;

        dt.rows({ search: 'applied' }).every(function() {
            var node = this.node();
            if (!node) return;
            var $row = jQuery(node);
            totalDebet += parseFloat($row.attr('data-debet') || 0) || 0;
            totalKredit += parseFloat($row.attr('data-kredit') || 0) || 0;
        });

        var saldoAkhir = totalDebet - totalKredit;
        var seimbangVal = saldoAkhir >= 0 ? totalDebet : saldoAkhir;

        jQuery('#lap-jk-summary-total-debet').text(fmtLapAmount(totalDebet));
        jQuery('#lap-jk-summary-total-kredit').text(fmtLapAmount(totalKredit));
        jQuery('#lap-jk-summary-saldo-akhir').text(fmtLapAmount(saldoAkhir));
        jQuery('#lap-jk-summary-seimbang-debet').text(fmtLapAmount(seimbangVal));
        jQuery('#lap-jk-summary-seimbang-kredit').text(fmtLapAmount(seimbangVal));
    }

    function syncLapJurnalKasTableWidths(dt) {
        lapColWidths = readLapColWidths();
        var $mainTable = getLapMainTable();
        var $summaryTable = jQuery('.lap-jk-summary-table');
        if (!$mainTable || !$mainTable.length) return;

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
            if (lapColWidths[i]) jQuery(this).css('width', lapColWidths[i]);
        });
        $summaryTable.find('colgroup col').each(function(i) {
            if (lapColWidths[i]) jQuery(this).css('width', lapColWidths[i]);
        });
        $summaryTable.css('width', tableWidth + 'px');
        bindLapSummaryScrollSync();
    }

    function lapDtReflow() {
        if (!lapDt) return;
        syncLapJurnalKasTableWidths(lapDt);
        updateLapSummaryTotals(lapDt);
    }

    function getLapMainTable() {
        if (!hasDataTable()) return null;
        var $table = jQuery('#lap-jurnal-kas-table-wrap .lap-jk-dt-responsive > table#lapJurnalKasMainTable');
        if ($table.length) return $table.first();
        return jQuery('#lapJurnalKasMainTable').first();
    }

    function restoreLapTableDom() {
        if (!hasDataTable()) return;
        var $table = getLapMainTable();
        if (!$table || !$table.length) return;

        var $responsive = jQuery('#lap-jurnal-kas-table-wrap .lap-jk-dt-responsive');
        var $wrapper = $table.closest('.dataTables_wrapper');

        if ($wrapper.length) {
            if ($responsive.length) {
                $responsive.empty().append($table);
            } else {
                $table.insertBefore($wrapper);
            }
            $wrapper.remove();
        }

        jQuery('#lap-jurnal-kas-table-wrap .dataTables_scroll, #lap-jurnal-kas-table-wrap .DTFC_Cloned').remove();
    }

    function destroyLapJurnalKasDatatable() {
        if (!hasDataTable()) {
            lapDt = null;
            return;
        }
        var $table = getLapMainTable();
        if ($table && $table.length && jQuery.fn.DataTable.isDataTable($table)) {
            try {
                $table.DataTable().clear().destroy();
            } catch (e1) {
                try { $table.DataTable().destroy(); } catch (e2) {}
            }
        }
        if (lapDt) {
            try { lapDt.destroy(); } catch (e3) {}
        }
        lapDt = null;
        jQuery('.DTFC_Cloned, .DTFC_LeftWrapper, .DTFC_RightWrapper').remove();
        restoreLapTableDom();
    }

    function setLapTableBodyHtml(html) {
        if (!hasDataTable()) return;
        var $table = getLapMainTable();
        if (!$table || !$table.length) return;

        var $tbody = $table.children('tbody');
        if (!$tbody.length) {
            $table.append('<tbody id="lap-jk-tbody"></tbody>');
            $tbody = $table.children('tbody');
        }
        $tbody.attr('id', 'lap-jk-tbody').html(html || '');
    }

    function initLapJurnalKasDatatable() {
        if (!hasDataTable()) return null;

        var $table = getLapMainTable();
        if (!$table || !$table.length) return null;

        if (jQuery.fn.DataTable.isDataTable($table)) return lapDt;

        var scrollH = 'calc(100vh - 320px)';
        var root = document.getElementById('lap-jurnal-kas-table-wrap');
        if (root && window.getComputedStyle) {
            var vh = window.getComputedStyle(root).getPropertyValue('--lap-jk-viewport-h').trim();
            if (vh) scrollH = vh;
        }

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
            scrollY: scrollH,
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

        lapDt.on('search.dt draw.dt order.dt column-visibility.dt page.dt length.dt', function() {
            setTimeout(lapDtReflow, 0);
        });

        return lapDt;
    }

    function updateLapJurnalKasHeader(res) {
        if (res.bulan_label) {
            jQuery('#lap-jk-bulan-label').text(res.bulan_label);
        }
        if (res.bulan_ns_value) {
            lastLoadedBulanNs = res.bulan_ns_value;
            serverBulanNs = res.bulan_ns_value;
            var el = document.getElementById('bulan_ns_lap');
            if (el && el.value !== res.bulan_ns_value) {
                el.value = res.bulan_ns_value;
            }
        }
        var $excelBtn = jQuery('#btn-lap-jurnal-kas-excel');
        if ($excelBtn.length) {
            if (res.is_published && res.url_lap_jurnal_kas_excel) {
                $excelBtn.attr('href', res.url_lap_jurnal_kas_excel).removeClass('d-none');
            } else {
                $excelBtn.addClass('d-none');
            }
        }
        if (typeof res.not_published_html === 'string') {
            jQuery('#lap-jk-alert-wrap').html(res.not_published_html);
        }
        if (typeof res.summary_html === 'string') {
            jQuery('#lap-jk-summary-wrap').html(res.summary_html);
        }
    }

    function applyLapJurnalKasData(res, val) {
        updateLapJurnalKasHeader(res);
        destroyLapJurnalKasDatatable();
        setLapTableBodyHtml(res.tbody_html || '');
        initLapJurnalKasDatatable();
        lastLoadedBulanNs = res.bulan_ns_value || val;
        saveMonthChoice(lastLoadedBulanNs);

        var parsed = parseMonthValue(lastLoadedBulanNs);
        if (parsed && window.history && window.history.replaceState) {
            window.history.replaceState(null, '', urlCariBase + '/' + parsed.year + '/' + parsed.month);
        }
    }

    function refreshLapJurnalKasDatatable(force) {
        var val = getBulanNsValue();
        if (!val) return;
        if (!force && val === lastLoadedBulanNs) return;
        if (lapRefreshing) return;

        waitForLibs(function() {
            if (lapRefreshing) return;
            if (!force && val === lastLoadedBulanNs) return;

            lapRefreshing = true;
            jQuery('#lap-jurnal-kas-table-wrap').addClass('lap-jk-loading');
            jQuery('#btn-cari-lap-jurnal-kas').prop('disabled', true);

            jQuery.ajax({
                url: urlAjaxRefresh,
                type: 'POST',
                dataType: 'json',
                data: { bulan_ns: val },
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).done(function(res) {
                if (!res || !res.ok) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Gagal',
                            text: (res && res.message) ? res.message : 'Gagal memuat data Laporan Buku Kas.'
                        });
                    }
                    return;
                }
                applyLapJurnalKasData(res, val);
            }).fail(function(xhr) {
                var parsed = parseMonthValue(val);
                if (parsed) {
                    window.location.href = urlCariBase + '/' + parsed.year + '/' + parsed.month;
                    return;
                }
                var msg = 'Tidak dapat memuat data Laporan Buku Kas.';
                if (xhr && xhr.responseText && xhr.responseText.indexOf('<') === 0) {
                    msg = 'Sesi login habis. Silakan login ulang.';
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
                }
            }).always(function() {
                lapRefreshing = false;
                jQuery('#lap-jurnal-kas-table-wrap').removeClass('lap-jk-loading');
                jQuery('#btn-cari-lap-jurnal-kas').prop('disabled', false);
            });
        });
    }

    function scheduleMonthRefresh(force) {
        clearTimeout(lapMonthDebounce);
        lapMonthDebounce = setTimeout(function() {
            refreshLapJurnalKasDatatable(!!force);
        }, force ? 0 : 250);
    }

    function bindMonthPickerEvents() {
        var bulanEl = document.getElementById('bulan_ns_lap');
        if (bulanEl) {
            bulanEl.addEventListener('change', function() {
                var val = (this.value || '').trim();
                if (!/^\d{4}-\d{2}$/.test(val)) return;
                scheduleMonthRefresh(false);
            });
            bulanEl.addEventListener('input', function() {
                var val = (this.value || '').trim();
                if (!/^\d{4}-\d{2}$/.test(val)) return;
                scheduleMonthRefresh(false);
            });
        }

        var btnCari = document.getElementById('btn-cari-lap-jurnal-kas');
        if (btnCari) {
            btnCari.addEventListener('click', function(e) {
                e.preventDefault();
                scheduleMonthRefresh(true);
            });
        }
    }

    function bindResizeHandlers() {
        jQuery(window).off('resize.lapJurnalKasDt orientationchange.lapJurnalKasDt')
            .on('resize.lapJurnalKasDt orientationchange.lapJurnalKasDt', function() {
                setTimeout(lapDtReflow, 100);
            });

        if (window.visualViewport) {
            window.visualViewport.addEventListener('resize', function() {
                setTimeout(lapDtReflow, 100);
            });
        }

        if (window.ResizeObserver) {
            var lapWrapEl = document.getElementById('lap-jurnal-kas-table-wrap');
            if (lapWrapEl) {
                new ResizeObserver(function() { lapDtReflow(); }).observe(lapWrapEl);
            }
        }
    }

    function bootLapJurnalKas() {
        if (lapLibsReady) return;
        waitForLibs(function() {
            if (lapLibsReady) return;
            lapLibsReady = true;
            lastLoadedBulanNs = getBulanNsValue() || serverBulanNs || '';
            if (lastLoadedBulanNs) saveMonthChoice(lastLoadedBulanNs);
            try {
                initLapJurnalKasDatatable();
            } catch (err) {
                console.error('Lap Jurnal Kas DataTable init error:', err);
            }
            bindResizeHandlers();
        });
    }

    bindMonthPickerEvents();
    bootLapJurnalKas();
    if (document.readyState !== 'complete') {
        window.addEventListener('load', bootLapJurnalKas);
    }
})();
</script>
