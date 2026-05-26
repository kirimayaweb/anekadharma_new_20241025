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
                        <li class="nav-item">
                            <a class="nav-link" id="tab-generate-persediaan" data-toggle="pill" href="#panel-generate-persediaan" role="tab" aria-controls="panel-generate-persediaan" aria-selected="false">Generate Persediaan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-recalculate" data-toggle="pill" href="#panel-recalculate" role="tab" aria-controls="panel-recalculate" aria-selected="false">Recalculate</a>
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
                                <div class="row mb-2 align-items-center">
                                    <div class="col-md-12 d-flex align-items-center flex-wrap">
                                        <strong class="mb-0">DATA PERSEDIAAN</strong>
                                        <button type="button" class="btn btn-success btn-sm ml-2" id="btn-tambah-persediaan" title="Tambah barang/jasa ke persediaan bulan terpilih">
                                            <i class="fas fa-plus"></i> Tambah Persediaan
                                        </button>
                                    </div>
                                </div>
                                <div class="row mb-3 align-items-end">
                                    <div class="col-md-12">
                                        <label for="bulan_persediaan">Bulan:</label>
                                        <input type="month" id="bulan_persediaan" name="bulan_persediaan" class="form-control d-inline-block" style="width:auto;vertical-align:middle;" value="<?php echo htmlspecialchars($bulan_tampil); ?>">
                                        <button type="submit" class="btn btn-danger ml-1 btn-cari-persediaan">Cari</button>
                                        <button type="button" id="btn-cetak-excel-persediaan" class="btn btn-primary ml-1" data-url="<?php echo site_url('persediaan/excel'); ?>">Cetak ke Excel</button>
                                    </div>
                                </div>
                            </form>

                            <?php
                            $persediaan_fields_tgl_total = persediaan_list_fields_tgl_keluar_sampai_total_10();
                            $idx_col_total_10 = persediaan_list_col_index_total_10();
                            $idx_col_nilai_persediaan = persediaan_list_col_index_nilai_persediaan();
                            $total_kolom_persediaan = count(persediaan_export_headers());
                            ?>
                            <table id="table-persediaan" class="table table-bordered table-striped" style="width:100%">
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
                                        <?php } ?>
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
                                    $total_total_10 = 0;
                                    $total_nilai_persediaan = 0;
                                    foreach ($Persediaan_data as $persediaan) {
                                        $total_10_row = persediaan_parse_angka(persediaan_row_get($persediaan, 'total_10'));
                                        $nilai_persediaan_row = persediaan_hitung_nilai_persediaan_row($persediaan);
                                        $total_total_10 += $total_10_row;
                                        $total_nilai_persediaan += $nilai_persediaan_row;
                                        $sisa_stock = persediaan_hitung_sisa_stock($persediaan);
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
                                                <td><?php echo persediaan_row_get($persediaan, $field_tgl_total); ?></td>
                                            <?php } ?>
                                            <td><?php echo persediaan_format_angka_tampil($nilai_persediaan_row); ?></td>
                                            <td><?php echo isset($persediaan->penjualan) ? $persediaan->penjualan : 0 ?></td>
                                            <td><?php echo isset($persediaan->pecah_satuan) ? $persediaan->pecah_satuan : 0 ?></td>
                                            <td><?php echo isset($persediaan->bahan_produksi) ? $persediaan->bahan_produksi : 0 ?></td>
                                            <td><?php echo is_numeric($sisa_stock) && floor($sisa_stock) == $sisa_stock ? (int) $sisa_stock : $sisa_stock; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <?php for ($col_foot = 0; $col_foot < $total_kolom_persediaan; $col_foot++) { ?>
                                            <?php if ($col_foot === ($idx_col_total_10 - 1)) { ?>
                                                <th style="text-align:right;">Total</th>
                                            <?php } elseif ($col_foot === $idx_col_total_10) { ?>
                                                <th style="text-align:right;"><?php echo persediaan_format_angka_tampil($total_total_10); ?></th>
                                            <?php } elseif ($col_foot === $idx_col_nilai_persediaan) { ?>
                                                <th style="text-align:right;"><?php echo persediaan_format_angka_tampil($total_nilai_persediaan); ?></th>
                                            <?php } else { ?>
                                                <th></th>
                                            <?php } ?>
                                        <?php } ?>
                                    </tr>
                                </tfoot>
                            </table>

                            <div class="modal fade" id="modal-tambah-persediaan" tabindex="-1" role="dialog" aria-labelledby="modalTambahPersediaanLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title" id="modalTambahPersediaanLabel">Tambah Persediaan</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form id="form-tambah-persediaan" autocomplete="off">
                                            <div class="modal-body">
                                                <p class="text-muted small mb-3" id="info-bulan-tambah-persediaan">
                                                    Bulan persediaan: <strong><?php echo htmlspecialchars(date('m/Y', strtotime($bulan_tampil . '-01')), ENT_QUOTES, 'UTF-8'); ?></strong>
                                                    — <em>tanggal beli otomatis tanggal 1 bulan tersebut</em>
                                                </p>
                                                <div class="form-group">
                                                    <label for="tambah_namabarang">Nama Barang / Jasa <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="tambah_namabarang" name="namabarang" placeholder="Nama barang atau jasa" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tambah_satuan">Satuan <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="tambah_satuan" name="satuan" placeholder="Contoh: PCS, KG, LITER" required>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label for="tambah_harga_satuan">Harga Satuan (HPP) <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="tambah_harga_satuan" name="harga_satuan" placeholder="0" inputmode="numeric" autocomplete="off" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success" id="btn-submit-tambah-persediaan">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
                            <div id="rekap-table-wrap" class="table-responsive" style="max-height:420px;">
                            <table id="table-rekap" class="table table-bordered table-striped table-sm mb-0" style="width:100%">
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

                        <!-- TAB 3: GENERATE PERSEDIAAN BULAN BARU -->
                        <?php
                        $gen_bulan_default = isset($gen_bulan_default) ? (int) $gen_bulan_default : (int) date('n');
                        $gen_tahun_default = isset($gen_tahun_default) ? (int) $gen_tahun_default : (int) date('Y');
                        $gen_tahun_min = isset($gen_tahun_min) ? (int) $gen_tahun_min : 2020;
                        $gen_tahun_max = isset($gen_tahun_max) ? (int) $gen_tahun_max : ((int) date('Y') + 2);
                        $nama_bulan_id = array(
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                        );
                        ?>
                        <div class="tab-pane fade" id="panel-generate-persediaan" role="tabpanel" aria-labelledby="tab-generate-persediaan">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5 class="mb-2">Generate data persediaan dari data bulan sebelumnya</h5>
                                    <p class="text-muted small mb-3">
                                        Memproses seluruh record dari <strong>bulan sebelumnya</strong> (1:1 ke bulan target).
                                        Disalin: <strong>uuid_barang, namabarang, satuan, hpp</strong>; <strong>uuid_persediaan</strong> baru;
                                        <strong>tanggal_beli</strong> = tanggal 1 bulan terpilih.
                                        <strong>sa</strong> dan <strong>total_10</strong> = nilai <strong>total_10</strong> bulan sumber (sama).
                                        <strong>beli</strong>, <strong>penjualan</strong>, dan kolom distribusi = <strong>0</strong>
                                        (diisi lewat tab Recalculate pembelian &amp; penjualan).
                                        Field lain = 0/kosong. Data bulan target lama dihapus dulu saat generate.
                                        <em>Hanya user Admin / Administrator (id_user_level 1, 2, atau 99).</em>
                                    </p>
                                    <?php if (empty($can_generate_persediaan)) { ?>
                                    <div class="alert alert-warning py-2">
                                        Akun Anda tidak memiliki akses generate. Diperlukan level <strong>Admin</strong> / <strong>Administrator</strong>
                                        (<code>id_user_level</code> = 1, 2, atau 99).
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-end">
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <label for="gen_bulan_persediaan">Bulan target</label>
                                    <select id="gen_bulan_persediaan" class="form-control">
                                        <?php foreach ($nama_bulan_id as $num => $label_bulan) { ?>
                                            <option value="<?php echo (int) $num; ?>"<?php echo ((int) $num === $gen_bulan_default) ? ' selected' : ''; ?>>
                                                <?php echo htmlspecialchars($label_bulan, ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <label for="gen_tahun_persediaan">Tahun target</label>
                                    <select id="gen_tahun_persediaan" class="form-control">
                                        <?php for ($th = $gen_tahun_max; $th >= $gen_tahun_min; $th--) { ?>
                                            <option value="<?php echo (int) $th; ?>"<?php echo ((int) $th === $gen_tahun_default) ? ' selected' : ''; ?>>
                                                <?php echo (int) $th; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-6 col-sm-12 mb-2">
                                    <button type="button" id="btn-generate-persediaan-bulan" class="btn btn-secondary btn-lg" disabled>
                                        <i class="fas fa-copy"></i> Generate Persediaan Bulan Terpilih
                                    </button>
                                    <button type="button" id="btn-cetak-excel-generate" class="btn btn-primary btn-lg ml-1">
                                        <i class="fas fa-file-excel"></i> Cetak ke Excel
                                    </button>
                                </div>
                            </div>
                            <div class="alert alert-secondary" id="gen-persediaan-info-sumber">
                                <strong>Bulan sumber:</strong> <span id="gen-label-bulan-sumber">—</span>
                                &nbsp;|&nbsp;
                                <strong>Record sumber:</strong> <span id="gen-count-sumber">—</span>
                                &nbsp;|&nbsp;
                                <strong>Record target (sudah ada):</strong> <span id="gen-count-target">—</span>
                            </div>
                            <div class="alert alert-info" id="gen-persediaan-status">
                                Pilih bulan dan tahun target, lalu sistem akan mengecek ketersediaan data.
                            </div>
                            <div class="card card-outline card-warning d-none" id="gen-persediaan-link-wrap">
                                <div class="card-body py-2">
                                    <span class="text-muted small">URL proses generate:</span>
                                    <a href="#" id="gen-persediaan-url-link" target="_blank" rel="noopener"></a>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 4: RECALCULATE -->
                        <?php
                        $recalc_bulan_default = isset($bulan_tampil) && preg_match('/^\d{4}-\d{2}$/', $bulan_tampil)
                            ? $bulan_tampil
                            : date('Y-m');
                        $recalc_parts = explode('-', $recalc_bulan_default);
                        $recalc_bulan_num = isset($recalc_parts[1]) ? (int) $recalc_parts[1] : (int) date('n');
                        $recalc_tahun_num = isset($recalc_parts[0]) ? (int) $recalc_parts[0] : (int) date('Y');
                        ?>
                        <div class="tab-pane fade" id="panel-recalculate" role="tabpanel" aria-labelledby="tab-recalculate">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5 class="mb-2">Recalculate data persediaan dari pembelian &amp; penjualan</h5>
                                    <p class="text-muted small mb-0">
                                        Cek <code>tbl_pembelian</code>, <code>tbl_pembelian_jasa</code>, <code>tbl_penjualan</code> bulan terpilih.
                                        Update <strong>beli</strong> dari pembelian (uuid+satuan+hpp) dan <strong>penjualan</strong> + kolom unit dari penjualan.
                                        Proses via modal — halaman tetap di sini.
                                    </p>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-end">
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <label for="recalc_bulan_persediaan">Bulan</label>
                                    <select id="recalc_bulan_persediaan" class="form-control">
                                        <?php foreach ($nama_bulan_id as $num => $label_bulan) { ?>
                                            <option value="<?php echo (int) $num; ?>"<?php echo ((int) $num === $recalc_bulan_num) ? ' selected' : ''; ?>>
                                                <?php echo htmlspecialchars($label_bulan, ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <label for="recalc_tahun_persediaan">Tahun</label>
                                    <select id="recalc_tahun_persediaan" class="form-control">
                                        <?php for ($th = $gen_tahun_max; $th >= $gen_tahun_min; $th--) { ?>
                                            <option value="<?php echo (int) $th; ?>"<?php echo ((int) $th === $recalc_tahun_num) ? ' selected' : ''; ?>>
                                                <?php echo (int) $th; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-6 col-sm-12 mb-2">
                                    <button type="button" id="btn-recalculate-persediaan-tab" class="btn btn-info btn-lg">
                                        <i class="fas fa-sync-alt"></i> Recalculate
                                    </button>
                                    <button type="button" id="btn-cetak-excel-recalculate" class="btn btn-success btn-lg ml-1">
                                        <i class="fas fa-file-excel"></i> Cetak ke Excel
                                    </button>
                                </div>
                            </div>
                            <div class="alert alert-secondary py-2" id="recalc-info-ringkas">
                                <strong>Bulan:</strong> <span id="recalc-label-bulan">—</span>
                                &nbsp;|&nbsp; <strong>Persediaan:</strong> <span id="recalc-count-persediaan">—</span>
                                &nbsp;|&nbsp; <strong>Pembelian:</strong> <span id="recalc-count-pembelian">—</span>
                                &nbsp;|&nbsp; <strong>Penjualan:</strong> <span id="recalc-count-penjualan">—</span>
                            </div>
                            <div class="alert alert-info py-2 mb-0" id="recalc-status">
                                Pilih bulan dan tahun, lalu klik <strong>Recalculate</strong>.
                            </div>
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
    #btn-generate-persediaan-bulan:disabled { cursor: not-allowed; opacity: 0.72; }
    #btn-generate-persediaan-bulan.btn-success { color: #fff; }
    #btn-generate-persediaan-bulan.btn-danger { color: #fff; }
    #gen-persediaan-status.alert-success { color: #155724; background: #d4edda; border-color: #c3e6cb; }
    #gen-persediaan-status.alert-danger { color: #721c24; background: #f8d7da; border-color: #f5c6cb; }
    #recalc-status.alert-success { color: #155724; background: #d4edda; border-color: #c3e6cb; }
    #recalc-status.alert-danger { color: #721c24; background: #f8d7da; border-color: #f5c6cb; }
    #swal-recalc-log {
        max-height: 180px;
        overflow-y: auto;
        font-size: 11px;
        text-align: left;
        margin-top: 10px;
        padding: 8px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }
    #swal-recalc-progress {
        height: 100%;
        width: 0%;
        background: #17a2b8;
        border-radius: 5px;
        transition: width 0.25s ease;
    }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    var urlTambahPersediaan = <?php echo json_encode(isset($url_tambah_persediaan) ? $url_tambah_persediaan : site_url('Persediaan/ajax_tambah_persediaan')); ?>;
    var urlCekGeneratePersediaan = <?php echo json_encode(isset($url_cek_generate_persediaan) ? $url_cek_generate_persediaan : site_url('Persediaan/ajax_cek_generate_persediaan_bulan')); ?>;
    var urlAnalisaGeneratePersediaan = <?php echo json_encode(isset($url_analisa_generate_persediaan) ? $url_analisa_generate_persediaan : site_url('Persediaan/ajax_analisa_generate_persediaan_bulan')); ?>;
    var urlAnalisaRecalculatePersediaan = <?php echo json_encode(isset($url_analisa_recalculate_persediaan) ? $url_analisa_recalculate_persediaan : site_url('Persediaan/ajax_analisa_recalculate_persediaan')); ?>;
    var urlRecalculatePersediaanBatch = <?php echo json_encode(isset($url_recalculate_persediaan_batch) ? $url_recalculate_persediaan_batch : site_url('Persediaan/ajax_recalculate_persediaan_batch')); ?>;
    var urlRecalculateExcel = <?php echo json_encode(isset($url_recalculate_excel) ? $url_recalculate_excel : site_url('Persediaan/excel_recalculate')); ?>;
    var urlExcelPersediaan = <?php echo json_encode(site_url('Persediaan/excel')); ?>;
    var userCanGeneratePersediaan = <?php echo !empty($can_generate_persediaan) ? 'true' : 'false'; ?>;
    var genCekXhr = null;

    function getBulanTargetGenerate() {
        var bulan = parseInt($('#gen_bulan_persediaan').val(), 10);
        var tahun = parseInt($('#gen_tahun_persediaan').val(), 10);
        if (!bulan || bulan < 1 || bulan > 12 || !tahun) {
            return '';
        }
        return tahun + '-' + ('0' + bulan).slice(-2);
    }

    function setStatusGeneratePersediaan(type, html) {
        var $box = $('#gen-persediaan-status');
        $box.removeClass('alert-info alert-success alert-danger alert-warning');
        if (type) {
            $box.addClass('alert-' + type);
        } else {
            $box.addClass('alert-info');
        }
        $box.html(html);
    }

    function updateTombolGeneratePersediaan(mode, url) {
        var $btn = $('#btn-generate-persediaan-bulan');
        $btn.removeClass('btn-success btn-danger btn-warning btn-secondary');
        if (!userCanGeneratePersediaan) {
            $btn.prop('disabled', true).addClass('btn-secondary').removeData('url');
            $('#gen-persediaan-link-wrap').addClass('d-none');
            return;
        }
        if (mode === 'ready' && url) {
            $btn.prop('disabled', false).addClass('btn-success').data('url', url);
            $('#gen-persediaan-url-link').attr('href', url).text(url);
            $('#gen-persediaan-link-wrap').removeClass('d-none');
        } else if (mode === 'denied') {
            $btn.prop('disabled', true).addClass('btn-danger').removeData('url');
            $('#gen-persediaan-link-wrap').addClass('d-none');
        } else {
            $btn.prop('disabled', true).addClass('btn-secondary').removeData('url');
            $('#gen-persediaan-link-wrap').addClass('d-none');
        }
    }

    function cekGeneratePersediaanBulan() {
        var bulanKey = getBulanTargetGenerate();
        if (!bulanKey) {
            setStatusGeneratePersediaan('warning', 'Pilih bulan dan tahun target yang valid.');
            updateTombolGeneratePersediaan('idle');
            return;
        }

        if (genCekXhr && genCekXhr.readyState !== 4) {
            genCekXhr.abort();
        }

        setStatusGeneratePersediaan('info', '<i class="fas fa-spinner fa-spin"></i> Mengecek data persediaan bulan <strong>' + bulanKey + '</strong>...');
        updateTombolGeneratePersediaan('idle');

        genCekXhr = $.ajax({
            url: urlCekGeneratePersediaan,
            type: 'POST',
            dataType: 'json',
            data: { bulan: bulanKey }
        }).done(function(res) {
            if (!res || !res.ok) {
                setStatusGeneratePersediaan('danger', (res && res.message) ? res.message : 'Gagal mengecek data persediaan.');
                $('#gen-label-bulan-sumber').text('—');
                $('#gen-count-sumber').text('—');
                return;
            }

            var labelSumber = res.bulan_sumber || '';
            if (labelSumber.indexOf('-') === 4) {
                var ps = labelSumber.split('-');
                labelSumber = ps[1] + '/' + ps[0];
            }
            $('#gen-label-bulan-sumber').text(labelSumber + ' (tanggal_beli ' + (res.tanggal_beli_sumber || '') + ')');
            $('#gen-count-sumber').text(typeof res.count_sumber !== 'undefined' ? res.count_sumber : '0');

            $('#gen-count-target').text(typeof res.count_target !== 'undefined' ? res.count_target : '0');

            if (res.user_can_generate === false || !userCanGeneratePersediaan) {
                setStatusGeneratePersediaan('warning', res.message || 'Hanya Admin / Administrator (level 1, 2, atau 99) yang dapat generate.');
                updateTombolGeneratePersediaan('denied');
            } else if (!res.can_generate) {
                setStatusGeneratePersediaan('warning', res.message || 'Belum dapat generate (bulan sumber kosong).');
                updateTombolGeneratePersediaan('idle');
            } else {
                setStatusGeneratePersediaan('success', res.message || 'Siap generate. Tombol <strong>hijau</strong> — meski sudah ada data di bulan target.');
                updateTombolGeneratePersediaan('ready', res.url_generate || '');
            }
        }).fail(function() {
            setStatusGeneratePersediaan('danger', 'Gagal menghubungi server. Periksa koneksi lalu coba lagi.');
            updateTombolGeneratePersediaan('idle');
        });
    }

    $('#gen_bulan_persediaan, #gen_tahun_persediaan').on('change', function() {
        cekGeneratePersediaanBulan();
    });

    function buildHtmlAnalisaGenerate(a) {
        var html = '<div style="text-align:left;font-size:13px;line-height:1.5;">';
        html += '<p><strong>Bulan sumber:</strong> ' + (a.bulan_sumber_label || '') + ' (' + (a.tanggal_beli_sumber || '') + ')</p>';
        html += '<p><strong>Bulan target:</strong> ' + (a.bulan_target_label || '') + ' (' + (a.tanggal_beli_target || '') + ')</p>';
        html += '<table class="table table-sm table-bordered mb-2" style="font-size:12px;">';
        html += '<tr><td>Total record bulan sumber</td><td class="text-right"><strong>' + (a.total_sumber || 0) + '</strong></td></tr>';
        html += '<tr><td>Record sudah ada di bulan target</td><td class="text-right"><strong>' + (a.total_target || 0) + '</strong></td></tr>';
        html += '<tr><td>Perkiraan INSERT (salin semua baris sumber)</td><td class="text-right text-success"><strong>' + (a.estimasi_insert || 0) + '</strong></td></tr>';
        if ((a.total_target || 0) > 0) {
            html += '<tr><td>Record bulan target lama (akan dihapus dulu)</td><td class="text-right text-warning"><strong>' + (a.total_target || 0) + '</strong></td></tr>';
        }
        html += '<tr><td>Total target setelah generate</td><td class="text-right"><strong>' + (a.estimasi_total_target_setelah || a.total_sumber || 0) + '</strong></td></tr>';
        html += '<tr><td>Harus sama dengan total sumber</td><td class="text-right"><strong>' + (a.total_sumber || 0) + '</strong></td></tr>';
        html += '<tr><td>Grup uuid_barang ganda (bulan sumber)</td><td class="text-right"><strong>' + (a.grup_duplikat_uuid_barang_sumber || 0) + '</strong> grup / ' + (a.baris_duplikat_uuid_barang_sumber || 0) + ' baris tambahan</td></tr>';
        html += '<tr><td>Grup uuid_barang ganda (bulan target)</td><td class="text-right"><strong>' + (a.grup_duplikat_uuid_barang_target || 0) + '</strong> grup</td></tr>';
        html += '<tr><td>Record sumber tanpa uuid_barang</td><td class="text-right">'
            + '<strong class="' + ((a.estimasi_kosong_uuid_barang || 0) > 0 ? 'text-danger' : '') + '">'
            + (a.estimasi_kosong_uuid_barang || 0) + '</strong></td></tr>';
        html += '</table>';
        html += '<p class="text-muted small mb-2">' + (a.penjelasan || '') + '</p>';

        var uk = a.uuid_barang_kosong || {};
        if ((uk.total_kosong_sumber || 0) > 0) {
            html += '<div class="alert alert-warning py-2 px-2 mb-2" style="font-size:12px;">';
            html += '<strong>uuid_barang kosong di bulan sumber:</strong> ' + uk.total_kosong_sumber + ' record.<br/>';
            html += (uk.penjelasan || '') + '<br/>';
            html += '<em>Saat generate: tiap baris akan mendapat <strong>uuid_barang baru unik</strong> di bulan sumber, lalu disalin ke bulan target.</em>';
            html += '</div>';

            if (uk.rekap_penyebab && uk.rekap_penyebab.length) {
                html += '<p class="mb-1"><strong>Rekap penyebab:</strong></p>';
                html += '<table class="table table-sm table-bordered mb-2" style="font-size:11px;">';
                html += '<thead><tr><th>Penyebab</th><th class="text-right">Jumlah</th></tr></thead><tbody>';
                for (var p = 0; p < uk.rekap_penyebab.length; p++) {
                    var rp = uk.rekap_penyebab[p];
                    html += '<tr><td>' + (rp.label || rp.kode || '') + '</td><td class="text-right"><strong>' + (rp.jumlah || 0) + '</strong></td></tr>';
                }
                html += '</tbody></table>';
            }

            if (uk.daftar_sample && uk.daftar_sample.length) {
                html += '<p class="mb-1"><strong>Contoh record (max 25):</strong></p>';
                html += '<div style="max-height:160px;overflow:auto;font-size:11px;border:1px solid #dee2e6;padding:6px;">';
                html += '<table class="table table-sm mb-0"><thead><tr><th>ID</th><th>Nama</th><th>Satuan</th><th>Penyebab</th></tr></thead><tbody>';
                for (var s = 0; s < uk.daftar_sample.length; s++) {
                    var sm = uk.daftar_sample[s];
                    html += '<tr><td>' + sm.id + '</td><td>' + (sm.namabarang || '') + '</td><td>' + (sm.satuan || '') + '</td><td><small>' + (sm.penyebab || '') + '</small></td></tr>';
                }
                html += '</tbody></table></div>';
            }
        }

        if (a.daftar_duplikat_uuid_barang && a.daftar_duplikat_uuid_barang.length) {
            html += '<p class="mb-1"><strong>Contoh uuid_barang ganda (bulan sumber):</strong></p>';
            html += '<div style="max-height:120px;overflow:auto;font-size:11px;border:1px solid #dee2e6;padding:6px;">';
            html += '<table class="table table-sm mb-0"><thead><tr><th>uuid_barang</th><th>Jumlah</th><th>Baris tambahan</th></tr></thead><tbody>';
            for (var i = 0; i < a.daftar_duplikat_uuid_barang.length; i++) {
                var d = a.daftar_duplikat_uuid_barang[i];
                html += '<tr><td style="word-break:break-all;">' + (d.uuid_barang || '') + '</td><td>' + d.jumlah + '</td><td>' + d.baris_tambahan + '</td></tr>';
            }
            html += '</tbody></table></div>';
        }

        html += '<p class="text-info small mt-2 mb-0"><strong>Catatan:</strong> Generate hanya menyalin identitas barang + '
            + '<strong>sa/total_10</strong> dari <strong>total_10</strong> bulan sumber. '
            + '<strong>beli</strong> dan <strong>penjualan</strong> dihitung di tab <strong>Recalculate</strong>.</p>';
        if ((a.grup_duplikat_uuid_barang_sumber || 0) > 0) {
            html += '<p class="text-muted small mb-0">uuid_barang ganda di sumber (' + a.grup_duplikat_uuid_barang_sumber
                + ' grup) diperbaiki otomatis sebelum salin agar tiap baris sumber unik.</p>';
        }
        if ((a.estimasi_kosong_uuid_barang || 0) > 0) {
            html += '<p class="text-muted small mb-0">uuid_barang kosong diperbaiki otomatis (uuid baru unik) di bulan sumber sebelum salin.</p>';
        }
        html += '</div>';
        return html;
    }

    $('#btn-generate-persediaan-bulan').on('click', function(e) {
        e.preventDefault();
        if ($(this).prop('disabled')) {
            return false;
        }
        var url = $(this).data('url');
        var bulanKey = getBulanTargetGenerate();
        if (!url || !bulanKey) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'warning', title: 'Belum siap', text: 'Pilih bulan/tahun target terlebih dahulu.' });
            }
            return false;
        }

        if (typeof Swal === 'undefined') {
            if (confirm('Lanjutkan proses generate persediaan?')) {
                window.location.href = url;
            }
            return false;
        }

        Swal.fire({
            title: 'Menganalisa data...',
            allowOutsideClick: false,
            didOpen: function() { Swal.showLoading(); }
        });

        $.ajax({
            url: urlAnalisaGeneratePersediaan,
            type: 'POST',
            dataType: 'json',
            data: { bulan: bulanKey }
        }).done(function(a) {
            if (!a || !a.ok) {
                Swal.fire({
                    icon: 'error',
                    title: 'Analisa gagal',
                    text: (a && a.message) ? a.message : 'Tidak dapat menganalisa data.'
                });
                return;
            }

            var icon = (
                (a.estimasi_duplikat_sumber > 0)
                || (a.grup_duplikat_uuid_barang_sumber > 0)
                || (a.estimasi_kosong_uuid_barang > 0)
            ) ? 'warning' : 'question';
            Swal.fire({
                icon: icon,
                title: 'Konfirmasi Generate Persediaan',
                html: buildHtmlAnalisaGenerate(a),
                width: 720,
                showCancelButton: true,
                confirmButtonText: 'OK — Lanjutkan Generate',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    window.location.href = a.url_generate || url;
                }
            });
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Tidak dapat menghubungi server untuk analisa data.'
            });
        });

        return false;
    });

    function getBulanKeyRecalculate() {
        var bulan = parseInt($('#recalc_bulan_persediaan').val(), 10);
        var tahun = parseInt($('#recalc_tahun_persediaan').val(), 10);
        if (!bulan || !tahun) return '';
        return tahun + '-' + String(bulan).padStart(2, '0');
    }

    function setRecalcStatus(type, html) {
        var $el = $('#recalc-status');
        $el.removeClass('alert-info alert-success alert-danger alert-warning');
        $el.addClass(type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-info')));
        $el.html(html);
    }

    function refreshRecalcInfoRingkas() {
        var bulanKey = getBulanKeyRecalculate();
        if (!bulanKey) return;
        $.ajax({
            url: urlAnalisaRecalculatePersediaan,
            type: 'POST',
            dataType: 'json',
            data: { bulan: bulanKey }
        }).done(function(a) {
            if (!a || !a.ok) return;
            $('#recalc-label-bulan').text(a.bulan_label || bulanKey);
            $('#recalc-count-persediaan').text(a.total_persediaan || 0);
            $('#recalc-count-pembelian').text((a.total_pembelian_all || 0) + ' (barang:' + (a.total_pembelian || 0) + ' jasa:' + (a.total_pembelian_jasa || 0) + ')');
            $('#recalc-count-penjualan').text(a.total_penjualan || 0);
        });
    }

    function buildHtmlAnalisaRecalculate(a) {
        var html = '<div style="text-align:left;font-size:13px;">';
        html += '<p><strong>Bulan:</strong> ' + (a.bulan_label || '') + ' (' + (a.tanggal_beli || '') + ')</p>';
        html += '<table class="table table-sm table-bordered mb-2" style="font-size:12px;">';
        html += '<tr><td>Record persediaan</td><td class="text-right"><strong>' + (a.total_persediaan || 0) + '</strong></td></tr>';
        html += '<tr><td>tbl_pembelian</td><td class="text-right">' + (a.total_pembelian || 0) + '</td></tr>';
        html += '<tr><td>tbl_pembelian_jasa</td><td class="text-right">' + (a.total_pembelian_jasa || 0) + '</td></tr>';
        html += '<tr><td>tbl_penjualan</td><td class="text-right">' + (a.total_penjualan || 0) + '</td></tr>';
        html += '</table>';
        if (a.penjelasan) {
            html += '<p class="text-muted small">' + a.penjelasan + '</p>';
        }
        if (!a.can_proceed) {
            html += '<p class="text-danger small mb-0">' + (a.message_empty || 'Tidak dapat recalculate — tidak ada data sumber atau persediaan kosong.') + '</p>';
        }
        html += '</div>';
        return html;
    }

    var recalcLogLines = [];

    function escapeHtmlRecalc(s) {
        if (s == null) return '';
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function htmlImportPenjualanBaru(importPenj) {
        if (!importPenj) return '';
        var html = '';
        if (importPenj.created > 0 && importPenj.created_details && importPenj.created_details.length) {
            html += '<hr/><div class="text-left" style="max-height:220px;overflow:auto;font-size:12px;">'
                + '<strong>Baris baru dari import penjualan (' + importPenj.created + '):</strong><br/>';
            importPenj.created_details.forEach(function(d) {
                html += '<div class="border-left border-warning pl-2 my-1">'
                    + '<strong>ID Penjualan:</strong> ' + (d.id_penjualan || '-') + '<br/>'
                    + '<strong>Nama:</strong> ' + escapeHtmlRecalc(d.nama_barang || '') + '<br/>'
                    + '<strong>Satuan penjualan:</strong> ' + escapeHtmlRecalc(d.satuan_penjualan || '')
                    + ' → <strong>disimpan:</strong> ' + escapeHtmlRecalc(d.satuan_disimpan || '') + '<br/>'
                    + '<strong>Harga:</strong> ' + escapeHtmlRecalc(d.harga_satuan || '')
                    + ' | <strong>ID Persediaan baru:</strong> ' + (d.id_persediaan || '-') + '<br/>'
                    + '<strong>Kunci sync:</strong> <code>' + escapeHtmlRecalc(d.sync_key || '') + '</code><br/>'
                    + '<em>' + escapeHtmlRecalc(d.alasan_insert || '') + '</em>'
                    + '</div>';
            });
            html += '</div>';
        }
        if (importPenj.skipped_sudah_ada > 0) {
            html += '<br/><small class="text-success">Skip copy ulang: ' + importPenj.skipped_sudah_ada
                + ' penjualan sudah ada di persediaan (nama+satuan+harga cocok).</small>';
        }
        return html;
    }

    function appendRecalcLog(line, cls) {
        recalcLogLines.push('<div class="' + (cls || '') + '">' + line + '</div>');
        if (recalcLogLines.length > 80) recalcLogLines.shift();
    }

    function htmlRecalcModalProgress(data, faseLabel) {
        var total = data.total_phase || data.total_penjualan || data.total_persediaan || 1;
        var selesai = data.offset_selesai || 0;
        var pct = total > 0 ? Math.min(100, Math.round((selesai / total) * 100)) : 0;
        var html = '<p style="margin:0 0 6px;"><strong>' + faseLabel + '</strong> — ' + selesai + ' / ' + total + '</p>';
        html += '<div style="height:8px;background:#e9ecef;border-radius:4px;overflow:hidden;margin-bottom:8px;">';
        html += '<div id="swal-recalc-progress" style="height:100%;width:' + pct + '%;background:#17a2b8;border-radius:4px;"></div></div>';
        html += '<div id="swal-recalc-log">' + recalcLogLines.join('') + '</div>';
        return html;
    }

    function parseJsonFetchResponse(r) {
        return r.text().then(function(text) {
            var trimmed = (text || '').trim();
            if (!r.ok) {
                throw new Error('HTTP ' + r.status + (trimmed ? ': ' + trimmed.substring(0, 240) : ''));
            }
            if (trimmed.charAt(0) === '<') {
                throw new Error('Respon server bukan JSON (sesi habis atau error PHP). Refresh halaman lalu coba lagi.');
            }
            try {
                return JSON.parse(trimmed);
            } catch (e) {
                throw new Error('JSON tidak valid: ' + trimmed.substring(0, 200));
            }
        });
    }

    function runRecalculateBatch(bulanKey, offset, recalcRunning, isStart) {
        var fd = new FormData();
        fd.append('bulan', bulanKey);
        fd.append('offset', offset);
        fd.append('limit', 40);
        if (isStart) {
            fd.append('start', '1');
        }

        fetch(urlRecalculatePersediaanBatch, {
            method: 'POST',
            body: fd,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(parseJsonFetchResponse)
        .then(function(data) {
            if (!data.ok) {
                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Recalculate gagal' });
                recalcRunning.active = false;
                setRecalcStatus('danger', data.message || 'Recalculate gagal');
                return;
            }

            if (data.reset_beli && data.reset_beli.record_direset) {
                appendRecalcLog('Reset beli: ' + data.reset_beli.record_direset + ' record persediaan', 'text-info');
            }
            if (data.pesan) {
                appendRecalcLog(data.pesan, 'text-primary');
            }
            if (data.penjualan_info && data.penjualan_info.pesan) {
                appendRecalcLog(data.penjualan_info.pesan, 'text-primary');
            }
            if (data.message_phase) {
                appendRecalcLog(data.message_phase, 'text-primary');
            }
            if (data.reset && data.reset.record_direset) {
                appendRecalcLog('Reset penjualan+unit: ' + data.reset.record_direset + ' record', 'text-info');
            }

            if (data.items && data.items.length) {
                data.items.slice(-3).forEach(function(it) {
                    var line = (it.fase || data.phase || '') + ' | ' + (it.status || '') + ' | '
                        + (it.namabarang || it.nama_barang || '') + ' | ' + (it.keterangan || '');
                    appendRecalcLog(line, it.status === 'OK' ? 'text-success' : 'text-warning');
                });
            }

            if (data.next_phase === 'penjualan') {
                appendRecalcLog('— Fase pembelian selesai. Lanjut penjualan...', 'text-primary font-weight-bold');
                recalcRunning.offset = 0;
                recalcRunning.isStart = false;
            } else {
                recalcRunning.offset = data.offset_selesai || 0;
                recalcRunning.isStart = false;
            }

            var faseLabel = (data.phase === 'penjualan' || (data.summary && data.summary.penjualan_ok !== undefined))
                ? 'Fase Penjualan → unit'
                : 'Fase Pembelian → beli';
            Swal.update({ html: htmlRecalcModalProgress(data, faseLabel) });

            if (!data.done) {
                setTimeout(function() {
                    runRecalculateBatch(bulanKey, recalcRunning.offset, recalcRunning, false);
                }, 60);
                return;
            }

            recalcRunning.active = false;
            Swal.close();
            var s = data.summary || {};
            var beliInfo = s.beli_info || {};
            var penjInfo = s.penjualan_info || {};
            var syncUuid = s.sync_uuid_penjualan || penjInfo.sync_uuid_penjualan || null;
            var importPenj = s.import_penjualan || penjInfo.import_penjualan || null;
            var syncPembelian = s.sync_pembelian || beliInfo.sync_pembelian || null;
            var importPembelian = s.import_pembelian || beliInfo.import_pembelian || null;
            var msg = '<strong>Recalculate selesai</strong><br/>'
                + 'Beli di-update: ' + (s.beli_updated || 0) + ' record'
                + (beliInfo.with_beli ? ' (' + beliInfo.with_beli + ' dengan beli &gt; 0)' : '') + '<br/>'
                + 'Penjualan: ' + (s.total_penjualan || penjInfo.total_penjualan || 0) + ' record diproses → '
                + (s.penjualan_ok || 0) + ' berhasil, ' + (s.penjualan_skip || 0) + ' dilewati'
                + (s.penjualan_created ? ', ' + s.penjualan_created + ' baris persediaan baru' : '')
                + (s.with_penjualan ? '<br/>(' + s.with_penjualan + ' baris persediaan penjualan &gt; 0)' : '') + '<br/>';
            if (importPembelian && importPembelian.tbl_pembelian) {
                var ib = importPembelian.tbl_pembelian;
                var ij = importPembelian.tbl_pembelian_jasa || {};
                msg += 'Import pembelian→persediaan: barang ' + (ib.created || 0) + ' baru, jasa ' + (ij.created || 0) + ' baru.<br/>';
            }
            if (syncPembelian && syncPembelian.tbl_pembelian) {
                var sb = syncPembelian.tbl_pembelian;
                var sj = syncPembelian.tbl_pembelian_jasa || {};
                msg += 'Sinkron uuid pembelian: barang ' + (sb.updated || 0) + ' di-update, '
                    + (sb.sudah_sesuai || 0) + ' sudah sesuai, ' + (sb.tidak_ditemukan || 0) + ' tidak cocok; jasa '
                    + (sj.updated || 0) + ' di-update, ' + (sj.sudah_sesuai || 0) + ' sudah sesuai, '
                    + (sj.tidak_ditemukan || 0) + ' tidak cocok.<br/>';
            }
            if (importPenj && (importPenj.created || importPenj.failed || importPenj.skipped_sudah_ada)) {
                msg += 'Import penjualan→persediaan: ' + (importPenj.created || 0) + ' baris baru'
                    + (importPenj.skipped_sudah_ada ? ', ' + importPenj.skipped_sudah_ada + ' sudah ada (tidak di-copy ulang)' : '')
                    + (importPenj.failed ? ', ' + importPenj.failed + ' gagal' : '')
                    + (importPenj.uuid_regenerated ? ' (uuid baru: ' + importPenj.uuid_regenerated + ')' : '') + '.<br/>';
                msg += htmlImportPenjualanBaru(importPenj);
            }
            if (syncUuid) {
                msg += 'Sinkron uuid penjualan: ' + (syncUuid.updated || 0) + ' di-update, '
                    + (syncUuid.sudah_sesuai || 0) + ' sudah sesuai, '
                    + (syncUuid.tidak_ditemukan || 0) + ' tidak cocok (nama+satuan+harga)'
                    + (syncUuid.gagal ? ', ' + syncUuid.gagal + ' gagal update' : '') + '.<br/>';
            }
            var total10Penj = s.total_10_penjualan || penjInfo.total_10_penjualan || null;
            if (total10Penj && total10Penj.updated) {
                msg += 'Update total_10 (sa+beli-penjualan): ' + total10Penj.updated + ' baris persediaan.<br/>';
            }
            if (beliInfo.pesan) {
                msg += '<small>' + beliInfo.pesan + '</small><br/>';
            }
            if (penjInfo.pesan) {
                msg += '<small>' + penjInfo.pesan + '</small>';
            }
            setRecalcStatus('success', msg);
            Swal.fire({
                icon: 'success',
                title: 'Recalculate Selesai',
                html: msg + '<br/><small>Klik OK — halaman di-refresh, tetap di tab Recalculate.</small>',
                confirmButtonText: 'OK'
            }).then(function() {
                if ($('#bulan_persediaan').val() !== bulanKey) {
                    $('#bulan_persediaan').val(bulanKey);
                }
                try {
                    sessionStorage.setItem('persediaan_show_tab', 'recalculate');
                } catch (eTab) {}
                $('#form-persediaan-bulan').submit();
            });
            refreshRecalcInfoRingkas();
        })
        .catch(function(err) {
            recalcRunning.active = false;
            Swal.fire({ icon: 'error', title: 'Error', text: String(err) });
            setRecalcStatus('danger', 'Error: ' + String(err));
        });
    }

    $('#recalc_bulan_persediaan, #recalc_tahun_persediaan').on('change', refreshRecalcInfoRingkas);

    $('#btn-recalculate-persediaan-tab').on('click', function() {
        var bulanKey = getBulanKeyRecalculate();
        if (!bulanKey) {
            Swal.fire({ icon: 'warning', title: 'Bulan belum dipilih', text: 'Pilih bulan dan tahun.' });
            return;
        }

        Swal.fire({ title: 'Menganalisa...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });

        $.ajax({
            url: urlAnalisaRecalculatePersediaan,
            type: 'POST',
            dataType: 'json',
            data: { bulan: bulanKey }
        }).done(function(a) {
            if (!a || !a.ok) {
                Swal.fire({ icon: 'error', title: 'Analisa gagal', text: (a && a.message) ? a.message : 'Gagal analisa.' });
                return;
            }

            if (!a.can_proceed) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak ada data',
                    html: buildHtmlAnalisaRecalculate(a)
                });
                setRecalcStatus('warning', a.message_empty || 'Tidak ada data pembelian/penjualan untuk bulan ini.');
                return;
            }

            Swal.fire({
                icon: 'question',
                title: 'Konfirmasi Recalculate',
                html: buildHtmlAnalisaRecalculate(a),
                width: 640,
                showCancelButton: true,
                confirmButtonText: 'Ya, Recalculate',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#17a2b8'
            }).then(function(result) {
                if (!result.isConfirmed) return;

                recalcLogLines = [];
                appendRecalcLog('Memulai recalculate bulan ' + (a.bulan_label || bulanKey), 'text-primary');

                Swal.fire({
                    title: 'Recalculate Persediaan',
                    html: htmlRecalcModalProgress({ offset_selesai: 0, total_phase: a.total_persediaan || 1, phase: 'beli' }, 'Menyiapkan...'),
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: function() { Swal.showLoading(); }
                });

                var runner = { offset: 0, active: true, isStart: true };
                runRecalculateBatch(bulanKey, 0, runner, true);
                setRecalcStatus('info', 'Recalculate sedang berjalan...');
            });
        }).fail(function() {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat menghubungi server.' });
        });
    });

    function getBulanPersediaanAktif() {
        return $('#bulan_persediaan').val() || '';
    }

    function updateInfoBulanTambahPersediaan() {
        var bulan = getBulanPersediaanAktif();
        if (!bulan) {
            $('#info-bulan-tambah-persediaan').html('Pilih bulan di filter Data Persediaan terlebih dahulu.');
            return;
        }
        var parts = bulan.split('-');
        var label = parts.length === 2 ? (parts[1] + '/' + parts[0]) : bulan;
        $('#info-bulan-tambah-persediaan').html(
            'Bulan persediaan: <strong>' + label + '</strong> — <em>tanggal beli otomatis tanggal 1 bulan tersebut</em>'
        );
    }

    function formatAngkaHppInput(input) {
        var angka = String(input.value || '').replace(/[^0-9]/g, '');
        if (!angka) {
            input.value = '';
            return;
        }
        input.value = angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    $('#btn-tambah-persediaan').on('click', function() {
        if (!getBulanPersediaanAktif()) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Bulan belum dipilih',
                    text: 'Pilih bulan di filter Data Persediaan terlebih dahulu.'
                });
            } else {
                alert('Pilih bulan di filter Data Persediaan terlebih dahulu.');
            }
            return;
        }
        updateInfoBulanTambahPersediaan();
        $('#form-tambah-persediaan')[0].reset();
        $('#modal-tambah-persediaan').modal('show');
    });

    $('#bulan_persediaan').on('change', function() {
        updateInfoBulanTambahPersediaan();
        if (!$(this).val() || rekapRecalcRunning) {
            return;
        }
        $('#form-persediaan-bulan').submit();
    });

    $('#tambah_harga_satuan').on('input keyup paste', function() {
        var el = this;
        setTimeout(function() { formatAngkaHppInput(el); }, 0);
    });

    $('#form-tambah-persediaan').on('submit', function(e) {
        e.preventDefault();

        var bulan = getBulanPersediaanAktif();
        if (!bulan) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'warning', title: 'Bulan belum dipilih', text: 'Pilih bulan terlebih dahulu.' });
            }
            return false;
        }

        var namabarang = $.trim($('#tambah_namabarang').val());
        var satuan = $.trim($('#tambah_satuan').val());
        var hargaSatuan = $.trim($('#tambah_harga_satuan').val());

        if (!namabarang || !satuan || !hargaSatuan) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'warning', title: 'Data belum lengkap', text: 'Lengkapi nama barang/jasa, satuan, dan harga satuan.' });
            }
            return false;
        }

        var $btn = $('#btn-submit-tambah-persediaan');
        var btnText = $btn.text();
        $btn.prop('disabled', true).text('Menyimpan...');

        var formData = new FormData();
        formData.append('bulan_persediaan', bulan);
        formData.append('namabarang', namabarang);
        formData.append('satuan', satuan);
        formData.append('harga_satuan', hargaSatuan);

        fetch(urlTambahPersediaan, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res && (res.ok || res.success)) {
                $('#modal-tambah-persediaan').modal('hide');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message || 'Data persediaan berhasil ditambahkan.',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        $('#form-persediaan-bulan').submit();
                    });
                } else {
                    alert(res.message || 'Berhasil');
                    $('#form-persediaan-bulan').submit();
                }
                return;
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: res && res.duplicate ? 'warning' : 'error',
                    title: res && res.duplicate ? 'Nama sudah ada' : 'Gagal menyimpan',
                    text: (res && res.message) ? res.message : 'Gagal menambah persediaan.'
                });
            } else {
                alert((res && res.message) ? res.message : 'Gagal menambah persediaan.');
            }
        })
        .catch(function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Kesalahan', text: 'Terjadi kesalahan saat menyimpan data.' });
            } else {
                alert('Terjadi kesalahan saat menyimpan data.');
            }
        })
        .finally(function() {
            $btn.prop('disabled', false).text(btnText);
        });

        return false;
    });

    var dtPersediaan = $('#table-persediaan').DataTable({
        scrollY: 500,
        scrollX: true,
        scrollCollapse: true,
        pageLength: 25,
        order: [[0, 'asc']]
    });

    var urlRekapAjax = <?php echo json_encode(isset($url_rekap_ajax) ? $url_rekap_ajax : site_url('Persediaan/ajax_rekap_bulan')); ?>;
    var urlRekapSyncStep = <?php echo json_encode(isset($url_rekap_sync_step) ? $url_rekap_sync_step : site_url('Persediaan/ajax_rekap_sync_step')); ?>;
    var urlRekapExcel = <?php echo json_encode(isset($url_rekap_excel) ? $url_rekap_excel : site_url('Persediaan/excel_rekap')); ?>;
    var rekapTotalSteps = <?php echo (int) (isset($rekap_total_steps) ? $rekap_total_steps : 21); ?>;
    var rekapLoading = false;
    var rekapRecalcRunning = false;
    /** Cegah load ganda saat rekalkulasi selesai lalu tab Rekap di-show programmatically. */
    var rekapSkipNextPanelLoad = false;

    function escapeHtmlRekap(s) {
        if (s == null) return '';
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function getBulanRekapAktif() {
        return $('#bulan_rekap').val() || '';
    }

    /**
     * Bersihkan sisa DataTable rekap (versi lama) tanpa menyentuh #table-persediaan.
     */
    function cleanupLegacyRekapDataTable() {
        var $table = $('#table-rekap');
        if (!$table.length || !$.fn.DataTable || !$.fn.DataTable.isDataTable($table)) {
            return;
        }
        try {
            $table.DataTable().destroy();
        } catch (e1) {
            try {
                $table.DataTable().clear().destroy();
            } catch (e2) { /* abaikan */ }
        }
        var $wrapper = $table.closest('.dataTables_wrapper');
        if ($wrapper.length) {
            $table.detach();
            $wrapper.before($table);
            $wrapper.remove();
        }
        $table.removeClass('dataTable no-footer');
    }

    function renderRekapTable(res) {
        cleanupLegacyRekapDataTable();
        res = res || {};

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

        var $tbody = $('#table-rekap tbody');
        if ($tbody.length) {
            $tbody.html(html);
        }
        $('#rekap-total-detail').text(res.total_detail_tampil || '0');
        $('#rekap-status').text(res.bulan ? ('Bulan: ' + res.bulan) : '');
    }

    function loadRekapDataOnly() {
        if (rekapLoading) {
            return Promise.reject(new Error('Sedang memuat rekap'));
        }
        var bulan = getBulanRekapAktif();
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
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r) { return parseJsonFetchResponse(r); })
        .then(function(res) {
            if (!res.ok) {
                throw new Error(res.message || 'Gagal memuat rekap');
            }
            try {
                renderRekapTable(res);
            } catch (renderErr) {
                throw new Error(renderErr && renderErr.message ? renderErr.message : 'Gagal menampilkan data rekap');
            }
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
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function(r) { return parseJsonFetchResponse(r); });
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

        var bulan = getBulanRekapAktif();
        if (!bulan) {
            return Promise.reject(new Error('Bulan belum dipilih di tab Rekap'));
        }

        if (typeof Swal === 'undefined') {
            return Promise.reject(new Error('SweetAlert tidak tersedia'));
        }

        rekapRecalcRunning = true;
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
            return new Promise(function(resolve, reject) {
                Swal.close();
                rekapSkipNextPanelLoad = true;
                if (opts.showTabAfter !== false) {
                    tampilkanTabRekap();
                }
                /* Tunggu tab/rekap DOM siap setelah SweetAlert ditutup (cegah parentNode null). */
                window.setTimeout(function() {
                    loadRekapDataOnly().then(resolve).catch(reject);
                }, 120);
            });
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
            var msg = err && err.message ? err.message : String(err);
            if (/parentNode/i.test(msg)) {
                msg = 'Gagal memuat tampilan rekap setelah rekalkulasi. Silakan buka tab Rekap sekali lagi atau refresh halaman.';
            }
            Swal.fire({
                icon: 'error',
                title: 'Rekalkulasi Gagal',
                text: msg
            });
            throw err;
        })
        .finally(function() {
            rekapRecalcRunning = false;
            rekapSkipNextPanelLoad = false;
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

    $('#btn-cetak-excel-recalculate').on('click', function() {
        var bulanKey = getBulanKeyRecalculate();
        if (!bulanKey) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih bulan dan tahun terlebih dahulu.' });
            return;
        }

        var formData = new FormData();
        formData.append('bulan', bulanKey);

        tampilkanSwalExcelProgress();

        fetch(urlRecalculateExcel, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Export Excel recalculate gagal (HTTP ' + response.status + ')');
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
                text: 'File Excel recalculate (7 sheet, termasuk data tidak sync) berhasil diunduh.',
                timer: 2200,
                showConfirmButton: false
            });
            setRecalcStatus('success', 'Excel recalculate bulan ' + bulanKey + ' berhasil diunduh.');
        })
        .catch(function(err) {
            if (excelProgressTimer) {
                clearInterval(excelProgressTimer);
                excelProgressTimer = null;
            }
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: err && err.message ? err.message : 'Terjadi kesalahan saat export Excel recalculate.'
            });
            setRecalcStatus('danger', err && err.message ? err.message : 'Export Excel gagal.');
        });
    });

    $('#btn-cetak-excel-generate').on('click', function() {
        var bulanKey = getBulanTargetGenerate();
        if (!bulanKey) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih bulan dan tahun target terlebih dahulu.' });
            return;
        }

        var formData = new FormData();
        formData.append('bulan_persediaan', bulanKey);

        tampilkanSwalExcelProgress();

        fetch(urlExcelPersediaan, {
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
                text: 'File Excel persediaan bulan ' + bulanKey + ' berhasil diunduh (urut namabarang ASC).',
                timer: 2200,
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
                text: err && err.message ? err.message : 'Terjadi kesalahan saat export Excel generate.'
            });
        });
    });

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
        var bulan = getBulanRekapAktif();
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
            setTimeout(function() {
                tampilkanTabRekap();
                loadRekapDataOnly();
            }, 300);
        }
    } catch (e) {}

    // Tab Rekap: ubah bulan hanya memengaruhi rekap (tidak mengubah datepicker tab Persediaan)
    $('#bulan_rekap').on('change', function() {
        if (!$(this).val() || rekapRecalcRunning) {
            return;
        }
        runRekapRecalcWithSwal({ showTabAfter: false, submitFormAfter: false });
    });

    $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
        if ($(e.target).attr('href') === '#panel-rekap') {
            if (!rekapRecalcRunning && !rekapLoading && !rekapSkipNextPanelLoad) {
                loadRekapDataOnly();
            }
        } else if ($(e.target).attr('href') === '#panel-generate-persediaan') {
            cekGeneratePersediaanBulan();
        } else if ($(e.target).attr('href') === '#panel-data-persediaan') {
            dtPersediaan.columns.adjust().draw();
        }
    });

    try {
        if (sessionStorage.getItem('persediaan_show_tab') === 'generate') {
            sessionStorage.removeItem('persediaan_show_tab');
            setTimeout(function() {
                $('#tab-generate-persediaan').tab('show');
                cekGeneratePersediaanBulan();
            }, 300);
        } else if (sessionStorage.getItem('persediaan_show_tab') === 'recalculate') {
            sessionStorage.removeItem('persediaan_show_tab');
            setTimeout(function() {
                $('#tab-recalculate').tab('show');
                refreshRecalcInfoRingkas();
            }, 300);
        }
    } catch (eGenTab) {}

    if (userCanGeneratePersediaan) {
        setTimeout(function() {
            if ($('#panel-generate-persediaan').hasClass('active') || $('#panel-generate-persediaan').hasClass('show')) {
                cekGeneratePersediaanBulan();
            }
        }, 400);
    }

    $('a[href="#panel-recalculate"]').on('shown.bs.tab', function() {
        refreshRecalcInfoRingkas();
    });
    setTimeout(function() {
        if ($('#panel-recalculate').hasClass('active') || $('#panel-recalculate').hasClass('show')) {
            refreshRecalcInfoRingkas();
        }
    }, 500);
});
</script>
