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
                            <a class="nav-link" id="tab-compare-manual" data-toggle="pill" href="#panel-compare-manual" role="tab" aria-controls="panel-compare-manual" aria-selected="false">Compare apps DB - Manual Data</a>
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
                            <?php if ($this->session->flashdata('pesan_persediaan')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($this->session->flashdata('pesan_persediaan'), ENT_QUOTES, 'UTF-8'); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php endif; ?>

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
                                        <button type="button" id="btn-cetak-excel-persediaan" class="btn btn-primary ml-1">Cetak ke Excel</button>
                                        <span class="ml-2 text-muted small" id="info-jumlah-persediaan-bulan">
                                            Menampilkan <?php echo count($Persediaan_data); ?> baris — bulan <?php echo htmlspecialchars(date('m/Y', strtotime($bulan_tampil . '-01')), ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </div>
                                </div>
                            </form>

                            <?php
                            $persediaan_fields_tgl_total = persediaan_list_fields_tgl_keluar_sampai_total_10();
                            $idx_col_total_10 = persediaan_list_col_index_total_10();
                            $idx_col_nilai_persediaan = persediaan_list_col_index_nilai_persediaan();
                            $total_kolom_persediaan = count(persediaan_export_headers()) + 1;
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
                                            <?php if (persediaan_field_has_nominal_column($field_tgl_total)) { ?>
                                                <th class="text-right"><?php echo htmlspecialchars(persediaan_field_nominal_header_label($field_tgl_total), ENT_QUOTES, 'UTF-8'); ?></th>
                                            <?php } ?>
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
                                    $total_nominal_unit = array();
                                    foreach (persediaan_list_unit_columns() as $uf_total) {
                                        $total_nominal_unit[$uf_total] = 0;
                                    }
                                    foreach ($Persediaan_data as $persediaan) {
                                        $total_10_row = persediaan_parse_angka(persediaan_row_get($persediaan, 'total_10'));
                                        $nilai_persediaan_row = persediaan_hitung_nilai_persediaan_row($persediaan);
                                        $total_total_10 += $total_10_row;
                                        $total_nilai_persediaan += $nilai_persediaan_row;
                                        foreach (persediaan_list_unit_columns() as $uf_total) {
                                            $total_nominal_unit[$uf_total] += persediaan_hitung_kolom_nominal_row($persediaan, $uf_total);
                                        }
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
                                                <?php if (persediaan_field_has_nominal_column($field_tgl_total)) { ?>
                                                    <td class="text-right"><?php echo persediaan_tampil_kolom_nominal_row($persediaan, $field_tgl_total); ?></td>
                                                <?php } ?>
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
                                        <?php
                                        $footer_cells = persediaan_datatable_footer_cells($total_total_10, $total_nilai_persediaan, $total_nominal_unit);
                                        $footer_cells[] = '';
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
                                    <h5 class="mb-2">Generate &amp; Recalculate data persediaan</h5>
                                    <p class="text-muted small mb-3">
                                        <strong>Fase 1 — Generate:</strong> salin dari <strong>bulan sebelumnya</strong> hanya jika <strong>sa &gt; 0</strong> atau <strong>total_10 &gt; 0</strong>.
                                        Record sumber <strong>sa=0 &amp; total_10=0</strong> dilewati. Baris target dengan kondisi sama dihapus.
                                        <strong>Fase 2 — Pembelian:</strong> cocokkan nama+satuan+hpp+spop → tambah <strong>beli</strong> dan <strong>total_10 += jumlah beli</strong>; jika belum ada → insert (sa=0).
                                        Duplikat <strong>beli=0</strong> dengan spop kosong/0 dihapus jika ada baris sama (namabarang+sa+satuan+hpp, satuan tidak case-sensitive) yang spop-nya terisi.
                                        <strong>Fase 3 — Penjualan:</strong> cocokkan nama+satuan+hpp → jumlah masuk kolom <strong>unit</strong> + field <strong>penjualan</strong>;
                                        <strong>total_10 = (sa+beli) − penjualan</strong> (sisa stock = total_10 setelah update).
                                        <strong>Fase 4 — Produksi bahan:</strong> dari <strong>sys_unit_produk_bahan</strong> (filter <strong>tgl_transaksi</strong> bulan target),
                                        cocokkan <strong>nama_barang_bahan + satuan_bahan + harga_satuan_bahan</strong> ke persediaan →
                                        <strong>bahan_produksi += jumlah_bahan</strong>, <strong>total_10 −= jumlah_bahan</strong> (sisa stock = total_10 setelah update).
                                        <em>Hanya user <strong>admin.id@gmail.com</strong> dan <strong>iwanesia.id@gmail.com</strong>.</em>
                                    </p>
                                    <?php if (empty($can_generate_persediaan)) { ?>
                                    <div class="alert alert-warning py-2">
                                        Akun Anda tidak memiliki akses Generate &amp; Recalculate.
                                        Hanya login <strong>admin.id@gmail.com</strong> dan <strong>iwanesia.id@gmail.com</strong>.
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="gen-recalc-toolbar mb-3">
                                <div class="gen-recalc-toolbar-row">
                                    <div class="gen-recalc-field gen-recalc-field-bulan">
                                        <label for="gen_bulan_persediaan" class="gen-recalc-label">Bulan target</label>
                                        <select id="gen_bulan_persediaan" class="form-control gen-recalc-select-bulan">
                                            <?php foreach ($nama_bulan_id as $num => $label_bulan) { ?>
                                                <option value="<?php echo (int) $num; ?>"<?php echo ((int) $num === $gen_bulan_default) ? ' selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($label_bulan, ENT_QUOTES, 'UTF-8'); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="gen-recalc-field gen-recalc-field-tahun">
                                        <label for="gen_tahun_persediaan" class="gen-recalc-label">Tahun target</label>
                                        <select id="gen_tahun_persediaan" class="form-control gen-recalc-select-tahun">
                                            <?php for ($th = $gen_tahun_max; $th >= $gen_tahun_min; $th--) { ?>
                                                <option value="<?php echo (int) $th; ?>"<?php echo ((int) $th === $gen_tahun_default) ? ' selected' : ''; ?>>
                                                    <?php echo (int) $th; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="gen-recalc-actions">
                                        <button type="button" id="btn-generate-persediaan-bulan" class="btn btn-secondary gen-recalc-btn" disabled>
                                            <i class="fas fa-sync-alt"></i> Generate &amp; Recalculate
                                        </button>
                                        <button type="button" id="btn-cetak-excel-generate" class="btn btn-primary gen-recalc-btn" title="Export semua tabel ke Excel (multi-sheet)">
                                            <i class="fas fa-file-excel"></i> Excel Semua Tabel
                                        </button>
                                        <button type="button" id="btn-cetak-excel-rekonsiliasi-transaksi" class="btn btn-success gen-recalc-btn" title="Rekonsiliasi persediaan vs pembelian, penjualan, produksi, pecah satuan">
                                            <i class="fas fa-file-excel"></i> Excel Rekonsiliasi TRANSAKSI
                                        </button>
                                    </div>
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
                            <div class="card card-outline card-success" id="gen-recalc-result-wrap">
                                <div class="card-header"><h3 class="card-title">Hasil Proses Generate &amp; Recalculate</h3></div>
                                <div class="card-body">
                                    <div id="gen-recalc-summary" class="alert alert-light border mb-3 small text-muted">
                                        Belum ada proses. Klik <strong>Generate &amp; Recalculate</strong> — tabel di bawah akan terisi otomatis.
                                    </div>

                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>1. Data Persediaan — Semua Record Generate <span id="gen-count-persediaan-all" class="badge badge-secondary">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="persediaan_all" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="table-responsive mb-3">
                                        <table id="tbl-gen-recalc-persediaan-all" class="table table-sm table-bordered table-striped gen-recalc-dt" style="width:100%;font-size:12px;">
                                            <thead><tr>
                                                <th>No</th><th>Aksi</th><th>ID</th><th>Nama Barang</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>SA</th><th>Beli</th><th>Total_10</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>2. Generate — Update Record <span id="gen-count-update" class="badge badge-info">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="generate_update" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="table-responsive mb-3">
                                        <table id="tbl-gen-recalc-generate-update" class="table table-sm table-bordered gen-recalc-dt" style="width:100%;font-size:12px;">
                                            <thead><tr>
                                                <th>No</th><th>ID</th><th>Nama Barang</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>SA</th><th>Beli</th><th>Total_10</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>3. Generate — Record Baru (Insert) <span id="gen-count-insert" class="badge badge-success">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="generate_insert" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="table-responsive mb-4">
                                        <table id="tbl-gen-recalc-generate-insert" class="table table-sm table-bordered gen-recalc-dt" style="width:100%;font-size:12px;">
                                            <thead><tr>
                                                <th>No</th><th>ID</th><th>UUID</th><th>Nama Barang</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>SA</th><th>Total_10</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <hr/>
                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>4. Semua Data Pembelian Diproses <span id="gen-count-pembelian" class="badge badge-secondary">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="pembelian" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="table-responsive mb-3">
                                        <table id="tbl-gen-recalc-pembelian" class="table table-sm table-bordered table-striped gen-recalc-dt" style="width:100%;font-size:12px;">
                                            <thead><tr>
                                                <th>No</th><th>Aksi</th><th>Tabel</th><th>ID Pembelian</th><th>ID Persediaan</th><th>Nama</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>Jumlah</th><th>Beli Baru</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>5. Rekap Update Beli (Pembelian → Persediaan) <span id="gen-count-pembelian-update" class="badge badge-info">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="pembelian_update" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="table-responsive mb-3">
                                        <table id="tbl-gen-recalc-pembelian-update" class="table table-sm table-bordered gen-recalc-dt" style="width:100%;font-size:12px;">
                                            <thead><tr>
                                                <th>No</th><th>ID Pembelian</th><th>ID Persediaan</th><th>Nama</th><th>Jumlah</th><th>Beli Lama</th><th>Beli Baru</th><th>Total_10</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>6. Record Persediaan Baru dari Pembelian <span id="gen-count-pembelian-baru" class="badge badge-success">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="pembelian_baru" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="table-responsive mb-4">
                                        <table id="tbl-gen-recalc-pembelian-baru" class="table table-sm table-bordered gen-recalc-dt" style="width:100%;font-size:12px;">
                                            <thead><tr>
                                                <th>No</th><th>ID Pembelian</th><th>ID Persediaan</th><th>Nama</th><th>Satuan</th><th>HPP</th><th>Beli</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <hr/>
                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>7. Semua Data Penjualan Diproses <span id="gen-count-penjualan" class="badge badge-secondary">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="penjualan" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="table-responsive mb-3">
                                        <table id="tbl-gen-recalc-penjualan" class="table table-sm table-bordered table-striped gen-recalc-dt" style="width:100%;font-size:12px;">
                                            <thead><tr>
                                                <th>No</th><th>Aksi</th><th>ID Penjualan</th><th>ID Persediaan</th><th>Nama</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>Unit</th><th>Jumlah</th><th>Penjualan Baru</th><th>Total_10</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>8. Rekap Update Penjualan → Persediaan <span id="gen-count-penjualan-update" class="badge badge-info">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="penjualan_update" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="table-responsive">
                                        <table id="tbl-gen-recalc-penjualan-update" class="table table-sm table-bordered gen-recalc-dt" style="width:100%;font-size:12px;">
                                            <thead><tr>
                                                <th>No</th><th>ID Penjualan</th><th>ID Persediaan</th><th>Nama</th><th>Unit</th><th>Jumlah</th><th>Penjualan Lama</th><th>Penjualan Baru</th><th>Unit Lama</th><th>Unit Baru</th><th>Total_10</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <hr/>
                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>9. Semua Data Bahan Produksi Diproses <span id="gen-count-produksi" class="badge badge-secondary">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="produksi" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="table-responsive mb-3">
                                        <table id="tbl-gen-recalc-produksi" class="table table-sm table-bordered table-striped gen-recalc-dt" style="width:100%;font-size:12px;">
                                            <thead><tr>
                                                <th>No</th><th>Aksi</th><th>ID Bahan</th><th>ID Persediaan</th><th>Nama</th><th>Satuan</th><th>HPP</th><th>Unit Produksi</th><th>Jumlah Bahan</th><th>Bahan Produksi Baru</th><th>Total_10</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>10. Rekap Update Bahan Produksi → Persediaan <span id="gen-count-produksi-update" class="badge badge-info">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="produksi_update" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="table-responsive">
                                        <table id="tbl-gen-recalc-produksi-update" class="table table-sm table-bordered gen-recalc-dt" style="width:100%;font-size:12px;">
                                            <thead><tr>
                                                <th>No</th><th>ID Bahan</th><th>ID Persediaan</th><th>Nama</th><th>Unit Produksi</th><th>Jumlah Bahan</th><th>Bahan Lama</th><th>Bahan Baru</th><th>Total_10</th><th>Sisa Stock</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <h6 class="mt-4 d-flex align-items-center flex-wrap">
                                        <span>11. Pecah Satuan — Semua Record <span id="gen-count-pecah-satuan" class="badge badge-secondary">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="pecah_satuan" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="table-responsive mb-3">
                                        <table id="tbl-gen-recalc-pecah-satuan" class="table table-sm table-bordered table-striped gen-recalc-dt" style="width:100%;font-size:12px;">
                                            <thead><tr>
                                                <th>No</th><th>Aksi</th><th>ID Pecah</th><th>ID Sumber</th><th>ID Target</th><th>Nama Sumber</th><th>Satuan</th><th>HPP</th><th>Jumlah Pecah</th><th>Pecah Baru</th><th>Total_10 Sumber</th><th>Nama Baru</th><th>Satuan Baru</th><th>HPP Baru</th><th>Jumlah Baru</th><th>SA Target</th><th>Total_10 Target</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>12. Pecah Satuan — Update Record <span id="gen-count-pecah-satuan-update" class="badge badge-info">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="pecah_satuan_update" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="table-responsive">
                                        <table id="tbl-gen-recalc-pecah-satuan-update" class="table table-sm table-bordered gen-recalc-dt" style="width:100%;font-size:12px;">
                                            <thead><tr>
                                                <th>No</th><th>ID Pecah</th><th>ID Sumber</th><th>ID Target</th><th>Nama Sumber</th><th>Nama Baru</th><th>Jumlah Pecah</th><th>Jumlah Baru</th><th>Pecah Baru</th><th>Total_10 Sumber</th><th>SA Target</th><th>Total_10 Target</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 4: COMPARE APPS DB - MANUAL DATA -->
                        <?php
                        $compare_bulan_default = isset($bulan_tampil) && preg_match('/^\d{4}-\d{2}$/', $bulan_tampil)
                            ? $bulan_tampil
                            : date('Y-m');
                        $compare_parts = explode('-', $compare_bulan_default);
                        $compare_bulan_num = isset($compare_parts[1]) ? (int) $compare_parts[1] : (int) date('n');
                        $compare_tahun_num = isset($compare_parts[0]) ? (int) $compare_parts[0] : (int) date('Y');
                        ?>
                        <div class="tab-pane fade" id="panel-compare-manual" role="tabpanel" aria-labelledby="tab-compare-manual">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5 class="mb-2">Compare apps DB — Manual Data</h5>
                                    <p class="text-muted small mb-0">
                                        Bandingkan data <strong>persediaan</strong> bulan terpilih dengan tabel manual di database.
                                        Cocokkan berdasarkan <strong>nama barang + satuan + hpp + spop</strong>.
                                        Kolom kosong = record tidak ada di sisi tersebut.
                                    </p>
                                    <?php if (empty($can_compare_persediaan)) { ?>
                                    <div class="alert alert-warning py-2">
                                        Akun Anda tidak memiliki akses Compare.
                                        Hanya login <strong>admin.id@gmail.com</strong> dan <strong>iwanesia.id@gmail.com</strong>.
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-end">
                                <div class="col-md-2 col-sm-6 mb-2">
                                    <label for="compare_bulan_persediaan">Bulan</label>
                                    <select id="compare_bulan_persediaan" class="form-control">
                                        <?php foreach ($nama_bulan_id as $num => $label_bulan) { ?>
                                            <option value="<?php echo (int) $num; ?>"<?php echo ((int) $num === $compare_bulan_num) ? ' selected' : ''; ?>>
                                                <?php echo htmlspecialchars($label_bulan, ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-6 mb-2">
                                    <label for="compare_tahun_persediaan">Tahun</label>
                                    <select id="compare_tahun_persediaan" class="form-control">
                                        <?php for ($th = $gen_tahun_max; $th >= $gen_tahun_min; $th--) { ?>
                                            <option value="<?php echo (int) $th; ?>"<?php echo ((int) $th === $compare_tahun_num) ? ' selected' : ''; ?>>
                                                <?php echo (int) $th; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-12 mb-2">
                                    <label for="compare_tabel_pilihan">Tabel database</label>
                                    <select id="compare_tabel_pilihan" class="form-control">
                                        <option value="">— Muat daftar tabel —</option>
                                    </select>
                                    <small class="text-muted">Minimal kolom: nama barang, satuan, hpp/harga_satuan, spop</small>
                                </div>
                                <div class="col-md-4 col-sm-12 mb-2">
                                    <button type="button" id="btn-compare-tabel" class="btn btn-info btn-lg"<?php echo empty($can_compare_persediaan) ? ' disabled' : ''; ?>>
                                        <i class="fas fa-columns"></i> Compare
                                    </button>
                                </div>
                            </div>
                            <div class="alert alert-secondary py-2" id="compare-info-ringkas">
                                <strong>Bulan:</strong> <span id="compare-label-bulan">—</span>
                                &nbsp;|&nbsp; <strong>Tabel:</strong> <span id="compare-label-tabel">—</span>
                                &nbsp;|&nbsp; <strong>Total_10 kosong:</strong> <span id="compare-count-total10">—</span>
                                &nbsp;|&nbsp; <strong>Tidak di tabel manual:</strong> <span id="compare-count-tidak">—</span>
                                &nbsp;|&nbsp; <strong>Tidak di persediaan:</strong> <span id="compare-count-hanya">—</span>
                                &nbsp;|&nbsp; <strong>Cocok:</strong> <span id="compare-count-cocok">—</span>
                            </div>
                            <div class="alert alert-info py-2 mb-3" id="compare-status">
                                Pilih bulan, tahun, dan tabel — lalu klik <strong>Compare</strong>.
                            </div>

                            <h6 class="d-flex align-items-center flex-wrap mt-3">
                                <span>1. Persediaan Total_10 Kosong / 0 <span id="compare-badge-total10" class="badge badge-secondary">0</span></span>
                                <button type="button" class="btn btn-xs btn-outline-primary btn-compare-excel ml-2 mb-1" data-jenis="total10_kosong"><i class="fas fa-file-excel"></i> Excel</button>
                            </h6>
                            <div class="compare-dt-wrap mb-4">
                                <table id="table-compare-total10" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:12px;">
                                    <thead><tr>
                                        <th>No</th><th>Namabarang</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>Sa</th><th>Beli</th><th>Total_10</th>
                                    </tr></thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <h6 class="d-flex align-items-center flex-wrap">
                                <span>2. Persediaan Tidak Ada di Tabel Manual <span id="compare-badge-tidak" class="badge badge-warning">0</span></span>
                                <button type="button" class="btn btn-xs btn-outline-primary btn-compare-excel ml-2 mb-1" data-jenis="tidak_di_tabel"><i class="fas fa-file-excel"></i> Excel</button>
                            </h6>
                            <div class="compare-dt-wrap mb-4">
                                <table id="table-compare-tidak" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:12px;">
                                    <thead><tr>
                                        <th>No</th><th>P_Namabarang</th><th>P_Satuan</th><th>P_HPP</th><th>P_SPOP</th><th>P_Total_10</th>
                                        <th>C_Nama</th><th>C_Satuan</th><th>C_HPP</th><th>C_SPOP</th>
                                    </tr></thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <h6 class="d-flex align-items-center flex-wrap">
                                <span>3. Tabel Manual Tidak Ada di Persediaan <span id="compare-badge-hanya" class="badge badge-info">0</span></span>
                                <button type="button" class="btn btn-xs btn-outline-primary btn-compare-excel ml-2 mb-1" data-jenis="hanya_tabel"><i class="fas fa-file-excel"></i> Excel</button>
                            </h6>
                            <div class="compare-dt-wrap mb-4">
                                <table id="table-compare-hanya" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:12px;">
                                    <thead><tr>
                                        <th>No</th><th>P_Namabarang</th><th>P_Satuan</th><th>P_HPP</th><th>P_SPOP</th><th>P_Total_10</th>
                                        <th>C_Nama</th><th>C_Satuan</th><th>C_HPP</th><th>C_SPOP</th>
                                    </tr></thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <h6 class="d-flex align-items-center flex-wrap">
                                <span>4. Data Cocok (Persediaan &amp; Tabel Manual) <span id="compare-badge-cocok" class="badge badge-success">0</span></span>
                                <button type="button" class="btn btn-xs btn-outline-primary btn-compare-excel ml-2 mb-1" data-jenis="cocok"><i class="fas fa-file-excel"></i> Excel</button>
                            </h6>
                            <div class="compare-dt-wrap">
                                <table id="table-compare-cocok" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:12px;">
                                    <thead><tr>
                                        <th>No</th><th>P_Namabarang</th><th>P_Satuan</th><th>P_HPP</th><th>P_SPOP</th><th>P_Total_10</th>
                                        <th>C_Nama</th><th>C_Satuan</th><th>C_HPP</th><th>C_SPOP</th>
                                    </tr></thead>
                                    <tbody></tbody>
                                </table>
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
    .gen-recalc-toolbar-row {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 18px 24px;
    }
    .gen-recalc-label {
        display: block;
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #343a40;
        white-space: nowrap;
        line-height: 1.25;
    }
    .gen-recalc-field {
        flex: 0 0 auto;
    }
    .gen-recalc-select-bulan {
        width: 222px;
        min-width: 222px;
        max-width: 222px;
        font-size: 21px;
        padding: 9px 15px;
        height: calc(3.375rem + 2px);
    }
    .gen-recalc-select-tahun {
        width: 144px;
        min-width: 144px;
        max-width: 144px;
        font-size: 21px;
        padding: 9px 15px;
        height: calc(3.375rem + 2px);
    }
    .gen-recalc-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 15px;
        flex: 1 1 auto;
        min-width: 0;
    }
    .gen-recalc-actions .gen-recalc-btn {
        white-space: nowrap;
        font-size: 21px;
        font-weight: 600;
        padding: 12px 21px;
        line-height: 1.4;
    }
    .gen-recalc-actions .gen-recalc-btn .fas {
        font-size: 0.95em;
    }
    @media (min-width: 1400px) {
        .gen-recalc-toolbar-row {
            flex-wrap: nowrap;
        }
        .gen-recalc-actions {
            flex-wrap: nowrap;
            justify-content: flex-start;
        }
    }
    @media (min-width: 992px) and (max-width: 1399.98px) {
        .gen-recalc-actions {
            width: 100%;
        }
    }
    #btn-generate-persediaan-bulan:disabled { cursor: not-allowed; opacity: 0.72; }
    #btn-compare-tabel:disabled { cursor: not-allowed; opacity: 0.72; }
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
    #table-persediaan tfoot th {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        font-weight: 600;
        vertical-align: middle;
        padding: 6px 8px;
    }
    #table-persediaan tfoot th.persediaan-foot-total-label {
        text-align: right;
        white-space: nowrap;
    }
    #table-persediaan tfoot th.persediaan-foot-num {
        text-align: right;
        white-space: nowrap;
    }
    .compare-dt-wrap {
        width: 100%;
        overflow: hidden;
    }
    .compare-dt-wrap .dataTables_wrapper {
        width: 100%;
    }
    .compare-dt-wrap .dataTables_scrollHead,
    .compare-dt-wrap .dataTables_scrollBody {
        overflow-x: auto !important;
        overflow-y: auto !important;
    }
    .compare-dt-wrap .dataTables_scrollBody {
        max-height: 400px;
    }
    .compare-dt-wrap table.dataTable thead th,
    .compare-dt-wrap table.dataTable tbody td {
        white-space: nowrap;
    }
</style>

<script>
window.addEventListener('load', function() {
    if (!window.jQuery || !jQuery.fn || !jQuery.fn.dataTable) {
        console.error('Persediaan: jQuery/DataTables belum dimuat. Muat ulang halaman.');
        return;
    }
    var $ = window.jQuery;
    var urlTambahPersediaan = <?php echo json_encode(isset($url_tambah_persediaan) ? $url_tambah_persediaan : site_url('Persediaan/ajax_tambah_persediaan')); ?>;
    var urlCekGeneratePersediaan = <?php echo json_encode(isset($url_cek_generate_persediaan) ? $url_cek_generate_persediaan : site_url('Persediaan/ajax_cek_generate_persediaan_bulan')); ?>;
    var urlAnalisaGeneratePersediaan = <?php echo json_encode(isset($url_analisa_generate_persediaan) ? $url_analisa_generate_persediaan : site_url('Persediaan/ajax_analisa_generate_persediaan_bulan')); ?>;
    var urlAnalisaRecalculatePersediaan = <?php echo json_encode(isset($url_analisa_recalculate_persediaan) ? $url_analisa_recalculate_persediaan : site_url('Persediaan/ajax_analisa_recalculate_persediaan')); ?>;
    var urlRecalculatePersediaanBatch = <?php echo json_encode(isset($url_recalculate_persediaan_batch) ? $url_recalculate_persediaan_batch : site_url('Persediaan/ajax_recalculate_persediaan_batch')); ?>;
    var urlGenerateRecalculateBatch = <?php echo json_encode(isset($url_generate_recalculate_batch) ? $url_generate_recalculate_batch : site_url('Persediaan/ajax_generate_recalculate_batch')); ?>;
    var urlLoadGenRecalcHistory = <?php echo json_encode(isset($url_load_gen_recalc_history) ? $url_load_gen_recalc_history : site_url('Persediaan/ajax_load_gen_recalc_history')); ?>;
    var urlExcelGenRecalc = <?php echo json_encode(isset($url_excel_gen_recalc) ? $url_excel_gen_recalc : site_url('Persediaan/excel_gen_recalc')); ?>;
    var urlExcelRekonsiliasiTransaksi = <?php echo json_encode(isset($url_excel_rekonsiliasi_transaksi) ? $url_excel_rekonsiliasi_transaksi : site_url('Persediaan/excel_rekonsiliasi_transaksi')); ?>;
    var urlRecalculateExcel = <?php echo json_encode(isset($url_recalculate_excel) ? $url_recalculate_excel : site_url('Persediaan/excel_recalculate')); ?>;
    var urlExcelPersediaan = <?php echo json_encode(isset($url_excel_persediaan) ? $url_excel_persediaan : site_url('Persediaan/excel')); ?>;
    var urlCompareTabelList = <?php echo json_encode(isset($url_compare_tabel_list) ? $url_compare_tabel_list : site_url('Persediaan/ajax_compare_tabel_list')); ?>;
    var urlCompareTabelRun = <?php echo json_encode(isset($url_compare_tabel_run) ? $url_compare_tabel_run : site_url('Persediaan/ajax_compare_tabel_run')); ?>;
    var urlCompareTabelExcel = <?php echo json_encode(isset($url_compare_tabel_excel) ? $url_compare_tabel_excel : site_url('Persediaan/excel_compare_tabel')); ?>;
    var userCanGeneratePersediaan = <?php echo !empty($can_generate_persediaan) ? 'true' : 'false'; ?>;
    var userCanComparePersediaan = <?php echo !empty($can_compare_persediaan) ? 'true' : 'false'; ?>;
    var genCekXhr = null;

    function getBulanTargetGenerate() {
        var bulan = parseInt($('#gen_bulan_persediaan').val(), 10);
        var tahun = parseInt($('#gen_tahun_persediaan').val(), 10);
        if (bulan >= 1 && bulan <= 12 && tahun) {
            return tahun + '-' + ('0' + bulan).slice(-2);
        }
        var bulanTab1 = ($('#bulan_persediaan').val() || '').trim();
        if (bulanTab1 && /^\d{4}-\d{2}$/.test(bulanTab1)) {
            return bulanTab1;
        }
        return '';
    }

    function getBulanExportRekonsiliasi() {
        var bulanTab1 = ($('#bulan_persediaan').val() || '').trim();
        if (bulanTab1 && /^\d{4}-\d{2}$/.test(bulanTab1)) {
            return bulanTab1;
        }
        return getBulanTargetGenerate();
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
            $btn.prop('disabled', false).addClass('btn-success').removeData('url');
            $('#gen-persediaan-link-wrap').addClass('d-none');
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
                setStatusGeneratePersediaan('warning', res.message || 'Hanya admin.id@gmail.com dan iwanesia.id@gmail.com yang dapat generate.');
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
        var bulanKey = getBulanTargetGenerate();
        cekGeneratePersediaanBulan();
        loadGenRecalcHistoryFromServer(bulanKey);
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

        html += '<p class="text-info small mt-2 mb-0"><strong>Catatan:</strong> Fase 1: hanya salin sumber dengan sa atau total_10 &gt; 0; sa=0 &amp; total_10=0 dilewati/dihapus di target. Fase 2–3: beli/penjualan seperti biasa.</p>';
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

    var genRecalcData = {
        persediaan_all: [],
        generate_update: [],
        generate_insert: [],
        pembelian: [],
        pembelian_update: [],
        pembelian_baru: [],
        penjualan: [],
        penjualan_update: [],
        produksi: [],
        produksi_update: [],
        pecah_satuan: [],
        pecah_satuan_update: []
    };
    var genRecalcSummaryHtml = '';
    var genRecalcStoragePrefix = 'genRecalcResult_';
    var genRecalcDt = {};
    var genRecalcDtLang = {
        emptyTable: 'Belum ada data',
        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
        infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
        infoFiltered: '(disaring dari _MAX_ total data)',
        lengthMenu: 'Tampilkan _MENU_ data',
        search: 'Cari:',
        zeroRecords: 'Tidak ada data yang cocok',
        paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' }
    };

    function escapeHtmlGen(s) {
        if (s == null) return '';
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function saveGenRecalcResultToStorage(bulanKey) {
        if (!bulanKey) return;
        try {
            sessionStorage.setItem(genRecalcStoragePrefix + bulanKey, JSON.stringify({
                bulan: bulanKey,
                summaryHtml: genRecalcSummaryHtml || '',
                data: genRecalcData,
                savedAt: Date.now()
            }));
        } catch (eSave) {}
    }

    function restoreGenRecalcResultFromStorage(bulanKey) {
        if (!bulanKey) return false;
        try {
            var raw = sessionStorage.getItem(genRecalcStoragePrefix + bulanKey);
            if (!raw) return false;
            var parsed = JSON.parse(raw);
            if (!parsed || !parsed.data) return false;
            genRecalcData = parsed.data;
            genRecalcSummaryHtml = parsed.summaryHtml || '';
            if (genRecalcSummaryHtml) {
                $('#gen-recalc-summary').html(genRecalcSummaryHtml);
            }
            destroyAllGenRecalcDataTables();
            renderGenRecalcDataTables();
            setTimeout(adjustGenRecalcDataTables, 200);
            return genRecalcData.persediaan_all.length > 0
                || genRecalcData.pembelian.length > 0
                || genRecalcData.penjualan.length > 0
                || genRecalcData.produksi.length > 0
                || genRecalcData.generate_update.length > 0
                || genRecalcData.generate_insert.length > 0;
        } catch (eRestore) {
            return false;
        }
    }

    function clearGenRecalcResultStorage(bulanKey) {
        if (!bulanKey) return;
        try {
            sessionStorage.removeItem(genRecalcStoragePrefix + bulanKey);
        } catch (eClr) {}
    }

    function resetGenRecalcTablesEmpty() {
        genRecalcData = {
            persediaan_all: [],
            generate_update: [],
            generate_insert: [],
            pembelian: [],
            pembelian_update: [],
            pembelian_baru: [],
            penjualan: [],
            penjualan_update: [],
            produksi: [],
            produksi_update: [],
            pecah_satuan: [],
            pecah_satuan_update: []
        };
        genRecalcSummaryHtml = '';
        $('#gen-recalc-summary').html('<em>Belum ada proses untuk bulan ini. Klik <strong>Generate &amp; Recalculate</strong>.</em>');
        destroyAllGenRecalcDataTables();
        renderGenRecalcDataTables();
    }

    function applyGenRecalcHistoryResponse(res) {
        if (!res || !res.data) return false;
        genRecalcData = {
            persediaan_all: res.data.persediaan_all || [],
            generate_update: res.data.generate_update || [],
            generate_insert: res.data.generate_insert || [],
            pembelian: res.data.pembelian || [],
            pembelian_update: res.data.pembelian_update || [],
            pembelian_baru: res.data.pembelian_baru || [],
            penjualan: res.data.penjualan || [],
            penjualan_update: res.data.penjualan_update || [],
            produksi: res.data.produksi || [],
            produksi_update: res.data.produksi_update || [],
            pecah_satuan: res.data.pecah_satuan || [],
            pecah_satuan_update: res.data.pecah_satuan_update || []
        };
        genRecalcSummaryHtml = res.summary_html || '';
        var infoProses = '';
        if (res.created_at) {
            infoProses = '<br/><small class="text-muted">History proses: ' + escapeHtmlGen(res.created_at);
            if (res.nama_user) {
                infoProses += ' — ' + escapeHtmlGen(res.nama_user);
            }
            infoProses += '</small>';
        }
        if (genRecalcSummaryHtml) {
            $('#gen-recalc-summary').html(genRecalcSummaryHtml + infoProses);
        }
        destroyAllGenRecalcDataTables();
        renderGenRecalcDataTables();
        saveGenRecalcResultToStorage(getBulanTargetGenerate());
        setTimeout(adjustGenRecalcDataTables, 200);
        return true;
    }

    var genRecalcHistoryXhr = null;
    function loadGenRecalcHistoryFromServer(bulanKey) {
        if (!bulanKey || !userCanGeneratePersediaan || !urlLoadGenRecalcHistory) {
            return;
        }
        if (genRecalcHistoryXhr && genRecalcHistoryXhr.readyState !== 4) {
            genRecalcHistoryXhr.abort();
        }
        genRecalcHistoryXhr = $.ajax({
            url: urlLoadGenRecalcHistory,
            type: 'POST',
            dataType: 'json',
            data: { bulan: bulanKey }
        }).done(function(res) {
            if (res && res.ok && res.has_history) {
                applyGenRecalcHistoryResponse(res);
                return;
            }
            if (!restoreGenRecalcResultFromStorage(bulanKey)) {
                resetGenRecalcTablesEmpty();
            }
            if (res && res.ok && res.tables_ready === false && res.message) {
                $('#gen-recalc-summary').html('<span class="text-warning">' + escapeHtmlGen(res.message) + '</span>');
            }
        }).fail(function() {
            if (!restoreGenRecalcResultFromStorage(bulanKey)) {
                resetGenRecalcTablesEmpty();
            }
        });
    }

    function exportGenRecalcExcel(jenis) {
        var bulanKey = getBulanTargetGenerate();
        if (!bulanKey) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih bulan dan tahun target terlebih dahulu.' });
            return;
        }

        var formData = new FormData();
        formData.append('bulan', bulanKey);
        if (jenis) {
            formData.append('jenis', jenis);
        }

        tampilkanSwalExcelProgress();

        fetch(urlExcelGenRecalc, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(unduhExcelDariResponse)
        .then(function(result) {
            triggerDownloadBlob(result);
            selesaiSwalExcelProgress();
            var label = jenis ? ('tabel ' + jenis) : 'semua tabel (10 sheet)';
            Swal.fire({
                icon: 'success',
                title: 'Selesai',
                text: 'File Excel ' + label + ' bulan ' + bulanKey + ' berhasil diunduh.',
                timer: 2400,
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
                text: err && err.message ? err.message : 'Export Excel gagal. Pastikan tabel history sudah dibuat dan proses generate pernah dijalankan.'
            });
        });
    }

    function pushGenRecalcPersediaanItems(items) {
        if (!items || !items.length) return;
        items.forEach(function(it) {
            genRecalcData.persediaan_all.push(it);
            if (it.aksi === 'UPDATE') {
                genRecalcData.generate_update.push(it);
            } else if (it.aksi === 'INSERT') {
                genRecalcData.generate_insert.push(it);
            }
        });
    }

    function updateGenRecalcBadges() {
        $('#gen-count-persediaan-all').text(genRecalcData.persediaan_all.length);
        $('#gen-count-update').text(genRecalcData.generate_update.length);
        $('#gen-count-insert').text(genRecalcData.generate_insert.length);
        $('#gen-count-pembelian').text(genRecalcData.pembelian.length);
        $('#gen-count-pembelian-update').text(genRecalcData.pembelian_update.length);
        $('#gen-count-pembelian-baru').text(genRecalcData.pembelian_baru.length);
        $('#gen-count-penjualan').text(genRecalcData.penjualan.length);
        $('#gen-count-penjualan-update').text(genRecalcData.penjualan_update.length);
        $('#gen-count-produksi').text(genRecalcData.produksi.length);
        $('#gen-count-produksi-update').text(genRecalcData.produksi_update.length);
        $('#gen-count-pecah-satuan').text(genRecalcData.pecah_satuan.length);
        $('#gen-count-pecah-satuan-update').text(genRecalcData.pecah_satuan_update.length);
    }

    function buildRowsGenRecalc() {
        var rowsAll = genRecalcData.persediaan_all.map(function(it, i) {
            return [
                i + 1, it.aksi || '', it.id || '', it.namabarang || '', it.satuan || '',
                it.hpp || '', it.spop || '', it.sa || '', it.beli || '', it.total_10 || '', it.keterangan || ''
            ];
        });
        var rowsUpd = genRecalcData.generate_update.map(function(it, i) {
            return [
                i + 1, it.id || '', it.namabarang || '', it.satuan || '', it.hpp || '',
                it.spop || '', it.sa || '', it.beli || '', it.total_10 || '', it.keterangan || ''
            ];
        });
        var rowsIns = genRecalcData.generate_insert.map(function(it, i) {
            return [
                i + 1, it.id || '', it.uuid_persediaan || '', it.namabarang || '', it.satuan || '',
                it.hpp || '', it.spop || '', it.sa || '', it.total_10 || '', it.keterangan || ''
            ];
        });
        var rowsB = genRecalcData.pembelian.map(function(it, i) {
            return [
                i + 1, it.aksi || '', it.tabel || '', it.id_pembelian || '', it.id_persediaan || '',
                it.namabarang || '', it.satuan || '', it.hpp || '', it.spop || '',
                it.jumlah_pembelian || '', it.beli_baru || '', it.keterangan || ''
            ];
        });
        var rowsPU = genRecalcData.pembelian_update.map(function(it, i) {
            return [
                i + 1, it.id_pembelian || '', it.id_persediaan || '', it.namabarang || '',
                it.jumlah_pembelian || '', it.beli_lama || '', it.beli_baru || '', it.total_10 || '', it.keterangan || ''
            ];
        });
        var rowsPB = genRecalcData.pembelian_baru.map(function(it, i) {
            return [
                i + 1, it.id_pembelian || '', it.id_persediaan || '', it.namabarang || '',
                it.satuan || '', it.hpp || '', it.beli_baru || '', it.keterangan || ''
            ];
        });
        var rowsPenj = genRecalcData.penjualan.map(function(it, i) {
            return [
                i + 1, it.aksi || '', it.id_penjualan || '', it.id_persediaan || '', it.namabarang || '',
                it.satuan || '', it.hpp || '', it.spop || '', it.nama_unit || '',
                it.jumlah_penjualan || '', it.penjualan_baru || '', it.total_10 || '', it.keterangan || ''
            ];
        });
        var rowsPenjU = genRecalcData.penjualan_update.map(function(it, i) {
            return [
                i + 1, it.id_penjualan || '', it.id_persediaan || '', it.namabarang || '', it.nama_unit || '',
                it.jumlah_penjualan || '', it.penjualan_lama || '', it.penjualan_baru || '',
                it.unit_lama || '', it.unit_baru || '', it.total_10 || '', it.keterangan || ''
            ];
        });
        var rowsProd = genRecalcData.produksi.map(function(it, i) {
            return [
                i + 1, it.aksi || '', it.id_produksi_bahan || '', it.id_persediaan || '', it.namabarang || '',
                it.satuan || '', it.hpp || '', it.nama_unit || '', it.jumlah_bahan || '',
                it.bahan_produksi_baru || '', it.total_10 || '', it.keterangan || ''
            ];
        });
        var rowsProdU = genRecalcData.produksi_update.map(function(it, i) {
            return [
                i + 1, it.id_produksi_bahan || '', it.id_persediaan || '', it.namabarang || '', it.nama_unit || '',
                it.jumlah_bahan || '', it.bahan_produksi_lama || '', it.bahan_produksi_baru || '',
                it.total_10 || '', it.sisa_stock || '', it.keterangan || ''
            ];
        });
        var rowsPecah = genRecalcData.pecah_satuan.map(function(it, i) {
            return [
                i + 1, it.aksi || '', it.id_pecah_satuan || '', it.id_persediaan_sumber || '', it.id_persediaan_target || '',
                it.namabarang || '', it.satuan || '', it.hpp || '', it.jumlah_pecah || '', it.pecah_satuan_baru || '',
                it.total_10_sumber || '', it.nama_barang_baru || '', it.satuan_barang_baru || '', it.hpp_barang_baru || '',
                it.jumlah_barang_baru || '', it.sa_target || '', it.total_10_target || '', it.keterangan || ''
            ];
        });
        var rowsPecahU = genRecalcData.pecah_satuan_update.map(function(it, i) {
            return [
                i + 1, it.id_pecah_satuan || '', it.id_persediaan_sumber || '', it.id_persediaan_target || '',
                it.namabarang || '', it.nama_barang_baru || '', it.jumlah_pecah || '', it.jumlah_barang_baru || '',
                it.pecah_satuan_baru || '', it.total_10_sumber || '', it.sa_target || '', it.total_10_target || '', it.keterangan || ''
            ];
        });
        return {
            all: rowsAll, update: rowsUpd, insert: rowsIns,
            pembelian: rowsB, pembelian_update: rowsPU, pembelian_baru: rowsPB,
            penjualan: rowsPenj, penjualan_update: rowsPenjU,
            produksi: rowsProd, produksi_update: rowsProdU,
            pecah_satuan: rowsPecah, pecah_satuan_update: rowsPecahU
        };
    }

    function destroyGenRecalcDataTable(sel) {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable(sel)) {
            $(sel).DataTable().clear().destroy();
        }
        genRecalcDt[sel] = null;
    }

    function destroyAllGenRecalcDataTables() {
        [
            '#tbl-gen-recalc-persediaan-all',
            '#tbl-gen-recalc-generate-update',
            '#tbl-gen-recalc-generate-insert',
            '#tbl-gen-recalc-pembelian',
            '#tbl-gen-recalc-pembelian-update',
            '#tbl-gen-recalc-pembelian-baru',
            '#tbl-gen-recalc-penjualan',
            '#tbl-gen-recalc-penjualan-update',
            '#tbl-gen-recalc-produksi',
            '#tbl-gen-recalc-produksi-update',
            '#tbl-gen-recalc-pecah-satuan',
            '#tbl-gen-recalc-pecah-satuan-update'
        ].forEach(destroyGenRecalcDataTable);
    }

    function fillGenRecalcTableSimple(sel, rows) {
        var html = '';
        rows.forEach(function(r) {
            html += '<tr>' + r.map(function(c) { return '<td>' + escapeHtmlGen(c) + '</td>'; }).join('') + '</tr>';
        });
        if (!html) {
            html = '<tr><td colspan="20" class="text-center text-muted">Belum ada data</td></tr>';
        }
        $(sel + ' tbody').html(html);
    }

    function upsertGenRecalcDataTable(sel, rows, orderCol) {
        if (!$.fn.DataTable) {
            fillGenRecalcTableSimple(sel, rows);
            return;
        }
        if ($.fn.DataTable.isDataTable(sel)) {
            var dt = $(sel).DataTable();
            dt.clear();
            if (rows.length) {
                dt.rows.add(rows);
            }
            dt.draw(false);
            genRecalcDt[sel] = dt;
            return;
        }
        genRecalcDt[sel] = $(sel).DataTable({
            data: rows,
            pageLength: 25,
            order: orderCol !== undefined ? [[orderCol, 'asc']] : [],
            language: genRecalcDtLang,
            autoWidth: false
        });
    }

    function renderGenRecalcDataTables() {
        updateGenRecalcBadges();
        var rows = buildRowsGenRecalc();
        upsertGenRecalcDataTable('#tbl-gen-recalc-persediaan-all', rows.all, 3);
        upsertGenRecalcDataTable('#tbl-gen-recalc-generate-update', rows.update, 2);
        upsertGenRecalcDataTable('#tbl-gen-recalc-generate-insert', rows.insert, 3);
        upsertGenRecalcDataTable('#tbl-gen-recalc-pembelian', rows.pembelian, 5);
        upsertGenRecalcDataTable('#tbl-gen-recalc-pembelian-update', rows.pembelian_update, 3);
        upsertGenRecalcDataTable('#tbl-gen-recalc-pembelian-baru', rows.pembelian_baru, 3);
        upsertGenRecalcDataTable('#tbl-gen-recalc-penjualan', rows.penjualan, 4);
        upsertGenRecalcDataTable('#tbl-gen-recalc-penjualan-update', rows.penjualan_update, 3);
        upsertGenRecalcDataTable('#tbl-gen-recalc-produksi', rows.produksi, 4);
        upsertGenRecalcDataTable('#tbl-gen-recalc-produksi-update', rows.produksi_update, 3);
        upsertGenRecalcDataTable('#tbl-gen-recalc-pecah-satuan', rows.pecah_satuan, 5);
        upsertGenRecalcDataTable('#tbl-gen-recalc-pecah-satuan-update', rows.pecah_satuan_update, 3);
    }

    function adjustGenRecalcDataTables() {
        Object.keys(genRecalcDt).forEach(function(sel) {
            var dt = genRecalcDt[sel];
            if (dt && dt.columns) {
                try { dt.columns.adjust().draw(false); } catch (eAdj) {}
            }
        });
    }

    function initGenRecalcDataTablesEmpty() {
        renderGenRecalcDataTables();
    }

    function htmlGenRecalcProgress(data) {
        var phaseLabel = 'Fase 1: Generate dari bulan sumber';
        if (data.phase === 'pembelian') {
            phaseLabel = 'Fase 2: Pembelian → beli';
        } else if (data.phase === 'penjualan') {
            phaseLabel = 'Fase 3: Penjualan → penjualan & total_10';
        } else if (data.phase === 'produksi') {
            phaseLabel = 'Fase 4: Produksi bahan → bahan_produksi & total_10';
        } else if (data.phase === 'pecah_satuan') {
            phaseLabel = 'Fase 5: Pecah satuan → pecah_satuan & total_10 / SA target';
        }
        var total = data.total_phase || data.total_sumber || 1;
        var selesai = data.offset_selesai || 0;
        var pct = total > 0 ? Math.min(100, Math.round((selesai / total) * 100)) : 0;
        return '<p style="margin:0 0 8px;"><strong>' + phaseLabel + '</strong> — ' + selesai + ' / ' + total + '</p>'
            + '<div style="height:8px;background:#e9ecef;border-radius:4px;overflow:hidden;margin-bottom:8px;">'
            + '<div style="height:100%;width:' + pct + '%;background:#28a745;border-radius:4px;"></div></div>'
            + '<p class="small text-muted mb-0">' + escapeHtmlGen(data.pesan || '') + '</p>';
    }

    function destroyGenRecalcDataTables() {
        destroyAllGenRecalcDataTables();
    }

    function runGenerateRecalculateBatch(bulanKey, offset, runner) {
        if (!userCanGeneratePersediaan) {
            runner.active = false;
            Swal.fire({
                icon: 'warning',
                title: 'Akses ditolak',
                text: 'Generate & Recalculate hanya untuk admin.id@gmail.com dan iwanesia.id@gmail.com.'
            });
            return;
        }
        var fd = new FormData();
        fd.append('bulan', bulanKey);
        fd.append('offset', offset);
        fd.append('limit', 30);
        if (runner.isStart) {
            fd.append('start', '1');
        }

        fetch(urlGenerateRecalculateBatch, {
            method: 'POST',
            body: fd,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(parseJsonFetchResponse)
        .then(function(data) {
            if (!data.ok) {
                Swal.fire({
                    icon: data.no_source_data ? 'warning' : 'error',
                    title: data.no_source_data ? 'Bulan referensi kosong' : 'Gagal',
                    text: data.message || 'Proses gagal'
                });
                runner.active = false;
                return;
            }

            if (data.items_persediaan && data.items_persediaan.length) {
                pushGenRecalcPersediaanItems(data.items_persediaan);
            }
            if (data.items_pembelian && data.items_pembelian.length) {
                genRecalcData.pembelian = genRecalcData.pembelian.concat(data.items_pembelian);
            }
            if (data.items_pembelian_update && data.items_pembelian_update.length) {
                genRecalcData.pembelian_update = genRecalcData.pembelian_update.concat(data.items_pembelian_update);
            }
            if (data.items_pembelian_baru && data.items_pembelian_baru.length) {
                genRecalcData.pembelian_baru = genRecalcData.pembelian_baru.concat(data.items_pembelian_baru);
            }
            if (data.items_penjualan && data.items_penjualan.length) {
                genRecalcData.penjualan = genRecalcData.penjualan.concat(data.items_penjualan);
            }
            if (data.items_penjualan_update && data.items_penjualan_update.length) {
                genRecalcData.penjualan_update = genRecalcData.penjualan_update.concat(data.items_penjualan_update);
            }
            if (data.items_produksi && data.items_produksi.length) {
                genRecalcData.produksi = genRecalcData.produksi.concat(data.items_produksi);
            }
            if (data.items_produksi_update && data.items_produksi_update.length) {
                genRecalcData.produksi_update = genRecalcData.produksi_update.concat(data.items_produksi_update);
            }
            if (data.items_pecah_satuan && data.items_pecah_satuan.length) {
                genRecalcData.pecah_satuan = genRecalcData.pecah_satuan.concat(data.items_pecah_satuan);
            }
            if (data.items_pecah_satuan_update && data.items_pecah_satuan_update.length) {
                genRecalcData.pecah_satuan_update = genRecalcData.pecah_satuan_update.concat(data.items_pecah_satuan_update);
            }

            renderGenRecalcDataTables();
            saveGenRecalcResultToStorage(bulanKey);
            adjustGenRecalcDataTables();
            Swal.update({ html: htmlGenRecalcProgress(data) });

            if (!data.done) {
                runner.offset = data.offset_selesai || 0;
                runner.isStart = false;
                setTimeout(function() {
                    runGenerateRecalculateBatch(bulanKey, runner.offset, runner);
                }, 60);
                return;
            }

            runner.active = false;
            Swal.close();

            var s = data.summary || {};
            var summaryHtml = '<strong>Generate &amp; Recalculate selesai</strong><br/>'
                + 'Bulan target: <strong>' + escapeHtmlGen(s.bulan_label || bulanKey) + '</strong> '
                + '(sumber: ' + escapeHtmlGen(s.bulan_sumber_label || '') + ')<br/>'
                + 'Generate — Insert: <strong>' + (s.generate_insert || 0) + '</strong>, Update: <strong>' + (s.generate_update || 0) + '</strong><br/>'
                + 'Pembelian diproses: <strong>' + (s.total_pembelian || 0) + '</strong> — '
                + 'Update beli: <strong>' + (s.pembelian_update || 0) + '</strong>, '
                + 'Record baru: <strong>' + (s.pembelian_insert || 0) + '</strong>'
                + (s.pembelian_gagal ? ', Gagal: <strong>' + s.pembelian_gagal + '</strong>' : '')
                + '<br/>Penjualan diproses: <strong>' + (s.total_penjualan || 0) + '</strong> — '
                + 'Update penjualan: <strong>' + (s.penjualan_update || 0) + '</strong>'
                + (s.penjualan_tidak_cocok ? ', Tidak cocok: <strong>' + s.penjualan_tidak_cocok + '</strong>' : '')
                + (s.penjualan_gagal ? ', Gagal: <strong>' + s.penjualan_gagal + '</strong>' : '')
                + '<br/>Produksi bahan diproses: <strong>' + (s.total_produksi || 0) + '</strong> — '
                + 'Update bahan_produksi: <strong>' + (s.produksi_update || 0) + '</strong>'
                + (s.produksi_tidak_cocok ? ', Tidak cocok: <strong>' + s.produksi_tidak_cocok + '</strong>' : '')
                + (s.produksi_gagal ? ', Gagal: <strong>' + s.produksi_gagal + '</strong>' : '')
                + '<br/>Pecah satuan diproses: <strong>' + (s.total_pecah_satuan || 0) + '</strong> — '
                + 'Update pecah: <strong>' + (s.pecah_update || 0) + '</strong>'
                + (s.pecah_tidak_cocok ? ', Tidak cocok: <strong>' + s.pecah_tidak_cocok + '</strong>' : '')
                + (s.pecah_gagal ? ', Gagal: <strong>' + s.pecah_gagal + '</strong>' : '')
                + '<br/>Hapus duplikat spop kosong/0 (beli=0): <strong>' + (s.cleanup_spop_kosong || 0) + '</strong>'
                + ((s.cleanup_spop_kosong_grup || 0) > 0 ? ' (' + s.cleanup_spop_kosong_grup + ' grup)' : '');
            $('#gen-recalc-summary').html(summaryHtml);
            genRecalcSummaryHtml = summaryHtml;
            renderGenRecalcDataTables();
            saveGenRecalcResultToStorage(bulanKey);
            setStatusGeneratePersediaan('success', summaryHtml);

            Swal.fire({
                icon: 'success',
                title: 'Selesai',
                html: summaryHtml + '<br/><small>Detail proses ditampilkan di tabel di bawah.</small>',
                confirmButtonText: 'OK'
            }).then(function() {
                if ($('#bulan_persediaan').val() !== bulanKey) {
                    $('#bulan_persediaan').val(bulanKey);
                }
                cekGeneratePersediaanBulan();
                if (data.refresh_persediaan && $('#form-persediaan-bulan').length) {
                    $('#form-persediaan-bulan').submit();
                }
                setTimeout(function() {
                    adjustGenRecalcDataTables();
                }, 300);
            });
        })
        .catch(function(err) {
            runner.active = false;
            Swal.fire({ icon: 'error', title: 'Error', text: String(err) });
        });
    }

    $('#btn-generate-persediaan-bulan').on('click', function(e) {
        e.preventDefault();
        if (!userCanGeneratePersediaan) {
            Swal.fire({
                icon: 'warning',
                title: 'Akses ditolak',
                html: 'Generate &amp; Recalculate hanya untuk <strong>admin.id@gmail.com</strong> dan <strong>iwanesia.id@gmail.com</strong>.'
            });
            return false;
        }
        if ($(this).prop('disabled')) {
            return false;
        }
        var bulanKey = getBulanTargetGenerate();
        if (!bulanKey) {
            Swal.fire({ icon: 'warning', title: 'Belum siap', text: 'Pilih bulan/tahun target terlebih dahulu.' });
            return false;
        }

        if (typeof Swal === 'undefined') {
            if (confirm('Lanjutkan Generate & Recalculate?')) {
                runGenerateRecalculateBatch(bulanKey, 0, { offset: 0, active: true, isStart: true });
            }
            return false;
        }

        Swal.fire({
            title: 'Menganalisa data...',
            allowOutsideClick: false,
            didOpen: function() { Swal.showLoading(); }
        });

        $.ajax({
            url: urlCekGeneratePersediaan,
            type: 'POST',
            dataType: 'json',
            data: { bulan: bulanKey }
        }).done(function(res) {
            if (!res || !res.ok) {
                Swal.fire({ icon: 'error', title: 'Gagal', text: (res && res.message) ? res.message : 'Gagal cek data.' });
                return;
            }
            if (!res.can_generate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Bulan referensi kosong',
                    html: res.message || 'Belum ada data persediaan di bulan sebelumnya.'
                });
                return;
            }

            Swal.fire({
                icon: 'question',
                title: 'Konfirmasi Generate & Recalculate',
                html: '<p>Bulan target: <strong>' + bulanKey + '</strong></p>'
                    + '<p class="small text-muted mb-0">Fase 1: salin sumber (sa atau total_10 &gt; 0), hapus target sa=0 &amp; total_10=0.<br/>'
                    + 'Fase 2: pembelian → beli += jumlah, total_10 += jumlah (nama+satuan+hpp+spop).<br/>'
                    + 'Fase 3: penjualan → unit + penjualan += jumlah, total_10 -= jumlah (nama+satuan+hpp).<br/>'
                    + 'Fase 4: bahan produksi dari sys_unit_produk_bahan → bahan_produksi += jumlah_bahan, total_10 -= jumlah_bahan (nama+satuan+hpp).<br/>'
                    + 'Fase 5: pecah satuan dari tbl_pembelian_pecah_satuan → sumber pecah_satuan += jumlah, total_10 -= jumlah; target sa/total_10 += jumlah_barang_baru.</p>',
                showCancelButton: true,
                confirmButtonText: 'Ya, Generate & Recalculate',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745'
            }).then(function(result) {
                if (!result.isConfirmed) return;

                genRecalcData = {
                    persediaan_all: [],
                    generate_update: [],
                    generate_insert: [],
                    pembelian: [],
                    pembelian_update: [],
                    pembelian_baru: [],
                    penjualan: [],
                    penjualan_update: [],
                    produksi: [],
                    produksi_update: []
                };
                genRecalcSummaryHtml = '';
                clearGenRecalcResultStorage(bulanKey);
                destroyGenRecalcDataTables();
                $('#gen-recalc-summary').html('<em>Proses berjalan...</em>');
                initGenRecalcDataTablesEmpty();

                Swal.fire({
                    title: 'Generate & Recalculate',
                    html: htmlGenRecalcProgress({ phase: 'generate', offset_selesai: 0, total_phase: res.count_sumber || 1, pesan: 'Memulai...' }),
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: function() { Swal.showLoading(); }
                });

                runGenerateRecalculateBatch(bulanKey, 0, { offset: 0, active: true, isStart: true });
            });
        }).fail(function() {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat menghubungi server.' });
        });

        return false;
    });

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

    var compareDtStore = {};
    var compareTablesLoaded = false;
    var compareLastResult = null;
    var compareDtLang = {
        emptyTable: 'Belum ada data',
        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
        infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
        search: 'Cari:',
        zeroRecords: 'Tidak ada data yang cocok',
        paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' }
    };

    function getBulanKeyCompare() {
        var bulan = parseInt($('#compare_bulan_persediaan').val(), 10);
        var tahun = parseInt($('#compare_tahun_persediaan').val(), 10);
        if (!bulan || !tahun) return '';
        return tahun + '-' + String(bulan).padStart(2, '0');
    }

    function setCompareStatus(type, html) {
        var $el = $('#compare-status');
        $el.removeClass('alert-info alert-success alert-danger alert-warning');
        $el.addClass(type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-info')));
        $el.html(html);
    }

    function escapeHtmlCompare(s) {
        if (s == null) return '';
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function updateCompareInfoRingkas(res) {
        res = res || compareLastResult || {};
        var stats = res.stats || {};
        $('#compare-label-bulan').text(res.bulan_label || getBulanKeyCompare() || '—');
        $('#compare-label-tabel').text(res.table || $('#compare_tabel_pilihan').val() || '—');
        $('#compare-count-total10').text(typeof stats.total10_kosong !== 'undefined' ? stats.total10_kosong : '—');
        $('#compare-count-tidak').text(typeof stats.tidak_di_tabel !== 'undefined' ? stats.tidak_di_tabel : '—');
        $('#compare-count-hanya').text(typeof stats.hanya_tabel !== 'undefined' ? stats.hanya_tabel : '—');
        $('#compare-count-cocok').text(typeof stats.cocok !== 'undefined' ? stats.cocok : '—');
        $('#compare-badge-total10').text(typeof stats.total10_kosong !== 'undefined' ? stats.total10_kosong : 0);
        $('#compare-badge-tidak').text(typeof stats.tidak_di_tabel !== 'undefined' ? stats.tidak_di_tabel : 0);
        $('#compare-badge-hanya').text(typeof stats.hanya_tabel !== 'undefined' ? stats.hanya_tabel : 0);
        $('#compare-badge-cocok').text(typeof stats.cocok !== 'undefined' ? stats.cocok : 0);
    }

    function loadCompareTableList(force) {
        if (compareTablesLoaded && !force) return;
        var $sel = $('#compare_tabel_pilihan');
        $sel.prop('disabled', true);
        $.ajax({
            url: urlCompareTabelList,
            type: 'POST',
            dataType: 'json'
        }).done(function(res) {
            if (!res || !res.ok) {
                setCompareStatus('danger', (res && res.message) ? res.message : 'Gagal memuat daftar tabel.');
                return;
            }
            var cur = $sel.val();
            $sel.find('option:not(:first)').remove();
            (res.tables || []).forEach(function(tbl) {
                $sel.append($('<option>', { value: tbl, text: tbl }));
            });
            if (cur) $sel.val(cur);
            compareTablesLoaded = true;
            setCompareStatus('info', 'Pilih bulan, tahun, dan tabel — lalu klik <strong>Compare</strong>.');
        }).fail(function() {
            setCompareStatus('danger', 'Tidak dapat memuat daftar tabel dari server.');
        }).always(function() {
            $sel.prop('disabled', false);
        });
    }

    function buildCompareRows(jenis, items) {
        items = items || [];
        if (jenis === 'total10_kosong') {
            return items.map(function(it, i) {
                return [
                    i + 1,
                    it.p_namabarang || '',
                    it.p_satuan || '',
                    it.p_hpp || '',
                    it.p_spop || '',
                    it.p_sa || '',
                    it.p_beli || '',
                    it.p_total_10 || ''
                ];
            });
        }
        return items.map(function(it, i) {
            return [
                i + 1,
                it.p_namabarang || '',
                it.p_satuan || '',
                it.p_hpp || '',
                it.p_spop || '',
                it.p_total_10 || '',
                it.c_namabarang || '',
                it.c_satuan || '',
                it.c_hpp || '',
                it.c_spop || ''
            ];
        });
    }

    function upsertCompareDataTable(selector, rows, orderCol) {
        if ($.fn.DataTable.isDataTable(selector)) {
            var dt = $(selector).DataTable();
            dt.clear();
            if (rows.length) dt.rows.add(rows);
            dt.draw(false);
            compareDtStore[selector] = dt;
            return dt;
        }
        compareDtStore[selector] = $(selector).DataTable({
            data: rows,
            scrollY: 400,
            scrollX: true,
            scrollCollapse: true,
            paging: true,
            pageLength: 25,
            order: orderCol !== undefined ? [[orderCol, 'asc']] : [],
            columnDefs: [{ targets: 0, orderable: false }],
            language: compareDtLang,
            autoWidth: false,
            deferRender: true
        });
        return compareDtStore[selector];
    }

    function renderCompareAllTables(res) {
        res = res || {};
        upsertCompareDataTable('#table-compare-total10', buildCompareRows('total10_kosong', res.items_total10_kosong || []), 1);
        upsertCompareDataTable('#table-compare-tidak', buildCompareRows('tidak_di_tabel', res.items_tidak_di_tabel || []), 1);
        upsertCompareDataTable('#table-compare-hanya', buildCompareRows('hanya_tabel', res.items_hanya_tabel || []), 6);
        upsertCompareDataTable('#table-compare-cocok', buildCompareRows('cocok', res.items_cocok || []), 1);
        setTimeout(function() {
            Object.keys(compareDtStore).forEach(function(sel) {
                var dt = compareDtStore[sel];
                if (dt && dt.columns) {
                    try { dt.columns.adjust().draw(false); } catch (eAdj) {}
                }
            });
        }, 200);
    }

    function updateTombolComparePersediaan() {
        var $btn = $('#btn-compare-tabel');
        if (!userCanComparePersediaan) {
            $btn.prop('disabled', true).removeClass('btn-info').addClass('btn-secondary');
            setCompareStatus('warning', 'Compare hanya untuk <strong>admin.id@gmail.com</strong> dan <strong>iwanesia.id@gmail.com</strong>.');
            return;
        }
        $btn.prop('disabled', false).removeClass('btn-secondary').addClass('btn-info');
    }

    function runCompareTabel() {
        if (!userCanComparePersediaan) {
            Swal.fire({
                icon: 'warning',
                title: 'Akses ditolak',
                html: 'Compare hanya untuk <strong>admin.id@gmail.com</strong> dan <strong>iwanesia.id@gmail.com</strong>.'
            });
            return;
        }
        var bulanKey = getBulanKeyCompare();
        var tabel = $('#compare_tabel_pilihan').val() || '';
        if (!bulanKey) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih bulan dan tahun.' });
            return;
        }
        if (!tabel) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih tabel database yang akan dibandingkan.' });
            return;
        }

        setCompareStatus('info', '<i class="fas fa-spinner fa-spin"></i> Membandingkan persediaan dengan tabel <strong>' + escapeHtmlCompare(tabel) + '</strong>...');

        $.ajax({
            url: urlCompareTabelRun,
            type: 'POST',
            dataType: 'json',
            data: { bulan: bulanKey, tabel: tabel }
        }).done(function(res) {
            if (!res || !res.ok) {
                setCompareStatus('danger', (res && res.message) ? res.message : 'Compare gagal.');
                Swal.fire({ icon: 'error', title: 'Compare gagal', text: (res && res.message) ? res.message : 'Gagal compare.' });
                return;
            }
            compareLastResult = res;
            renderCompareAllTables(res);
            updateCompareInfoRingkas(res);
            var s = res.stats || {};
            setCompareStatus('success', 'Compare selesai — Total_10 kosong: <strong>' + (s.total10_kosong || 0) + '</strong>, '
                + 'Tidak di tabel manual: <strong>' + (s.tidak_di_tabel || 0) + '</strong>, '
                + 'Tidak di persediaan: <strong>' + (s.hanya_tabel || 0) + '</strong>, '
                + 'Cocok: <strong>' + (s.cocok || 0) + '</strong>.');
        }).fail(function() {
            setCompareStatus('danger', 'Tidak dapat menghubungi server.');
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat menghubungi server.' });
        });
    }

    function exportCompareExcel(jenis) {
        if (!userCanComparePersediaan) {
            Swal.fire({
                icon: 'warning',
                title: 'Akses ditolak',
                text: 'Export Excel compare hanya untuk admin.id@gmail.com dan iwanesia.id@gmail.com.'
            });
            return;
        }
        var bulanKey = getBulanKeyCompare();
        var tabel = $('#compare_tabel_pilihan').val() || '';
        if (!bulanKey || !tabel) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih bulan, tahun, dan tabel terlebih dahulu.' });
            return;
        }

        var formData = new FormData();
        formData.append('bulan', bulanKey);
        formData.append('tabel', tabel);
        formData.append('jenis', jenis);

        tampilkanSwalExcelProgress();
        fetch(urlCompareTabelExcel, {
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
                text: 'File Excel berhasil diunduh.',
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
                text: err && err.message ? err.message : 'Export Excel compare gagal.'
            });
        });
    }

    $('a[href="#panel-compare-manual"]').on('shown.bs.tab', function() {
        updateTombolComparePersediaan();
        loadCompareTableList(false);
        updateCompareInfoRingkas();
    });

    $('#compare_bulan_persediaan, #compare_tahun_persediaan, #compare_tabel_pilihan').on('change', function() {
        updateCompareInfoRingkas({ bulan_label: getBulanKeyCompare(), table: $('#compare_tabel_pilihan').val() });
    });

    $('#btn-compare-tabel').on('click', function() {
        if (!userCanComparePersediaan || $(this).prop('disabled')) {
            return false;
        }
        runCompareTabel();
    });

    $(document).on('click', '.btn-compare-excel', function() {
        exportCompareExcel($(this).data('jenis') || '');
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

    var dtPersediaan = null;
    try {
        if ($.fn.DataTable.isDataTable('#table-persediaan')) {
            $('#table-persediaan').DataTable().destroy();
        }
        dtPersediaan = $('#table-persediaan').DataTable({
            scrollY: 500,
            scrollX: true,
            scrollCollapse: true,
            pageLength: 25,
            lengthMenu: [[25, 50, 100, 250, -1], [25, 50, 100, 250, 'Semua']],
            order: [[3, 'asc']],
            columnDefs: [
                { targets: 0, orderable: false },
                { targets: 3, type: 'string' }
            ],
            language: {
                lengthMenu: 'Tampilkan _MENU_ baris',
                info: 'Menampilkan _START_–_END_ dari _TOTAL_ baris',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '(difilter dari _MAX_ total baris)',
                zeroRecords: 'Tidak ada data persediaan untuk bulan ini'
            }
        });
    } catch (dtErr) {
        console.warn('DataTable persediaan:', dtErr);
    }

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

    $('#btn-cetak-excel-generate').on('click', function() {
        exportGenRecalcExcel('');
    });

    $('#btn-cetak-excel-rekonsiliasi-transaksi').on('click', function() {
        if (!userCanGeneratePersediaan) {
            Swal.fire({
                icon: 'warning',
                title: 'Akses ditolak',
                text: 'Export rekonsiliasi transaksi hanya untuk admin.id@gmail.com dan iwanesia.id@gmail.com.'
            });
            return;
        }
        var bulanKey = getBulanExportRekonsiliasi();
        if (!bulanKey) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih bulan persediaan atau bulan/tahun target terlebih dahulu.' });
            return;
        }
        if (!urlExcelRekonsiliasiTransaksi) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'URL export rekonsiliasi tidak tersedia.' });
            return;
        }
        var fd = new FormData();
        fd.append('bulan', bulanKey);
        tampilkanSwalExcelProgress();
        fetch(urlExcelRekonsiliasiTransaksi, {
            method: 'POST',
            body: fd,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(unduhExcelDariResponse)
        .then(function(result) { triggerDownloadBlob(result); })
        .then(function() {
            selesaiSwalExcelProgress();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'File Excel rekonsiliasi transaksi bulan ' + bulanKey + ' berhasil diunduh.',
                timer: 2200,
                showConfirmButton: false
            });
        })
        .catch(function(err) {
            selesaiSwalExcelProgress();
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: err && err.message ? err.message : 'Export Excel rekonsiliasi transaksi gagal.'
            });
        });
    });

    $(document).on('click', '.btn-gen-recalc-excel', function() {
        exportGenRecalcExcel($(this).data('jenis') || '');
    });

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

    $('#btn-cetak-excel-persediaan').on('click', function(e) {
        e.preventDefault();
        if (!urlExcelPersediaan) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'URL export Excel tidak tersedia.' });
            return;
        }
        var bulan = $('#bulan_persediaan').val() || '';
        var formData = new FormData();
        formData.append('bulan_persediaan', bulan);

        tampilkanSwalExcelProgress();

        fetch(urlExcelPersediaan, {
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
            Swal.close();
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
            setTimeout(function() {
                var bulanKey = getBulanTargetGenerate();
                loadGenRecalcHistoryFromServer(bulanKey);
                adjustGenRecalcDataTables();
            }, 150);
        } else if ($(e.target).attr('href') === '#panel-compare-manual') {
            updateTombolComparePersediaan();
            loadCompareTableList(false);
            updateCompareInfoRingkas();
            setTimeout(function() {
                Object.keys(compareDtStore).forEach(function(sel) {
                    var dt = compareDtStore[sel];
                    if (dt && dt.columns) {
                        try { dt.columns.adjust().draw(false); } catch (eAdj) {}
                    }
                });
            }, 150);
        } else if ($(e.target).attr('href') === '#panel-data-persediaan') {
            if (dtPersediaan && dtPersediaan.columns) {
                dtPersediaan.columns.adjust().draw();
            }
        }
    });

    try {
        if (sessionStorage.getItem('persediaan_show_tab') === 'generate') {
            sessionStorage.removeItem('persediaan_show_tab');
            setTimeout(function() {
                $('#tab-generate-persediaan').tab('show');
                cekGeneratePersediaanBulan();
            }, 300);
        } else if (sessionStorage.getItem('persediaan_show_tab') === 'compare') {
            sessionStorage.removeItem('persediaan_show_tab');
            setTimeout(function() {
                $('#tab-compare-manual').tab('show');
                loadCompareTableList(false);
                updateCompareInfoRingkas();
            }, 300);
        }
    } catch (eGenTab) {}

    if (userCanGeneratePersediaan) {
        setTimeout(function() {
            var bulanKey = getBulanTargetGenerate();
            loadGenRecalcHistoryFromServer(bulanKey);
            if ($('#panel-generate-persediaan').hasClass('active') || $('#panel-generate-persediaan').hasClass('show')) {
                cekGeneratePersediaanBulan();
                adjustGenRecalcDataTables();
            }
        }, 400);
    } else {
        updateTombolGeneratePersediaan('denied');
        setStatusGeneratePersediaan('warning', 'Generate &amp; Recalculate hanya untuk <strong>admin.id@gmail.com</strong> dan <strong>iwanesia.id@gmail.com</strong>.');
    }

    updateTombolComparePersediaan();

    setTimeout(function() {
        if ($('#panel-compare-manual').hasClass('active') || $('#panel-compare-manual').hasClass('show')) {
            loadCompareTableList(false);
            updateCompareInfoRingkas();
        }
    }, 500);
});
</script>
