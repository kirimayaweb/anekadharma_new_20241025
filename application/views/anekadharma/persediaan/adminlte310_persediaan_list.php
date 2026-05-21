<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Persediaan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right"></ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="col-md-12">
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="persediaan-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-data-persediaan" data-toggle="pill" href="#panel-data-persediaan" role="tab" aria-controls="panel-data-persediaan" aria-selected="true">Data Persediaan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-rekap" data-toggle="pill" href="#panel-rekap" role="tab" aria-controls="panel-rekap" aria-selected="false">Rekap</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="persediaan-tabs-content">

                        <!-- TAB 1: DATA PERSEDIAAN -->
                        <div class="tab-pane fade show active" id="panel-data-persediaan" role="tabpanel" aria-labelledby="tab-data-persediaan">
                            <?php
                            $action_cari_form = isset($action_cari) && $action_cari ? $action_cari : site_url('persediaan/search');
                            $Persediaan_data = isset($Persediaan_data) && is_array($Persediaan_data) ? $Persediaan_data : array();
                            $bulan_tampil = isset($bulan_persediaan_selected) && $bulan_persediaan_selected !== ''
                                ? $bulan_persediaan_selected
                                : date('Y-m');
                            ?>
                            <form action="<?php echo $action_cari_form; ?>" method="post" id="form-persediaan-bulan">
                                <div class="row mb-3 align-items-end">
                                    <div class="col-md-12">
                                        <strong>DATA PERSEDIAAN</strong>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <label for="bulan_persediaan">Bulan:</label>
                                        <input type="month" id="bulan_persediaan" name="bulan_persediaan" class="form-control d-inline-block" style="width:auto;vertical-align:middle;" value="<?php echo htmlspecialchars($bulan_tampil); ?>">
                                        <button type="submit" class="btn btn-danger ml-1 btn-cari-persediaan">Cari</button>
                                        <button type="button" id="btn-cetak-excel-persediaan" class="btn btn-primary ml-1" data-url="<?php echo site_url('persediaan/excel'); ?>">Cetak ke Excel</button>
                                    </div>
                                </div>
                            </form>

                            <table id="table-persediaan" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="50px">No</th>
                                        <th>Tanggal</th>
                                        <th>Kode</th>
                                        <th>Namabarang</th>
                                        <th>Satuan</th>
                                        <th>Hpp</th>
                                        <th>Sa</th>
                                        <th>Spop</th>
                                        <th>Beli</th>
                                        <th>Tuj</th>
                                        <th>Tgl Keluar</th>
                                        <th>Sekret</th>
                                        <th>Cetak</th>
                                        <th>Grafikita</th>
                                        <th>Dinas Umum</th>
                                        <th>Atk Rsud</th>
                                        <th>Ppbmp Kbs</th>
                                        <th>Kbs</th>
                                        <th>Ppbmp</th>
                                        <th>Medis</th>
                                        <th>Siiplah Bosda</th>
                                        <th>Sembako</th>
                                        <th>Fc Gose</th>
                                        <th>Fc Manding</th>
                                        <th>Fc Psamya</th>
                                        <th>Total 10</th>
                                        <th>Nilai Persediaan</th>
                                        <th>Terjual</th>
                                        <th>Jumlah Pecah Satuan</th>
                                        <th>Bahan Produksi</th>
                                        <th>Sisa / Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $start = 0;
                                    $total_nilai_persediaan = 0;
                                    foreach ($Persediaan_data as $persediaan) {
                                        $nilai_persediaan_row = persediaan_parse_angka(isset($persediaan->nilai_persediaan) ? $persediaan->nilai_persediaan : 0);
                                        $total_nilai_persediaan += $nilai_persediaan_row;
                                        $sisa_stock = persediaan_hitung_sisa_stock($persediaan);
                                    ?>
                                        <tr>
                                            <td><?php echo ++$start ?></td>
                                            <td><?php echo persediaan_format_bulan_tahun($persediaan, $bulan_tampil); ?></td>
                                            <td><?php echo $persediaan->kode ?></td>
                                            <td><?php echo $persediaan->namabarang ?></td>
                                            <td><?php echo $persediaan->satuan ?></td>
                                            <td><?php echo $persediaan->hpp ?></td>
                                            <td><?php echo $persediaan->sa ?></td>
                                            <td><?php echo $persediaan->spop ?></td>
                                            <td><?php echo $persediaan->beli ?></td>
                                            <td><?php echo $persediaan->tuj ?></td>
                                            <td><?php echo $persediaan->tgl_keluar ?></td>
                                            <td><?php echo $persediaan->sekret ?></td>
                                            <td><?php echo $persediaan->cetak ?></td>
                                            <td><?php echo $persediaan->grafikita ?></td>
                                            <td><?php echo $persediaan->dinas_umum ?></td>
                                            <td><?php echo $persediaan->atk_rsud ?></td>
                                            <td><?php echo $persediaan->ppbmp_kbs ?></td>
                                            <td><?php echo $persediaan->kbs ?></td>
                                            <td><?php echo $persediaan->ppbmp ?></td>
                                            <td><?php echo $persediaan->medis ?></td>
                                            <td><?php echo $persediaan->siiplah_bosda ?></td>
                                            <td><?php echo $persediaan->sembako ?></td>
                                            <td><?php echo $persediaan->fc_gose ?></td>
                                            <td><?php echo $persediaan->fc_manding ?></td>
                                            <td><?php echo $persediaan->fc_psamya ?></td>
                                            <td><?php echo $persediaan->total_10 ?></td>
                                            <td><?php echo $persediaan->nilai_persediaan ?></td>
                                            <td><?php echo isset($persediaan->penjualan) ? $persediaan->penjualan : 0 ?></td>
                                            <td><?php echo isset($persediaan->pecah_satuan) ? $persediaan->pecah_satuan : 0 ?></td>
                                            <td><?php echo isset($persediaan->bahan_produksi) ? $persediaan->bahan_produksi : 0 ?></td>
                                            <td><?php echo is_numeric($sisa_stock) && floor($sisa_stock) == $sisa_stock ? (int) $sisa_stock : $sisa_stock; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="26" style="text-align:right;">Total Nilai Persediaan</th>
                                        <th style="text-align:right;"><?php echo number_format($total_nilai_persediaan, 0, ',', '.'); ?></th>
                                        <th colspan="4"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- TAB 2: REKAP -->
                        <div class="tab-pane fade" id="panel-rekap" role="tabpanel" aria-labelledby="tab-rekap">
                            <div class="row mb-3 align-items-end">
                                <div class="col-md-12">
                                    <strong>REKAP PERSEDIAAN</strong>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label for="bulan_rekap">Bulan:</label>
                                    <input type="month" id="bulan_rekap" class="form-control d-inline-block" style="width:auto;vertical-align:middle;" value="<?php echo htmlspecialchars($bulan_tampil); ?>">
                                    <button type="button" id="btn-cetak-excel-rekap" class="btn btn-primary ml-1" data-url="<?php echo isset($url_rekap_excel) ? $url_rekap_excel : site_url('Persediaan/excel_rekap'); ?>">Cetak ke Excel</button>
                                    <span id="rekap-status" class="ml-2 text-muted"></span>
                                </div>
                            </div>
                            <table id="table-rekap" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="60px">Nomor</th>
                                        <th>Deskripsi</th>
                                        <th class="text-right">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr class="font-weight-bold bg-light">
                                        <td colspan="2" class="text-right">Total (baris 8–21)</td>
                                        <td id="rekap-total-detail" class="text-right">0</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    div.dataTables_wrapper { width: 100%; margin: 0 auto; }
    .tab-pane { padding-top: 10px; }
    #swal-excel-progress, #swal-rekap-progress {
        height: 100%;
        width: 0%;
        background: #007bff;
        border-radius: 5px;
        transition: width 0.25s ease;
    }
    #swal-rekap-log {
        max-height: 160px;
        overflow-y: auto;
        font-size: 12px;
        text-align: left;
        margin-top: 10px;
        padding: 8px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }
    #swal-rekap-log .rekap-log-ok { color: #155724; }
    #swal-rekap-log .rekap-log-run { color: #004085; font-weight: bold; }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    var dtPersediaan = $('#table-persediaan').DataTable({
        scrollY: 500,
        scrollX: true,
        scrollCollapse: true,
        pageLength: 25,
        order: [[0, 'asc']]
    });

    var dtRekap = null;
    var urlRekapAjax = <?php echo json_encode(isset($url_rekap_ajax) ? $url_rekap_ajax : site_url('Persediaan/ajax_rekap_bulan')); ?>;
    var urlRekapSyncStep = <?php echo json_encode(isset($url_rekap_sync_step) ? $url_rekap_sync_step : site_url('Persediaan/ajax_rekap_sync_step')); ?>;
    var urlRekapExcel = <?php echo json_encode(isset($url_rekap_excel) ? $url_rekap_excel : site_url('Persediaan/excel_rekap')); ?>;
    var rekapTotalSteps = <?php echo (int) (isset($rekap_total_steps) ? $rekap_total_steps : 21); ?>;
    var rekapLoading = false;
    var rekapRecalcRunning = false;

    function escapeHtmlRekap(s) {
        if (s == null) return '';
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function syncBulanRekapDariPersediaan() {
        var bulan = $('#bulan_persediaan').val();
        if (bulan) {
            $('#bulan_rekap').val(bulan);
        }
    }

    function syncBulanPersediaanDariRekap() {
        var bulan = $('#bulan_rekap').val();
        if (bulan) {
            $('#bulan_persediaan').val(bulan);
        }
    }

    function renderRekapTable(res) {
        if ($.fn.DataTable.isDataTable('#table-rekap')) {
            dtRekap.destroy();
            dtRekap = null;
        }

        var html = '';
        if (res.items && res.items.length) {
            res.items.forEach(function(it) {
                html += '<tr>'
                    + '<td>' + escapeHtmlRekap(it.nomor) + '</td>'
                    + '<td>' + escapeHtmlRekap(it.deskripsi) + '</td>'
                    + '<td class="text-right">' + escapeHtmlRekap(it.nominal_tampil) + '</td>'
                    + '</tr>';
            });
        } else {
            html = '<tr><td colspan="3" class="text-center">Tidak ada data rekap</td></tr>';
        }
        $('#table-rekap tbody').html(html);
        $('#rekap-total-detail').text(res.total_detail_tampil || '0');

        dtRekap = $('#table-rekap').DataTable({
            scrollY: 400,
            scrollX: true,
            pageLength: 25,
            ordering: false,
            searching: true
        });
        $('#rekap-status').text('Bulan: ' + (res.bulan || ''));
    }

    function loadRekapDataOnly() {
        if (rekapLoading) {
            return Promise.reject(new Error('Sedang memuat rekap'));
        }
        var bulan = $('#bulan_rekap').val() || $('#bulan_persediaan').val() || '';
        if (!bulan) {
            return Promise.resolve();
        }

        rekapLoading = true;
        $('#table-rekap').css('opacity', '0.5');

        var formData = new FormData();
        formData.append('bulan_persediaan', bulan);

        return fetch(urlRekapAjax, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (!res.ok) {
                throw new Error(res.message || 'Gagal memuat rekap');
            }
            renderRekapTable(res);
            return res;
        })
        .finally(function() {
            rekapLoading = false;
            $('#table-rekap').css('opacity', '1');
        });
    }

    function tampilkanTabRekap() {
        $('#tab-rekap').tab('show');
    }

    function updateSwalRekapProgress(step, total, label, pct) {
        var bar = document.getElementById('swal-rekap-progress');
        if (bar) {
            bar.style.width = pct + '%';
        }
        var stepEl = document.getElementById('swal-rekap-step-label');
        if (stepEl) {
            stepEl.textContent = 'Langkah ' + step + ' / ' + total + ' — ' + label;
        }
    }

    function appendSwalRekapLog(htmlLine, isCurrent) {
        var log = document.getElementById('swal-rekap-log');
        if (!log) {
            return;
        }
        var prev = log.querySelector('.rekap-log-run');
        if (prev) {
            prev.classList.remove('rekap-log-run');
            prev.classList.add('rekap-log-ok');
        }
        var div = document.createElement('div');
        div.className = isCurrent ? 'rekap-log-run' : 'rekap-log-ok';
        div.innerHTML = htmlLine;
        log.appendChild(div);
        log.scrollTop = log.scrollHeight;
    }

    function tampilkanSwalRekapProgress(bulan) {
        Swal.fire({
            title: 'Rekalkulasi Rekap',
            html: '<p style="margin:0 0 8px;font-size:14px;">Bulan: <strong>' + escapeHtmlRekap(bulan) + '</strong></p>'
                + '<p id="swal-rekap-step-label" style="margin:0 0 8px;font-size:13px;color:#333;">Memulai...</p>'
                + '<div style="height:12px;background:#e9ecef;border-radius:6px;overflow:hidden;">'
                + '<div id="swal-rekap-progress"></div></div>'
                + '<div id="swal-rekap-log"></div>',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false
        });
    }

    function jalankanRekapSyncStep(bulan, step) {
        var formData = new FormData();
        formData.append('bulan_persediaan', bulan);
        formData.append('step', String(step));

        return fetch(urlRekapSyncStep, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        }).then(function(r) { return r.json(); });
    }

    /**
     * Rekalkulasi per record + SweetAlert progress.
     * opts: { showTabAfter, submitFormAfter, onComplete }
     */
    function runRekapRecalcWithSwal(opts) {
        opts = opts || {};
        if (rekapRecalcRunning) {
            return Promise.reject(new Error('Rekalkulasi sedang berjalan'));
        }

        var bulan = $('#bulan_rekap').val() || $('#bulan_persediaan').val() || '';
        if (!bulan) {
            return Promise.reject(new Error('Bulan belum dipilih'));
        }

        if (typeof Swal === 'undefined') {
            return Promise.reject(new Error('SweetAlert tidak tersedia'));
        }

        rekapRecalcRunning = true;
        syncBulanRekapDariPersediaan();
        tampilkanSwalRekapProgress(bulan);

        var chain = Promise.resolve();
        for (var s = 1; s <= rekapTotalSteps; s++) {
            (function(stepNum) {
                chain = chain.then(function() {
                    return jalankanRekapSyncStep(bulan, stepNum).then(function(res) {
                        if (!res.ok) {
                            throw new Error(res.message || 'Gagal langkah ' + stepNum);
                        }
                        var pct = Math.round((stepNum / rekapTotalSteps) * 100);
                        updateSwalRekapProgress(
                            res.step,
                            res.total_steps || rekapTotalSteps,
                            res.nama_rekap || '',
                            pct
                        );
                        appendSwalRekapLog(
                            escapeHtmlRekap(res.step) + '. ' + escapeHtmlRekap(res.nama_rekap)
                            + ' — ' + escapeHtmlRekap(res.nominal_tampil)
                            + ' <small>(' + escapeHtmlRekap(res.aksi) + ')</small>',
                            true
                        );
                        return res;
                    });
                });
            })(s);
        }

        return chain
        .then(function() {
            Swal.close();
            if (opts.showTabAfter !== false) {
                tampilkanTabRekap();
            }
            return loadRekapDataOnly();
        })
        .then(function(res) {
            if (opts.submitFormAfter) {
                try {
                    sessionStorage.setItem('persediaan_show_tab', 'rekap');
                } catch (e) {}
                $('#form-persediaan-bulan').submit();
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Rekalkulasi Selesai',
                    text: 'Data rekap bulan ' + bulan + ' telah diperbarui (' + rekapTotalSteps + ' record).',
                    timer: 2200,
                    showConfirmButton: false
                });
            }
            if (typeof opts.onComplete === 'function') {
                opts.onComplete(res);
            }
            return res;
        })
        .catch(function(err) {
            Swal.fire({
                icon: 'error',
                title: 'Rekalkulasi Gagal',
                text: err && err.message ? err.message : String(err)
            });
            throw err;
        })
        .finally(function() {
            rekapRecalcRunning = false;
        });
    }

    var excelProgressTimer = null;

    function parseExcelFilename(disposition) {
        if (!disposition) {
            return 'Persediaan_export.xlsx';
        }
        var match = /filename\*=UTF-8''([^;]+)|filename="([^"]+)"|filename=([^;\s]+)/i.exec(disposition);
        if (!match) {
            return 'Persediaan_export.xlsx';
        }
        var name = (match[1] || match[2] || match[3] || '').trim();
        try {
            return decodeURIComponent(name);
        } catch (e) {
            return name;
        }
    }

    function tampilkanSwalExcelProgress() {
        Swal.fire({
            title: 'Memproses Excel',
            html: '<p style="margin:0 0 10px;font-size:14px;">Mohon tunggu, sedang menyiapkan file export...</p>'
                + '<div style="height:10px;background:#e9ecef;border-radius:5px;overflow:hidden;">'
                + '<div id="swal-excel-progress"></div></div>',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: function() {
                var bar = document.getElementById('swal-excel-progress');
                var pct = 15;
                if (excelProgressTimer) {
                    clearInterval(excelProgressTimer);
                }
                excelProgressTimer = setInterval(function() {
                    pct = Math.min(92, pct + 6);
                    if (bar) {
                        bar.style.width = pct + '%';
                    }
                }, 180);
            },
            willClose: function() {
                if (excelProgressTimer) {
                    clearInterval(excelProgressTimer);
                    excelProgressTimer = null;
                }
            }
        });
    }

    function selesaiSwalExcelProgress() {
        var bar = document.getElementById('swal-excel-progress');
        if (bar) {
            bar.style.width = '100%';
        }
        if (excelProgressTimer) {
            clearInterval(excelProgressTimer);
            excelProgressTimer = null;
        }
        Swal.close();
    }

    $('#btn-cetak-excel-persediaan').on('click', function() {
        var url = $(this).data('url');
        var bulan = $('#bulan_persediaan').val() || '';
        var formData = new FormData();
        formData.append('bulan_persediaan', bulan);

        tampilkanSwalExcelProgress();

        fetch(url, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Export Excel gagal (HTTP ' + response.status + ')');
            }
            var disposition = response.headers.get('Content-Disposition');
            var filename = parseExcelFilename(disposition);
            return response.blob().then(function(blob) {
                return { blob: blob, filename: filename };
            });
        })
        .then(function(result) {
            var link = document.createElement('a');
            var objectUrl = window.URL.createObjectURL(result.blob);
            link.href = objectUrl;
            link.download = result.filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(objectUrl);

            selesaiSwalExcelProgress();
            Swal.fire({
                icon: 'success',
                title: 'Selesai',
                text: 'File Excel berhasil diproses dan diunduh.',
                timer: 1800,
                showConfirmButton: false
            });
        })
        .catch(function(err) {
            if (excelProgressTimer) {
                clearInterval(excelProgressTimer);
                excelProgressTimer = null;
            }
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: err && err.message ? err.message : 'Terjadi kesalahan saat export Excel.'
            });
        });
    });

    $('#btn-cetak-excel-rekap').on('click', function() {
        var bulan = $('#bulan_rekap').val() || $('#bulan_persediaan').val() || '';
        var formData = new FormData();
        formData.append('bulan_persediaan', bulan);

        tampilkanSwalExcelProgress();

        fetch(urlRekapExcel, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Export Excel rekap gagal (HTTP ' + response.status + ')');
            }
            var disposition = response.headers.get('Content-Disposition');
            var filename = parseExcelFilename(disposition);
            return response.blob().then(function(blob) {
                return { blob: blob, filename: filename };
            });
        })
        .then(function(result) {
            var link = document.createElement('a');
            var objectUrl = window.URL.createObjectURL(result.blob);
            link.href = objectUrl;
            link.download = result.filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(objectUrl);

            selesaiSwalExcelProgress();
            Swal.fire({
                icon: 'success',
                title: 'Selesai',
                text: 'File Excel rekap berhasil diunduh.',
                timer: 1800,
                showConfirmButton: false
            });
        })
        .catch(function(err) {
            if (excelProgressTimer) {
                clearInterval(excelProgressTimer);
                excelProgressTimer = null;
            }
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: err && err.message ? err.message : 'Terjadi kesalahan saat export Excel rekap.'
            });
        });
    });

    // Setelah reload halaman (search), buka tab Rekap jika baru selesai rekalkulasi
    try {
        if (sessionStorage.getItem('persediaan_show_tab') === 'rekap') {
            sessionStorage.removeItem('persediaan_show_tab');
            syncBulanRekapDariPersediaan();
            setTimeout(function() {
                tampilkanTabRekap();
                loadRekapDataOnly();
            }, 300);
        }
    } catch (e) {}

    // Pilih bulan di tab Persediaan → rekalkulasi + progress → tab Rekap → refresh data persediaan
    $('#bulan_persediaan').on('change', function() {
        if (!$(this).val() || rekapRecalcRunning) {
            return;
        }
        syncBulanRekapDariPersediaan();
        $('#table-persediaan').css('opacity', '0.45');

        runRekapRecalcWithSwal({
            showTabAfter: true,
            submitFormAfter: true
        }).catch(function() {
            $('#table-persediaan').css('opacity', '1');
        });
    });

    $('#bulan_rekap').on('change', function() {
        if (!$(this).val() || rekapRecalcRunning) {
            return;
        }
        syncBulanPersediaanDariRekap();
        runRekapRecalcWithSwal({ showTabAfter: true, submitFormAfter: false });
    });

    $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
        if ($(e.target).attr('href') === '#panel-rekap') {
            syncBulanRekapDariPersediaan();
            if (!rekapRecalcRunning) {
                loadRekapDataOnly();
            }
        } else if ($(e.target).attr('href') === '#panel-data-persediaan') {
            dtPersediaan.columns.adjust().draw();
        }
    });
});
</script>
