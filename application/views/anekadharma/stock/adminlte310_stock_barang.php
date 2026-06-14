<?php
$this->load->helper('persediaan_display');
$action_cari_form = isset($action_cari) && $action_cari ? $action_cari : site_url('Tbl_pembelian/stock');
$Persediaan_data = isset($Persediaan_data) && is_array($Persediaan_data) ? $Persediaan_data : array();
$bulan_tampil = isset($bulan_persediaan_selected) && $bulan_persediaan_selected !== ''
    ? $bulan_persediaan_selected
    : date('Y-m');
$url_excel_stock = isset($url_excel_stock) ? $url_excel_stock : site_url('Tbl_pembelian/excel_stock');
$persediaan_fields_tgl_total = persediaan_list_fields_tgl_keluar_sampai_total_10();
$idx_col_total_10 = persediaan_list_col_index_total_10();
$idx_col_nilai_persediaan = persediaan_list_col_index_nilai_persediaan();
?>
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Stock Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right"></ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <strong>DATA STOCK BARANG (PERSEDIAAN)</strong>
                </div>
                <div class="card-body">

                    <form action="<?php echo $action_cari_form; ?>" method="post" id="form-stock-bulan">
                        <div class="row mb-3 align-items-end">
                            <div class="col-md-12">
                                <label for="bulan_persediaan">Bulan:</label>
                                <input type="month" id="bulan_persediaan" name="bulan_persediaan" class="form-control d-inline-block" style="width:auto;vertical-align:middle;" value="<?php echo htmlspecialchars($bulan_tampil, ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="button" id="btn-cetak-excel-stock" class="btn btn-primary ml-1">Cetak ke Excel</button>
                            </div>
                        </div>
                    </form>

                    <table id="table-stock-persediaan" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th width="50px">No</th>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Namabarang</th>
                                <th>Satuan</th>
                                <th>Hpp</th>
                                <th>Sa</th>
                                <th>Spop</th>
                                <th>Beli</th>
                                <th>Tuj</th>
                                <?php foreach ($persediaan_fields_tgl_total as $field_tgl_total) { ?>
                                    <th><?php echo htmlspecialchars(persediaan_field_label($field_tgl_total), ENT_QUOTES, 'UTF-8'); ?></th>
                                    <?php if (persediaan_field_has_nominal_column($field_tgl_total)) { ?>
                                        <th class="text-right"><?php echo htmlspecialchars(persediaan_field_nominal_header_label($field_tgl_total), ENT_QUOTES, 'UTF-8'); ?></th>
                                    <?php } ?>
                                <?php } ?>
                                <th>Nilai Persediaan</th>
                                <th>Terjual</th>
                                <th>Jumlah Pecah Satuan</th>
                                <th>Bahan Produksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $start = 0;
                            $total_total_10 = 0;
                            $total_nilai_persediaan = 0;
                            $total_nominal_unit = array();
                            foreach (persediaan_list_unit_columns() as $uf_total) {
                                $total_nominal_unit[$uf_total] = 0;
                            }
                            foreach ($Persediaan_data as $persediaan) {
                                $total_10_row = persediaan_hitung_total_10_kalkulasi($persediaan);
                                $nilai_persediaan_row = persediaan_hitung_nilai_persediaan_stock_row($persediaan);
                                $total_total_10 += $total_10_row;
                                $total_nilai_persediaan += $nilai_persediaan_row;
                                foreach (persediaan_list_unit_columns() as $uf_total) {
                                    $total_nominal_unit[$uf_total] += persediaan_hitung_kolom_nominal_row($persediaan, $uf_total);
                                }
                            ?>
                                <tr>
                                    <td><?php echo ++$start ?></td>
                                    <td><?php echo persediaan_format_bulan_tahun($persediaan, $bulan_tampil); ?></td>
                                    <td><?php echo isset($persediaan->kategori) ? htmlspecialchars($persediaan->kategori, ENT_QUOTES, 'UTF-8') : ''; ?></td>
                                    <td><?php echo $persediaan->namabarang ?></td>
                                    <td><?php echo $persediaan->satuan ?></td>
                                    <td><?php echo $persediaan->hpp ?></td>
                                    <td><?php echo $persediaan->sa ?></td>
                                    <td><?php echo $persediaan->spop ?></td>
                                    <td><?php echo $persediaan->beli ?></td>
                                    <td><?php echo $persediaan->tuj ?></td>
                                    <?php foreach ($persediaan_fields_tgl_total as $field_tgl_total) { ?>
                                        <?php if ($field_tgl_total === 'total_10') { ?>
                                            <td><?php echo persediaan_tampil_total_10_stock_row($persediaan); ?></td>
                                        <?php } else { ?>
                                            <td><?php echo persediaan_row_get($persediaan, $field_tgl_total); ?></td>
                                            <?php if (persediaan_field_has_nominal_column($field_tgl_total)) { ?>
                                                <td class="text-right"><?php echo persediaan_tampil_kolom_nominal_row($persediaan, $field_tgl_total); ?></td>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <td><?php echo persediaan_format_angka_tampil($nilai_persediaan_row); ?></td>
                                    <td><?php echo isset($persediaan->penjualan) ? $persediaan->penjualan : 0 ?></td>
                                    <td><?php echo isset($persediaan->pecah_satuan) ? $persediaan->pecah_satuan : 0 ?></td>
                                    <td><?php echo isset($persediaan->bahan_produksi) ? $persediaan->bahan_produksi : 0 ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <?php
                                $footer_cells = persediaan_datatable_footer_cells($total_total_10, $total_nilai_persediaan, $total_nominal_unit);
                                $idx_foot_total_10 = persediaan_list_col_index_total_10();
                                $idx_foot_nilai = persediaan_list_col_index_nilai_persediaan();
                                $idx_foot_nominal = array();
                                foreach (persediaan_list_unit_columns() as $uf_foot) {
                                    if (persediaan_field_has_nominal_column($uf_foot)) {
                                        $idx_foot_nominal[] = persediaan_list_col_index_unit_nominal($uf_foot);
                                    }
                                }
                                foreach ($footer_cells as $col_foot => $foot_val) {
                                    $foot_val = (string) $foot_val;
                                    $cls = '';
                                    if ($foot_val === 'Total') {
                                        $cls = ' persediaan-foot-total-label';
                                    } elseif ($foot_val !== '' && (
                                        $col_foot === $idx_foot_total_10
                                        || $col_foot === $idx_foot_nilai
                                        || in_array($col_foot, $idx_foot_nominal, true)
                                    )) {
                                        $cls = ' persediaan-foot-num';
                                    }
                                    echo '<th class="' . trim($cls) . '">' . htmlspecialchars($foot_val, ENT_QUOTES, 'UTF-8') . '</th>';
                                }
                                ?>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </section>
</div>

<style>
    #table-stock-persediaan tfoot th {
        font-weight: bold;
        background: #f8f9fa;
        padding: 6px 8px;
    }
    #table-stock-persediaan tfoot th.persediaan-foot-total-label {
        text-align: right;
        white-space: nowrap;
    }
    #table-stock-persediaan tfoot th.persediaan-foot-num {
        text-align: right;
        white-space: nowrap;
    }
    #swal-excel-progress {
        height: 100%;
        width: 15%;
        background: #28a745;
        border-radius: 5px;
        transition: width 0.2s ease;
    }
</style>

<script>
window.addEventListener('load', function() {
    if (!window.jQuery || !jQuery.fn || !jQuery.fn.dataTable) {
        console.error('Stock: jQuery/DataTables belum dimuat. Muat ulang halaman.');
        return;
    }
    var $ = window.jQuery;
    var urlExcelStock = <?php echo json_encode($url_excel_stock); ?>;
    var excelProgressTimer = null;
    var bulanStockAktif = <?php echo json_encode($bulan_tampil); ?>;

    $('#bulan_persediaan').on('change', function() {
        var bulan = $(this).val() || '';
        if (!bulan || bulan === bulanStockAktif) {
            return;
        }
        bulanStockAktif = bulan;
        $('#form-stock-bulan').trigger('submit');
    });

    try {
        if ($.fn.DataTable.isDataTable('#table-stock-persediaan')) {
            $('#table-stock-persediaan').DataTable().destroy();
        }
        $('#table-stock-persediaan').DataTable({
            scrollY: 500,
            scrollX: true,
            scrollCollapse: true,
            pageLength: 25,
            order: [[3, 'asc']],
            columnDefs: [
                { targets: 0, orderable: false },
                { targets: 3, type: 'string' }
            ],
            language: {
                emptyTable: 'Belum ada data',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
                infoFiltered: '(disaring dari _MAX_ total data)',
                lengthMenu: 'Tampilkan _MENU_ data',
                search: 'Cari:',
                zeroRecords: 'Tidak ada data yang cocok',
                paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' }
            }
        });
    } catch (dtErr) {
        console.warn('DataTable stock:', dtErr);
    }

    function parseExcelFilename(disposition) {
        if (!disposition) {
            return 'Stock_Persediaan.xlsx';
        }
        var match = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(disposition);
        var name = (match && match[1] ? match[1] : '').replace(/['"]/g, '').trim();
        try {
            return decodeURIComponent(name) || 'Stock_Persediaan.xlsx';
        } catch (e) {
            return name || 'Stock_Persediaan.xlsx';
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

    function unduhExcelDariResponse(response) {
        if (!response.ok) {
            throw new Error('Export Excel gagal (HTTP ' + response.status + ')');
        }
        var ct = (response.headers.get('Content-Type') || '').toLowerCase();
        var disposition = response.headers.get('Content-Disposition');
        return response.blob().then(function(blob) {
            if (ct.indexOf('html') !== -1 || (blob.size < 8000 && disposition === null)) {
                return blob.text().then(function(txt) {
                    if (txt.indexOf('<!DOCTYPE') !== -1 || txt.indexOf('<html') !== -1) {
                        throw new Error('Sesi habis atau server mengembalikan halaman HTML. Login ulang lalu coba lagi.');
                    }
                    throw new Error('Respon server bukan file Excel.');
                });
            }
            return {
                blob: blob,
                filename: parseExcelFilename(disposition)
            };
        });
    }

    function triggerDownloadBlob(result) {
        var link = document.createElement('a');
        var objectUrl = window.URL.createObjectURL(result.blob);
        link.href = objectUrl;
        link.download = result.filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(objectUrl);
    }

    $('#btn-cetak-excel-stock').on('click', function(e) {
        e.preventDefault();
        if (!urlExcelStock) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'URL export Excel tidak tersedia.' });
            return;
        }
        var bulan = $('#bulan_persediaan').val() || '';
        var formData = new FormData();
        formData.append('bulan_persediaan', bulan);

        tampilkanSwalExcelProgress();

        fetch(urlExcelStock, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(unduhExcelDariResponse)
        .then(function(result) {
            triggerDownloadBlob(result);
            selesaiSwalExcelProgress();
            Swal.fire({
                icon: 'success',
                title: 'Selesai',
                text: 'File Excel stock persediaan berhasil diunduh.',
                timer: 1800,
                showConfirmButton: false
            });
        })
        .catch(function(err) {
            if (excelProgressTimer) {
                clearInterval(excelProgressTimer);
                excelProgressTimer = null;
            }
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: err && err.message ? err.message : 'Terjadi kesalahan saat export Excel.'
            });
        });
    });
});
</script>
