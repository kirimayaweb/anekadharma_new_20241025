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
                            $bulan_tampil = isset($bulan_persediaan_selected) ? $bulan_persediaan_selected : '';
                            ?>
                            <form action="<?php echo $action_cari_form; ?>" method="post" id="form-persediaan-bulan">
                                <div class="row mb-3 align-items-end">
                                    <div class="col-md-12">
                                        <strong>DATA PERSEDIAAN</strong>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <label for="bulan_persediaan">Bulan:</label>
                                        <input type="month" id="bulan_persediaan" name="bulan_persediaan" class="form-control d-inline-block" style="width:auto;vertical-align:middle;" value="<?php echo isset($bulan_persediaan_selected) ? htmlspecialchars($bulan_persediaan_selected) : ''; ?>">
                                        <button type="submit" class="btn btn-danger ml-1">Cari</button>
                                        <button type="submit" formaction="<?php echo site_url('persediaan/cetak_pdf'); ?>" formtarget="_blank" class="btn btn-success ml-1">Cetak PDF</button>
                                        <button type="submit" formaction="<?php echo site_url('persediaan/excel'); ?>" class="btn btn-primary ml-1">Cetak ke Excel</button>
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

                        <!-- TAB 2: REKAP PERUSAHAAN (sys_konsumen) -->
                        <div class="tab-pane fade" id="panel-rekap" role="tabpanel" aria-labelledby="tab-rekap">
                            <?php
                            $sys_konsumen_data = isset($sys_konsumen_data) && is_array($sys_konsumen_data) ? $sys_konsumen_data : array();
                            ?>
                            <p class="mb-2"><strong>Rekap Data Perusahaan</strong> (tabel <code>sys_konsumen</code>)</p>
                            <table id="table-rekap-konsumen" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="50px">No</th>
                                        <th>Kode</th>
                                        <th>Nama Perusahaan</th>
                                        <th>Kelompok di Persediaan</th>
                                        <th>No. Kontak</th>
                                        <th>Alamat</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no_rekap = 0;
                                    foreach ($sys_konsumen_data as $konsumen) {
                                    ?>
                                        <tr>
                                            <td><?php echo ++$no_rekap ?></td>
                                            <td><?php echo isset($konsumen->kode_konsumen) ? $konsumen->kode_konsumen : '' ?></td>
                                            <td><?php echo isset($konsumen->nama_konsumen) ? $konsumen->nama_konsumen : '' ?></td>
                                            <td><?php echo isset($konsumen->kelompok_dipersediaan) ? $konsumen->kelompok_dipersediaan : '' ?></td>
                                            <td><?php echo isset($konsumen->nmr_kontak_konsumen) ? $konsumen->nmr_kontak_konsumen : '' ?></td>
                                            <td><?php echo isset($konsumen->alamat_konsumen) ? $konsumen->alamat_konsumen : '' ?></td>
                                            <td><?php echo isset($konsumen->keterangan) ? $konsumen->keterangan : '' ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
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

    var dtRekap = $('#table-rekap-konsumen').DataTable({
        scrollY: 400,
        scrollX: true,
        pageLength: 25,
        order: [[2, 'asc']]
    });

    // Perbaiki lebar kolom saat tab Rekap pertama kali dibuka
    $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
        if ($(e.target).attr('href') === '#panel-rekap') {
            dtRekap.columns.adjust().draw();
        } else if ($(e.target).attr('href') === '#panel-data-persediaan') {
            dtPersediaan.columns.adjust().draw();
        }
    });
});
</script>
