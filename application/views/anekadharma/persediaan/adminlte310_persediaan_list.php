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
                            <a class="nav-link" id="tab-compare-manual" data-toggle="pill" href="#panel-compare-manual" role="tab" aria-controls="panel-compare-manual" aria-selected="false">Compare Data Manual - Online</a>
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
                            $this->load->helper('persediaan_display');
                            $Persediaan_data_barang = persediaan_filter_rows_by_kategori_tab($Persediaan_data, false);
                            $Persediaan_data_jasa = persediaan_filter_rows_by_kategori_tab($Persediaan_data, true);
                            $bulan_tampil = isset($bulan_persediaan_selected) && $bulan_persediaan_selected !== ''
                                ? $bulan_persediaan_selected
                                : date('Y-m');
                            $bulan_label_tampil = htmlspecialchars(date('m/Y', strtotime($bulan_tampil . '-01')), ENT_QUOTES, 'UTF-8');
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
                                        <span class="ml-2 text-muted small" id="info-jumlah-persediaan-bulan">
                                            Bulan <?php echo $bulan_label_tampil; ?> —
                                            Barang: <strong><?php echo count($Persediaan_data_barang); ?></strong> baris,
                                            Jasa: <strong><?php echo count($Persediaan_data_jasa); ?></strong> baris
                                            (total <?php echo count($Persediaan_data); ?> baris)
                                        </span>
                                    </div>
                                </div>
                            </form>

                            <ul class="nav nav-pills mb-2" id="persediaan-data-subtabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab-persediaan-barang" data-toggle="pill" href="#panel-persediaan-barang" role="tab" aria-controls="panel-persediaan-barang" aria-selected="true">
                                        Barang <span class="badge badge-primary" id="badge-persediaan-barang"><?php echo count($Persediaan_data_barang); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-persediaan-jasa" data-toggle="pill" href="#panel-persediaan-jasa" role="tab" aria-controls="panel-persediaan-jasa" aria-selected="false">
                                        Jasa <span class="badge badge-info" id="badge-persediaan-jasa"><?php echo count($Persediaan_data_jasa); ?></span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content" id="persediaan-data-subtabs-content">
                                <div class="tab-pane fade show active" id="panel-persediaan-barang" role="tabpanel" aria-labelledby="tab-persediaan-barang">
                                    <div class="d-flex align-items-center flex-wrap mb-2">
                                        <span class="text-muted small mr-auto">Data persediaan kategori <strong>bukan Jasa</strong></span>
                                        <button type="button" class="btn btn-primary btn-sm btn-cetak-excel-persediaan-tab" data-filter="barang">
                                            <i class="fas fa-file-excel"></i> Cetak ke Excel
                                        </button>
                                    </div>
                                    <?php $this->load->view('anekadharma/persediaan/_persediaan_tab_data_table', array(
                                        'Persediaan_rows' => $Persediaan_data_barang,
                                        'table_id' => 'table-persediaan-barang',
                                        'bulan_tampil' => $bulan_tampil,
                                        'tab_mode' => 'barang',
                                    )); ?>
                                </div>
                                <div class="tab-pane fade" id="panel-persediaan-jasa" role="tabpanel" aria-labelledby="tab-persediaan-jasa">
                                    <div class="d-flex align-items-center flex-wrap mb-2">
                                        <span class="text-muted small mr-auto">Data persediaan kategori <strong>Jasa</strong></span>
                                        <button type="button" class="btn btn-primary btn-sm btn-cetak-excel-persediaan-tab" data-filter="jasa">
                                            <i class="fas fa-file-excel"></i> Cetak ke Excel
                                        </button>
                                    </div>
                                    <?php $this->load->view('anekadharma/persediaan/_persediaan_tab_data_table', array(
                                        'Persediaan_rows' => $Persediaan_data_jasa,
                                        'table_id' => 'table-persediaan-jasa',
                                        'bulan_tampil' => $bulan_tampil,
                                        'tab_mode' => 'jasa',
                                    )); ?>
                                </div>
                            </div>
                            <script>
                            (function() {
                                try {
                                    var subTab = localStorage.getItem('persediaan_active_data_subtab');
                                    if (subTab !== 'jasa') {
                                        return;
                                    }
                                    var tabBarang = document.getElementById('tab-persediaan-barang');
                                    var tabJasa = document.getElementById('tab-persediaan-jasa');
                                    var panelBarang = document.getElementById('panel-persediaan-barang');
                                    var panelJasa = document.getElementById('panel-persediaan-jasa');
                                    if (!tabBarang || !tabJasa || !panelBarang || !panelJasa) {
                                        return;
                                    }
                                    tabBarang.classList.remove('active');
                                    tabBarang.setAttribute('aria-selected', 'false');
                                    tabJasa.classList.add('active');
                                    tabJasa.setAttribute('aria-selected', 'true');
                                    panelBarang.classList.remove('show', 'active');
                                    panelJasa.classList.add('show', 'active');
                                } catch (eSubTab) {}
                            })();
                            </script>

                            <div class="modal fade" id="modal-ubah-persediaan-jasa" tabindex="-1" role="dialog" aria-labelledby="modalUbahPersediaanJasaLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title" id="modalUbahPersediaanJasaLabel">Ubah Data Jasa</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form id="form-ubah-persediaan-jasa" autocomplete="off">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" id="ubah_jasa_id" value="">
                                                <div class="form-group">
                                                    <label for="ubah_jasa_namabarang">Nama Jasa <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="ubah_jasa_namabarang" name="namabarang" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="ubah_jasa_satuan">Satuan <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="ubah_jasa_satuan" name="satuan" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="ubah_jasa_hpp">HPP</label>
                                                    <input type="text" class="form-control text-right" id="ubah_jasa_hpp" name="hpp">
                                                </div>
                                                <div class="form-group">
                                                    <label for="ubah_jasa_sa">SA</label>
                                                    <input type="text" class="form-control text-right" id="ubah_jasa_sa" name="sa">
                                                </div>
                                                <div class="form-group">
                                                    <label for="ubah_jasa_spop">SPOP</label>
                                                    <input type="text" class="form-control" id="ubah_jasa_spop" name="spop">
                                                </div>
                                                <div class="form-group">
                                                    <label for="ubah_jasa_beli">Beli</label>
                                                    <input type="text" class="form-control text-right" id="ubah_jasa_beli" name="beli">
                                                </div>
                                                <div class="form-group">
                                                    <label for="ubah_jasa_tuj">Tuj</label>
                                                    <input type="text" class="form-control text-right" id="ubah_jasa_tuj" name="tuj">
                                                </div>
                                                <div class="form-group">
                                                    <label for="ubah_jasa_total_10">Total 10</label>
                                                    <input type="text" class="form-control text-right" id="ubah_jasa_total_10" name="total_10">
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label for="ubah_jasa_nilai_persediaan">Nilai Persediaan</label>
                                                    <input type="text" class="form-control text-right" id="ubah_jasa_nilai_persediaan" name="nilai_persediaan">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning" id="btn-submit-ubah-persediaan-jasa">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

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
                            <div id="rekap-table-wrap" class="table-responsive persediaan-dt-area persediaan-dt-area-scroll">
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
                                        <strong>Fase 3 — Produk jadi:</strong> dari <strong>sys_unit_produk</strong> (filter <strong>tgl_transaksi</strong> bulan target),
                                        jika ada <strong>uuid_persediaan</strong> → kalkulasi ke record tersebut; jika tidak → agregasi nama+satuan+harga_satuan+spop → insert produk baru atau <strong>sa += jumlah_produksi</strong>, <strong>total_10</strong> disesuaikan.
                                        <strong>Fase 4 — Produksi bahan:</strong> dari <strong>sys_unit_produk_bahan</strong> (filter <strong>tgl_transaksi</strong> bulan target),
                                        cocokkan bahan (uuid_persediaan / nama+satuan+hpp+spop) → <strong>bahan_produksi += jumlah_bahan</strong>, <strong>total_10 −= jumlah_bahan</strong>.
                                        <strong>Fase 5 — Penjualan:</strong> dari <strong>tbl_penjualan</strong> (filter <strong>tgl_jual</strong> bulan target),
                                        cocokkan <strong>uuid_persediaan</strong> (jika kosong/tidak ada → <strong>nama_barang + satuan</strong>) ke persediaan bulan target
                                        → kolom <strong>unit</strong> += jumlah, <strong>penjualan</strong> += jumlah, <strong>total_10</strong> -= jumlah.
                                        <strong>Fase 6 — Pecah satuan:</strong> dari <strong>tbl_pembelian_pecah_satuan</strong> → kurangi <strong>total_10</strong> bahan sumber, tambah record/target pecah.
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
                            <div class="alert alert-info small mb-3" id="gen-recalc-mode-notice">
                                <strong>Mode bertahap: Generate + Pembelian.</strong>
                                Saat klik Generate, sistem <strong>menghapus dulu</strong> semua record <code>persediaan</code> dengan <code>tanggal_beli</code> = bulan target,
                                lalu salin 1:1 dari bulan sebelumnya, kemudian proses <code>tbl_pembelian</code>.
                                Cocokkan: uraian/namabarang + satuan + harga_satuan/hpp + spop (case-insensitive, angka tanpa titik).
                                Fase penjualan, produksi, dan pecah satuan <strong>belum dijalankan</strong>.
                            </div>

                            <div class="card card-outline card-primary mb-3" id="gen-history-generate-wrap">
                                <div class="card-header py-2">
                                    <h3 class="card-title mb-0">History Generate — Klik tanggal untuk muat rekap</h3>
                                </div>
                                <div class="card-body p-2">
                                    <p class="text-muted small mb-2 px-1" id="gen-history-generate-intro">
                                        Daftar proses generate per bulan target. Klik baris untuk menampilkan semua datatable rekap &amp; proses di bawah.
                                    </p>
                                    <div class="gen-recalc-table-scroll gen-recalc-summary-scroll" style="min-height:200px;max-height:320px;">
                                        <table id="tbl-gen-history-generate" class="table table-sm table-bordered table-hover gen-recalc-dt mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal Klik Generate</th>
                                                    <th>Selesai</th>
                                                    <th>Hapus Target</th>
                                                    <th>Generate</th>
                                                    <th>Pembelian</th>
                                                    <th>Status</th>
                                                    <th>User</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="gen-history-generate-tbody">
                                                <tr><td colspan="8" class="text-muted text-center small">Memuat history...</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
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
                                    <div class="gen-recalc-dt-block mb-3">
                                        <div class="gen-recalc-table-scroll">
                                        <table id="tbl-gen-recalc-persediaan-all" class="table table-bordered table-striped gen-recalc-dt">
                                            <thead><tr>
                                                <th>No</th><th>Aksi</th><th>ID</th><th>Nama Barang</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>SA</th><th>Beli</th><th>Total_10</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>

                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>2. Generate — Update Record <span id="gen-count-update" class="badge badge-info">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="generate_update" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-3">
                                        <div class="gen-recalc-table-scroll">
                                        <table id="tbl-gen-recalc-generate-update" class="table table-bordered gen-recalc-dt">
                                            <thead><tr>
                                                <th>No</th><th>ID</th><th>Nama Barang</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>SA</th><th>Beli</th><th>Total_10</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>

                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>3. Generate — Record Baru (Insert) <span id="gen-count-insert" class="badge badge-success">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="generate_insert" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-4">
                                        <div class="gen-recalc-table-scroll">
                                        <table id="tbl-gen-recalc-generate-insert" class="table table-bordered gen-recalc-dt">
                                            <thead><tr>
                                                <th>No</th><th>ID</th><th>UUID</th><th>Nama Barang</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>SA</th><th>Total_10</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>

                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>Verifikasi Generate vs Bulan Sumber <span id="gen-count-verifikasi" class="badge badge-warning">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="generate_verifikasi" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <p class="small text-muted mb-2">Bandingkan kolom <code>total_10</code> bulan sumber (&gt; 0) dengan record bulan target hasil generate. Status <strong>COCOK</strong> = SA target = total_10 sumber, beli=0, penjualan=0, total_10=SA.</p>
                                    <div class="gen-recalc-dt-block mb-4" id="gen-recalc-verifikasi-wrap">
                                        <div class="gen-recalc-table-scroll">
                                        <table id="tbl-gen-recalc-verifikasi" class="table table-bordered table-striped gen-recalc-dt">
                                            <thead><tr>
                                                <th>No</th><th>Status</th><th>ID Sumber</th><th>ID Target</th><th>Nama Barang</th><th>Satuan</th><th>HPP</th><th>SPOP</th>
                                                <th>SA Sumber</th><th>Total_10 Field Sumber</th><th>SA Target</th><th>Beli Target</th><th>Penjualan Target</th><th>Total_10 Target</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>

                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>History Data Bermasalah / Tidak Di-generate <span id="gen-count-masalah" class="badge badge-danger">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="generate_masalah" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <p class="small text-muted mb-2">Catatan proses generate: nilai <code>total_10</code> minus, record dilewati (total_10 &le; 0), dan hasil verifikasi yang tidak cocok — disimpan dengan tanggal/waktu klik Generate.</p>
                                    <div class="gen-recalc-dt-block mb-4" id="gen-recalc-masalah-wrap">
                                        <div class="gen-recalc-table-scroll">
                                        <table id="tbl-gen-recalc-masalah" class="table table-bordered table-striped gen-recalc-dt">
                                            <thead><tr>
                                                <th>No</th><th>Waktu Generate</th><th>Status</th><th>ID Sumber</th><th>ID Target</th><th>Nama Barang</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>Total_10 Sumber</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>

                                    <div id="gen-recalc-phase-lanjut" class="d-none">
                                    <hr/>
                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>4. Semua Data Pembelian Diproses <span id="gen-count-pembelian" class="badge badge-secondary">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="pembelian" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-3">
                                        <div class="gen-recalc-table-scroll">
                                        <table id="tbl-gen-recalc-pembelian" class="table table-bordered table-striped gen-recalc-dt">
                                            <thead><tr>
                                                <th>No</th><th>Aksi</th><th>Tabel</th><th>ID Pembelian</th><th>ID Persediaan</th><th>Nama</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>Jumlah</th><th>Beli Baru</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>
                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>5. Rekap Update Beli (Pembelian → Persediaan) <span id="gen-count-pembelian-update" class="badge badge-info">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="pembelian_update" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-3">
                                        <div class="gen-recalc-table-scroll">
                                        <table id="tbl-gen-recalc-pembelian-update" class="table table-bordered gen-recalc-dt">
                                            <thead><tr>
                                                <th>No</th><th>ID Pembelian</th><th>ID Persediaan</th><th>Nama</th><th>Jumlah</th><th>Beli Lama</th><th>Beli Baru</th><th>Total_10</th><th>Keterangan</th><th>Check Total_10</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>
                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>6. Record Persediaan Baru dari Pembelian <span id="gen-count-pembelian-baru" class="badge badge-success">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="pembelian_baru" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-4">
                                        <div class="gen-recalc-table-scroll">
                                        <table id="tbl-gen-recalc-pembelian-baru" class="table table-bordered gen-recalc-dt">
                                            <thead><tr>
                                                <th>No</th><th>ID Pembelian</th><th>ID Persediaan</th><th>Nama</th><th>Satuan</th><th>HPP</th><th>Beli</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>

                                    <div id="gen-recalc-phase-produksi" class="d-none">
                                    <hr/>
                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>7. Semua Data Produk Jadi Diproses (sys_unit_produk) <span id="gen-count-unit-produk" class="badge badge-secondary">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="unit_produk" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-3">
                                        <div class="gen-recalc-table-scroll">
                                        <table id="tbl-gen-recalc-unit-produk" class="table table-bordered table-striped gen-recalc-dt">
                                            <thead><tr>
                                                <th>No</th><th>Aksi</th><th>ID Unit Produk</th><th>ID Persediaan</th><th>Nama</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>Unit</th><th>Jumlah Produksi</th><th>SA Baru</th><th>Total_10</th><th>Keterangan</th><th>Check Total_10</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>
                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>8. Rekap Insert/Update Produk Jadi → Persediaan <span id="gen-count-unit-produk-update" class="badge badge-info">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="unit_produk_update" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-4">
                                        <div class="gen-recalc-table-scroll">
                                        <table id="tbl-gen-recalc-unit-produk-update" class="table table-bordered gen-recalc-dt">
                                            <thead><tr>
                                                <th>No</th><th>ID Unit Produk</th><th>ID Persediaan</th><th>Nama</th><th>Unit</th><th>Jumlah Produksi</th><th>SA Lama</th><th>SA Baru</th><th>Total_10</th><th>Keterangan</th><th>Check Total_10</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>

                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>9. Semua Data Bahan Produksi Diproses (sys_unit_produk_bahan) <span id="gen-count-produksi" class="badge badge-secondary">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="produksi" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-3">
                                        <div class="gen-recalc-table-scroll">
                                        <table id="tbl-gen-recalc-produksi" class="table table-bordered table-striped gen-recalc-dt">
                                            <thead><tr>
                                                <th>No</th><th>Aksi</th><th>ID Bahan</th><th>ID Persediaan</th><th>Nama</th><th>Satuan</th><th>HPP</th><th>Unit Produksi</th><th>Jumlah Bahan</th><th>Bahan Produksi Baru</th><th>Total_10</th><th>Keterangan</th><th>Check Total_10</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>
                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>10. Rekap Update Bahan Produksi → Persediaan <span id="gen-count-produksi-update" class="badge badge-info">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="produksi_update" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-4">
                                        <div class="gen-recalc-table-scroll">
                                        <table id="tbl-gen-recalc-produksi-update" class="table table-bordered gen-recalc-dt">
                                            <thead><tr>
                                                <th>No</th><th>ID Bahan</th><th>ID Persediaan</th><th>Nama</th><th>Unit Produksi</th><th>Jumlah Bahan</th><th>Bahan Lama</th><th>Bahan Baru</th><th>Total_10</th><th>Sisa Stock</th><th>Keterangan</th><th>Check Total_10</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>
                                    </div><!-- /gen-recalc-phase-produksi -->

                                    <div id="gen-recalc-phase-penjualan" class="d-none">
                                    <hr/>
                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>11. Semua Data Penjualan Diproses (tbl_penjualan) <span id="gen-count-penjualan" class="badge badge-secondary">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="penjualan" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <p class="small text-muted mb-2 px-1">Cocokkan <code>uuid_persediaan</code> di tbl_penjualan ke persediaan bulan target; jika tidak ditemukan, fallback <strong>nama_barang + satuan</strong>. Update: <strong>unit</strong> += jumlah, <strong>penjualan</strong> += jumlah, <strong>total_10</strong> -= jumlah.</p>
                                    <div class="gen-recalc-dt-block mb-3">
                                        <div class="gen-recalc-table-scroll">
                                        <table id="tbl-gen-recalc-penjualan" class="table table-bordered table-striped gen-recalc-dt">
                                            <thead><tr>
                                                <th>No</th><th>Aksi</th><th>ID Penjualan</th><th>ID Persediaan</th><th>UUID Persediaan</th><th>Nama</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>Unit</th><th>Jumlah</th><th>Penjualan Baru</th><th>Total_10</th><th>Keterangan</th><th>Check Total_10</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>
                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>12. Rekap Update Penjualan Berhasil → Persediaan <span id="gen-count-penjualan-update" class="badge badge-success">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="penjualan_update" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-4">
                                        <div class="gen-recalc-table-scroll">
                                        <table id="tbl-gen-recalc-penjualan-update" class="table table-bordered gen-recalc-dt">
                                            <thead><tr>
                                                <th>No</th><th>ID Penjualan</th><th>ID Persediaan</th><th>UUID Persediaan</th><th>Nama</th><th>Unit</th><th>Jumlah</th><th>Penjualan Lama</th><th>Penjualan Baru</th><th>Unit Lama</th><th>Unit Baru</th><th>Total_10</th><th>Keterangan</th><th>Check Total_10</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>
                                    </div><!-- /gen-recalc-phase-penjualan -->

                                    <div id="gen-recalc-phase-full-only">
                                    <hr/>
                                    <h6 class="mt-4 d-flex align-items-center flex-wrap">
                                        <span>13. Pecah Satuan — Semua Record <span id="gen-count-pecah-satuan" class="badge badge-secondary">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="pecah_satuan" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-3">
                                        <div class="gen-recalc-table-scroll">
                                        <table id="tbl-gen-recalc-pecah-satuan" class="table table-bordered table-striped gen-recalc-dt">
                                            <thead><tr>
                                                <th>No</th><th>Aksi</th><th>ID Pecah</th><th>ID Sumber</th><th>ID Target</th><th>Nama Sumber</th><th>Satuan</th><th>HPP</th><th>Jumlah Pecah</th><th>Pecah Baru</th><th>Total_10 Sumber</th><th>Nama Baru</th><th>Satuan Baru</th><th>HPP Baru</th><th>Jumlah Baru</th><th>SA Target</th><th>Total_10 Target</th><th>Keterangan</th><th>Check Total_10</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>

                                    <h6 class="mt-2 d-flex align-items-center flex-wrap">
                                        <span>14. Pecah Satuan — Update Record <span id="gen-count-pecah-satuan-update" class="badge badge-info">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-gen-recalc-excel ml-2 mb-1" data-jenis="pecah_satuan_update" title="Cetak tabel ini ke Excel"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-3">
                                        <div class="gen-recalc-table-scroll">
                                        <table id="tbl-gen-recalc-pecah-satuan-update" class="table table-bordered gen-recalc-dt">
                                            <thead><tr>
                                                <th>No</th><th>ID Pecah</th><th>ID Sumber</th><th>ID Target</th><th>Nama Sumber</th><th>Nama Baru</th><th>Jumlah Pecah</th><th>Jumlah Baru</th><th>Pecah Baru</th><th>Total_10 Sumber</th><th>SA Target</th><th>Total_10 Target</th><th>Keterangan</th><th>Check Total_10</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>
                                    </div><!-- /gen-recalc-phase-full-only -->
                                    </div><!-- /gen-recalc-phase-lanjut -->
                                </div>
                            </div>

                            <div class="card card-outline card-success mt-3 d-none" id="gen-recalc-summary-wrap">
                                <div class="card-header">
                                    <h3 class="card-title">Rekap Hasil Generate &amp; Recalculate Pembelian</h3>
                                </div>
                                <div class="card-body p-2">
                                    <p class="text-muted small mb-3 px-1" id="gen-recalc-summary-intro">
                                        Tabel rekap di bawah menampilkan hasil generate dan recalculate pembelian untuk bulan target yang dipilih.
                                    </p>

                                    <h6 class="d-flex align-items-center flex-wrap px-1">
                                        <span>1. Persediaan Asli Bulan Sebelumnya <span id="gen-sum-count-persediaan-lalu" class="badge badge-secondary">0</span></span>
                                        <span class="text-muted small ml-2" id="gen-sum-label-persediaan-lalu"></span>
                                        <button type="button" class="btn btn-xs btn-outline-success btn-gen-recalc-summary-excel ml-2 mb-1" data-jenis="persediaan_bulan_lalu"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-3">
                                        <div class="gen-recalc-table-scroll gen-recalc-summary-scroll">
                                        <table id="tbl-gen-sum-persediaan-lalu" class="table table-bordered table-striped gen-recalc-dt gen-recalc-summary-dt">
                                            <thead><tr><th>No</th><th>Nama Barang</th><th>Satuan</th><th>HPP</th><th>SA</th><th>SPOP</th><th>Beli</th><th>Total 10</th><th>Nilai Persediaan</th></tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>

                                    <h6 class="d-flex align-items-center flex-wrap px-1">
                                        <span>2. Persediaan Total Bulan Target (Barang &amp; Jasa) <span id="gen-sum-count-persediaan-target" class="badge badge-primary">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-success btn-gen-recalc-summary-excel ml-2 mb-1" data-jenis="persediaan_total_target"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-3">
                                        <div class="gen-recalc-table-scroll gen-recalc-summary-scroll">
                                        <table id="tbl-gen-sum-persediaan-target" class="table table-bordered table-striped gen-recalc-dt gen-recalc-summary-dt">
                                            <thead><tr><th>No</th><th>Nama Barang</th><th>Satuan</th><th>HPP</th><th>SA</th><th>SPOP</th><th>Beli</th><th>Total 10</th><th>Nilai Persediaan</th></tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>

                                    <h6 class="d-flex align-items-center flex-wrap px-1">
                                        <span>3. Pembelian Masuk ke Persediaan (Semua) <span id="gen-sum-count-pembelian-semua" class="badge badge-info">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-success btn-gen-recalc-summary-excel ml-2 mb-1" data-jenis="pembelian_semua"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-3">
                                        <div class="gen-recalc-table-scroll gen-recalc-summary-scroll">
                                        <table id="tbl-gen-sum-pembelian-semua" class="table table-bordered table-striped gen-recalc-dt gen-recalc-summary-dt">
                                            <thead><tr><th>No</th><th>Uraian</th><th>Satuan</th><th>Harga Satuan</th><th>Jumlah</th><th>SPOP</th><th>Tgl PO</th></tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>

                                    <h6 class="d-flex align-items-center flex-wrap px-1">
                                        <span>4. Pembelian — Update Beli di Persediaan <span id="gen-sum-count-pembelian-update" class="badge badge-warning">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-success btn-gen-recalc-summary-excel ml-2 mb-1" data-jenis="pembelian_update_beli"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-3">
                                        <div class="gen-recalc-table-scroll gen-recalc-summary-scroll">
                                        <table id="tbl-gen-sum-pembelian-update" class="table table-bordered table-striped gen-recalc-dt gen-recalc-summary-dt">
                                            <thead><tr><th>No</th><th>Aksi</th><th>ID Pembelian</th><th>ID Persediaan</th><th>Nama</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>Jumlah</th><th>Record Grup</th><th>Beli Lama</th><th>Beli Baru</th><th>Total 10</th><th>Keterangan</th></tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>

                                    <h6 class="d-flex align-items-center flex-wrap px-1">
                                        <span>5. Pembelian — Record Persediaan Baru <span id="gen-sum-count-pembelian-baru" class="badge badge-success">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-success btn-gen-recalc-summary-excel ml-2 mb-1" data-jenis="pembelian_insert_baru"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-3">
                                        <div class="gen-recalc-table-scroll gen-recalc-summary-scroll">
                                        <table id="tbl-gen-sum-pembelian-baru" class="table table-bordered table-striped gen-recalc-dt gen-recalc-summary-dt">
                                            <thead><tr><th>No</th><th>Aksi</th><th>ID Pembelian</th><th>ID Persediaan</th><th>Nama</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>Jumlah</th><th>Record Grup</th><th>Beli Baru</th><th>Total 10</th><th>Keterangan</th></tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>

                                    <h6 class="d-flex align-items-center flex-wrap px-1">
                                        <span>6. Persediaan Bulan Lalu Tidak Masuk ke Bulan Ini <span id="gen-sum-count-persediaan-tidak-masuk" class="badge badge-danger">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-success btn-gen-recalc-summary-excel ml-2 mb-1" data-jenis="persediaan_sumber_tidak_masuk"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-3">
                                        <div class="gen-recalc-table-scroll gen-recalc-summary-scroll">
                                        <table id="tbl-gen-sum-persediaan-tidak-masuk" class="table table-bordered table-striped gen-recalc-dt gen-recalc-summary-dt">
                                            <thead><tr><th>No</th><th>Nama Barang</th><th>Satuan</th><th>HPP</th><th>SA</th><th>SPOP</th><th>Beli</th><th>Total 10</th><th>Nilai Persediaan</th></tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>

                                    <h6 class="d-flex align-items-center flex-wrap px-1">
                                        <span>7. Pembelian — SPOP Lebih dari 1 Record <span id="gen-sum-count-pembelian-spop-multi" class="badge badge-dark">0</span></span>
                                        <span class="text-muted small ml-2" id="gen-sum-label-pembelian-spop-multi"></span>
                                        <button type="button" class="btn btn-xs btn-outline-success btn-gen-recalc-summary-excel ml-2 mb-1" data-jenis="pembelian_spop_multi"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-3">
                                        <div class="gen-recalc-table-scroll gen-recalc-summary-scroll">
                                        <table id="tbl-gen-sum-pembelian-spop-multi" class="table table-bordered table-striped gen-recalc-dt gen-recalc-summary-dt">
                                            <thead><tr><th>No</th><th>ID Pembelian</th><th>Uraian</th><th>Satuan</th><th>Harga Satuan</th><th>Jumlah</th><th>SPOP</th><th>Tgl PO</th><th>Jumlah Record SPOP</th><th>Keterangan</th></tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>

                                    <h6 class="d-flex align-items-center flex-wrap px-1">
                                        <span>8. Pembelian — SPOP 1 Record Saja <span id="gen-sum-count-pembelian-spop-single" class="badge badge-secondary">0</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-success btn-gen-recalc-summary-excel ml-2 mb-1" data-jenis="pembelian_spop_single"><i class="fas fa-file-excel"></i> Excel</button>
                                    </h6>
                                    <div class="gen-recalc-dt-block mb-3">
                                        <div class="gen-recalc-table-scroll gen-recalc-summary-scroll">
                                        <table id="tbl-gen-sum-pembelian-spop-single" class="table table-bordered table-striped gen-recalc-dt gen-recalc-summary-dt">
                                            <thead><tr><th>No</th><th>ID Pembelian</th><th>Uraian</th><th>Satuan</th><th>Harga Satuan</th><th>Jumlah</th><th>SPOP</th><th>Tgl PO</th><th>Jumlah Record SPOP</th><th>Keterangan</th></tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-outline card-warning mt-3 d-none" id="gen-recalc-gagal-persediaan-wrap">
                                <div class="card-header py-2 bg-warning">
                                    <h3 class="card-title mb-0">
                                        Gagal Memasukan ke Persediaan
                                        <span id="gen-count-gagal-persediaan" class="badge badge-dark ml-1">0</span>
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-xs btn-dark btn-gen-recalc-excel" data-jenis="gagal_insert_persediaan" title="Export tabel gagal insert persediaan ke Excel">
                                            <i class="fas fa-file-excel"></i> Excel
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <p class="text-muted small mb-2 px-1" id="gen-recalc-gagal-persediaan-intro">
                                        Record produk jadi, pembelian baru, atau bahan produksi yang gagal di-insert/update ke tabel persediaan.
                                        Lihat kolom <strong>Masalah / Error</strong> untuk detail penyebabnya.
                                    </p>
                                    <div class="gen-recalc-dt-block mb-0">
                                        <div class="gen-recalc-table-scroll gen-recalc-summary-scroll" style="min-height:200px;max-height:480px;">
                                        <table id="tbl-gen-recalc-gagal-persediaan" class="table table-sm table-bordered table-striped gen-recalc-dt mb-0">
                                            <thead><tr>
                                                <th>No</th><th>Fase</th><th>Aksi</th><th>Tabel</th><th>ID Sumber</th><th>ID Target</th>
                                                <th>Nama</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>Jumlah</th><th>Masalah / Error</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-outline card-danger mt-3 d-none" id="gen-recalc-gagal-wrap">
                                <div class="card-header py-2 bg-danger">
                                    <h3 class="card-title text-white mb-0">
                                        Gagal Generate atau Recalculate
                                        <span id="gen-count-gagal" class="badge badge-light ml-1">0</span>
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-xs btn-light btn-gen-recalc-excel" data-jenis="gagal_generate_recalculate" title="Export tabel gagal ke Excel">
                                            <i class="fas fa-file-excel"></i> Excel
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <p class="text-muted small mb-2 px-1" id="gen-recalc-gagal-intro">
                                        Record yang gagal diproses saat generate atau recalculate. Lihat kolom <strong>Keterangan</strong> untuk penyebab
                                        (mis. uraian/satuan kosong, sudah ada di persediaan, error database insert).
                                    </p>
                                    <div class="gen-recalc-dt-block mb-0">
                                        <div class="gen-recalc-table-scroll gen-recalc-summary-scroll" style="min-height:200px;max-height:480px;">
                                        <table id="tbl-gen-recalc-gagal" class="table table-sm table-bordered table-striped gen-recalc-dt mb-0">
                                            <thead><tr>
                                                <th>No</th><th>Fase</th><th>Aksi</th><th>Tabel</th><th>ID Sumber</th><th>ID Target</th>
                                                <th>Nama</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>Jumlah</th><th>Keterangan</th>
                                            </tr></thead>
                                            <tbody></tbody>
                                            <tfoot><tr></tr></tfoot>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 4: COMPARE DATA MANUAL - ONLINE -->
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
                                    <h5 class="mb-2">Compare Data Manual — Online</h5>
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
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="compare_db_tabel_cek" class="mb-1">Pilih tabel database (semua tabel di sistem)</label>
                                    <select id="compare_db_tabel_cek" class="form-control form-control-sm compare-db-tabel-select mb-2"<?php echo empty($can_compare_persediaan) ? ' disabled' : ''; ?>>
                                        <option value="">— Muat daftar tabel —</option>
                                    </select>
                                    <div id="compare-db-tabel-info" class="compare-db-tabel-info d-none mb-3">
                                        <div class="small mb-2">
                                            <span class="text-muted">Nama tabel:</span>
                                            <strong id="compare-db-tabel-nama" class="text-primary">—</strong>
                                        </div>
                                        <button type="button" id="btn-compare-db-cek-data" class="btn btn-outline-info btn-sm"<?php echo empty($can_compare_persediaan) ? ' disabled' : ''; ?>>
                                            <i class="fas fa-search"></i> Cek Data
                                        </button>
                                        <button type="button" id="btn-compare-db-insert-persediaan" class="btn btn-sm ml-1 d-none btn-compare-db-insert-persediaan"<?php echo empty($can_compare_persediaan) ? ' disabled' : ''; ?>>
                                            <i class="fas fa-database"></i> Insert ke tabel Persediaan
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <small class="text-muted d-block mb-1">Minimal kolom: nama barang, satuan, hpp/harga_satuan, spop</small>
                                    <small class="text-muted d-block mb-2">
                                        Rekomendasi: beri nama file CSV yang jelas dan mudah dikenali,
                                        contoh: <code>persediaan_manual_2025_01.csv</code> atau <code>persediaan_manual_2025.csv</code>.
                                        Tabel database dibuat dari <strong>nama file CSV</strong> (tanpa .csv).
                                        Jika nama tabel sudah ada, sistem menambahkan urutan di belakang: <code>_1</code>, <code>_2</code>, dan seterusnya.
                                    </small>
                                    <label for="compare_csv_file" class="mb-1">Pilih file CSV ( untuk di upload ke database sistem / aplikasi menjadi tabel data )</label>
                                    <div class="d-flex flex-wrap align-items-end compare-csv-upload-row">
                                        <div class="custom-file custom-file-sm mb-0 compare-csv-file-wrap">
                                            <input type="file" class="custom-file-input" id="compare_csv_file" accept=".csv,text/csv"<?php echo empty($can_compare_persediaan) ? ' disabled' : ''; ?>>
                                            <label class="custom-file-label" for="compare_csv_file" data-browse="Pilih">Cari / pilih file CSV...</label>
                                        </div>
                                        <div id="compare-csv-upload-info" class="compare-csv-upload-info d-none ml-3 mb-1">
                                            <div class="small mb-1">
                                                <span class="text-muted">File:</span>
                                                <strong id="compare-csv-filename" class="text-dark">—</strong>
                                            </div>
                                            <div class="small mb-1">
                                                <span class="text-muted">Tabel DB:</span>
                                                <strong id="compare-csv-tablename" class="text-primary">—</strong>
                                                <span class="text-muted" id="compare-csv-rowcount"></span>
                                            </div>
                                            <button type="button" id="btn-compare-csv-lihat" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-table"></i> Lihat Data
                                            </button>
                                            <button type="button" id="btn-compare-csv-insert-persediaan" class="btn btn-outline-success btn-sm ml-1">
                                                <i class="fas fa-database"></i> Insert ke Persediaan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-end compare-toolbar-row flex-nowrap">
                                <div class="col-auto mb-2">
                                    <label for="compare_bulan_persediaan" class="small mb-1">Bulan</label>
                                    <select id="compare_bulan_persediaan" class="form-control form-control-sm compare-toolbar-control">
                                        <?php foreach ($nama_bulan_id as $num => $label_bulan) { ?>
                                            <option value="<?php echo (int) $num; ?>"<?php echo ((int) $num === $compare_bulan_num) ? ' selected' : ''; ?>>
                                                <?php echo htmlspecialchars($label_bulan, ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-auto mb-2">
                                    <label for="compare_tahun_persediaan" class="small mb-1">Tahun</label>
                                    <select id="compare_tahun_persediaan" class="form-control form-control-sm compare-toolbar-control">
                                        <?php for ($th = $gen_tahun_max; $th >= $gen_tahun_min; $th--) { ?>
                                            <option value="<?php echo (int) $th; ?>"<?php echo ((int) $th === $compare_tahun_num) ? ' selected' : ''; ?>>
                                                <?php echo (int) $th; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-auto mb-2">
                                    <label for="compare_tabel_pilihan" class="small mb-1">pilih tabel untuk di bandingkan / comparing</label>
                                    <select id="compare_tabel_pilihan" class="form-control form-control-sm compare-toolbar-control compare-toolbar-tabel">
                                        <option value="">— Muat daftar tabel —</option>
                                    </select>
                                </div>
                                <div class="col-auto mb-2">
                                    <label class="small mb-1 d-block">&nbsp;</label>
                                    <button type="button" id="btn-compare-tabel" class="btn btn-info btn-sm"<?php echo empty($can_compare_persediaan) ? ' disabled' : ''; ?>>
                                        <i class="fas fa-columns"></i> Compare
                                    </button>
                                    <button type="button" id="btn-compare-excel-all" class="btn btn-success btn-sm ml-1"<?php echo empty($can_compare_persediaan) ? ' disabled' : ''; ?>>
                                        <i class="fas fa-file-excel"></i> Cetak Excel ALL
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
                                <table id="table-compare-total10" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
                                    <thead><tr>
                                        <th>No</th><th>Namabarang</th><th>Satuan</th><th>HPP</th><th>SPOP</th><th>Sa</th><th>Beli</th><th>Total_10</th>
                                    </tr></thead>
                                    <tbody></tbody>
                                    <tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>
                                </table>
                            </div>

                            <h6 class="d-flex align-items-center flex-wrap">
                                <span>2. Persediaan Tidak Ada di Tabel Manual <span id="compare-badge-tidak" class="badge badge-warning">0</span></span>
                                <button type="button" class="btn btn-xs btn-outline-primary btn-compare-excel ml-2 mb-1" data-jenis="tidak_di_tabel"><i class="fas fa-file-excel"></i> Excel</button>
                            </h6>
                            <div class="compare-dt-wrap mb-4">
                                <table id="table-compare-tidak" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
                                    <thead><tr>
                                        <th>No</th><th>P_Namabarang</th><th>P_Satuan</th><th>P_HPP</th><th>P_SPOP</th><th>P_SA</th><th>P_Beli</th><th>P_Total_10</th>
                                        <th>C_Nama</th><th>C_Satuan</th><th>C_HPP</th><th>C_SPOP</th><th>C_SA</th><th>C_Beli</th><th>C_Total_10</th>
                                    </tr></thead>
                                    <tbody></tbody>
                                    <tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>
                                </table>
                            </div>

                            <h6 class="d-flex align-items-center flex-wrap">
                                <span>3. Tabel Manual Tidak Ada di Persediaan <span id="compare-badge-hanya" class="badge badge-info">0</span></span>
                                <button type="button" class="btn btn-xs btn-outline-primary btn-compare-excel ml-2 mb-1" data-jenis="hanya_tabel"><i class="fas fa-file-excel"></i> Excel</button>
                            </h6>
                            <div class="compare-dt-wrap mb-4">
                                <table id="table-compare-hanya" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
                                    <thead><tr>
                                        <th>No</th><th>P_Namabarang</th><th>P_Satuan</th><th>P_HPP</th><th>P_SPOP</th><th>P_SA</th><th>P_Beli</th><th>P_Total_10</th>
                                        <th>C_Nama</th><th>C_Satuan</th><th>C_HPP</th><th>C_SPOP</th><th>C_SA</th><th>C_Beli</th><th>C_Total_10</th>
                                    </tr></thead>
                                    <tbody></tbody>
                                    <tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>
                                </table>
                            </div>

                            <h6 class="d-flex align-items-center flex-wrap">
                                <span>4. Data Cocok (Persediaan &amp; Tabel Manual) <span id="compare-badge-cocok" class="badge badge-success">0</span></span>
                                <button type="button" class="btn btn-xs btn-outline-primary btn-compare-excel ml-2 mb-1" data-jenis="cocok"><i class="fas fa-file-excel"></i> Excel</button>
                            </h6>
                            <div class="compare-dt-wrap mb-4">
                                <table id="table-compare-cocok" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
                                    <thead><tr>
                                        <th>No</th><th>P_Namabarang</th><th>P_Satuan</th><th>P_HPP</th><th>P_SPOP</th><th>P_SA</th><th>P_Beli</th><th>P_Total_10</th>
                                        <th>C_Nama</th><th>C_Satuan</th><th>C_HPP</th><th>C_SPOP</th><th>C_SA</th><th>C_Beli</th><th>C_Total_10</th>
                                    </tr></thead>
                                    <tbody></tbody>
                                    <tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>
                                </table>
                            </div>

                            <h6 class="d-flex align-items-center flex-wrap mt-3">
                                <span>5. Pembelian Tidak Ada di Tabel Manual <span id="compare-badge-pembelian-tidak" class="badge badge-danger">0</span></span>
                                <button type="button" class="btn btn-xs btn-outline-primary btn-compare-excel ml-2 mb-1" data-jenis="pembelian_tidak_manual"><i class="fas fa-file-excel"></i> Excel</button>
                            </h6>
                            <div class="compare-dt-wrap mb-4">
                                <table id="table-compare-pembelian-tidak" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
                                    <thead><tr>
                                        <th>No</th><th>Tgl PO</th><th>Nama Barang</th><th>SPOP</th><th>Satuan</th><th>Harga Satuan</th><th>Jumlah</th><th>Keterangan</th>
                                    </tr></thead>
                                    <tbody></tbody>
                                    <tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>
                                </table>
                            </div>

                            <h6 class="d-flex align-items-center flex-wrap">
                                <span>6. Penjualan Tidak Ada di Tabel Manual <span id="compare-badge-penjualan-tidak" class="badge badge-danger">0</span></span>
                                <button type="button" class="btn btn-xs btn-outline-primary btn-compare-excel ml-2 mb-1" data-jenis="penjualan_tidak_manual"><i class="fas fa-file-excel"></i> Excel</button>
                            </h6>
                            <div class="compare-dt-wrap mb-4">
                                <table id="table-compare-penjualan-tidak" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
                                    <thead><tr>
                                        <th>No</th><th>Tgl Jual</th><th>Nama Barang</th><th>SPOP</th><th>Satuan</th><th>Harga Satuan</th><th>Jumlah</th><th>Keterangan</th>
                                    </tr></thead>
                                    <tbody></tbody>
                                    <tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>
                                </table>
                            </div>

                            <h6 class="d-flex align-items-center flex-wrap">
                                <span>7. Produksi Tidak Ada di Tabel Manual <span id="compare-badge-produksi-tidak" class="badge badge-danger">0</span></span>
                                <button type="button" class="btn btn-xs btn-outline-primary btn-compare-excel ml-2 mb-1" data-jenis="produksi_tidak_manual"><i class="fas fa-file-excel"></i> Excel</button>
                            </h6>
                            <div class="compare-dt-wrap mb-4">
                                <table id="table-compare-produksi-tidak" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
                                    <thead><tr>
                                        <th>No</th><th>Nama Barang Bahan</th><th>Satuan Bahan</th><th>Harga Satuan Bahan</th><th>SPOP</th><th>Jumlah Bahan</th><th>Tgl Transaksi</th>
                                    </tr></thead>
                                    <tbody></tbody>
                                    <tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>
                                </table>
                            </div>

                            <h6 class="d-flex align-items-center flex-wrap">
                                <span>8. Pecah Satuan Tidak Ada di Tabel Manual <span id="compare-badge-pecah-tidak" class="badge badge-danger">0</span></span>
                                <button type="button" class="btn btn-xs btn-outline-primary btn-compare-excel ml-2 mb-1" data-jenis="pecah_tidak_manual"><i class="fas fa-file-excel"></i> Excel</button>
                            </h6>
                            <div class="compare-dt-wrap mb-4">
                                <table id="table-compare-pecah-tidak" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
                                    <thead><tr>
                                        <th>No</th><th>Uraian</th><th>Satuan</th><th>Harga Satuan</th><th>SPOP</th><th>Jumlah</th>
                                    </tr></thead>
                                    <tbody></tbody>
                                    <tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="modal fade" id="modal-compare-csv-preview" tabindex="-1" role="dialog" aria-labelledby="modalCompareCsvPreviewLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document" style="max-width:95%;">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white py-2">
                                        <h5 class="modal-title" id="modalCompareCsvPreviewLabel">Data Tabel Database</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body pb-2">
                                        <p class="text-muted small mb-2" id="compare-csv-preview-meta">
                                            Memuat informasi tabel...
                                        </p>
                                        <div id="compare-csv-preview-loading" class="text-center py-4 text-muted d-none">
                                            <i class="fas fa-spinner fa-spin"></i> Memuat data tabel...
                                        </div>
                                        <div class="compare-csv-preview-dt-wrap">
                                            <table id="table-compare-csv-preview" class="table table-bordered table-striped table-sm" style="width:100%;font-size:12px;">
                                                <thead><tr></tr></thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer py-2">
                                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
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
    /* Tab Generate Persediaan — box lebar penuh, scroll di dalam DataTable */
    #panel-generate-persediaan .gen-recalc-dt-block {
        margin-bottom: 1rem;
        width: 100%;
    }
    #panel-generate-persediaan .gen-recalc-table-scroll {
        display: block;
        width: 100%;
        min-height: 0;
        max-height: none;
        overflow: hidden;
        -webkit-overflow-scrolling: touch;
        border: 1px solid #ffd54f;
        border-radius: 4px;
        background: #fff;
        margin-bottom: 0;
        box-shadow: 0 0 5px rgba(255, 193, 7, 0.65), 0 0 1px rgba(255, 215, 0, 0.95);
    }
    #panel-generate-persediaan .gen-recalc-summary-scroll {
        width: 100%;
        min-height: 0;
        max-height: none;
        overflow: hidden;
    }
    #panel-generate-persediaan .gen-recalc-dt-paging-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        padding: 0.35rem 0.5rem 0.5rem;
        border: 1px solid #ffd54f;
        border-top: none;
        border-radius: 0 0 4px 4px;
        background: #fffef5;
        font-size: 14px;
        box-shadow: 0 0 4px rgba(255, 193, 7, 0.35);
    }
    #panel-generate-persediaan .gen-recalc-dt-paging-bar .dataTables_info,
    #panel-generate-persediaan .gen-recalc-dt-paging-bar .dataTables_paginate {
        margin: 0;
        padding: 0;
        float: none;
    }
    #panel-generate-persediaan .gen-recalc-summary-scroll + .gen-recalc-dt-paging-bar {
        margin-top: 0;
    }
    #panel-generate-persediaan .gen-recalc-table-scroll .dataTables_wrapper .dataTables_info,
    #panel-generate-persediaan .gen-recalc-table-scroll .dataTables_wrapper .dataTables_paginate {
        display: none;
    }
    #panel-generate-persediaan .gen-recalc-summary-scroll .dataTables_wrapper .dataTables_info,
    #panel-generate-persediaan .gen-recalc-summary-scroll .dataTables_wrapper .dataTables_paginate {
        display: none;
    }
    #panel-generate-persediaan #gen-recalc-summary-wrap .card-body {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    #panel-generate-persediaan table.gen-recalc-summary-dt tr.gen-recalc-row-subtotal td,
    #panel-generate-persediaan table.gen-recalc-summary-dt tr.gen-recalc-row-subtotal th {
        background: #c8e6c9 !important;
        font-weight: 700;
        border-top: 2px solid #66bb6a;
    }
    #panel-generate-persediaan table.gen-recalc-summary-dt tfoot th {
        position: sticky;
        bottom: 0;
        z-index: 2;
        background: #e8f5e9;
        font-weight: 700;
        border-top: 2px solid #81c784;
        white-space: nowrap;
    }
    #panel-generate-persediaan table.gen-recalc-dt tfoot th {
        position: sticky;
        bottom: 0;
        z-index: 2;
        background: #e8f5e9;
        font-weight: 700;
        border-top: 2px solid #81c784;
        white-space: nowrap;
    }
    #panel-generate-persediaan table.gen-recalc-dt tfoot th.gen-recalc-foot-total-label,
    #panel-generate-persediaan table.gen-recalc-summary-dt tfoot th.gen-recalc-foot-total-label {
        text-align: left;
    }
    #panel-generate-persediaan table.gen-recalc-dt tfoot th.gen-recalc-foot-num,
    #panel-generate-persediaan table.gen-recalc-summary-dt tfoot th.gen-recalc-foot-num {
        text-align: right;
    }
    #panel-generate-persediaan .gen-recalc-table-scroll .dataTables_scrollFoot {
        overflow: hidden !important;
    }
    #panel-generate-persediaan .gen-recalc-dt-block.mb-4 {
        margin-bottom: 1.5rem;
    }
    #panel-generate-persediaan .gen-recalc-table-scroll .dataTables_wrapper {
        width: 100%;
        max-width: 100%;
        margin-bottom: 0;
        font-size: 15px;
        overflow: hidden;
    }
    #panel-generate-persediaan .gen-recalc-table-scroll .dataTables_scroll {
        width: 100% !important;
        overflow: hidden;
    }
    #panel-generate-persediaan .gen-recalc-table-scroll .dataTables_scrollHead {
        overflow: hidden !important;
    }
    #panel-generate-persediaan .gen-recalc-table-scroll .dataTables_scrollBody {
        overflow: auto !important;
        border-bottom: 1px solid #dee2e6;
    }
    #panel-generate-persediaan table.gen-recalc-dt {
        width: 100% !important;
        min-width: 100%;
        margin-bottom: 0;
        font-size: 15px;
        line-height: 1.5;
    }
    #panel-generate-persediaan table.gen-recalc-dt thead th {
        position: sticky;
        top: 0;
        z-index: 3;
        background: #e9ecef;
        color: #212529;
        font-size: 15px;
        font-weight: 600;
        white-space: nowrap;
        padding: 10px 14px;
        vertical-align: middle;
        border-bottom: 2px solid #dee2e6;
        box-shadow: inset 0 -1px 0 #dee2e6;
    }
    #panel-generate-persediaan table.gen-recalc-dt tbody td {
        font-size: 15px;
        padding: 9px 14px;
        vertical-align: top;
        white-space: nowrap;
    }
    #panel-generate-persediaan table.gen-recalc-dt tbody td.gen-recalc-cell-wrap,
    #panel-generate-persediaan table.gen-recalc-dt thead th.gen-recalc-cell-wrap {
        white-space: normal;
        word-break: break-word;
        min-width: 140px;
        max-width: 300px;
    }
    #panel-generate-persediaan table.gen-recalc-dt thead th:nth-last-child(-n+2),
    #panel-generate-persediaan table.gen-recalc-dt tbody td:nth-last-child(-n+2) {
        white-space: normal;
        word-break: break-word;
        min-width: 140px;
        max-width: 320px;
    }
    #panel-generate-persediaan .gen-recalc-table-scroll .dtfc-fixed-left,
    #panel-generate-persediaan .gen-recalc-table-scroll .DTFC_LeftWrapper table.dataTable tbody td,
    #panel-generate-persediaan .gen-recalc-table-scroll .DTFC_LeftWrapper table.dataTable thead th,
    #panel-generate-persediaan .gen-recalc-table-scroll .DTFC_LeftWrapper table.dataTable tfoot th {
        background: #fff;
    }
    #panel-generate-persediaan .gen-recalc-table-scroll .dtfc-fixed-left-head,
    #panel-generate-persediaan .gen-recalc-table-scroll .DTFC_LeftHeadWrapper table.dataTable thead th,
    #panel-generate-persediaan .gen-recalc-table-scroll .DTFC_LeftFootWrapper table.dataTable tfoot th {
        background: #e9ecef !important;
        z-index: 5;
    }
    #panel-generate-persediaan .gen-recalc-table-scroll .dtfc-fixed-left,
    #panel-generate-persediaan .gen-recalc-table-scroll .DTFC_LeftWrapper {
        z-index: 4;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }
    #panel-generate-persediaan .gen-recalc-table-scroll .dataTables_scrollBody {
        border-bottom: 1px solid #dee2e6;
    }
    #panel-generate-persediaan .gen-recalc-table-scroll .dataTables_length,
    #panel-generate-persediaan .gen-recalc-table-scroll .dataTables_filter,
    #panel-generate-persediaan .gen-recalc-table-scroll .dataTables_info,
    #panel-generate-persediaan .gen-recalc-table-scroll .dataTables_paginate {
        position: sticky;
        left: 0;
        width: 100%;
        min-width: max-content;
        background: #fff;
        padding: 6px 8px;
        font-size: 13px;
    }
    #panel-generate-persediaan .gen-recalc-table-scroll .dataTables_paginate {
        padding-bottom: 8px;
    }
    #panel-generate-persediaan .gen-recalc-table-scroll::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }
    #panel-generate-persediaan .gen-recalc-table-scroll::-webkit-scrollbar-thumb {
        background: #adb5bd;
        border-radius: 5px;
    }
    #panel-generate-persediaan .gen-recalc-table-scroll::-webkit-scrollbar-track {
        background: #f1f3f5;
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
    #table-persediaan tfoot th,
    table.persediaan-tab-dt tfoot th {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        font-weight: 600;
        vertical-align: middle;
        padding: 6px 8px;
    }
    #table-persediaan tfoot th.persediaan-foot-total-label,
    table.persediaan-tab-dt tfoot th.persediaan-foot-total-label {
        text-align: right;
        white-space: nowrap;
    }
    #table-persediaan tfoot th.persediaan-foot-num,
    table.persediaan-tab-dt tfoot th.persediaan-foot-num {
        text-align: right;
        white-space: nowrap;
    }
    .persediaan-tab-dt-wrap,
    .persediaan-dt-area-scroll {
        width: 100%;
        overflow: visible;
        max-height: none;
    }
    /* Tab Compare — box tabel: border kuning, scroll vertikal di body DT, horizontal hanya di DT */
    #panel-compare-manual .compare-dt-wrap,
    #modal-compare-csv-preview .compare-csv-preview-dt-wrap {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
        overflow-y: visible;
        box-sizing: border-box;
        border: 2px solid #ffeb3b;
        border-radius: 6px;
        padding: 6px 8px 8px;
        margin-bottom: 1rem;
        background: #fffef5;
        box-shadow: 0 0 0 1px rgba(255, 235, 59, 0.45), inset 0 0 12px rgba(255, 249, 196, 0.35);
    }
    #panel-compare-manual .compare-dt-wrap .dataTables_wrapper,
    #modal-compare-csv-preview .compare-csv-preview-dt-wrap .dataTables_wrapper {
        width: 100% !important;
        max-width: 100%;
        overflow: hidden;
        margin: 0;
    }
    #panel-compare-manual .compare-dt-wrap .dataTables_scroll,
    #modal-compare-csv-preview .compare-csv-preview-dt-wrap .dataTables_scroll {
        width: 100% !important;
        overflow: hidden;
    }
    #panel-compare-manual .compare-dt-wrap .dataTables_scrollHead,
    #modal-compare-csv-preview .compare-csv-preview-dt-wrap .dataTables_scrollHead {
        overflow: hidden !important;
    }
    #panel-compare-manual .compare-dt-wrap .dataTables_scrollHeadInner,
    #panel-compare-manual .compare-dt-wrap .dataTables_scrollHeadInner table,
    #modal-compare-csv-preview .compare-csv-preview-dt-wrap .dataTables_scrollHeadInner,
    #modal-compare-csv-preview .compare-csv-preview-dt-wrap .dataTables_scrollHeadInner table {
        width: 100% !important;
    }
    #panel-compare-manual .compare-dt-wrap .dataTables_scrollBody,
    #modal-compare-csv-preview .compare-csv-preview-dt-wrap .dataTables_scrollBody {
        overflow-x: auto !important;
        overflow-y: auto !important;
        width: 100% !important;
    }
    #panel-compare-manual .compare-dt-wrap .dataTables_scrollBody table,
    #modal-compare-csv-preview .compare-csv-preview-dt-wrap .dataTables_scrollBody table {
        width: 100% !important;
    }
    #panel-compare-manual .compare-dt-wrap tfoot th,
    #modal-compare-csv-preview .compare-csv-preview-dt-wrap tfoot th {
        background: #fffde7;
        border-top: 2px solid #ffeb3b;
        font-weight: 700;
        white-space: nowrap;
    }
    #panel-compare-manual .compare-dt-wrap tfoot th.compare-foot-total-label,
    #modal-compare-csv-preview .compare-csv-preview-dt-wrap tfoot th.compare-foot-total-label {
        text-align: right;
    }
    #panel-compare-manual .compare-dt-wrap tfoot th.compare-foot-num,
    #modal-compare-csv-preview .compare-csv-preview-dt-wrap tfoot th.compare-foot-num {
        text-align: right;
        font-variant-numeric: tabular-nums;
    }
    /* Sub-tab Barang / Jasa — tab Data Persediaan */
    #persediaan-data-subtabs {
        border: 2px solid #ffeb3b;
        border-radius: 6px;
        padding: 8px 10px 6px;
        background: #fffef8;
        gap: 4px;
    }
    #persediaan-data-subtabs .nav-item {
        margin-bottom: 4px;
    }
    #persediaan-data-subtabs .nav-link {
        font-size: 1rem;
        line-height: 1.5;
        font-style: italic;
        font-weight: 400;
        color: #495057;
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        border-radius: 4px;
        margin-right: 6px;
        padding: 0.5rem 1rem;
        transition: background-color 0.15s ease, border-color 0.15s ease;
    }
    #persediaan-data-subtabs .nav-link:hover {
        color: #343a40;
        background-color: #e9ecef;
    }
    #persediaan-data-subtabs .nav-link.active,
    #persediaan-data-subtabs .nav-link.active:hover,
    #persediaan-data-subtabs .nav-link.active:focus {
        font-size: 1rem;
        line-height: 1.5;
        font-style: normal;
        font-weight: 700;
        color: #000 !important;
        background-color: #0d47a1 !important;
        border: 2px solid #f48fb1 !important;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.35);
        padding: 0.5rem 1rem;
        text-shadow: 0 0 3px #fff, 0 0 6px #fff, 0 1px 2px rgba(255, 255, 255, 0.9);
    }
    #persediaan-data-subtabs .nav-link.active .badge {
        font-size: 0.875rem;
        font-weight: 700;
        color: #000;
        background-color: #ffeb3b;
        border: 1px solid #f48fb1;
        text-shadow: none;
    }
    #persediaan-data-subtabs .nav-link:not(.active) .badge {
        font-size: 0.875rem;
        font-weight: 400;
        font-style: normal;
    }
    #persediaan-data-subtabs-content {
        border: 1px solid #ffeb3b;
        border-top: none;
        border-radius: 0 0 6px 6px;
        padding: 10px 8px 4px;
        margin-top: -2px;
        background: #fff;
    }
    .persediaan-dt-area-scroll {
        overflow: auto;
        min-height: 420px;
        max-height: calc(100vh - 210px);
    }
    .persediaan-tab-dt-wrap .dataTables_wrapper {
        width: 100%;
        font-size: 15px;
    }
    #panel-compare-manual .compare-dt-wrap .dataTables_wrapper,
    #modal-compare-csv-preview .compare-csv-preview-dt-wrap .dataTables_wrapper {
        font-size: 13px;
    }
    .persediaan-tab-dt-wrap .dataTables_scrollHead,
    .persediaan-tab-dt-wrap .dataTables_scrollBody {
        overflow-x: auto !important;
        overflow-y: auto !important;
    }
    .persediaan-tab-dt-wrap table.dataTable thead th,
    .persediaan-tab-dt-wrap table.dataTable tbody td,
    #panel-compare-manual .compare-dt-wrap table.dataTable thead th,
    #panel-compare-manual .compare-dt-wrap table.dataTable tbody td,
    #modal-compare-csv-preview .compare-csv-preview-dt-wrap table.dataTable thead th,
    #modal-compare-csv-preview .compare-csv-preview-dt-wrap table.dataTable tbody td {
        white-space: nowrap;
        font-size: 13px;
        padding: 7px 9px;
    }
    .persediaan-tab-dt-wrap table.dataTable thead th,
    .persediaan-tab-dt-wrap table.dataTable tbody td {
        font-size: 15px;
    }
    .persediaan-tab-dt-wrap table.dataTable th.persediaan-col-money,
    .persediaan-tab-dt-wrap table.dataTable td.persediaan-col-money,
    .persediaan-tab-dt-wrap table.dataTable tfoot th.persediaan-col-money,
    .persediaan-tab-dt-wrap table.dataTable tfoot th.persediaan-foot-num {
        text-align: right !important;
        font-variant-numeric: tabular-nums;
    }
    .persediaan-tab-dt-wrap .dtfc-fixed-left,
    .persediaan-tab-dt-wrap .DTFC_LeftWrapper table.dataTable tbody td,
    .persediaan-tab-dt-wrap .DTFC_LeftWrapper table.dataTable thead th,
    .persediaan-tab-dt-wrap .DTFC_LeftWrapper table.dataTable tfoot th {
        background: #fff;
    }
    .persediaan-tab-dt-wrap .dtfc-fixed-left-head,
    .persediaan-tab-dt-wrap .DTFC_LeftHeadWrapper table.dataTable thead th,
    .persediaan-tab-dt-wrap .DTFC_LeftFootWrapper table.dataTable tfoot th {
        background: #f8f9fa !important;
        z-index: 4;
    }
    .persediaan-tab-dt-wrap .dtfc-fixed-left,
    .persediaan-tab-dt-wrap .DTFC_LeftWrapper {
        z-index: 3;
        box-shadow: 2px 0 4px rgba(0, 0, 0, 0.08);
    }
    .persediaan-jasa-col-tanggal {
        min-width: 108px;
        vertical-align: middle !important;
    }
    .persediaan-jasa-row-aksi {
        white-space: nowrap;
    }
    .persediaan-jasa-row-aksi .btn {
        padding: 0.12rem 0.32rem;
        font-size: 0.72rem;
        line-height: 1.25;
    }
    .persediaan-jasa-row-aksi .btn + .btn {
        margin-left: 2px;
    }
    .compare-toolbar-row .compare-toolbar-control {
        width: 110px;
        min-width: 110px;
    }
    #compare_tahun_persediaan.compare-toolbar-control {
        width: 88px;
        min-width: 88px;
    }
    #compare_tabel_pilihan.compare-toolbar-tabel {
        width: 360px;
        min-width: 270px;
        max-width: 480px;
    }
    .custom-file-sm .custom-file-label,
    .custom-file-sm .custom-file-label::after {
        height: calc(1.8125rem + 2px);
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }
    .compare-csv-file-wrap {
        max-width: 520px;
        min-width: 280px;
        flex: 0 1 520px;
    }
    .compare-csv-upload-info {
        flex: 1 1 240px;
        min-width: 220px;
        padding: 6px 10px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }
    .compare-db-tabel-select {
        max-width: 640px;
    }
    .compare-db-tabel-info {
        max-width: 640px;
        padding: 10px 12px;
        background: #e8f4fd;
        border: 1px solid #b8daff;
        border-radius: 4px;
    }
    #btn-compare-db-insert-persediaan.btn-compare-db-insert-persediaan {
        background-color: #ff5252;
        border-color: #e53935;
        color: #fff;
    }
    #btn-compare-db-insert-persediaan.btn-compare-db-insert-persediaan:hover,
    #btn-compare-db-insert-persediaan.btn-compare-db-insert-persediaan:focus {
        background-color: #e53935;
        border-color: #c62828;
        color: #fff;
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
    var urlGetPersediaanJasa = <?php echo json_encode(isset($url_get_persediaan_jasa) ? $url_get_persediaan_jasa : site_url('Persediaan/ajax_get_persediaan_jasa')); ?>;
    var urlUpdatePersediaanJasa = <?php echo json_encode(isset($url_update_persediaan_jasa) ? $url_update_persediaan_jasa : site_url('Persediaan/ajax_update_persediaan_jasa')); ?>;
    var urlHapusPersediaanJasa = <?php echo json_encode(isset($url_hapus_persediaan_jasa) ? $url_hapus_persediaan_jasa : site_url('Persediaan/ajax_hapus_persediaan_jasa')); ?>;
    var urlCekGeneratePersediaan = <?php echo json_encode(isset($url_cek_generate_persediaan) ? $url_cek_generate_persediaan : site_url('Persediaan/ajax_cek_generate_persediaan_bulan')); ?>;
    var urlAnalisaGeneratePersediaan = <?php echo json_encode(isset($url_analisa_generate_persediaan) ? $url_analisa_generate_persediaan : site_url('Persediaan/ajax_analisa_generate_persediaan_bulan')); ?>;
    var urlAnalisaRecalculatePersediaan = <?php echo json_encode(isset($url_analisa_recalculate_persediaan) ? $url_analisa_recalculate_persediaan : site_url('Persediaan/ajax_analisa_recalculate_persediaan')); ?>;
    var urlRecalculatePersediaanBatch = <?php echo json_encode(isset($url_recalculate_persediaan_batch) ? $url_recalculate_persediaan_batch : site_url('Persediaan/ajax_recalculate_persediaan_batch')); ?>;
    var urlGenerateRecalculateBatch = <?php echo json_encode(isset($url_generate_recalculate_batch) ? $url_generate_recalculate_batch : site_url('Persediaan/ajax_generate_recalculate_batch')); ?>;
    var urlLoadGenRecalcHistory = <?php echo json_encode(isset($url_load_gen_recalc_history) ? $url_load_gen_recalc_history : site_url('Persediaan/ajax_load_gen_recalc_history')); ?>;
    var urlGenRecalcSummaryTables = <?php echo json_encode(isset($url_gen_recalc_summary_tables) ? $url_gen_recalc_summary_tables : site_url('Persediaan/ajax_gen_recalc_summary_tables')); ?>;
    var urlListHistoryGenerate = <?php echo json_encode(isset($url_list_history_generate) ? $url_list_history_generate : site_url('Persediaan/ajax_list_history_generate')); ?>;
    var urlLoadHistoryGenerate = <?php echo json_encode(isset($url_load_history_generate) ? $url_load_history_generate : site_url('Persediaan/ajax_load_history_generate')); ?>;
    var urlExcelGenRecalcSummary = <?php echo json_encode(isset($url_excel_gen_recalc_summary) ? $url_excel_gen_recalc_summary : site_url('Persediaan/excel_gen_recalc_summary')); ?>;
    var urlExcelGenRecalc = <?php echo json_encode(isset($url_excel_gen_recalc) ? $url_excel_gen_recalc : site_url('Persediaan/excel_gen_recalc')); ?>;
    var urlExcelRekonsiliasiTransaksi = <?php echo json_encode(isset($url_excel_rekonsiliasi_transaksi) ? $url_excel_rekonsiliasi_transaksi : site_url('Persediaan/excel_rekonsiliasi_transaksi')); ?>;
    var urlRecalculateExcel = <?php echo json_encode(isset($url_recalculate_excel) ? $url_recalculate_excel : site_url('Persediaan/excel_recalculate')); ?>;
    var urlExcelPersediaan = <?php echo json_encode(isset($url_excel_persediaan) ? $url_excel_persediaan : site_url('Persediaan/excel')); ?>;
    var urlCompareTabelList = <?php echo json_encode(isset($url_compare_tabel_list) ? $url_compare_tabel_list : site_url('Persediaan/ajax_compare_tabel_list')); ?>;
    var urlCompareTabelRun = <?php echo json_encode(isset($url_compare_tabel_run) ? $url_compare_tabel_run : site_url('Persediaan/ajax_compare_tabel_run')); ?>;
    var urlCompareTabelExcel = <?php echo json_encode(isset($url_compare_tabel_excel) ? $url_compare_tabel_excel : site_url('Persediaan/excel_compare_tabel')); ?>;
    var urlCompareTabelExcelAll = <?php echo json_encode(isset($url_compare_tabel_excel_all) ? $url_compare_tabel_excel_all : site_url('Persediaan/excel_compare_tabel_all')); ?>;
    var urlCompareImportCsv = <?php echo json_encode(isset($url_compare_import_csv) ? $url_compare_import_csv : site_url('Persediaan/ajax_compare_import_csv')); ?>;
    var urlCompareTabelPreview = <?php echo json_encode(isset($url_compare_tabel_preview) ? $url_compare_tabel_preview : site_url('Persediaan/ajax_compare_tabel_preview')); ?>;
    var urlCompareInsertToPersediaan = <?php echo json_encode(isset($url_compare_insert_to_persediaan) ? $url_compare_insert_to_persediaan : site_url('Persediaan/ajax_compare_insert_to_persediaan')); ?>;
    var urlCompareCheckInsertEligible = <?php echo json_encode(isset($url_compare_check_insert_eligible) ? $url_compare_check_insert_eligible : site_url('Persediaan/ajax_compare_check_insert_persediaan_eligible')); ?>;
    var userCanGeneratePersediaan = <?php echo !empty($can_generate_persediaan) ? 'true' : 'false'; ?>;
    var userCanComparePersediaan = <?php echo !empty($can_compare_persediaan) ? 'true' : 'false'; ?>;
    var genCekXhr = null;

    var PERS_JASA_DT_SEARCH_KEY = 'persediaan_jasa_dt_search';

    function formatAngkaInputField(input) {
        if (!input) {
            return;
        }
        formatAngkaHppInput(input);
    }

    function bindPersediaanJasaRowActions() {
        var $panel = $('#panel-persediaan-jasa');
        $panel.off('click.persJasaUbah', '.btn-ubah-persediaan-jasa-row')
            .on('click.persJasaUbah', '.btn-ubah-persediaan-jasa-row', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var id = parseInt($(this).attr('data-id') || '0', 10) || 0;
                if (id <= 0) {
                    return;
                }
                openModalUbahPersediaanJasa(id);
            });
        $panel.off('click.persJasaHapus', '.btn-hapus-persediaan-jasa-row')
            .on('click.persJasaHapus', '.btn-hapus-persediaan-jasa-row', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var id = parseInt($(this).attr('data-id') || '0', 10) || 0;
                if (id <= 0) {
                    return;
                }
                hapusPersediaanJasaById(id);
            });
    }

    function applyPersediaanJasaSavedSearch() {
        var dt = dtPersediaanStore['#table-persediaan-jasa'];
        if (!dt) {
            return;
        }

        var term = '';
        try {
            term = (localStorage.getItem(PERS_JASA_DT_SEARCH_KEY) || '').trim();
        } catch (eSearch) {
            term = '';
        }

        if (term !== '') {
            dt.search(term).draw();
        }

        var $wrap = $('#table-persediaan-jasa').closest('.dataTables_wrapper');
        $wrap.find('.dataTables_filter input').off('input.persJasaSearchClear').on('input.persJasaSearchClear', function() {
            if ($.trim($(this).val()) === '') {
                try {
                    localStorage.removeItem(PERS_JASA_DT_SEARCH_KEY);
                } catch (eClr) {}
            }
        });
    }

    function reloadPersediaanHalamanJasa(namaFilter) {
        savePersediaanMainTabKey('data');
        savePersediaanDataSubTabKey('jasa');
        if (namaFilter) {
            try {
                localStorage.setItem(PERS_JASA_DT_SEARCH_KEY, namaFilter);
            } catch (eSave) {}
        }
        saveCurrentPersediaanTabs();
        $('#form-persediaan-bulan').submit();
    }

    function fillFormUbahPersediaanJasa(data) {
        data = data || {};
        $('#ubah_jasa_id').val(data.id || '');
        $('#ubah_jasa_namabarang').val(data.namabarang || '');
        $('#ubah_jasa_satuan').val(data.satuan || '');
        $('#ubah_jasa_hpp').val(data.hpp || '');
        $('#ubah_jasa_sa').val(data.sa || '');
        $('#ubah_jasa_spop').val(data.spop || '');
        $('#ubah_jasa_beli').val(data.beli || '');
        $('#ubah_jasa_tuj').val(data.tuj || '');
        $('#ubah_jasa_total_10').val(data.total_10 || '');
        $('#ubah_jasa_nilai_persediaan').val(data.nilai_persediaan || '');

        ['ubah_jasa_hpp', 'ubah_jasa_nilai_persediaan'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) {
                formatAngkaInputField(el);
            }
        });

        var nama = $.trim(data.namabarang || '');
        $('#modalUbahPersediaanJasaLabel').text(nama !== '' ? ('Ubah Data Jasa — ' + nama) : 'Ubah Data Jasa');
    }

    function openModalUbahPersediaanJasa(id) {
        id = parseInt(id, 10) || 0;
        if (id <= 0) {
            return;
        }

        var formData = new FormData();
        formData.append('id', String(id));

        fetch(urlGetPersediaanJasa, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (!res || !res.ok || !res.data) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: (res && res.message) ? res.message : 'Data jasa tidak ditemukan.'
                });
                return;
            }
            fillFormUbahPersediaanJasa(res.data);
            $('#modal-ubah-persediaan-jasa').modal('show');
        })
        .catch(function() {
            Swal.fire({ icon: 'error', title: 'Kesalahan', text: 'Gagal memuat data jasa.' });
        });
    }

    function hapusPersediaanJasaById(id) {
        id = parseInt(id, 10) || 0;
        if (id <= 0) {
            return;
        }

        Swal.fire({
            icon: 'warning',
            title: 'Konfirmasi',
            text: 'Anda yakin akan menghapus data ini?',
            showCancelButton: true,
            confirmButtonText: 'OK',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (!result.isConfirmed) {
                return;
            }

            var formData = new FormData();
            formData.append('id', String(id));

            fetch(urlHapusPersediaanJasa, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res && (res.ok || res.success)) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message || 'Data jasa berhasil dihapus.',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        try {
                            localStorage.removeItem(PERS_JASA_DT_SEARCH_KEY);
                        } catch (eClr) {}
                        reloadPersediaanHalamanJasa('');
                    });
                    return;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: (res && res.message) ? res.message : 'Gagal menghapus data jasa.'
                });
            })
            .catch(function() {
                Swal.fire({ icon: 'error', title: 'Kesalahan', text: 'Terjadi kesalahan saat menghapus data.' });
            });
        });
    }

    bindPersediaanJasaRowActions();

    $('#ubah_jasa_hpp, #ubah_jasa_nilai_persediaan').on('input keyup paste', function() {
        var el = this;
        setTimeout(function() { formatAngkaInputField(el); }, 0);
    });

    $('#form-ubah-persediaan-jasa').on('submit', function(e) {
        e.preventDefault();

        var namabarang = $.trim($('#ubah_jasa_namabarang').val());
        var satuan = $.trim($('#ubah_jasa_satuan').val());
        if (!namabarang || !satuan) {
            Swal.fire({ icon: 'warning', title: 'Data belum lengkap', text: 'Lengkapi Nama Jasa dan Satuan.' });
            return false;
        }

        Swal.fire({
            icon: 'question',
            title: 'Konfirmasi',
            text: 'Anda yakin akan mengubah data ini?',
            showCancelButton: true,
            confirmButtonText: 'OK',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (!result.isConfirmed) {
                return;
            }

            var $btn = $('#btn-submit-ubah-persediaan-jasa');
            var btnText = $btn.text();
            $btn.prop('disabled', true).text('Menyimpan...');

            var formData = new FormData(document.getElementById('form-ubah-persediaan-jasa'));

            fetch(urlUpdatePersediaanJasa, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res && (res.ok || res.success)) {
                    $('#modal-ubah-persediaan-jasa').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message || 'Data jasa berhasil diubah.',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        reloadPersediaanHalamanJasa(res.namabarang || namabarang);
                    });
                    return;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: (res && res.message) ? res.message : 'Gagal mengubah data jasa.'
                });
            })
            .catch(function() {
                Swal.fire({ icon: 'error', title: 'Kesalahan', text: 'Terjadi kesalahan saat menyimpan data.' });
            })
            .finally(function() {
                $btn.prop('disabled', false).text(btnText);
            });
        });

        return false;
    });

    var PERS_TAB_MAIN_KEY = 'persediaan_active_main_tab';
    var PERS_TAB_SUB_KEY = 'persediaan_active_data_subtab';

    function persediaanMainTabKeyFromHref(href) {
        var map = {
            '#panel-data-persediaan': 'data',
            '#panel-rekap': 'rekap',
            '#panel-generate-persediaan': 'generate',
            '#panel-compare-manual': 'compare'
        };
        return map[href] || 'data';
    }

    function persediaanDataSubTabKeyFromHref(href) {
        return href === '#panel-persediaan-jasa' ? 'jasa' : 'barang';
    }

    function savePersediaanMainTabKey(key) {
        try {
            localStorage.setItem(PERS_TAB_MAIN_KEY, key);
        } catch (e) {}
    }

    function savePersediaanDataSubTabKey(key) {
        try {
            localStorage.setItem(PERS_TAB_SUB_KEY, key);
        } catch (e) {}
    }

    function saveCurrentPersediaanTabs() {
        var $main = $('#persediaan-tabs .nav-link.active');
        if ($main.length) {
            savePersediaanMainTabKey(persediaanMainTabKeyFromHref($main.attr('href') || ''));
        }
        var $sub = $('#persediaan-data-subtabs .nav-link.active');
        if ($sub.length) {
            savePersediaanDataSubTabKey(persediaanDataSubTabKeyFromHref($sub.attr('href') || ''));
        }
    }

    function getSavedPersediaanMainTabKey() {
        try {
            var legacy = sessionStorage.getItem('persediaan_show_tab');
            if (legacy) {
                sessionStorage.removeItem('persediaan_show_tab');
                savePersediaanMainTabKey(legacy);
                return legacy;
            }
            return localStorage.getItem(PERS_TAB_MAIN_KEY) || 'data';
        } catch (e) {
            return 'data';
        }
    }

    function getSavedPersediaanDataSubTabKey() {
        try {
            return localStorage.getItem(PERS_TAB_SUB_KEY) || 'barang';
        } catch (e) {
            return 'barang';
        }
    }

    function activatePersediaanMainTabByKey(key) {
        var map = {
            data: '#tab-data-persediaan',
            rekap: '#tab-rekap',
            generate: '#tab-generate-persediaan',
            compare: '#tab-compare-manual'
        };
        var sel = map[key] || map.data;
        if ($(sel).length) {
            $(sel).tab('show');
        }
    }

    function activatePersediaanDataSubTabByKey(key) {
        var map = {
            barang: '#tab-persediaan-barang',
            jasa: '#tab-persediaan-jasa'
        };
        var sel = map[key] || map.barang;
        if ($(sel).length) {
            $(sel).tab('show');
        }
    }

    function restorePersediaanTabsFromStorage() {
        var mainKey = getSavedPersediaanMainTabKey();
        var subKey = getSavedPersediaanDataSubTabKey();
        activatePersediaanMainTabByKey(mainKey);
        if (mainKey === 'data') {
            activatePersediaanDataSubTabByKey(subKey);
        }
    }

    function persistPersediaanDataSubTabFromClick($link) {
        if (!$link || !$link.length) {
            return;
        }
        savePersediaanDataSubTabKey(persediaanDataSubTabKeyFromHref($link.attr('href') || ''));
        savePersediaanMainTabKey('data');
    }

    var PERS_BULAN_DATA_KEY = 'persediaan_bulan_data';
    var PERS_BULAN_REKAP_KEY = 'persediaan_bulan_rekap';
    var PERS_GEN_BULAN_KEY = 'persediaan_gen_bulan';
    var PERS_GEN_TAHUN_KEY = 'persediaan_gen_tahun';
    var PERS_COMPARE_BULAN_KEY = 'persediaan_compare_bulan';
    var PERS_COMPARE_TAHUN_KEY = 'persediaan_compare_tahun';
    var PERS_COMPARE_TABEL_KEY = 'persediaan_compare_tabel';

    function persediaanParseBulanKey(bulanKey) {
        if (!bulanKey || !/^\d{4}-\d{2}$/.test(bulanKey)) {
            return null;
        }
        var parts = bulanKey.split('-');
        return {
            tahun: parseInt(parts[0], 10),
            bulan: parseInt(parts[1], 10)
        };
    }

    function savePersediaanBulanData(val) {
        val = (val || '').trim();
        if (!/^\d{4}-\d{2}$/.test(val)) {
            return;
        }
        try {
            localStorage.setItem(PERS_BULAN_DATA_KEY, val);
        } catch (e) {}
    }

    function getSavedPersediaanBulanData() {
        try {
            var val = (localStorage.getItem(PERS_BULAN_DATA_KEY) || '').trim();
            return /^\d{4}-\d{2}$/.test(val) ? val : '';
        } catch (e) {
            return '';
        }
    }

    function savePersediaanBulanRekap(val) {
        val = (val || '').trim();
        if (!/^\d{4}-\d{2}$/.test(val)) {
            return;
        }
        try {
            localStorage.setItem(PERS_BULAN_REKAP_KEY, val);
        } catch (e) {}
    }

    function getSavedPersediaanBulanRekap() {
        try {
            var val = (localStorage.getItem(PERS_BULAN_REKAP_KEY) || '').trim();
            return /^\d{4}-\d{2}$/.test(val) ? val : '';
        } catch (e) {
            return '';
        }
    }

    function savePersediaanGenBulanTahun(bulan, tahun) {
        if (typeof bulan === 'undefined' || bulan === null) {
            bulan = $('#gen_bulan_persediaan').val();
        }
        if (typeof tahun === 'undefined' || tahun === null) {
            tahun = $('#gen_tahun_persediaan').val();
        }
        try {
            if (bulan) {
                localStorage.setItem(PERS_GEN_BULAN_KEY, String(bulan));
            }
            if (tahun) {
                localStorage.setItem(PERS_GEN_TAHUN_KEY, String(tahun));
            }
        } catch (e) {}
    }

    function savePersediaanGenFromBulanKey(bulanKey) {
        var parsed = persediaanParseBulanKey(bulanKey);
        if (!parsed) {
            return;
        }
        if ($('#gen_bulan_persediaan option[value="' + parsed.bulan + '"]').length) {
            $('#gen_bulan_persediaan').val(String(parsed.bulan));
        }
        if ($('#gen_tahun_persediaan option[value="' + parsed.tahun + '"]').length) {
            $('#gen_tahun_persediaan').val(String(parsed.tahun));
        }
        savePersediaanGenBulanTahun(parsed.bulan, parsed.tahun);
    }

    function savePersediaanCompareBulanTahun() {
        try {
            var bulan = $('#compare_bulan_persediaan').val();
            var tahun = $('#compare_tahun_persediaan').val();
            if (bulan) {
                localStorage.setItem(PERS_COMPARE_BULAN_KEY, String(bulan));
            }
            if (tahun) {
                localStorage.setItem(PERS_COMPARE_TAHUN_KEY, String(tahun));
            }
        } catch (e) {}
    }

    function savePersediaanCompareTabel(val) {
        val = (val || '').trim();
        try {
            if (val) {
                localStorage.setItem(PERS_COMPARE_TABEL_KEY, val);
            } else {
                localStorage.removeItem(PERS_COMPARE_TABEL_KEY);
            }
        } catch (e) {}
    }

    function getSavedPersediaanCompareTabel() {
        try {
            return (localStorage.getItem(PERS_COMPARE_TABEL_KEY) || '').trim();
        } catch (e) {
            return '';
        }
    }

    function applySavedFilterControlsFromStorage() {
        var savedDataBulan = getSavedPersediaanBulanData();
        if (savedDataBulan) {
            $('#bulan_persediaan').val(savedDataBulan);
        }

        var savedRekapBulan = getSavedPersediaanBulanRekap();
        if (savedRekapBulan) {
            $('#bulan_rekap').val(savedRekapBulan);
        }

        try {
            var genBulan = localStorage.getItem(PERS_GEN_BULAN_KEY);
            var genTahun = localStorage.getItem(PERS_GEN_TAHUN_KEY);
            if (genBulan && $('#gen_bulan_persediaan option[value="' + genBulan + '"]').length) {
                $('#gen_bulan_persediaan').val(genBulan);
            }
            if (genTahun && $('#gen_tahun_persediaan option[value="' + genTahun + '"]').length) {
                $('#gen_tahun_persediaan').val(genTahun);
            }
        } catch (eGen) {}

        try {
            var cmpBulan = localStorage.getItem(PERS_COMPARE_BULAN_KEY);
            var cmpTahun = localStorage.getItem(PERS_COMPARE_TAHUN_KEY);
            if (cmpBulan && $('#compare_bulan_persediaan option[value="' + cmpBulan + '"]').length) {
                $('#compare_bulan_persediaan').val(cmpBulan);
            }
            if (cmpTahun && $('#compare_tahun_persediaan option[value="' + cmpTahun + '"]').length) {
                $('#compare_tahun_persediaan').val(cmpTahun);
            }
        } catch (eCmp) {}
    }

    function tryRestoreTab1BulanFromStorage() {
        var savedBulan = getSavedPersediaanBulanData();
        if (!savedBulan) {
            return false;
        }
        var currentBulan = ($('#bulan_persediaan').val() || '').trim();
        if (savedBulan === currentBulan) {
            return false;
        }
        $('#bulan_persediaan').val(savedBulan);
        savePersediaanBulanData(savedBulan);
        savePersediaanMainTabKey(getSavedPersediaanMainTabKey() || 'data');
        savePersediaanDataSubTabKey(getSavedPersediaanDataSubTabKey());
        $('#form-persediaan-bulan').submit();
        return true;
    }

    restorePersediaanTabsFromStorage();

    if (tryRestoreTab1BulanFromStorage()) {
        return;
    }

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

    function getBulanRekonsiliasiFromGenTab() {
        var bulan = parseInt($('#gen_bulan_persediaan').val(), 10);
        var tahun = parseInt($('#gen_tahun_persediaan').val(), 10);
        if (bulan >= 1 && bulan <= 12 && tahun) {
            return tahun + '-' + ('0' + bulan).slice(-2);
        }
        return '';
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
        savePersediaanGenBulanTahun();
        var bulanKey = getBulanTargetGenerate();
        cekGeneratePersediaanBulan();
        loadGenRecalcHistoryFromServer(bulanKey);
        loadGenRecalcSummaryTablesFromServer(bulanKey);
        loadHistoryGenerateList(bulanKey);
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

        html += '<p class="text-info small mt-2 mb-0"><strong>Catatan:</strong> Fase 1: salin 1:1 dari bulan sumber — hanya record dengan kolom <code>total_10</code> &gt; 0. Record total_10 &le; 0 atau minus tidak di-copy.</p>';
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

    function createEmptyGenRecalcData() {
        return {
            persediaan_all: [],
            generate_update: [],
            generate_insert: [],
            generate_verifikasi: [],
            generate_masalah: [],
            pembelian: [],
            pembelian_update: [],
            pembelian_baru: [],
            penjualan: [],
            penjualan_update: [],
            produksi: [],
            produksi_update: [],
            unit_produk: [],
            unit_produk_update: [],
            pecah_satuan: [],
            pecah_satuan_update: [],
            gagal_generate_recalculate: [],
            gagal_insert_persediaan: []
        };
    }

    function rebuildGagalInsertPersediaanData() {
        ensureGenRecalcDataShape();

        var out = [];
        var insertFail = { GAGAL: 1 };
        var produksiFail = { GAGAL: 1, TIDAK_COCOK: 1 };

        function pushGagalInsert(fase, aksi, it) {
            if (!it || typeof it !== 'object') return;
            var idSumber = it.id_pembelian || it.id_produksi_bahan || it.id_unit_produk || it.id_pecah_satuan || '';
            var idTarget = it.id_persediaan || it.id_persediaan_sumber || '';
            var jumlah = it.jumlah_pembelian || it.jumlah_bahan || it.jumlah_produksi || it.jumlah_pecah || '';
            out.push({
                fase: fase || '',
                aksi: aksi || '',
                tabel: it.tabel || '',
                id_sumber: idSumber,
                id_target: idTarget,
                namabarang: it.namabarang || '',
                satuan: it.satuan || '',
                hpp: it.hpp || '',
                spop: it.spop || '',
                jumlah: jumlah,
                keterangan: it.keterangan || ''
            });
        }

        (genRecalcData.pembelian || []).forEach(function(it) {
            if (insertFail[String(it.aksi || '').toUpperCase()]) {
                pushGagalInsert('pembelian', it.aksi, it);
            }
        });
        (genRecalcData.unit_produk || []).forEach(function(it) {
            if (insertFail[String(it.aksi || '').toUpperCase()]) {
                pushGagalInsert('unit_produk', it.aksi, it);
            }
        });
        (genRecalcData.produksi || []).forEach(function(it) {
            if (produksiFail[String(it.aksi || '').toUpperCase()]) {
                pushGagalInsert('produksi', it.aksi, it);
            }
        });
        (genRecalcData.pecah_satuan || []).forEach(function(it) {
            if (insertFail[String(it.aksi || '').toUpperCase()]) {
                pushGagalInsert('pecah_satuan', it.aksi, it);
            }
        });

        genRecalcData.gagal_insert_persediaan = out;
        return out;
    }

    function toggleGenRecalcGagalPersediaanWrap() {
        var n = (genRecalcData.gagal_insert_persediaan || []).length;
        $('#gen-count-gagal-persediaan').text(n);
        if (n > 0) {
            $('#gen-recalc-gagal-persediaan-wrap').removeClass('d-none');
            $('#gen-recalc-gagal-persediaan-intro').html(
                'Ditemukan <strong class="text-danger">' + n + '</strong> record gagal memasukan ke persediaan. '
                + 'Periksa kolom <strong>Masalah / Error</strong> — misalnya uuid_persediaan tidak cocok, '
                + 'uraian/satuan kosong, sudah ada di persediaan, atau error database saat insert.'
            );
        } else {
            $('#gen-recalc-gagal-persediaan-wrap').addClass('d-none');
        }
    }

    function toggleGenRecalcPhaseProduksi() {
        var has = (genRecalcData.unit_produk || []).length > 0
            || (genRecalcData.unit_produk_update || []).length > 0
            || (genRecalcData.produksi || []).length > 0
            || (genRecalcData.produksi_update || []).length > 0;
        if (has) {
            $('#gen-recalc-phase-produksi').removeClass('d-none');
        }
    }

    function toggleGenRecalcPhasePenjualan() {
        var has = (genRecalcData.penjualan || []).length > 0
            || (genRecalcData.penjualan_update || []).length > 0;
        if (has) {
            $('#gen-recalc-phase-penjualan').removeClass('d-none');
        }
    }

    function rebuildGagalGenerateRecalculateData() {
        ensureGenRecalcDataShape();

        var out = [];
        var verifikasiGagal = { BEDA: 1, TIDAK_ADA_TARGET: 1, TARGET_EKSTRA: 1 };
        var phaseFail = { GAGAL: 1, TIDAK_COCOK: 1 };

        function pushGagal(fase, aksi, it) {
            if (!it || typeof it !== 'object') return;
            var idSumber = it.id_pembelian || it.id_penjualan || it.id_produksi_bahan || it.id_unit_produk || it.id_pecah_satuan || it.id_sumber || '';
            var idTarget = it.id_persediaan || it.id_target || it.id_persediaan_sumber || '';
            var jumlah = it.jumlah_pembelian || it.jumlah_penjualan || it.jumlah_bahan || it.jumlah_produksi || it.jumlah_pecah || '';
            out.push({
                fase: fase || '',
                aksi: aksi || '',
                tabel: it.tabel || '',
                id_sumber: idSumber,
                id_target: idTarget,
                namabarang: it.namabarang || '',
                satuan: it.satuan || '',
                hpp: it.hpp || '',
                spop: it.spop || '',
                jumlah: jumlah,
                keterangan: it.keterangan || ''
            });
        }

        (genRecalcData.generate_masalah || []).forEach(function(it) {
            pushGagal(it.fase || 'generate', it.status || 'MASALAH', it);
        });
        (genRecalcData.generate_verifikasi || []).forEach(function(it) {
            var st = String(it.status || '').toUpperCase();
            if (verifikasiGagal[st]) {
                pushGagal('generate_verifikasi', st, it);
            }
        });
        (genRecalcData.pembelian || []).forEach(function(it) {
            if (phaseFail[String(it.aksi || '').toUpperCase()]) {
                pushGagal('pembelian', it.aksi, it);
            }
        });
        (genRecalcData.penjualan || []).forEach(function(it) {
            if (phaseFail[String(it.aksi || '').toUpperCase()]) {
                pushGagal('penjualan', it.aksi, it);
            }
        });
        (genRecalcData.unit_produk || []).forEach(function(it) {
            if (phaseFail[String(it.aksi || '').toUpperCase()]) {
                pushGagal('unit_produk', it.aksi, it);
            }
        });
        (genRecalcData.produksi || []).forEach(function(it) {
            if (phaseFail[String(it.aksi || '').toUpperCase()]) {
                pushGagal('produksi', it.aksi, it);
            }
        });
        (genRecalcData.pecah_satuan || []).forEach(function(it) {
            if (phaseFail[String(it.aksi || '').toUpperCase()]) {
                pushGagal('pecah_satuan', it.aksi, it);
            }
        });

        genRecalcData.gagal_generate_recalculate = out;
        return out;
    }

    function toggleGenRecalcGagalWrap() {
        var n = (genRecalcData.gagal_generate_recalculate || []).length;
        $('#gen-count-gagal').text(n);
        if (n > 0) {
            $('#gen-recalc-gagal-wrap').removeClass('d-none');
            $('#gen-recalc-gagal-intro').html(
                'Ditemukan <strong class="text-danger">' + n + '</strong> record gagal. '
                + 'Untuk pembelian: biasanya gagal karena <em>uraian/satuan kosong</em>, '
                + '<em>sudah ada di persediaan</em> (tidak di-insert ulang), atau <em>error database</em> saat insert.'
            );
        } else {
            $('#gen-recalc-gagal-wrap').addClass('d-none');
        }
    }

    function ensureGenRecalcDataShape() {
        if (!genRecalcData || typeof genRecalcData !== 'object') {
            genRecalcData = createEmptyGenRecalcData();
            return;
        }
        var empty = createEmptyGenRecalcData();
        Object.keys(empty).forEach(function(k) {
            if (!Array.isArray(genRecalcData[k])) {
                genRecalcData[k] = [];
            }
        });
    }

    var genRecalcData = createEmptyGenRecalcData();
    var genRecalcSummaryHtml = '';
    var genRecalcStoragePrefix = 'genRecalcResult_';
    var genRecalcDt = {};
    var genRecalcFcStore = {};
    var genRecalcTableTemplates = {};
    var genRecalcTableTplInited = false;
    var GEN_RECALC_TABLE_SELECTORS = [
        '#tbl-gen-recalc-persediaan-all',
        '#tbl-gen-recalc-generate-update',
        '#tbl-gen-recalc-generate-insert',
        '#tbl-gen-recalc-verifikasi',
        '#tbl-gen-recalc-masalah',
        '#tbl-gen-recalc-pembelian',
        '#tbl-gen-recalc-pembelian-update',
        '#tbl-gen-recalc-pembelian-baru',
        '#tbl-gen-recalc-unit-produk',
        '#tbl-gen-recalc-unit-produk-update',
        '#tbl-gen-recalc-penjualan',
        '#tbl-gen-recalc-penjualan-update',
        '#tbl-gen-recalc-produksi',
        '#tbl-gen-recalc-produksi-update',
        '#tbl-gen-recalc-pecah-satuan',
        '#tbl-gen-recalc-pecah-satuan-update',
        '#tbl-gen-recalc-gagal',
        '#tbl-gen-recalc-gagal-persediaan'
    ];
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
            if (genRecalcData.generate_verifikasi && genRecalcData.generate_verifikasi.length > 0) {
                $('#gen-recalc-phase-lanjut').addClass('d-none');
                $('#gen-recalc-summary-wrap').addClass('d-none');
            }
            setTimeout(adjustGenRecalcDataTables, 200);
            return genRecalcData.persediaan_all.length > 0
                || genRecalcData.generate_verifikasi.length > 0
                || genRecalcData.pembelian.length > 0
                || genRecalcData.unit_produk.length > 0
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
        genRecalcData = createEmptyGenRecalcData();
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
            generate_verifikasi: res.data.generate_verifikasi || [],
            generate_masalah: res.data.generate_masalah || [],
            pembelian: res.data.pembelian || [],
            pembelian_update: res.data.pembelian_update || [],
            pembelian_baru: res.data.pembelian_baru || [],
            penjualan: res.data.penjualan || [],
            penjualan_update: res.data.penjualan_update || [],
            produksi: res.data.produksi || [],
            produksi_update: res.data.produksi_update || [],
            unit_produk: res.data.unit_produk || [],
            unit_produk_update: res.data.unit_produk_update || [],
            pecah_satuan: res.data.pecah_satuan || [],
            pecah_satuan_update: res.data.pecah_satuan_update || [],
            gagal_generate_recalculate: res.data.gagal_generate_recalculate || [],
            gagal_insert_persediaan: res.data.gagal_insert_persediaan || []
        };
        rebuildGagalGenerateRecalculateData();
        rebuildGagalInsertPersediaanData();
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

    var genHistoryGenerateSelectedId = null;
    var genHistoryGenerateListXhr = null;
    var genHistoryGenerateLoadXhr = null;

    function formatGenHistoryStatusBadge(status) {
        var s = String(status || '').toLowerCase();
        if (s === 'selesai') {
            return '<span class="badge badge-success">Selesai</span>';
        }
        if (s === 'proses') {
            return '<span class="badge badge-warning">Proses</span>';
        }
        if (s === 'gagal') {
            return '<span class="badge badge-danger">Gagal</span>';
        }
        return '<span class="badge badge-secondary">' + escapeHtmlGen(status || '—') + '</span>';
    }

    function formatGenHistoryResetCell(item) {
        var n = parseInt(item.reset_deleted_count, 10) || 0;
        var verified = parseInt(item.target_kosong_verified, 10) === 1;
        var html = '<strong>' + n + '</strong>';
        if (verified) {
            html += ' <span class="text-success" title="Bulan target kosong diverifikasi">✓</span>';
        } else if (n > 0) {
            html += ' <span class="text-warning" title="Verifikasi kosong belum/lengkap">!</span>';
        }
        return html;
    }

    function renderHistoryGenerateListRows(items) {
        var $tbody = $('#gen-history-generate-tbody');
        $tbody.empty();
        if (!items || !items.length) {
            $tbody.html('<tr><td colspan="8" class="text-muted text-center small">Belum ada history generate untuk bulan ini.</td></tr>');
            return;
        }
        items.forEach(function(item) {
            var id = parseInt(item.id, 10) || 0;
            var isActive = genHistoryGenerateSelectedId === id;
            var $tr = $('<tr>')
                .attr('data-history-id', id)
                .toggleClass('table-success', isActive)
                .css('cursor', 'pointer');
            $tr.append($('<td>').html('<strong>' + escapeHtmlGen(item.tanggal_klik_generate || '—') + '</strong>'));
            $tr.append($('<td>').text(item.tanggal_selesai || '—'));
            $tr.append($('<td class="text-center">').html(formatGenHistoryResetCell(item)));
            $tr.append($('<td class="text-center small">').html(
                'Ins: <strong>' + (item.generate_insert || 0) + '</strong><br/>Upd: <strong>' + (item.generate_update || 0) + '</strong>'
            ));
            $tr.append($('<td class="text-center small">').html(
                'Proc: <strong>' + (item.total_pembelian || 0) + '</strong><br/>'
                + 'Upd: <strong>' + (item.pembelian_update || 0) + '</strong> / '
                + 'Baru: <strong>' + (item.pembelian_insert || 0) + '</strong>'
                + ((item.pembelian_gagal || 0) > 0 ? '<br/><span class="text-danger">Gagal: ' + item.pembelian_gagal + '</span>' : '')
            ));
            $tr.append($('<td>').html(formatGenHistoryStatusBadge(item.status) + (item.fase_terakhir ? '<br/><small class="text-muted">' + escapeHtmlGen(item.fase_terakhir) + '</small>' : '')));
            $tr.append($('<td class="small">').text(item.nama_user || '—'));
            $tr.append($('<td class="text-center">').html(
                '<button type="button" class="btn btn-xs btn-outline-primary btn-load-history-generate" data-history-id="' + id + '">Muat</button>'
            ));
            $tbody.append($tr);
        });
    }

    function loadHistoryGenerateList(bulanKey) {
        if (!bulanKey || !userCanGeneratePersediaan || !urlListHistoryGenerate) {
            return;
        }
        if (genHistoryGenerateListXhr && genHistoryGenerateListXhr.readyState !== 4) {
            genHistoryGenerateListXhr.abort();
        }
        $('#gen-history-generate-tbody').html('<tr><td colspan="8" class="text-muted text-center small">Memuat history...</td></tr>');
        genHistoryGenerateListXhr = $.ajax({
            url: urlListHistoryGenerate,
            type: 'POST',
            dataType: 'json',
            data: { bulan: bulanKey }
        }).done(function(res) {
            if (!res || !res.ok) {
                var msg = (res && res.message) ? res.message : 'Gagal memuat history generate.';
                $('#gen-history-generate-tbody').html('<tr><td colspan="8" class="text-danger text-center small">' + escapeHtmlGen(msg) + '</td></tr>');
                return;
            }
            if (res.tables_ready === false) {
                $('#gen-history-generate-intro').html(
                    '<span class="text-warning">Tabel history belum tersedia. Jalankan SQL <code>database/sql/persediaan_history_generate.sql</code> atau klik Generate sekali agar auto-create.</span>'
                );
            } else {
                $('#gen-history-generate-intro').html(
                    'Daftar proses generate per bulan target. Klik baris untuk menampilkan semua datatable rekap &amp; proses di bawah.'
                );
            }
            renderHistoryGenerateListRows(res.items || []);
        }).fail(function() {
            $('#gen-history-generate-tbody').html('<tr><td colspan="8" class="text-danger text-center small">Gagal memuat history generate.</td></tr>');
        });
    }

    function applyHistoryGenerateSnapshot(res) {
        if (!res || !res.ok) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: (res && res.message) ? res.message : 'History generate tidak dapat dimuat.'
            });
            return false;
        }

        genHistoryGenerateSelectedId = parseInt(res.history_id, 10) || (res.header ? parseInt(res.header.id, 10) : 0) || null;
        $('#gen-history-generate-tbody tr').removeClass('table-success');
        if (genHistoryGenerateSelectedId) {
            $('#gen-history-generate-tbody tr[data-history-id="' + genHistoryGenerateSelectedId + '"]').addClass('table-success');
        }

        var header = res.header || {};
        var infoHtml = '<div class="alert alert-secondary py-2 px-2 mb-2 small">'
            + '<strong>History Generate</strong> — Klik: <strong>' + escapeHtmlGen(header.tanggal_klik_generate || res.created_at || '') + '</strong>';
        if (header.tanggal_selesai) {
            infoHtml += ' | Selesai: <strong>' + escapeHtmlGen(header.tanggal_selesai) + '</strong>';
        }
        if (header.nama_user || res.nama_user) {
            infoHtml += ' | User: <strong>' + escapeHtmlGen(header.nama_user || res.nama_user) + '</strong>';
        }
        infoHtml += '<br/>Hapus bulan target: <strong>' + (header.reset_deleted_count || 0) + '</strong> record';
        if (parseInt(header.target_kosong_verified, 10) === 1) {
            infoHtml += ' <span class="text-success">(kosong diverifikasi ✓)</span>';
        }
        infoHtml += '</div>';

        if (res.summary_tables && res.summary_tables.ok) {
            renderGenRecalcSummaryTables(res.summary_tables);
            $('#gen-recalc-summary-wrap').removeClass('d-none');
        }

        if (res.summary_html) {
            $('#gen-recalc-summary').html(infoHtml + res.summary_html);
            genRecalcSummaryHtml = res.summary_html;
        } else if (res.summary && typeof res.summary === 'object') {
            var s = res.summary;
            var built = '<strong>Snapshot history generate</strong><br/>'
                + 'Bulan target: <strong>' + escapeHtmlGen(s.bulan_label || s.bulan || '') + '</strong><br/>'
                + 'Generate — Insert: <strong>' + (s.generate_insert || 0) + '</strong>, Update: <strong>' + (s.generate_update || 0) + '</strong><br/>'
                + 'Pembelian — Update: <strong>' + (s.pembelian_update || 0) + '</strong>, Baru: <strong>' + (s.pembelian_insert || 0) + '</strong>';
            $('#gen-recalc-summary').html(infoHtml + built);
            genRecalcSummaryHtml = built;
        } else {
            $('#gen-recalc-summary').html(infoHtml + '<em>Ringkasan teks tidak tersimpan di history ini.</em>');
        }

        applyGenRecalcHistoryResponse(res);

        var $wrap = $('#gen-recalc-result-wrap');
        if ($wrap.length) {
            $('html, body').animate({ scrollTop: $wrap.offset().top - 80 }, 300);
        }
        return true;
    }

    function loadHistoryGenerateSnapshot(historyId) {
        historyId = parseInt(historyId, 10) || 0;
        if (historyId < 1 || !userCanGeneratePersediaan || !urlLoadHistoryGenerate) {
            return;
        }
        if (genHistoryGenerateLoadXhr && genHistoryGenerateLoadXhr.readyState !== 4) {
            genHistoryGenerateLoadXhr.abort();
        }
        Swal.fire({
            title: 'Memuat history...',
            allowOutsideClick: false,
            didOpen: function() { Swal.showLoading(); }
        });
        genHistoryGenerateLoadXhr = $.ajax({
            url: urlLoadHistoryGenerate,
            type: 'POST',
            dataType: 'json',
            data: { id: historyId }
        }).done(function(res) {
            Swal.close();
            applyHistoryGenerateSnapshot(res);
        }).fail(function() {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memuat snapshot history generate.' });
        });
    }

    $(document).on('click', '#gen-history-generate-tbody tr[data-history-id]', function(e) {
        if ($(e.target).closest('.btn-load-history-generate').length) {
            return;
        }
        var id = $(this).attr('data-history-id');
        if (id) {
            loadHistoryGenerateSnapshot(id);
        }
    });

    $(document).on('click', '.btn-load-history-generate', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var id = $(this).attr('data-history-id');
        if (id) {
            loadHistoryGenerateSnapshot(id);
        }
    });

    function buildGenRecalcExcelPayload(jenis) {
        ensureGenRecalcDataShape();
        rebuildGagalGenerateRecalculateData();
        rebuildGagalInsertPersediaanData();

        if (!jenis) {
            return genRecalcData;
        }

        if (jenis === 'gagal_generate_recalculate') {
            return { gagal_generate_recalculate: genRecalcData.gagal_generate_recalculate || [] };
        }
        if (jenis === 'gagal_insert_persediaan') {
            return { gagal_insert_persediaan: genRecalcData.gagal_insert_persediaan || [] };
        }

        var payload = {};
        payload[jenis] = genRecalcData[jenis] || [];
        return payload;
    }

    function genRecalcExcelPayloadHasRows(payload, jenis) {
        if (!payload || typeof payload !== 'object') {
            return false;
        }
        if (!jenis) {
            return Object.keys(payload).some(function(k) {
                return Array.isArray(payload[k]) && payload[k].length > 0;
            });
        }
        return Array.isArray(payload[jenis]) && payload[jenis].length > 0;
    }

    function exportGenRecalcExcel(jenis) {
        var bulanKey = getBulanTargetGenerate();
        if (!bulanKey) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih bulan dan tahun target terlebih dahulu.' });
            return;
        }

        var payload = buildGenRecalcExcelPayload(jenis);
        var dataKey = jenis || '';
        if (!genRecalcExcelPayloadHasRows(payload, dataKey)) {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak ada data',
                text: 'Tabel yang dipilih belum berisi data. Jalankan Generate & Recalculate terlebih dahulu.'
            });
            return;
        }

        var formData = new FormData();
        formData.append('bulan', bulanKey);
        if (jenis) {
            formData.append('jenis', jenis);
        }
        try {
            formData.append('gen_recalc_data', JSON.stringify(payload));
        } catch (eJson) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Data terlalu besar untuk dikirim ke server.' });
            return;
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
                text: err && err.message ? err.message : 'Export Excel gagal. Pastikan proses generate sudah dijalankan dan tabel berisi data.'
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
        ensureGenRecalcDataShape();
        $('#gen-count-persediaan-all').text(genRecalcData.persediaan_all.length);
        $('#gen-count-update').text(genRecalcData.generate_update.length);
        $('#gen-count-insert').text(genRecalcData.generate_insert.length);
        $('#gen-count-verifikasi').text(genRecalcData.generate_verifikasi.length);
        $('#gen-count-masalah').text(genRecalcData.generate_masalah.length);
        $('#gen-count-pembelian').text(genRecalcData.pembelian.length);
        $('#gen-count-pembelian-update').text(genRecalcData.pembelian_update.length);
        $('#gen-count-pembelian-baru').text(genRecalcData.pembelian_baru.length);
        toggleGenRecalcGagalWrap();
        toggleGenRecalcGagalPersediaanWrap();
        toggleGenRecalcPhaseProduksi();
        $('#gen-count-unit-produk').text(genRecalcData.unit_produk.length);
        $('#gen-count-unit-produk-update').text(genRecalcData.unit_produk_update.length);
        $('#gen-count-penjualan').text(genRecalcData.penjualan.length);
        $('#gen-count-penjualan-update').text(genRecalcData.penjualan_update.length);
        toggleGenRecalcPhasePenjualan();
        $('#gen-count-produksi').text(genRecalcData.produksi.length);
        $('#gen-count-produksi-update').text(genRecalcData.produksi_update.length);
        $('#gen-count-pecah-satuan').text(genRecalcData.pecah_satuan.length);
        $('#gen-count-pecah-satuan-update').text(genRecalcData.pecah_satuan_update.length);
    }

    function parseGenRecalcAngkaSum(val) {
        if (val === null || val === undefined || val === '') {
            return 0;
        }
        var s = String(val).replace(/<[^>]*>/g, '').trim();
        if (!s) {
            return 0;
        }
        var n = parseFloat(s.replace(/\./g, '').replace(',', '.'));
        return isNaN(n) ? 0 : n;
    }

    function genRecalcNormalizeHeaderLabel(label) {
        return String(label || '').toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
    }

    function genRecalcNormalizeObjectKey(key) {
        return String(key || '').toLowerCase().replace(/\s+/g, '_');
    }

    function genRecalcCellIsNumeric(val) {
        if (val === null || val === undefined || val === '') {
            return false;
        }
        var s = String(val).replace(/<[^>]*>/g, '').trim();
        if (!s) {
            return false;
        }
        if (/^(total|cocok|skip|gagal|update|insert|beda|masalah|tidak_cocok|update_pembelian|update_penjualan)$/i.test(s)) {
            return false;
        }
        if (/^\d{4}-\d{2}-\d{2}/.test(s)) {
            return false;
        }
        var n = parseFloat(s.replace(/\./g, '').replace(',', '.'));
        return !isNaN(n);
    }

    function genRecalcLabelNeverSum(label) {
        var n = genRecalcNormalizeHeaderLabel(label);
        if (!n || n === 'no') {
            return true;
        }
        if (n.indexOf('id') >= 0 || n.indexOf('uuid') >= 0) {
            return true;
        }
        if (n.indexOf('nama') >= 0 || n === 'uraian' || n === 'satuan' || n.indexOf('keterangan') >= 0) {
            return true;
        }
        if (n.indexOf('check') >= 0 || n.indexOf('waktu') >= 0 || n.indexOf('tanggal') >= 0 || n.indexOf('tgl') === 0) {
            return true;
        }
        if (n === 'aksi' || n === 'status' || n === 'tabel' || n === 'fase' || n === 'user' || n === 'selesai') {
            return true;
        }
        if (n.indexOf('masalah') >= 0 || n.indexOf('error') >= 0) {
            return true;
        }
        if (n === 'unit_produksi' || n === 'record_grup' || n === 'jumlah_record_spop') {
            return true;
        }
        return false;
    }

    function genRecalcKeyNeverSum(key) {
        var n = genRecalcNormalizeObjectKey(key);
        if (!n || n === 'no') {
            return true;
        }
        return genRecalcLabelNeverSum(n.replace(/_/g, ' '));
    }

    function genRecalcLabelAlwaysSum(label) {
        var n = genRecalcNormalizeHeaderLabel(label);
        if (!n) {
            return false;
        }
        if (n === 'sa' || n === 'beli' || n === 'tuj' || n === 'hpp' || n === 'harga_satuan') {
            return true;
        }
        if (n.indexOf('jumlah') >= 0) {
            return true;
        }
        if (n.indexOf('total_10') >= 0 || n.indexOf('total10') >= 0 || n === 'total_10') {
            return true;
        }
        if (n.indexOf('nominal') >= 0 || n.indexOf('nilai') >= 0) {
            return true;
        }
        if (n.indexOf('penjualan') >= 0) {
            return true;
        }
        if (n.indexOf('beli') >= 0) {
            return true;
        }
        if (n.indexOf('sa_') === 0 || n.indexOf('_sa') >= 0 || n.indexOf('sa_baru') >= 0 || n.indexOf('sa_lama') >= 0 || n.indexOf('sa_target') >= 0 || n.indexOf('sa_sumber') >= 0) {
            return true;
        }
        if (n.indexOf('unit_lama') >= 0 || n.indexOf('unit_baru') >= 0) {
            return true;
        }
        if (n.indexOf('pecah') >= 0 || n.indexOf('bahan') >= 0 || n.indexOf('produksi') >= 0) {
            return true;
        }
        if (n.indexOf('sisa') >= 0 || n.indexOf('stock') >= 0) {
            return true;
        }
        if (/^(medis|kbs|ppbmp|sembako|grafikita|dinas|atk|cetak|sekret|pu_outsor|fc_|kop_)/.test(n) || n.indexOf('_nominal') >= 0) {
            return true;
        }
        return false;
    }

    function genRecalcKeyAlwaysSum(key) {
        return genRecalcLabelAlwaysSum(genRecalcNormalizeObjectKey(key).replace(/_/g, ' '));
    }

    function genRecalcColumnShouldSumByHeader(label, rows, colIdx, rowMeta) {
        if (genRecalcLabelNeverSum(label)) {
            return false;
        }
        if (genRecalcLabelAlwaysSum(label)) {
            return true;
        }
        var nonEmpty = 0;
        var numeric = 0;
        (rows || []).forEach(function(row, ri) {
            if (rowMeta && rowMeta[ri] === 'subtotal') {
                return;
            }
            if (!row || colIdx >= row.length) {
                return;
            }
            var val = row[colIdx];
            if (val === null || val === undefined || val === '') {
                return;
            }
            nonEmpty++;
            if (genRecalcCellIsNumeric(val)) {
                numeric++;
            }
        });
        return nonEmpty > 0 && numeric === nonEmpty;
    }

    function inferGenRecalcSumKeysFromObjectKeys(keys, rows) {
        return (keys || []).filter(function(key) {
            if (genRecalcKeyNeverSum(key)) {
                return false;
            }
            if (genRecalcKeyAlwaysSum(key)) {
                return true;
            }
            var nonEmpty = 0;
            var numeric = 0;
            (rows || []).forEach(function(row) {
                if (!row || row.row_type === 'subtotal') {
                    return;
                }
                var val = row[key];
                if (val === null || val === undefined || val === '') {
                    return;
                }
                nonEmpty++;
                if (genRecalcCellIsNumeric(val)) {
                    numeric++;
                }
            });
            return nonEmpty > 0 && numeric === nonEmpty;
        });
    }

    function getGenRecalcTableHeaders(sel) {
        var headers = [];
        $(sel).find('thead tr:first th').each(function() {
            headers.push($(this).text().trim());
        });
        return headers;
    }

    function sumGenRecalcRowsColumn(rows, colIdx, rowMeta) {
        var total = 0;
        (rows || []).forEach(function(row, ri) {
            if (rowMeta && rowMeta[ri] === 'subtotal') {
                return;
            }
            if (!row || colIdx >= row.length) {
                return;
            }
            total += parseGenRecalcAngkaSum(row[colIdx]);
        });
        return total;
    }

    function buildGenRecalcFooterCellsAuto(sel, rows, rowMeta) {
        var colCount = getGenRecalcTableColCount(sel);
        if (!colCount) {
            return [];
        }
        rows = rows || [];
        var headers = getGenRecalcTableHeaders(sel);
        var footer = [];
        for (var i = 0; i < colCount; i++) {
            if (i === 0) {
                footer.push({ html: '<strong>TOTAL</strong>', cls: 'gen-recalc-foot-total-label' });
                continue;
            }
            var label = headers[i] || '';
            if (!genRecalcColumnShouldSumByHeader(label, rows, i, rowMeta)) {
                footer.push({ html: '', cls: '' });
                continue;
            }
            var sum = sumGenRecalcRowsColumn(rows, i, rowMeta);
            footer.push({
                html: '<strong>' + formatGenRecalcAngkaSummary(sum) + '</strong>',
                cls: 'gen-recalc-foot-num text-right'
            });
        }
        return footer;
    }

    function normalizeGenRecalcFooterCellEntries(cells) {
        if (!cells || !cells.length) {
            return [];
        }
        return cells.map(function(c, idx) {
            if (c && typeof c === 'object' && Object.prototype.hasOwnProperty.call(c, 'html')) {
                return c;
            }
            var html = (c === null || c === undefined) ? '' : String(c);
            var cls = idx === 0 ? 'gen-recalc-foot-total-label' : (html ? 'gen-recalc-foot-num text-right' : '');
            return { html: html, cls: cls };
        });
    }

    function sumGenRecalcItemsField(items, field) {
        var total = 0;
        (items || []).forEach(function(it) {
            if (!it || it.row_type === 'subtotal') {
                return;
            }
            if (!field) {
                return;
            }
            var val = it[field];
            if ((val === null || val === undefined || val === '') && field === 'total_10_sumber') {
                val = it.total_10;
            }
            total += parseGenRecalcAngkaSum(val);
        });
        return total;
    }

    function buildGenRecalcProcessFooterCells(sel, rows) {
        if (rows && rows.length) {
            return buildGenRecalcFooterCellsAuto(sel, rows);
        }
        var stored = $(sel).data('genRecalcAllRows');
        if (stored && stored.length) {
            return buildGenRecalcFooterCellsAuto(sel, stored);
        }
        return buildGenRecalcFooterCellsAuto(sel, []);
    }

    function buildRowsGenRecalc() {
        ensureGenRecalcDataShape();
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
        var rowsVer = genRecalcData.generate_verifikasi.map(function(it, i) {
            return [
                i + 1, it.status || '', it.id_sumber || '', it.id_target || '', it.namabarang || '',
                it.satuan || '', it.hpp || '', it.spop || '', it.sa_sumber || '', it.total_10_field_sumber || '',
                it.sa_target || '', it.beli_target || '', it.penjualan_target || '', it.total_10_target || '',
                it.keterangan || ''
            ];
        });
        var rowsMasalah = genRecalcData.generate_masalah.map(function(it, i) {
            return [
                i + 1,
                it.waktu_generate || '',
                it.status || '',
                it.id_sumber || '',
                it.id_target || '',
                it.namabarang || '',
                it.satuan || '',
                it.hpp || '',
                it.spop || '',
                it.total_10_sumber || it.total_10 || '',
                it.keterangan || ''
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
                it.jumlah_pembelian || '', it.beli_lama || '', it.beli_baru || '', it.total_10 || '',
                it.keterangan || '', it.keterangan_check || ''
            ];
        });
        var rowsPB = genRecalcData.pembelian_baru.map(function(it, i) {
            return [
                i + 1, it.id_pembelian || '', it.id_persediaan || '', it.namabarang || '',
                it.satuan || '', it.hpp || '', it.beli_baru || '', it.keterangan || ''
            ];
        });
        var rowsUnit = genRecalcData.unit_produk.map(function(it, i) {
            return [
                i + 1, it.aksi || '', it.id_unit_produk || '', it.id_persediaan || '', it.namabarang || '',
                it.satuan || '', it.hpp || '', it.spop || '', it.nama_unit || '',
                it.jumlah_produksi || '', it.sa_baru || '', it.total_10 || '',
                it.keterangan || '', it.keterangan_check || ''
            ];
        });
        var rowsUnitU = genRecalcData.unit_produk_update.map(function(it, i) {
            return [
                i + 1, it.id_unit_produk || '', it.id_persediaan || '', it.namabarang || '', it.nama_unit || '',
                it.jumlah_produksi || '', it.sa_lama || '', it.sa_baru || '', it.total_10 || '',
                it.keterangan || '', it.keterangan_check || ''
            ];
        });
        var rowsPenj = genRecalcData.penjualan.map(function(it, i) {
            return [
                i + 1, it.aksi || '', it.id_penjualan || '', it.id_persediaan || '', it.uuid_persediaan || '',
                it.namabarang || '', it.satuan || '', it.hpp || '', it.spop || '', it.nama_unit || '',
                it.jumlah_penjualan || '', it.penjualan_baru || '', it.total_10 || '',
                it.keterangan || '', it.keterangan_check || ''
            ];
        });
        var rowsPenjU = genRecalcData.penjualan_update.map(function(it, i) {
            return [
                i + 1, it.id_penjualan || '', it.id_persediaan || '', it.uuid_persediaan || '', it.namabarang || '',
                it.nama_unit || '', it.jumlah_penjualan || '', it.penjualan_lama || '', it.penjualan_baru || '',
                it.unit_lama || '', it.unit_baru || '', it.total_10 || '',
                it.keterangan || '', it.keterangan_check || ''
            ];
        });
        var rowsProd = genRecalcData.produksi.map(function(it, i) {
            return [
                i + 1, it.aksi || '', it.id_produksi_bahan || '', it.id_persediaan || '', it.namabarang || '',
                it.satuan || '', it.hpp || '', it.nama_unit || '', it.jumlah_bahan || '',
                it.bahan_produksi_baru || '', it.total_10 || '',
                it.keterangan || '', it.keterangan_check || ''
            ];
        });
        var rowsProdU = genRecalcData.produksi_update.map(function(it, i) {
            return [
                i + 1, it.id_produksi_bahan || '', it.id_persediaan || '', it.namabarang || '', it.nama_unit || '',
                it.jumlah_bahan || '', it.bahan_produksi_lama || '', it.bahan_produksi_baru || '',
                it.total_10 || '', it.sisa_stock || '',
                it.keterangan || '', it.keterangan_check || ''
            ];
        });
        var rowsPecah = genRecalcData.pecah_satuan.map(function(it, i) {
            return [
                i + 1, it.aksi || '', it.id_pecah_satuan || '', it.id_persediaan_sumber || '', it.id_persediaan_target || '',
                it.namabarang || '', it.satuan || '', it.hpp || '', it.jumlah_pecah || '', it.pecah_satuan_baru || '',
                it.total_10_sumber || '', it.nama_barang_baru || '', it.satuan_barang_baru || '', it.hpp_barang_baru || '',
                it.jumlah_barang_baru || '', it.sa_target || '', it.total_10_target || '',
                it.keterangan || '', it.keterangan_check || ''
            ];
        });
        var rowsPecahU = genRecalcData.pecah_satuan_update.map(function(it, i) {
            return [
                i + 1, it.id_pecah_satuan || '', it.id_persediaan_sumber || '', it.id_persediaan_target || '',
                it.namabarang || '', it.nama_barang_baru || '', it.jumlah_pecah || '', it.jumlah_barang_baru || '',
                it.pecah_satuan_baru || '', it.total_10_sumber || '', it.sa_target || '', it.total_10_target || '',
                it.keterangan || '', it.keterangan_check || ''
            ];
        });
        rebuildGagalGenerateRecalculateData();
        rebuildGagalInsertPersediaanData();
        var rowsGagal = (genRecalcData.gagal_generate_recalculate || []).map(function(it, i) {
            return [
                i + 1, it.fase || '', it.aksi || '', it.tabel || '', it.id_sumber || '', it.id_target || '',
                it.namabarang || '', it.satuan || '', it.hpp || '', it.spop || '', it.jumlah || '', it.keterangan || ''
            ];
        });
        var rowsGagalPers = (genRecalcData.gagal_insert_persediaan || []).map(function(it, i) {
            return [
                i + 1, it.fase || '', it.aksi || '', it.tabel || '', it.id_sumber || '', it.id_target || '',
                it.namabarang || '', it.satuan || '', it.hpp || '', it.spop || '', it.jumlah || '', it.keterangan || ''
            ];
        });
        return {
            all: rowsAll, update: rowsUpd, insert: rowsIns, verifikasi: rowsVer, masalah: rowsMasalah,
            pembelian: rowsB, pembelian_update: rowsPU, pembelian_baru: rowsPB,
            unit_produk: rowsUnit, unit_produk_update: rowsUnitU,
            penjualan: rowsPenj, penjualan_update: rowsPenjU,
            produksi: rowsProd, produksi_update: rowsProdU,
            pecah_satuan: rowsPecah, pecah_satuan_update: rowsPecahU,
            gagal_generate_recalculate: rowsGagal,
            gagal_insert_persediaan: rowsGagalPers
        };
    }

    function isGenRecalcFreezeHeaderLabel(text) {
        var t = String(text || '').trim().toLowerCase().replace(/\s+/g, ' ');
        if (!t) {
            return false;
        }
        if (t === 'nama barang' || t === 'nama' || t === 'uraian') {
            return true;
        }
        if (t === 'sa' || t.indexOf('sa ') === 0) {
            return true;
        }
        if (t === 'beli' || t.indexOf('beli ') === 0) {
            return true;
        }
        return false;
    }

    function initGenRecalcTableTemplateCache(force) {
        GEN_RECALC_TABLE_SELECTORS.forEach(function(sel) {
            if (!force && genRecalcTableTemplates[sel]) {
                return;
            }
            var $t = $(sel);
            if (!$t.length) {
                return;
            }
            var $scroll = $t.closest('.gen-recalc-table-scroll');
            if (!$scroll.length) {
                return;
            }
            $scroll.attr('data-gen-recalc-table', sel.replace('#', ''));
            genRecalcTableTemplates[sel] = $scroll.html();
        });
        genRecalcTableTplInited = Object.keys(genRecalcTableTemplates).length > 0;
    }

    function restoreGenRecalcTableShell(sel) {
        if ($(sel).length) {
            return true;
        }
        var slug = String(sel || '').replace('#', '');
        var $scroll = $('.gen-recalc-table-scroll[data-gen-recalc-table="' + slug + '"]');
        var html = genRecalcTableTemplates[sel];
        if ($scroll.length && html) {
            $scroll.html(html);
        }
        return $(sel).length > 0;
    }

    function restoreAllGenRecalcTableShells() {
        initGenRecalcTableTemplateCache();
        GEN_RECALC_TABLE_SELECTORS.forEach(restoreGenRecalcTableShell);
    }

    function cleanupGenRecalcTableWrapper(sel) {
        var $table = $(sel);
        if (!$table.length) {
            return;
        }
        var $scroll = $table.closest('.gen-recalc-table-scroll');
        if (!$scroll.length) {
            return;
        }
        var $paging = $scroll.data('genRecalcPagingBar');
        if ($paging && $paging.length) {
            $paging.remove();
            $scroll.removeData('genRecalcPagingBar');
        }
        var $orphanWrapper = $scroll.children('.dataTables_wrapper');
        if ($orphanWrapper.length) {
            var $moved = $orphanWrapper.find('table' + sel).first();
            if ($moved.length) {
                $orphanWrapper.replaceWith($moved);
            } else {
                $orphanWrapper.remove();
            }
        }
        if (!$scroll.children(sel).length && $table.parent().is($scroll) === false) {
            $scroll.empty().append($table);
        }
        if ($table.find('tbody').length) {
            $table.find('tbody').empty();
        }
        if ($table.find('tfoot tr').length) {
            $table.find('tfoot tr').first().empty();
        }
    }

    function detectGenRecalcFreezeLeftCount(sel) {
        var maxIdx = -1;
        $(sel).find('thead tr:first th').each(function(i) {
            if (isGenRecalcFreezeHeaderLabel($(this).text())) {
                maxIdx = Math.max(maxIdx, i);
            }
        });
        return maxIdx >= 0 ? (maxIdx + 1) : 0;
    }

    function relayoutGenRecalcFixedColumns(dt) {
        if (!dt) {
            return;
        }
        try {
            if (dt.fixedColumns && typeof dt.fixedColumns === 'function') {
                var fcApi = dt.fixedColumns();
                if (fcApi && typeof fcApi.relayout === 'function') {
                    fcApi.relayout();
                    return;
                }
            }
            var settings = dt.settings ? dt.settings()[0] : null;
            if (settings && settings._oFixedColumns && typeof settings._oFixedColumns.fnRedrawLayout === 'function') {
                settings._oFixedColumns.fnRedrawLayout();
            }
        } catch (eFc) {
            /* abaikan */
        }
    }

    function destroyGenRecalcFixedColumns(sel) {
        genRecalcFcStore[sel] = null;
        try {
            var $scroll = $(sel).closest('.gen-recalc-table-scroll');
            $scroll.find('.DTFC_LeftWrapper, .DTFC_RightWrapper, .DTFC_ScrollWrapper').remove();
        } catch (eRm) {
            /* abaikan */
        }
    }

    function initGenRecalcFixedColumns(sel, dt, freezeLeft) {
        if (!dt || !freezeLeft || freezeLeft < 1) {
            return;
        }
        destroyGenRecalcFixedColumns(sel);
        if ($.fn.dataTable && $.fn.dataTable.FixedColumns) {
            try {
                genRecalcFcStore[sel] = new $.fn.dataTable.FixedColumns(dt, {
                    leftColumns: freezeLeft
                });
            } catch (eInitFc) {
                console.warn('FixedColumns gen-recalc:', sel, eInitFc);
            }
        }
        setTimeout(function() {
            try {
                dt.columns.adjust();
            } catch (eAdj) {}
            relayoutGenRecalcFixedColumns(dt);
        }, 100);
    }

    function getGenRecalcDtBaseOptions(sel) {
        var freezeLeft = detectGenRecalcFreezeLeftCount(sel);
        var $table = $(sel);
        var scrollY = getGenRecalcDataTableScrollY($table);
        return {
            scrollX: true,
            scrollY: scrollY + 'px',
            scrollCollapse: true,
            _genRecalcFreezeLeft: freezeLeft
        };
    }

    function destroyGenRecalcDataTable(sel) {
        destroyGenRecalcFixedColumns(sel);
        restoreGenRecalcTableShell(sel);
        if ($.fn.DataTable && $.fn.DataTable.isDataTable(sel)) {
            try {
                $(sel).DataTable().clear().destroy(false);
            } catch (eDestroy) {
                try {
                    $(sel).DataTable().destroy(false);
                } catch (eDestroy2) {}
            }
        }
        cleanupGenRecalcTableWrapper(sel);
        genRecalcDt[sel] = null;
    }

    function destroyAllGenRecalcDataTables() {
        GEN_RECALC_TABLE_SELECTORS.forEach(destroyGenRecalcDataTable);
        restoreAllGenRecalcTableShells();
    }

    function fillGenRecalcTableSimple(sel, rows) {
        var html = '';
        rows.forEach(function(r) {
            html += '<tr>' + r.map(function(c, ci) {
                var wrapCls = (ci >= r.length - 2) ? ' class="gen-recalc-cell-wrap"' : '';
                return '<td' + wrapCls + '>' + escapeHtmlGen(c) + '</td>';
            }).join('') + '</tr>';
        });
        if (!html) {
            html = '<tr><td colspan="20" class="text-center text-muted">Belum ada data</td></tr>';
        }
        $(sel + ' tbody').html(html);
    }

    function genRecalcDtCreatedRow(row, data) {
        if (!data || !data.length) {
            return;
        }
        var wrapStart = Math.max(0, data.length - 2);
        $('td', row).each(function(i) {
            if (i >= wrapStart) {
                $(this).addClass('gen-recalc-cell-wrap');
            }
        });
    }

    function upsertGenRecalcDataTable(sel, rows, orderCol, footerCells) {
        initGenRecalcTableTemplateCache();
        restoreGenRecalcTableShell(sel);
        if (!$(sel).length) {
            console.warn('GenRecalc table tidak ditemukan:', sel);
            return;
        }
        rows = rows || [];
        $(sel).data('genRecalcAllRows', rows);
        if (footerCells === undefined) {
            footerCells = buildGenRecalcProcessFooterCells(sel, rows);
        }
        footerCells = normalizeGenRecalcFooterCellEntries(footerCells);
        $(sel).data('genRecalcFooterCells', footerCells);
        if (!$.fn.DataTable) {
            fillGenRecalcTableSimple(sel, rows);
            applyGenRecalcSummaryFooter(sel, footerCells);
            return;
        }
        var baseOpts = getGenRecalcDtBaseOptions(sel);
        var freezeLeft = baseOpts._genRecalcFreezeLeft || 0;
        if ($.fn.DataTable.isDataTable(sel)) {
            var dt = $(sel).DataTable();
            dt.clear();
            if (rows.length) {
                dt.rows.add(rows);
            }
            dt.draw(false);
            refreshGenRecalcTableFooter(sel, rows);
            layoutGenRecalcDataTablePaging(sel);
            relayoutGenRecalcFixedColumns(dt);
            genRecalcDt[sel] = dt;
            setTimeout(function() {
                try {
                    dt.columns.adjust().draw(false);
                    refreshGenRecalcTableFooter(sel, rows);
                    layoutGenRecalcDataTablePaging(sel);
                    relayoutGenRecalcFixedColumns(dt);
                } catch (eAdj) {}
            }, 80);
            return;
        }
        genRecalcDt[sel] = $(sel).DataTable($.extend({
            data: rows,
            pageLength: 25,
            order: orderCol !== undefined ? [[orderCol, 'asc']] : [],
            language: genRecalcDtLang,
            autoWidth: true,
            deferRender: true,
            createdRow: genRecalcDtCreatedRow,
            footerCallback: function() {
                refreshGenRecalcTableFooter(sel, $(sel).data('genRecalcAllRows') || rows);
            },
            drawCallback: function() {
                refreshGenRecalcTableFooter(sel, $(sel).data('genRecalcAllRows') || rows);
                layoutGenRecalcDataTablePaging(sel);
                relayoutGenRecalcFixedColumns(genRecalcDt[sel]);
            }
        }, baseOpts));
        applyGenRecalcSummaryFooter(sel, footerCells);
        if (freezeLeft > 0) {
            initGenRecalcFixedColumns(sel, genRecalcDt[sel], freezeLeft);
        }
        setTimeout(function() {
            var dt = genRecalcDt[sel];
            if (dt && dt.columns) {
                try {
                    dt.columns.adjust().draw(false);
                    refreshGenRecalcTableFooter(sel, rows);
                    layoutGenRecalcDataTablePaging(sel);
                    relayoutGenRecalcFixedColumns(dt);
                } catch (eAdjInit) {}
            }
        }, 120);
    }

    function renderGenRecalcDataTables() {
        initGenRecalcTableTemplateCache();
        restoreAllGenRecalcTableShells();
        rebuildGagalGenerateRecalculateData();
        rebuildGagalInsertPersediaanData();
        updateGenRecalcBadges();
        toggleGenRecalcGagalWrap();
        toggleGenRecalcGagalPersediaanWrap();
        toggleGenRecalcPhaseProduksi();
        toggleGenRecalcPhasePenjualan();
        var rows = buildRowsGenRecalc();
        upsertGenRecalcDataTable('#tbl-gen-recalc-persediaan-all', rows.all, 3);
        upsertGenRecalcDataTable('#tbl-gen-recalc-generate-update', rows.update, 2);
        upsertGenRecalcDataTable('#tbl-gen-recalc-generate-insert', rows.insert, 3);
        upsertGenRecalcDataTable('#tbl-gen-recalc-verifikasi', rows.verifikasi, 1);
        upsertGenRecalcDataTable('#tbl-gen-recalc-masalah', rows.masalah, 2);
        upsertGenRecalcDataTable('#tbl-gen-recalc-pembelian', rows.pembelian, 5);
        upsertGenRecalcDataTable('#tbl-gen-recalc-pembelian-update', rows.pembelian_update, 3);
        upsertGenRecalcDataTable('#tbl-gen-recalc-pembelian-baru', rows.pembelian_baru, 3);
        upsertGenRecalcDataTable('#tbl-gen-recalc-unit-produk', rows.unit_produk, 4);
        upsertGenRecalcDataTable('#tbl-gen-recalc-unit-produk-update', rows.unit_produk_update, 3);
        upsertGenRecalcDataTable('#tbl-gen-recalc-penjualan', rows.penjualan, 4);
        upsertGenRecalcDataTable('#tbl-gen-recalc-penjualan-update', rows.penjualan_update, 3);
        upsertGenRecalcDataTable('#tbl-gen-recalc-produksi', rows.produksi, 4);
        upsertGenRecalcDataTable('#tbl-gen-recalc-produksi-update', rows.produksi_update, 3);
        upsertGenRecalcDataTable('#tbl-gen-recalc-pecah-satuan', rows.pecah_satuan, 5);
        upsertGenRecalcDataTable('#tbl-gen-recalc-pecah-satuan-update', rows.pecah_satuan_update, 3);
        upsertGenRecalcDataTable('#tbl-gen-recalc-gagal', rows.gagal_generate_recalculate, 11);
        upsertGenRecalcDataTable('#tbl-gen-recalc-gagal-persediaan', rows.gagal_insert_persediaan, 11);
        setTimeout(adjustGenRecalcDataTables, 150);
    }

    function adjustGenRecalcDataTables() {
        layoutAllGenRecalcDataTableBoxes();
        Object.keys(genRecalcDt).forEach(function(sel) {
            var dt = genRecalcDt[sel];
            if (dt && dt.columns) {
                try { dt.columns.adjust().draw(false); } catch (eAdj) {}
                applyScrollYToGenRecalcDataTable(dt, $(sel));
                relayoutGenRecalcFixedColumns(dt);
            }
        });
        Object.keys(genRecalcSummaryDt).forEach(function(sel) {
            var dt = genRecalcSummaryDt[sel];
            if (dt && dt.columns) {
                try { dt.columns.adjust().draw(false); } catch (eAdjSum) {}
                relayoutGenRecalcFixedColumns(dt);
            }
        });
    }

    var GEN_RECALC_SUMMARY_TABLES = [
        '#tbl-gen-sum-persediaan-lalu',
        '#tbl-gen-sum-persediaan-target',
        '#tbl-gen-sum-pembelian-semua',
        '#tbl-gen-sum-pembelian-update',
        '#tbl-gen-sum-pembelian-baru',
        '#tbl-gen-sum-persediaan-tidak-masuk',
        '#tbl-gen-sum-pembelian-spop-multi',
        '#tbl-gen-sum-pembelian-spop-single'
    ];

    var genRecalcSummaryDt = {};

    function genRecalcSummaryRowCells(row, keys) {
        keys = keys || [];
        if (Array.isArray(row)) {
            var arrOut = [];
            for (var ai = 0; ai < keys.length; ai++) {
                arrOut.push(row[ai] != null && row[ai] !== '' ? escapeHtmlGen(row[ai]) : '');
            }
            return arrOut;
        }
        return keys.map(function(k) {
            return escapeHtmlGen(row[k] != null ? row[k] : '');
        });
    }

    function formatGenRecalcAngkaSummary(val) {
        if (val === null || val === undefined || val === '') {
            return '';
        }
        var n = parseFloat(String(val).replace(/\./g, '').replace(',', '.'));
        if (isNaN(n)) {
            return escapeHtmlGen(val);
        }
        if (Math.abs(n - Math.round(n)) < 0.0001) {
            return escapeHtmlGen(String(Math.round(n)).replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
        }
        return escapeHtmlGen(String(n));
    }

    function ensureGenRecalcSummaryTableHead(sel, headers) {
        if (!headers || !headers.length) {
            return;
        }
        var $row = $(sel).find('thead tr').first();
        if (!$row.length) {
            return;
        }
        var html = '';
        headers.forEach(function(h) {
            html += '<th>' + escapeHtmlGen(h) + '</th>';
        });
        $row.html(html);
    }

    function buildGenRecalcSummaryFooterCells(keys, totals, rows) {
        totals = totals || {};
        rows = rows || [];
        return (keys || []).map(function(key, idx) {
            if (idx === 0) {
                return { html: '<strong>TOTAL</strong>', cls: 'gen-recalc-foot-total-label' };
            }
            if (Object.prototype.hasOwnProperty.call(totals, key) && totals[key] !== null && totals[key] !== '') {
                return {
                    html: '<strong>' + formatGenRecalcAngkaSummary(totals[key]) + '</strong>',
                    cls: 'gen-recalc-foot-num text-right'
                };
            }
            return { html: '', cls: '' };
        });
    }

    function buildGenRecalcSummaryFooterCellsMerged(sel, keys, rows, serverTotals) {
        var sumKeys = inferGenRecalcSumKeysFromObjectKeys(keys, rows);
        var totals = resolveGenRecalcSummaryTotals(rows, keys, serverTotals, sumKeys);
        var fromObjects = buildGenRecalcSummaryFooterCells(keys, totals, rows);
        var packedRows = (rows || []).map(function(r) {
            return genRecalcSummaryRowCells(r, keys);
        });
        var rowMeta = (rows || []).map(function(r) {
            return (r && r.row_type) ? r.row_type : 'detail';
        });
        var fromAuto = buildGenRecalcFooterCellsAuto(sel, packedRows, rowMeta);
        var colCount = Math.max(fromObjects.length, fromAuto.length, keys.length);
        var out = [];
        for (var i = 0; i < colCount; i++) {
            var a = fromAuto[i] || { html: '', cls: '' };
            var b = fromObjects[i] || { html: '', cls: '' };
            if (i === 0) {
                out.push({ html: '<strong>TOTAL</strong>', cls: 'gen-recalc-foot-total-label' });
            } else if (b.html) {
                out.push(b);
            } else if (a.html) {
                out.push(a);
            } else {
                out.push({ html: '', cls: '' });
            }
        }
        return out;
    }

    function sumGenRecalcSummaryRows(rows, sumKeys) {
        var totals = {};
        (sumKeys || []).forEach(function(k) {
            totals[k] = 0;
        });
        (rows || []).forEach(function(row) {
            if (!row || row.row_type === 'subtotal') {
                return;
            }
            (sumKeys || []).forEach(function(k) {
                if (row[k] === null || row[k] === undefined || row[k] === '') {
                    return;
                }
                totals[k] += parseGenRecalcAngkaSum(row[k]);
            });
        });
        return totals;
    }

    function resolveGenRecalcSummaryTotals(rows, keys, serverTotals, sumKeys) {
        var useKeys = (sumKeys && sumKeys.length)
            ? sumKeys
            : inferGenRecalcSumKeysFromObjectKeys(keys, rows);
        var computed = sumGenRecalcSummaryRows(rows, useKeys);
        if (!serverTotals || typeof serverTotals !== 'object') {
            return computed;
        }
        var out = {};
        useKeys.forEach(function(k) {
            if (Object.prototype.hasOwnProperty.call(serverTotals, k) && serverTotals[k] !== null && serverTotals[k] !== '') {
                out[k] = serverTotals[k];
            } else if (Object.prototype.hasOwnProperty.call(computed, k)) {
                out[k] = computed[k];
            }
        });
        return out;
    }

    function layoutGenRecalcDataTablePaging(sel) {
        var $table = $(sel);
        if (!$table.length) {
            return;
        }
        var $scroll = $table.closest('.gen-recalc-table-scroll');
        if (!$scroll.length) {
            return;
        }
        var $wrapper = $table.closest('.dataTables_wrapper');
        if (!$wrapper.length) {
            return;
        }
        var $pagingBar = $scroll.data('genRecalcPagingBar');
        if (!$pagingBar || !$pagingBar.length) {
            $pagingBar = $('<div class="gen-recalc-dt-paging-bar"></div>');
            $scroll.after($pagingBar);
            $scroll.data('genRecalcPagingBar', $pagingBar);
        }
        $pagingBar.empty();
        $wrapper.find('.dataTables_info, .dataTables_paginate').appendTo($pagingBar);
    }

    function layoutGenRecalcSummaryDataTable(sel) {
        layoutGenRecalcDataTablePaging(sel);
    }

    function refreshGenRecalcTableFooter(sel, rows) {
        var footerCells = buildGenRecalcProcessFooterCells(sel, rows || $(sel).data('genRecalcAllRows') || []);
        footerCells = normalizeGenRecalcFooterCellEntries(footerCells);
        $(sel).data('genRecalcFooterCells', footerCells);
        applyGenRecalcSummaryFooter(sel, footerCells);
    }

    function getGenRecalcSummaryFooterCells(sel) {
        return $(sel).data('genRecalcFooterCells') || [];
    }

    function inferPersediaanKeysFromRow(row, fallbackKeys) {
        if (!row || typeof row !== 'object') {
            return fallbackKeys;
        }
        var preferred = ['no', 'namabarang', 'satuan', 'hpp', 'sa', 'spop', 'beli', 'tuj'];
        var unitOrder = [
            'tgl_keluar', 'sekret', 'cetak', 'grafikita', 'dinas_umum', 'atk_rsud', 'ppbmp_kbs', 'kbs', 'ppbmp',
            'medis', 'siiplah_bosda', 'sembako', 'fc_gose', 'fc_manding', 'fc_psamya', 'kop_mp', 'pu_outsor', 'total_10'
        ];
        var keys = [];
        preferred.forEach(function(k) {
            if (Object.prototype.hasOwnProperty.call(row, k) && keys.indexOf(k) < 0) {
                keys.push(k);
            }
        });
        unitOrder.forEach(function(k) {
            if (k === 'tgl_keluar') {
                return;
            }
            if (!Object.prototype.hasOwnProperty.call(row, k)) {
                return;
            }
            if (keys.indexOf(k) < 0) {
                keys.push(k);
            }
            var nomKey = k + '_nominal';
            if (k !== 'total_10' && Object.prototype.hasOwnProperty.call(row, nomKey) && keys.indexOf(nomKey) < 0) {
                keys.push(nomKey);
            }
        });
        Object.keys(row).forEach(function(k) {
            if (k === 'row_type' || keys.indexOf(k) >= 0) {
                return;
            }
            keys.push(k);
        });
        if (keys.indexOf('nilai_persediaan') >= 0) {
            keys.splice(keys.indexOf('nilai_persediaan'), 1);
            keys.push('nilai_persediaan');
        }
        return keys.length > 4 ? keys : fallbackKeys;
    }

    function inferPersediaanSumKeys(keys, rows) {
        return inferGenRecalcSumKeysFromObjectKeys(keys, rows || []);
    }

    function buildPersediaanHeadersFromKeys(keys) {
        return (keys || []).map(function(k) {
            if (k === 'namabarang') return 'Nama Barang';
            if (k === 'nilai_persediaan') return 'Nilai Persediaan';
            if (k === 'total_10') return 'Total 10';
            if (k.slice(-8) === '_nominal') {
                return k.replace(/_nominal$/, '').replace(/_/g, ' ') + ' Nominal';
            }
            return k.charAt(0).toUpperCase() + k.slice(1).replace(/_/g, ' ');
        });
    }

    function syncGenRecalcPersediaanMeta(meta, sampleRow) {
        meta = meta || {};
        var fallbackKeys = ['no', 'namabarang', 'satuan', 'hpp', 'sa', 'spop', 'beli', 'tuj', 'total_10', 'nilai_persediaan'];
        var persKeys = (meta.persediaan_keys && meta.persediaan_keys.length)
            ? meta.persediaan_keys.slice()
            : fallbackKeys.slice();
        var persSumKeys = (meta.persediaan_sum_keys && meta.persediaan_sum_keys.length)
            ? meta.persediaan_sum_keys.slice()
            : [];

        if (sampleRow) {
            var inferred = inferPersediaanKeysFromRow(sampleRow, persKeys);
            if (inferred.length > persKeys.length) {
                persKeys = inferred;
            }
        }
        var persHeaders = buildPersediaanHeadersFromKeys(persKeys);
        if (!persSumKeys.length) {
            persSumKeys = inferPersediaanSumKeys(persKeys, sampleRow ? [sampleRow] : []);
        }
        return {
            persKeys: persKeys,
            persSumKeys: persSumKeys,
            persHeaders: persHeaders
        };
    }

    function buildGenRecalcDtColumns(colCount) {
        var cols = [];
        for (var i = 0; i < colCount; i++) {
            cols.push({ data: i, defaultContent: '' });
        }
        return cols;
    }

    function destroyGenRecalcSummaryTable(sel) {
        if (!$.fn.DataTable) {
            return;
        }
        destroyGenRecalcFixedColumns(sel);
        var $table = $(sel);
        if (!$table.length) {
            return;
        }
        var $scroll = $table.closest('.gen-recalc-table-scroll');
        if ($scroll.length) {
            var $paging = $scroll.data('genRecalcPagingBar');
            if ($paging && $paging.length) {
                $paging.remove();
                $scroll.removeData('genRecalcPagingBar');
            }
        }
        if ($.fn.DataTable.isDataTable(sel)) {
            try {
                $table.DataTable().destroy(false);
            } catch (eDestroySum) {}
            delete genRecalcSummaryDt[sel];
        }
        if ($scroll.length) {
            var $orphanWrapper = $scroll.children('.dataTables_wrapper');
            if ($orphanWrapper.length) {
                var $moved = $orphanWrapper.find('table' + sel).first();
                if ($moved.length) {
                    $orphanWrapper.replaceWith($moved);
                } else {
                    $orphanWrapper.remove();
                }
            }
            if (!$scroll.children(sel).length && $table.parent().is($scroll) === false && $table.length) {
                $scroll.empty().append($table);
            }
        }
        $table.removeData('genRecalcColCount');
        $table.removeData('genRecalcFooterCells');
        $table.removeData('genRecalcRowMeta');
        if ($table.find('tbody').length) {
            $table.find('tbody').empty();
        }
        if ($table.find('tfoot tr').length) {
            $table.find('tfoot tr').first().empty();
        }
    }

    function destroyAllGenRecalcSummaryTables() {
        GEN_RECALC_SUMMARY_TABLES.forEach(destroyGenRecalcSummaryTable);
    }

    function getGenRecalcTableColCount(sel) {
        var thCount = $(sel).find('thead tr:first th').length;
        if (thCount > 0) {
            return thCount;
        }
        var footerCells = $(sel).data('genRecalcFooterCells') || [];
        return footerCells.length || 0;
    }

    function padGenRecalcSummaryRowCells(cells, colCount) {
        var out = Array.isArray(cells) ? cells.slice() : [];
        while (out.length < colCount) {
            out.push('');
        }
        if (out.length > colCount) {
            out.length = colCount;
        }
        return out;
    }

    function applyGenRecalcSummaryFooter(sel, footerCells) {
        var $tfoot = $(sel).find('tfoot tr').first();
        if (!$tfoot.length) {
            $(sel).append('<tfoot><tr></tr></tfoot>');
            $tfoot = $(sel).find('tfoot tr').first();
        }
        footerCells = normalizeGenRecalcFooterCellEntries(footerCells || []);
        if (!footerCells.length) {
            $tfoot.empty();
            return;
        }
        $tfoot.html(footerCells.map(function(c) {
            var cls = c.cls ? (' class="' + c.cls + '"') : '';
            return '<th' + cls + '>' + c.html + '</th>';
        }).join(''));
    }

    function genRecalcSummaryRowPack(r, keys) {
        return {
            cells: genRecalcSummaryRowCells(r, keys),
            row_type: (r && r.row_type) ? r.row_type : 'detail'
        };
    }

    function normalizeGenRecalcSummaryRows(rows) {
        var dtRows = [];
        var rowMeta = [];
        (rows || []).forEach(function(item) {
            if (item && item.cells && Array.isArray(item.cells)) {
                dtRows.push(item.cells);
                rowMeta.push(item.row_type || 'detail');
            } else {
                dtRows.push(item);
                rowMeta.push('detail');
            }
        });
        return { dtRows: dtRows, rowMeta: rowMeta };
    }

    function applyGenRecalcSummaryRowClasses(sel, rowMeta) {
        var $table = $(sel);
        if (!$table.length || !$.fn.DataTable || !$.fn.DataTable.isDataTable(sel)) {
            return;
        }
        var api = $table.DataTable();
        api.rows().every(function(idx) {
            var type = rowMeta[idx] || 'detail';
            if (type === 'subtotal') {
                $(this.node()).addClass('gen-recalc-row-subtotal');
            } else {
                $(this.node()).removeClass('gen-recalc-row-subtotal');
            }
        });
    }

    function upsertGenRecalcSummaryTable(sel, rows, orderCol, footerCells, forceRebuild, colCount, headers) {
        if (!$.fn.DataTable) {
            return;
        }
        colCount = parseInt(colCount, 10) || 0;
        if (!colCount && footerCells && footerCells.length) {
            colCount = footerCells.length;
        }
        if (!colCount && headers && headers.length) {
            colCount = headers.length;
        }
        if (!colCount) {
            colCount = getGenRecalcTableColCount(sel);
        }
        if (!colCount) {
            return;
        }

        destroyGenRecalcSummaryTable(sel);

        if (headers && headers.length) {
            ensureGenRecalcSummaryTableHead(sel, headers);
        }

        var packed = normalizeGenRecalcSummaryRows(rows);
        var dtRows = packed.dtRows.map(function(row) {
            return padGenRecalcSummaryRowCells(row, colCount);
        });
        var rowMeta = packed.rowMeta;

        footerCells = padGenRecalcSummaryRowCells(footerCells || [], colCount);
        footerCells = normalizeGenRecalcFooterCellEntries(footerCells);
        if (!footerCells.length || footerCells.every(function(c) { return !c.html; })) {
            footerCells = buildGenRecalcFooterCellsAuto(sel, dtRows, rowMeta);
        }
        $(sel).data('genRecalcColCount', colCount);
        $(sel).data('genRecalcFooterCells', footerCells);
        $(sel).data('genRecalcAllRows', dtRows);
        $(sel).data('genRecalcRowMeta', rowMeta);

        var baseOpts = getGenRecalcDtBaseOptions(sel);
        var freezeLeft = baseOpts._genRecalcFreezeLeft || colCount;

        genRecalcSummaryDt[sel] = $(sel).DataTable($.extend({
            data: dtRows,
            columns: buildGenRecalcDtColumns(colCount),
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
            order: orderCol !== undefined ? [[orderCol, 'asc']] : [],
            language: genRecalcDtLang,
            autoWidth: true,
            deferRender: true,
            createdRow: function(row, data, dataIndex) {
                var meta = $(sel).data('genRecalcRowMeta') || [];
                if (meta[dataIndex] === 'subtotal') {
                    $(row).addClass('gen-recalc-row-subtotal');
                }
            },
            footerCallback: function() {
                applyGenRecalcSummaryFooter(sel, getGenRecalcSummaryFooterCells(sel));
            },
            drawCallback: function() {
                applyGenRecalcSummaryFooter(sel, getGenRecalcSummaryFooterCells(sel));
                layoutGenRecalcSummaryDataTable(sel);
                relayoutGenRecalcFixedColumns(genRecalcSummaryDt[sel]);
            }
        }, baseOpts));
        applyGenRecalcSummaryFooter(sel, footerCells);
        applyGenRecalcSummaryRowClasses(sel, rowMeta);
        layoutGenRecalcSummaryDataTable(sel);
        if (freezeLeft > 0) {
            initGenRecalcFixedColumns(sel, genRecalcSummaryDt[sel], freezeLeft);
        }
    }

    function renderGenRecalcSummaryTables(payload) {
        payload = payload || {};
        destroyAllGenRecalcSummaryTables();

        var meta = payload.rekap_meta || {};

        var pl = payload.persediaan_bulan_lalu || [];
        var pt = payload.persediaan_total_target || [];
        var pb = payload.pembelian_semua || [];
        var pu = payload.pembelian_update_beli || [];
        var pnb = payload.pembelian_insert_baru || [];
        var ptm = payload.persediaan_sumber_tidak_masuk || [];
        var psm = payload.pembelian_spop_multi || [];
        var pss = payload.pembelian_spop_single || [];

        var samplePersRow = pl[0] || pt[0] || ptm[0] || null;
        var persMeta = syncGenRecalcPersediaanMeta(meta, samplePersRow);
        var persKeys = persMeta.persKeys;
        var persSumKeys = persMeta.persSumKeys;
        var persHeaders = persMeta.persHeaders;

        var spopMultiGrup = parseInt(payload.pembelian_spop_multi_grup_count, 10) || 0;
        var spopMultiDetailCount = 0;
        psm.forEach(function(r) {
            if (!r || r.row_type === 'subtotal') {
                return;
            }
            spopMultiDetailCount++;
        });

        $('#gen-sum-count-persediaan-lalu').text(pl.length);
        $('#gen-sum-count-persediaan-target').text(pt.length);
        $('#gen-sum-count-pembelian-semua').text(pb.length);
        $('#gen-sum-count-pembelian-update').text(pu.length);
        $('#gen-sum-count-pembelian-baru').text(pnb.length);
        $('#gen-sum-count-persediaan-tidak-masuk').text(ptm.length);
        $('#gen-sum-count-pembelian-spop-multi').text(spopMultiGrup);
        $('#gen-sum-count-pembelian-spop-single').text(pss.length);
        $('#gen-sum-label-pembelian-spop-multi').text(
            spopMultiGrup > 0
                ? '(' + spopMultiDetailCount + ' baris detail + ' + spopMultiGrup + ' baris subtotal SPOP)'
                : ''
        );

        if (payload.bulan_sumber_label) {
            $('#gen-sum-label-persediaan-lalu').text('(Bulan ' + payload.bulan_sumber_label + ')');
        }
        if (payload.bulan_label) {
            $('#gen-recalc-summary-intro').html(
                'Rekap hasil generate &amp; recalculate pembelian — bulan target <strong>' + escapeHtmlGen(payload.bulan_label) + '</strong>'
                + (payload.bulan_sumber_label ? ' — sumber generate: <strong>' + escapeHtmlGen(payload.bulan_sumber_label) + '</strong>' : '')
                + '. Scroll hanya di dalam kotak tabel (border hijau).'
            );
        }

        var pembelianSemuaKeys = ['no', 'uraian', 'satuan', 'harga_satuan', 'jumlah', 'spop', 'tgl_po'];
        var pembelianSpopKeys = ['no', 'id_pembelian', 'uraian', 'satuan', 'harga_satuan', 'jumlah', 'spop', 'tgl_po', 'record_grup', 'keterangan_baris'];
        var pembelianUpdateKeys = ['no', 'aksi', 'id_pembelian', 'id_persediaan', 'namabarang', 'satuan', 'hpp', 'spop', 'jumlah_pembelian', 'record_grup', 'beli_lama', 'beli_baru', 'total_10', 'keterangan'];
        var pembelianBaruKeys = ['no', 'aksi', 'id_pembelian', 'id_persediaan', 'namabarang', 'satuan', 'hpp', 'spop', 'jumlah_pembelian', 'record_grup', 'beli_baru', 'total_10', 'keterangan'];
        var pembelianUpdateSumKeys = inferGenRecalcSumKeysFromObjectKeys(pembelianUpdateKeys, pu);
        var pembelianBaruSumKeys = inferGenRecalcSumKeysFromObjectKeys(pembelianBaruKeys, pnb);
        var pembelianSemuaSumKeys = inferGenRecalcSumKeysFromObjectKeys(pembelianSemuaKeys, pb);
        var pembelianSpopSumKeys = inferGenRecalcSumKeysFromObjectKeys(pembelianSpopKeys, psm);
        var persSumKeysPl = inferGenRecalcSumKeysFromObjectKeys(persKeys, pl);
        var persSumKeysPt = inferGenRecalcSumKeysFromObjectKeys(persKeys, pt);
        var persSumKeysPtm = inferGenRecalcSumKeysFromObjectKeys(persKeys, ptm);

        var plTotals = resolveGenRecalcSummaryTotals(pl, persKeys, payload.persediaan_bulan_lalu_totals, persSumKeysPl);
        var ptTotals = resolveGenRecalcSummaryTotals(pt, persKeys, payload.persediaan_total_target_totals, persSumKeysPt);
        var ptmTotals = resolveGenRecalcSummaryTotals(ptm, persKeys, payload.persediaan_sumber_tidak_masuk_totals, persSumKeysPtm);
        var pbTotals = resolveGenRecalcSummaryTotals(pb, pembelianSemuaKeys, payload.pembelian_semua_totals, pembelianSemuaSumKeys);
        var puTotals = resolveGenRecalcSummaryTotals(pu, pembelianUpdateKeys, payload.pembelian_update_beli_totals, pembelianUpdateSumKeys);
        var pnbTotals = resolveGenRecalcSummaryTotals(pnb, pembelianBaruKeys, payload.pembelian_insert_baru_totals, pembelianBaruSumKeys);
        var psmTotals = resolveGenRecalcSummaryTotals(psm, pembelianSpopKeys, payload.pembelian_spop_multi_totals, pembelianSpopSumKeys);
        var pssTotals = resolveGenRecalcSummaryTotals(pss, pembelianSpopKeys, payload.pembelian_spop_single_totals, pembelianSpopSumKeys);

        upsertGenRecalcSummaryTable('#tbl-gen-sum-persediaan-lalu', pl.map(function(r) {
            return genRecalcSummaryRowCells(r, persKeys);
        }), 1, buildGenRecalcSummaryFooterCellsMerged('#tbl-gen-sum-persediaan-lalu', persKeys, pl, plTotals), true, persKeys.length, persHeaders);
        upsertGenRecalcSummaryTable('#tbl-gen-sum-persediaan-target', pt.map(function(r) {
            return genRecalcSummaryRowCells(r, persKeys);
        }), 1, buildGenRecalcSummaryFooterCellsMerged('#tbl-gen-sum-persediaan-target', persKeys, pt, ptTotals), true, persKeys.length, persHeaders);
        upsertGenRecalcSummaryTable('#tbl-gen-sum-pembelian-semua', pb.map(function(r) {
            return genRecalcSummaryRowCells(r, pembelianSemuaKeys);
        }), 1, buildGenRecalcSummaryFooterCellsMerged('#tbl-gen-sum-pembelian-semua', pembelianSemuaKeys, pb, pbTotals), true, pembelianSemuaKeys.length);
        upsertGenRecalcSummaryTable('#tbl-gen-sum-pembelian-update', pu.map(function(r) {
            return genRecalcSummaryRowCells(r, pembelianUpdateKeys);
        }), 1, buildGenRecalcSummaryFooterCellsMerged('#tbl-gen-sum-pembelian-update', pembelianUpdateKeys, pu, puTotals), true, pembelianUpdateKeys.length);
        upsertGenRecalcSummaryTable('#tbl-gen-sum-pembelian-baru', pnb.map(function(r) {
            return genRecalcSummaryRowCells(r, pembelianBaruKeys);
        }), 1, buildGenRecalcSummaryFooterCellsMerged('#tbl-gen-sum-pembelian-baru', pembelianBaruKeys, pnb, pnbTotals), true, pembelianBaruKeys.length);
        upsertGenRecalcSummaryTable('#tbl-gen-sum-persediaan-tidak-masuk', ptm.map(function(r) {
            return genRecalcSummaryRowCells(r, persKeys);
        }), 1, buildGenRecalcSummaryFooterCellsMerged('#tbl-gen-sum-persediaan-tidak-masuk', persKeys, ptm, ptmTotals), true, persKeys.length, persHeaders);
        upsertGenRecalcSummaryTable('#tbl-gen-sum-pembelian-spop-multi', psm.map(function(r) {
            return genRecalcSummaryRowPack(r, pembelianSpopKeys);
        }), 6, buildGenRecalcSummaryFooterCellsMerged('#tbl-gen-sum-pembelian-spop-multi', pembelianSpopKeys, psm, psmTotals), true, pembelianSpopKeys.length);
        upsertGenRecalcSummaryTable('#tbl-gen-sum-pembelian-spop-single', pss.map(function(r) {
            return genRecalcSummaryRowPack(r, pembelianSpopKeys);
        }), 1, buildGenRecalcSummaryFooterCellsMerged('#tbl-gen-sum-pembelian-spop-single', pembelianSpopKeys, pss, pssTotals), true, pembelianSpopKeys.length);

        setTimeout(adjustGenRecalcDataTables, 120);
    }

    function loadGenRecalcSummaryTablesFromServer(bulanKey) {
        if (!bulanKey || !userCanGeneratePersediaan || !urlGenRecalcSummaryTables) {
            return;
        }
        var fd = new FormData();
        fd.append('bulan', bulanKey);
        fetch(urlGenRecalcSummaryTables, {
            method: 'POST',
            body: fd,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res && res.ok) {
                renderGenRecalcSummaryTables(res);
            }
        })
        .catch(function(err) {
            console.warn('GenRecalc summary tables:', err);
        });
    }

    function exportGenRecalcSummaryExcel(jenis) {
        if (!urlExcelGenRecalcSummary) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'URL export Excel ringkasan tidak tersedia.' });
            return;
        }
        var bulanKey = getBulanTargetGenerate();
        if (!bulanKey) {
            Swal.fire({ icon: 'warning', title: 'Bulan belum dipilih', text: 'Pilih bulan dan tahun target terlebih dahulu.' });
            return;
        }
        var formData = new FormData();
        formData.append('bulan', bulanKey);
        formData.append('jenis', jenis || '');
        tampilkanSwalExcelProgress();
        fetch(urlExcelGenRecalcSummary, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(unduhExcelDariResponse)
        .then(function(result) {
            triggerDownloadBlob(result);
            selesaiSwalExcelProgress();
            Swal.fire({ icon: 'success', title: 'Selesai', text: 'File Excel berhasil diunduh.', timer: 1800, showConfirmButton: false });
        })
        .catch(function(err) {
            if (excelProgressTimer) {
                clearInterval(excelProgressTimer);
                excelProgressTimer = null;
            }
            Swal.fire({ icon: 'error', title: 'Gagal', text: err && err.message ? err.message : 'Export Excel gagal.' });
        });
    }

    function initGenRecalcDataTablesEmpty() {
        renderGenRecalcDataTables();
    }

    function htmlGenRecalcProgress(data) {
        var phaseLabel = 'Fase 1: Generate dari bulan sumber';
        if (data.phase === 'pembelian') {
            phaseLabel = 'Fase 2: Pembelian → beli';
        } else if (data.phase === 'unit_produk') {
            phaseLabel = 'Fase 3: Produk jadi (sys_unit_produk) → SA & total_10';
        } else if (data.phase === 'produksi') {
            phaseLabel = 'Fase 4: Produksi bahan → bahan_produksi & total_10';
        } else if (data.phase === 'penjualan') {
            phaseLabel = 'Fase 5: Penjualan → uuid_persediaan atau nama+satuan; unit & penjualan += jumlah, total_10 -= jumlah';
        } else if (data.phase === 'pecah_satuan') {
            phaseLabel = 'Fase 6: Pecah satuan → pecah_satuan & total_10 / SA target';
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
            if (data.items_unit_produk && data.items_unit_produk.length) {
                genRecalcData.unit_produk = genRecalcData.unit_produk.concat(data.items_unit_produk);
            }
            if (data.items_unit_produk_update && data.items_unit_produk_update.length) {
                genRecalcData.unit_produk_update = genRecalcData.unit_produk_update.concat(data.items_unit_produk_update);
            }
            if (data.items_produksi && data.items_produksi.length) {
                genRecalcData.produksi = genRecalcData.produksi.concat(data.items_produksi);
            }
            if (data.items_produksi_update && data.items_produksi_update.length) {
                genRecalcData.produksi_update = genRecalcData.produksi_update.concat(data.items_produksi_update);
            }
            if (data.items_generate_verifikasi && data.items_generate_verifikasi.length) {
                genRecalcData.generate_verifikasi = data.items_generate_verifikasi;
            }
            if (data.items_generate_masalah && data.items_generate_masalah.length) {
                genRecalcData.generate_masalah = data.items_generate_masalah;
            }
            ensureGenRecalcDataShape();
            rebuildGagalGenerateRecalculateData();
            rebuildGagalInsertPersediaanData();
            if (data.phase === 'unit_produk' || data.phase === 'produksi') {
                $('#gen-recalc-phase-produksi').removeClass('d-none');
            }
            if (data.phase === 'penjualan' || (genRecalcData.penjualan || []).length > 0 || (genRecalcData.penjualan_update || []).length > 0) {
                $('#gen-recalc-phase-penjualan').removeClass('d-none');
            }
            if (data.items_pecah_satuan && data.items_pecah_satuan.length) {
                genRecalcData.pecah_satuan = genRecalcData.pecah_satuan.concat(data.items_pecah_satuan);
            }
            if (data.items_pecah_satuan_update && data.items_pecah_satuan_update.length) {
                genRecalcData.pecah_satuan_update = genRecalcData.pecah_satuan_update.concat(data.items_pecah_satuan_update);
            }

            try {
                renderGenRecalcDataTables();
                saveGenRecalcResultToStorage(bulanKey);
                adjustGenRecalcDataTables();
            } catch (eRender) {
                console.error('GenRecalc render:', eRender);
            }
            if (Swal.isVisible()) {
                Swal.update({ html: htmlGenRecalcProgress(data) });
            }

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
            var isGenerateOnly = !!(data.generate_only || s.generate_only);
            var isPembelianOnly = !!(data.pembelian_only || s.pembelian_only);
            var summaryHtml;
            if (isGenerateOnly) {
                $('#gen-recalc-phase-lanjut').addClass('d-none');
                $('#gen-recalc-summary-wrap').addClass('d-none');
                $('#gen-recalc-mode-notice').removeClass('d-none');
                var verIcon = (s.verifikasi_beda || 0) > 0 || (s.verifikasi_tidak_ada_target || 0) > 0 || (s.verifikasi_target_ekstra || 0) > 0
                    ? 'text-danger' : 'text-success';
                var masalahHtml = '';
                if ((s.masalah_total || 0) > 0) {
                    masalahHtml = '<br/><span class="text-danger">Data bermasalah/tidak di-generate: <strong>' + (s.masalah_total || 0) + '</strong>'
                        + ' (minus: <strong>' + (s.masalah_negatif || 0) + '</strong>, skip total_10≤0: <strong>' + (s.masalah_skip_total10 || 0) + '</strong>)</span>';
                }
                summaryHtml = '<strong class="text-warning">Fase generate selesai (salin 1:1)</strong><br/>'
                    + 'Waktu proses: <strong>' + escapeHtmlGen(s.generated_at || '') + '</strong><br/>'
                    + 'Bulan target: <strong>' + escapeHtmlGen(s.bulan_label || bulanKey) + '</strong> '
                    + '(sumber: ' + escapeHtmlGen(s.bulan_sumber_label || '') + ')<br/>'
                    + 'Generate — Insert: <strong>' + (s.generate_insert || 0) + '</strong>, Update: <strong>' + (s.generate_update || 0) + '</strong>, '
                    + 'Lewati: <strong>' + (s.generate_skip || 0) + '</strong><br/>'
                    + '<span class="' + verIcon + '">Verifikasi vs bulan sumber — Cocok: <strong>' + (s.verifikasi_cocok || 0) + '</strong>, '
                    + 'Beda: <strong>' + (s.verifikasi_beda || 0) + '</strong>, '
                    + 'Tidak ada di target: <strong>' + (s.verifikasi_tidak_ada_target || 0) + '</strong>, '
                    + 'Target ekstra: <strong>' + (s.verifikasi_target_ekstra || 0) + '</strong></span>'
                    + masalahHtml
                    + '<br/><em class="text-muted">Fase pembelian, produksi, pecah satuan, dan penjualan belum dijalankan.</em>';
            } else if (isPembelianOnly) {
                $('#gen-recalc-phase-lanjut').removeClass('d-none');
                $('#gen-recalc-phase-produksi').addClass('d-none');
                $('#gen-recalc-phase-penjualan').addClass('d-none');
                $('#gen-recalc-phase-full-only').addClass('d-none');
                $('#gen-recalc-summary-wrap').removeClass('d-none');
                $('#gen-recalc-mode-notice').removeClass('d-none');
                var resetLine = '';
                if ((s.reset_target || 0) > 0 || (s.target_kosong_verified || 0) === 1) {
                    resetLine = 'Hapus data bulan target: <strong>' + (s.reset_target || 0) + '</strong> record'
                        + ((s.target_kosong_verified || 0) === 1
                            ? ' <span class="text-success">(tanggal_beli kosong diverifikasi ✓)</span>'
                            : ' <span class="text-warning">(verifikasi kosong belum lengkap)</span>')
                        + '<br/>';
                }
                summaryHtml = '<strong class="text-success">Generate + Recalculate Pembelian selesai</strong><br/>'
                    + 'Waktu proses: <strong>' + escapeHtmlGen(s.generated_at || '') + '</strong><br/>'
                    + resetLine
                    + 'Bulan target: <strong>' + escapeHtmlGen(s.bulan_label || bulanKey) + '</strong> '
                    + '(sumber: ' + escapeHtmlGen(s.bulan_sumber_label || '') + ')<br/>'
                    + 'Generate — Insert: <strong>' + (s.generate_insert || 0) + '</strong>, Update: <strong>' + (s.generate_update || 0) + '</strong>, '
                    + 'Lewati: <strong>' + (s.generate_skip || 0) + '</strong><br/>'
                    + 'Pembelian (tbl_pembelian) diproses: <strong>' + (s.total_pembelian || 0) + '</strong> — '
                    + 'Update beli: <strong>' + (s.pembelian_update || 0) + '</strong>, '
                    + 'Record baru: <strong>' + (s.pembelian_insert || 0) + '</strong>'
                    + (s.pembelian_gagal ? ', Gagal: <strong class="text-danger">' + s.pembelian_gagal + '</strong>' : '')
                    + (s.pembelian_gagal ? ' — <em>lihat tabel <strong>Gagal Generate atau Recalculate</strong> di paling bawah</em>' : '')
                    + '<br/><em class="text-muted">Fase penjualan, produksi, dan pecah satuan belum dijalankan. Lihat 6 tabel rekap di bawah.</em>';
            } else {
                $('#gen-recalc-phase-lanjut').removeClass('d-none');
                $('#gen-recalc-phase-produksi').removeClass('d-none');
                $('#gen-recalc-phase-penjualan').removeClass('d-none');
                $('#gen-recalc-phase-full-only').removeClass('d-none');
                $('#gen-recalc-summary-wrap').removeClass('d-none');
                $('#gen-recalc-mode-notice').addClass('d-none');
                summaryHtml = '<strong>Generate &amp; Recalculate selesai</strong><br/>'
                    + 'Bulan target: <strong>' + escapeHtmlGen(s.bulan_label || bulanKey) + '</strong> '
                    + '(sumber: ' + escapeHtmlGen(s.bulan_sumber_label || '') + ')<br/>'
                    + 'Generate — Insert: <strong>' + (s.generate_insert || 0) + '</strong>, Update: <strong>' + (s.generate_update || 0) + '</strong><br/>'
                    + 'Pembelian diproses: <strong>' + (s.total_pembelian || 0) + '</strong> — '
                    + 'Update beli: <strong>' + (s.pembelian_update || 0) + '</strong>, '
                    + 'Record baru: <strong>' + (s.pembelian_insert || 0) + '</strong>'
                    + (s.pembelian_gagal ? ', Gagal: <strong>' + s.pembelian_gagal + '</strong>' : '')
                    + '<br/>Produk jadi (sys_unit_produk) diproses: <strong>' + (s.total_unit_produk || 0) + '</strong> — '
                    + 'Update SA: <strong>' + (s.unit_produk_update || 0) + '</strong>, '
                    + 'Insert baru: <strong>' + (s.unit_produk_insert || 0) + '</strong>'
                    + (s.unit_produk_gagal ? ', Gagal: <strong>' + s.unit_produk_gagal + '</strong>' : '')
                    + '<br/>Produksi bahan diproses: <strong>' + (s.total_produksi || 0) + '</strong> — '
                    + 'Update bahan_produksi: <strong>' + (s.produksi_update || 0) + '</strong>'
                    + (s.produksi_tidak_cocok ? ', Tidak cocok: <strong>' + s.produksi_tidak_cocok + '</strong>' : '')
                    + (s.produksi_gagal ? ', Gagal: <strong>' + s.produksi_gagal + '</strong>' : '')
                    + '<br/>Penjualan diproses: <strong>' + (s.total_penjualan || 0) + '</strong> — '
                    + 'Update penjualan: <strong>' + (s.penjualan_update || 0) + '</strong>'
                    + (s.penjualan_tidak_cocok ? ', Tidak cocok: <strong>' + s.penjualan_tidak_cocok + '</strong>' : '')
                    + (s.penjualan_gagal ? ', Gagal: <strong>' + s.penjualan_gagal + '</strong>' : '')
                    + '<br/>Pecah satuan diproses: <strong>' + (s.total_pecah_satuan || 0) + '</strong> — '
                    + 'Update pecah: <strong>' + (s.pecah_update || 0) + '</strong>'
                    + (s.pecah_tidak_cocok ? ', Tidak cocok: <strong>' + s.pecah_tidak_cocok + '</strong>' : '')
                    + (s.pecah_gagal ? ', Gagal: <strong>' + s.pecah_gagal + '</strong>' : '')
                    + '<br/>Hapus duplikat spop kosong/0 (beli=0): <strong>' + (s.cleanup_spop_kosong || 0) + '</strong>'
                    + ((s.cleanup_spop_kosong_grup || 0) > 0 ? ' (' + s.cleanup_spop_kosong_grup + ' grup)' : '');
            }
            $('#gen-recalc-summary').html(summaryHtml);
            genRecalcSummaryHtml = summaryHtml;
            renderGenRecalcDataTables();
            saveGenRecalcResultToStorage(bulanKey);
            setStatusGeneratePersediaan('success', summaryHtml);

            if (!isGenerateOnly) {
                if (data.summary_tables && data.summary_tables.ok) {
                    renderGenRecalcSummaryTables(data.summary_tables);
                } else {
                    loadGenRecalcSummaryTablesFromServer(bulanKey);
                }
            }
            loadHistoryGenerateList(bulanKey);

            var swalIcon = isGenerateOnly
                ? ((data.has_masalah || (s.verifikasi_beda || 0) > 0 || (s.verifikasi_tidak_ada_target || 0) > 0 || (s.verifikasi_target_ekstra || 0) > 0 || (s.masalah_negatif || 0) > 0) ? 'warning' : 'success')
                : 'success';
            var swalTitle = isGenerateOnly ? 'Generate selesai' : (isPembelianOnly ? 'Generate + Pembelian selesai' : 'Selesai');
            var masalahNotice = '';
            if (isGenerateOnly && (s.masalah_negatif || 0) > 0) {
                masalahNotice = '<br/><strong class="text-danger">Perhatian: ditemukan nilai total_10 minus di bulan sumber — tidak di-copy.</strong>';
            }
            Swal.fire({
                icon: swalIcon,
                title: swalTitle,
                html: summaryHtml + masalahNotice
                    + ((s.pembelian_gagal || 0) > 0
                        ? '<br/><strong class="text-danger">Ada ' + s.pembelian_gagal + ' pembelian gagal — scroll ke tabel merah <em>Gagal Generate atau Recalculate</em> di paling bawah.</strong>'
                        : '')
                    + '<br/><small>Detail proses ditampilkan di tabel di bawah (termasuk history data bermasalah).</small>',
                confirmButtonText: 'OK'
            }).then(function() {
                if ($('#bulan_persediaan').val() !== bulanKey) {
                    $('#bulan_persediaan').val(bulanKey);
                }
                savePersediaanBulanData(bulanKey);
                savePersediaanGenFromBulanKey(bulanKey);
                cekGeneratePersediaanBulan();
                if ((isGenerateOnly && data.refresh_persediaan) || (isPembelianOnly && data.refresh_persediaan) || (!isGenerateOnly && !isPembelianOnly && data.refresh_persediaan && $('#form-persediaan-bulan').length)) {
                    savePersediaanMainTabKey('generate');
                    saveCurrentPersediaanTabs();
                    $('#form-persediaan-bulan').submit();
                }
                setTimeout(function() {
                    adjustGenRecalcDataTables();
                    if ((s.pembelian_gagal || 0) > 0 && $('#gen-recalc-gagal-wrap').length) {
                        $('html, body').animate({ scrollTop: $('#gen-recalc-gagal-wrap').offset().top - 80 }, 400);
                    } else if ((genRecalcData.gagal_insert_persediaan || []).length > 0 && $('#gen-recalc-gagal-persediaan-wrap').length) {
                        $('html, body').animate({ scrollTop: $('#gen-recalc-gagal-persediaan-wrap').offset().top - 80 }, 400);
                    }
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
                    + '<p class="small text-muted mb-0">Fase 1: salin sumber (total_10 &gt;= 1), hapus target sa=0 &amp; beli=0 &amp; total_10=0.<br/>'
                    + 'Fase 2: pembelian → beli += jumlah, total_10 += jumlah (nama+satuan+hpp+spop).<br/>'
                    + 'Fase 3: produk jadi dari sys_unit_produk → insert/SA += jumlah_produksi (prioritas uuid_persediaan, lalu nama+satuan+hpp+spop).<br/>'
                    + 'Fase 4: bahan produksi dari sys_unit_produk_bahan → bahan_produksi += jumlah_bahan, total_10 -= jumlah_bahan (nama+satuan+hpp+spop).<br/>'
                    + 'Fase 5: penjualan dari tbl_penjualan → cocokkan uuid_persediaan, fallback nama_barang+satuan; unit + penjualan += jumlah, total_10 -= jumlah.<br/>'
                    + 'Fase 6: pecah satuan dari tbl_pembelian_pecah_satuan → sumber pecah_satuan += jumlah, total_10 -= jumlah; target sa/total_10 += jumlah_barang_baru.</p>',
                showCancelButton: true,
                confirmButtonText: 'Ya, Generate & Recalculate',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745'
            }).then(function(result) {
                if (!result.isConfirmed) return;

                genRecalcData = createEmptyGenRecalcData();
                genRecalcSummaryHtml = '';
                clearGenRecalcResultStorage(bulanKey);
                initGenRecalcTableTemplateCache();
                restoreAllGenRecalcTableShells();
                destroyGenRecalcDataTables();
                $('#gen-recalc-phase-produksi').addClass('d-none');
                $('#gen-recalc-phase-penjualan').addClass('d-none');
                $('#gen-recalc-phase-full-only').addClass('d-none');
                $('#gen-recalc-summary').html('<em>Proses berjalan...</em>');
                try {
                    initGenRecalcDataTablesEmpty();
                } catch (eInit) {
                    console.error('GenRecalc init tables:', eInit);
                }

                var totalPhase = res.count_sumber || 0;
                if (res.can_recalc_only && totalPhase <= 0) {
                    totalPhase = Math.max(
                        (res.count_target || 0),
                        1
                    );
                }

                Swal.fire({
                    title: 'Generate & Recalculate',
                    html: htmlGenRecalcProgress({
                        phase: res.can_recalc_only ? 'pembelian' : 'generate',
                        offset_selesai: 0,
                        total_phase: totalPhase,
                        pesan: 'Memulai proses...'
                    }),
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
    var compareCsvLastUpload = null;
    var compareCsvPreviewDt = null;
    var compareDtLang = {
        emptyTable: 'Belum ada data',
        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
        infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
        search: 'Cari:',
        zeroRecords: 'Tidak ada data yang cocok',
        paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' }
    };

    var compareDtFooterSumCols = {
        '#table-compare-total10': [5, 6, 7],
        '#table-compare-tidak': [5, 6, 7, 12, 13, 14],
        '#table-compare-hanya': [5, 6, 7, 12, 13, 14],
        '#table-compare-cocok': [5, 6, 7, 12, 13, 14],
        '#table-compare-pembelian-tidak': [6],
        '#table-compare-penjualan-tidak': [6],
        '#table-compare-produksi-tidak': [5],
        '#table-compare-pecah-tidak': [5]
    };
    var compareCsvPreviewFooterSumCols = [];

    function parseCompareFooterNum(value) {
        if (value == null || value === '') {
            return 0;
        }
        var s = String(value).trim();
        if (s === '' || s === '-') {
            return 0;
        }
        s = s.replace(/\s/g, '');
        if (s.indexOf(',') >= 0 && s.indexOf('.') >= 0) {
            s = s.replace(/\./g, '').replace(',', '.');
        } else if (s.indexOf(',') >= 0) {
            s = s.replace(',', '.');
        }
        var n = parseFloat(s);
        return isNaN(n) ? 0 : n;
    }

    function formatCompareFooterNum(value) {
        var n = parseCompareFooterNum(value);
        if (Math.floor(n) === n) {
            return n.toLocaleString('id-ID');
        }
        return n.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function detectCompareCsvSumColumnIndexes(headers) {
        var sumCols = [];
        (headers || []).forEach(function(header, idx) {
            var key = String(header || '').toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_|_$/g, '');
            if (key === '') {
                return;
            }
            if (/^(no|nama|namabarang|uraian|satuan|spop|tanggal|tgl|keterangan|uuid)/.test(key)) {
                return;
            }
            if (/(^|_)(sa|beli|tuj|total_10|total10|col_10|jumlah|nilai|nominal|penjualan|pecah|produksi|medis|kbs|ppbmp|sembako|grafikita|dinas|atk|cetak|fc_|kop_|pu_)/.test(key) || key === '10') {
                sumCols.push(idx);
            }
        });
        return sumCols;
    }

    function ensureCompareTableTfoot($table) {
        var colCount = $table.find('thead tr:first th').length;
        if (colCount < 1) {
            return;
        }
        var $tfoot = $table.find('tfoot');
        if (!$tfoot.length) {
            $tfoot = $('<tfoot><tr></tr></tfoot>');
            $table.append($tfoot);
        }
        var $row = $tfoot.find('tr').first();
        if ($row.find('th').length !== colCount) {
            var html = '';
            for (var i = 0; i < colCount; i++) {
                html += '<th></th>';
            }
            $row.html(html);
        }
    }

    function buildCompareFooterCallback(selector, sumColsOverride) {
        return function() {
            var api = this.api();
            var sumCols = sumColsOverride || compareDtFooterSumCols[selector] || [];
            if (!sumCols.length) {
                return;
            }
            var totals = {};
            sumCols.forEach(function(colIdx) {
                totals[colIdx] = 0;
            });
            api.rows({ search: 'applied' }).data().each(function(row) {
                sumCols.forEach(function(colIdx) {
                    totals[colIdx] += parseCompareFooterNum(row[colIdx]);
                });
            });
            api.columns().every(function() {
                var idx = this.index();
                var $foot = $(this.footer());
                if (!$foot.length) {
                    return;
                }
                if (idx === 0) {
                    $foot.html('Total').addClass('compare-foot-total-label').removeClass('compare-foot-num text-right');
                } else if (sumCols.indexOf(idx) >= 0) {
                    $foot.html(formatCompareFooterNum(totals[idx])).addClass('compare-foot-num text-right').removeClass('compare-foot-total-label');
                } else {
                    $foot.html('').removeClass('compare-foot-num compare-foot-total-label text-right');
                }
            });
        };
    }

    /** Tinggi scroll body DataTables — sisakan ruang filter, info, paging, footer. */
    function getDataTableScrollY($table, reserveBottom) {
        var $wrap = ($table && $table.length)
            ? $table.closest('.persediaan-tab-dt-wrap, .compare-dt-wrap, .compare-csv-preview-dt-wrap')
            : $();
        var isCompare = $wrap.hasClass('compare-dt-wrap') || $wrap.hasClass('compare-csv-preview-dt-wrap');
        if (reserveBottom == null || reserveBottom === undefined) {
            reserveBottom = isCompare ? 130 : 190;
        }
        if (!$table || !$table.length) {
            return Math.max(isCompare ? 280 : 420, Math.floor((window.innerHeight || 800) - reserveBottom));
        }
        var $anchor = $wrap.length ? $wrap : $table;
        var top = $anchor.offset() ? $anchor.offset().top : 0;
        var vh = window.innerHeight || document.documentElement.clientHeight || 800;
        var h = Math.max(isCompare ? 280 : 420, Math.floor(vh - top - reserveBottom));
        if (isCompare) {
            h = Math.min(h, Math.max(280, Math.floor(vh * 0.55)));
        }
        return h;
    }

    function layoutCompareDataTableBox($table) {
        if (!$table || !$table.length) {
            return;
        }
        var $wrap = $table.closest('.compare-dt-wrap, .compare-csv-preview-dt-wrap');
        if (!$wrap.length) {
            return;
        }
        var $wrapper = $table.closest('.dataTables_wrapper');
        $wrap.css({ overflowX: 'hidden', overflowY: 'visible', width: '100%' });
        if ($wrapper.length) {
            $wrapper.css({ overflow: 'hidden', width: '100%', maxWidth: '100%' });
            $wrapper.find('.dataTables_scroll').css({ overflow: 'hidden', width: '100%' });
            $wrapper.find('.dataTables_scrollHead').css('overflow', 'hidden');
            $wrapper.find('.dataTables_scrollBody').css({ overflowX: 'auto', overflowY: 'auto' });
        }
    }

    function applyScrollYToDataTable(dt, $table, reserveBottom) {
        if (!dt || !$table || !$table.length) {
            return;
        }
        var h = getDataTableScrollY($table, reserveBottom);
        try {
            var settings = dt.settings && dt.settings()[0];
            if (settings && settings.oScroll) {
                settings.oScroll.sY = h + 'px';
            }
        } catch (eScroll) {}
        var $wrapper = $table.closest('.dataTables_wrapper');
        $wrapper.find('.dataTables_scrollBody').css({
            'max-height': h + 'px',
            'height': h + 'px'
        });
        layoutCompareDataTableBox($table);
        try {
            dt.columns.adjust().draw(false);
        } catch (eDraw) {}
    }

    function getGenRecalcDataTableScrollY($table, reserveBottom) {
        if (reserveBottom == null || reserveBottom === undefined) {
            reserveBottom = 200;
        }
        if (!$table || !$table.length) {
            return Math.max(360, Math.floor((window.innerHeight || 800) - reserveBottom));
        }
        var $wrap = $table.closest('.gen-recalc-table-scroll');
        var top = ($wrap.length && $wrap.offset()) ? $wrap.offset().top : ($table.offset() ? $table.offset().top : 0);
        var vh = window.innerHeight || document.documentElement.clientHeight || 800;
        return Math.max(320, Math.min(560, Math.floor(vh - top - reserveBottom)));
    }

    function layoutGenRecalcDataTableBox($table) {
        if (!$table || !$table.length) {
            return;
        }
        var $wrap = $table.closest('.gen-recalc-table-scroll');
        if (!$wrap.length) {
            return;
        }
        var $wrapper = $table.closest('.dataTables_wrapper');
        $wrap.css({ overflow: 'hidden', width: '100%', maxHeight: 'none' });
        if ($wrapper.length) {
            $wrapper.css({ overflow: 'hidden', width: '100%', maxWidth: '100%' });
            $wrapper.find('.dataTables_scroll').css({ overflow: 'hidden', width: '100%' });
            $wrapper.find('.dataTables_scrollHead').css('overflow', 'hidden');
            $wrapper.find('.dataTables_scrollBody').css({ overflowX: 'auto', overflowY: 'auto' });
        }
    }

    function applyScrollYToGenRecalcDataTable(dt, $table, reserveBottom) {
        if (!dt || !$table || !$table.length) {
            return;
        }
        var h = getGenRecalcDataTableScrollY($table, reserveBottom);
        try {
            var settings = dt.settings && dt.settings()[0];
            if (settings && settings.oScroll) {
                settings.oScroll.sY = h + 'px';
            }
        } catch (eScroll) {}
        var $wrapper = $table.closest('.dataTables_wrapper');
        $wrapper.find('.dataTables_scrollBody').css({
            'max-height': h + 'px',
            'height': h + 'px'
        });
        layoutGenRecalcDataTableBox($table);
        try {
            dt.columns.adjust().draw(false);
        } catch (eDraw) {}
    }

    function layoutAllGenRecalcDataTableBoxes() {
        GEN_RECALC_TABLE_SELECTORS.forEach(function(sel) {
            layoutGenRecalcDataTableBox($(sel));
        });
    }

    function adjustGenRecalcScrollAreas() {
        layoutAllGenRecalcDataTableBoxes();
        Object.keys(genRecalcDt).forEach(function(sel) {
            var dt = genRecalcDt[sel];
            if (dt) {
                applyScrollYToGenRecalcDataTable(dt, $(sel));
            }
        });
    }

    function adjustRekapScrollArea() {
        var vh = window.innerHeight || document.documentElement.clientHeight || 800;
        var $wrap = $('#rekap-table-wrap');
        if (!$wrap.length) {
            return;
        }
        var top = $wrap.offset() ? $wrap.offset().top : 0;
        var maxH = Math.max(420, Math.floor(vh - top - 80));
        $wrap.css('max-height', maxH + 'px');
    }

    function adjustAllPersediaanDataTableAreas() {
        adjustGenRecalcScrollAreas();
        adjustRekapScrollArea();
        if (typeof adjustPersediaanTabDataTables === 'function') {
            adjustPersediaanTabDataTables();
        } else if (typeof dtPersediaanStore !== 'undefined') {
            Object.keys(dtPersediaanStore).forEach(function(sel) {
                applyScrollYToDataTable(dtPersediaanStore[sel], $(sel));
            });
        }
        Object.keys(compareDtStore).forEach(function(sel) {
            applyScrollYToDataTable(compareDtStore[sel], $(sel));
        });
        if (compareCsvPreviewDt) {
            applyScrollYToDataTable(compareCsvPreviewDt, $('#table-compare-csv-preview'), 220);
        }
        if (typeof adjustGenRecalcDataTables === 'function') {
            adjustGenRecalcDataTables();
        }
    }

    var resizePersediaanDtTimer = null;
    $(window).on('resize', function() {
        clearTimeout(resizePersediaanDtTimer);
        resizePersediaanDtTimer = setTimeout(adjustAllPersediaanDataTableAreas, 150);
    });

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
        $('#compare-badge-pembelian-tidak').text(typeof stats.pembelian_tidak_manual !== 'undefined' ? stats.pembelian_tidak_manual : 0);
        $('#compare-badge-penjualan-tidak').text(typeof stats.penjualan_tidak_manual !== 'undefined' ? stats.penjualan_tidak_manual : 0);
        $('#compare-badge-produksi-tidak').text(typeof stats.produksi_tidak_manual !== 'undefined' ? stats.produksi_tidak_manual : 0);
        $('#compare-badge-pecah-tidak').text(typeof stats.pecah_tidak_manual !== 'undefined' ? stats.pecah_tidak_manual : 0);
    }

    function fillCompareTableSelect($sel, tables, selectTable) {
        if (!$sel || !$sel.length) return;
        var cur = selectTable || $sel.val();
        $sel.find('option:not(:first)').remove();
        (tables || []).forEach(function(tbl) {
            $sel.append($('<option>', { value: tbl, text: tbl }));
        });
        if (cur) {
            $sel.val(cur);
        }
    }

    function updateCompareDbTabelInfoBox() {
        var tbl = ($('#compare_db_tabel_cek').val() || '').trim();
        var $box = $('#compare-db-tabel-info');
        if (!tbl) {
            $box.addClass('d-none');
            $('#compare-db-tabel-nama').text('—');
            toggleCompareDbInsertButton(false);
            return;
        }
        $('#compare-db-tabel-nama').text(tbl);
        $box.removeClass('d-none');
        toggleCompareDbInsertButton(false);
        checkCompareDbTableInsertEligible(tbl, function(res) {
            var cur = ($('#compare_db_tabel_cek').val() || '').trim();
            if (cur !== tbl) {
                return;
            }
            toggleCompareDbInsertButton(res && res.eligible);
        });
    }

    var compareDbInsertEligibleSeq = 0;

    function toggleCompareDbInsertButton(eligible) {
        var $btn = $('#btn-compare-db-insert-persediaan');
        if (eligible && userCanComparePersediaan) {
            $btn.removeClass('d-none');
        } else {
            $btn.addClass('d-none');
        }
    }

    function checkCompareDbTableInsertEligible(table, callback) {
        if (!table) {
            if (typeof callback === 'function') {
                callback({ eligible: false });
            }
            return;
        }
        var seq = ++compareDbInsertEligibleSeq;
        $.ajax({
            url: urlCompareCheckInsertEligible,
            type: 'POST',
            dataType: 'json',
            data: { tabel: table }
        }).done(function(res) {
            if (seq !== compareDbInsertEligibleSeq) {
                return;
            }
            if (typeof callback === 'function') {
                callback(res || {});
            }
        }).fail(function() {
            if (seq !== compareDbInsertEligibleSeq) {
                return;
            }
            if (typeof callback === 'function') {
                callback({ eligible: false });
            }
        });
    }

    function refreshPersediaanAfterCompareInsert() {
        if ($('#form-persediaan-bulan').length) {
            $('#form-persediaan-bulan').submit();
        }
    }

    function loadCompareTableList(force, selectTable, onReady) {
        if (compareTablesLoaded && !force) {
            if (selectTable) {
                $('#compare_tabel_pilihan').val(selectTable);
                $('#compare_db_tabel_cek').val(selectTable);
                updateCompareDbTabelInfoBox();
            }
            if (typeof onReady === 'function') {
                onReady();
            }
            return;
        }
        var $sel = $('#compare_tabel_pilihan');
        var $selDb = $('#compare_db_tabel_cek');
        $sel.prop('disabled', true);
        $selDb.prop('disabled', true);
        $.ajax({
            url: urlCompareTabelList,
            type: 'POST',
            dataType: 'json'
        }).done(function(res) {
            if (!res || !res.ok) {
                setCompareStatus('danger', (res && res.message) ? res.message : 'Gagal memuat daftar tabel.');
                return;
            }
            var tables = res.tables || [];
            fillCompareTableSelect($sel, tables, selectTable);
            fillCompareTableSelect($selDb, tables, selectTable);
            updateCompareDbTabelInfoBox();
            compareTablesLoaded = true;
            setCompareStatus('info', 'Pilih bulan, tahun, dan tabel — lalu klik <strong>Compare</strong>.');
        }).fail(function() {
            setCompareStatus('danger', 'Tidak dapat memuat daftar tabel dari server.');
        }).always(function() {
            $sel.prop('disabled', false);
            if (!userCanComparePersediaan) {
                $selDb.prop('disabled', true);
            } else {
                $selDb.prop('disabled', false);
            }
            if (typeof onReady === 'function') {
                onReady();
            }
        });
    }

    function refreshPersediaanTabDataAfterFilterRestore() {
        var mainKey = persediaanMainTabKeyFromHref($('#persediaan-tabs .nav-link.active').attr('href') || '');

        if (mainKey === 'rekap') {
            if (!rekapRecalcRunning && !rekapLoading && !rekapSkipNextPanelLoad) {
                loadRekapDataOnly();
            }
        } else if (mainKey === 'generate' && userCanGeneratePersediaan) {
            cekGeneratePersediaanBulan();
            loadGenRecalcHistoryFromServer(getBulanTargetGenerate());
            loadGenRecalcSummaryTablesFromServer(getBulanTargetGenerate());
            loadHistoryGenerateList(getBulanTargetGenerate());
            adjustGenRecalcDataTables();
        } else if (mainKey === 'compare') {
            var savedTabel = getSavedPersediaanCompareTabel();
            loadCompareTableList(true, savedTabel || null, function() {
                updateCompareInfoRingkas({
                    bulan_label: getBulanKeyCompare(),
                    table: $('#compare_tabel_pilihan').val() || savedTabel || ''
                });
                if (savedTabel && getBulanKeyCompare()) {
                    runCompareTabel();
                }
            });
        }

        adjustAllPersediaanDataTableAreas();
    }

    function showCompareCsvAlertMessage(res, icon, title) {
        var msg = (res && res.message) ? String(res.message) : '';
        var html = '<div style="text-align:left;font-size:14px;white-space:pre-wrap;">'
            + escapeHtmlCompare(msg) + '</div>';
        Swal.fire({
            icon: icon,
            title: title,
            html: html
        });
    }

    function updateCompareCsvUploadInfo(data) {
        data = data || null;
        var $box = $('#compare-csv-upload-info');
        if (!data || !data.table) {
            compareCsvLastUpload = null;
            $box.addClass('d-none');
            $('#compare-csv-filename').text('—');
            $('#compare-csv-tablename').text('—');
            $('#compare-csv-rowcount').text('');
            return;
        }
        compareCsvLastUpload = {
            file: data.file || '',
            table: data.table || '',
            rows: data.rows || 0
        };
        $('#compare-csv-filename').text(compareCsvLastUpload.file || '—');
        $('#compare-csv-tablename').text(compareCsvLastUpload.table || '—');
        $('#compare-csv-rowcount').text(
            compareCsvLastUpload.rows ? (' (' + compareCsvLastUpload.rows + ' baris)') : ''
        );
        $box.removeClass('d-none');
    }

    function renderCompareCsvPreviewTable(res) {
        res = res || {};
        var cols = res.columns || [];
        var rows = res.rows || [];
        var $table = $('#table-compare-csv-preview');

        if ($.fn.DataTable.isDataTable($table)) {
            $table.DataTable().destroy();
        }
        $table.find('thead tr').empty();
        $table.find('tbody').empty();
        $table.find('tfoot').remove();

        cols.forEach(function(col) {
            $table.find('thead tr').append($('<th>').text(col));
        });

        var dtRows = rows.map(function(row) {
            return cols.map(function(col) {
                return (row && row[col] != null) ? String(row[col]) : '';
            });
        });

        compareCsvPreviewFooterSumCols = detectCompareCsvSumColumnIndexes(cols);
        ensureCompareTableTfoot($table);

        compareCsvPreviewDt = $table.DataTable({
            data: dtRows,
            scrollX: true,
            scrollY: getDataTableScrollY($table, 220),
            scrollCollapse: true,
            paging: true,
            pageLength: 25,
            order: [],
            language: compareDtLang,
            autoWidth: false,
            deferRender: true,
            footerCallback: buildCompareFooterCallback('#table-compare-csv-preview', compareCsvPreviewFooterSumCols)
        });

        setTimeout(function() {
            layoutCompareDataTableBox($table);
            applyScrollYToDataTable(compareCsvPreviewDt, $table, 220);
        }, 200);
    }

    function openCompareCsvPreviewModal(table, fileLabel) {
        table = (table || '').trim();
        if (!table) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Belum ada tabel untuk ditampilkan.' });
            return;
        }

        $('#compare-csv-preview-meta').text('Memuat data tabel `' + table + '`...');
        $('#compare-csv-preview-loading').removeClass('d-none');
        $('.compare-csv-preview-dt-wrap').addClass('d-none');
        $('#modal-compare-csv-preview').modal('show');

        var formData = new FormData();
        formData.append('tabel', table);
        formData.append('limit', '1000');

        fetch(urlCompareTabelPreview, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(parseJsonFetchResponse)
        .then(function(res) {
            $('#compare-csv-preview-loading').addClass('d-none');
            $('.compare-csv-preview-dt-wrap').removeClass('d-none');
            if (!res || !res.ok) {
                $('#compare-csv-preview-meta').text((res && res.message) ? res.message : 'Gagal memuat data tabel.');
                renderCompareCsvPreviewTable({ columns: [], rows: [] });
                return;
            }
            var meta = 'File: ' + (fileLabel || '—')
                + ' | Tabel: `' + (res.table || table) + '`'
                + ' | Total: ' + (res.total || 0) + ' baris';
            if (res.truncated) {
                meta += ' (ditampilkan ' + (res.shown || 0) + ' baris pertama)';
            }
            $('#compare-csv-preview-meta').text(meta);
            $('#modalCompareCsvPreviewLabel').text('Data Tabel — ' + (res.table || table));
            renderCompareCsvPreviewTable(res);
        })
        .catch(function(err) {
            $('#compare-csv-preview-loading').addClass('d-none');
            $('.compare-csv-preview-dt-wrap').removeClass('d-none');
            $('#compare-csv-preview-meta').text(err && err.message ? err.message : 'Gagal memuat preview tabel.');
            renderCompareCsvPreviewTable({ columns: [], rows: [] });
        });
    }

    function importCompareCsvFile(file) {
        if (!file) return;
        if (!userCanComparePersediaan) {
            Swal.fire({
                icon: 'warning',
                title: 'Akses ditolak',
                text: 'Import CSV hanya untuk admin.id@gmail.com dan iwanesia.id@gmail.com.'
            });
            return;
        }

        var ext = (file.name || '').split('.').pop().toLowerCase();
        if (ext !== 'csv') {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'File harus berformat .csv' });
            return;
        }

        var formData = new FormData();
        formData.append('csv_file', file);
        formData.append('bulan_num', $('#compare_bulan_persediaan').val() || '');
        formData.append('tahun', $('#compare_tahun_persediaan').val() || '');

        Swal.fire({
            title: 'Memproses CSV...',
            html: 'Membuat tabel baru, menyesuaikan kolom, dan meng-upload data.',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: function() { Swal.showLoading(); }
        });

        fetch(urlCompareImportCsv, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(parseJsonFetchResponse)
        .then(function(res) {
            Swal.close();
            if (!res || !res.ok) {
                showCompareCsvAlertMessage(res, 'error', 'Import CSV gagal');
                return;
            }

            compareTablesLoaded = false;
            loadCompareTableList(true, res.table || '');
            updateCompareInfoRingkas({ table: res.table || '' });
            updateCompareCsvUploadInfo({
                file: res.file || (file && file.name ? file.name : ''),
                table: res.table || '',
                rows: res.rows || 0
            });
            setCompareStatus('success', escapeHtmlCompare(res.message || 'CSV berhasil disimpan.'));

            showCompareCsvAlertMessage(res, 'success', 'Import CSV berhasil');

            $('#compare_csv_file').val('').next('.custom-file-label').text('Cari / pilih file CSV...');
        })
        .catch(function(err) {
            Swal.close();
            showCompareCsvAlertMessage({
                message: err && err.message ? err.message : 'Import CSV gagal. Periksa koneksi atau refresh halaman.'
            }, 'error', 'Import CSV gagal');
        });
    }

    function insertCompareTableToPersediaan(tableOrOpts, maybeOpts) {
        if (!userCanComparePersediaan) {
            Swal.fire({
                icon: 'warning',
                title: 'Akses ditolak',
                text: 'Insert ke persediaan hanya untuk admin.id@gmail.com dan iwanesia.id@gmail.com.'
            });
            return;
        }

        var table = '';
        var opts = {};
        if (typeof tableOrOpts === 'string') {
            table = tableOrOpts;
            opts = maybeOpts || {};
        } else {
            opts = tableOrOpts || {};
            if (compareCsvLastUpload && compareCsvLastUpload.table) {
                table = compareCsvLastUpload.table;
            }
        }
        table = (table || '').trim();

        if (!table) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih atau upload tabel terlebih dahulu.' });
            return;
        }

        var bulanKey = getBulanKeyCompare();
        Swal.fire({
            icon: 'warning',
            title: 'Konfirmasi Insert',
            html: 'Data akan di insert dan akan ada pengaruh jumlah stock akan berubah,<br/>'
                + 'pastikan bahwa data ini benar-benar akan di insert ke tabel persediaan?<br/><br/>'
                + 'Tabel sumber: <strong>' + escapeHtmlCompare(table) + '</strong>',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ff5252'
        }).then(function(cf) {
            if (!cf.isConfirmed) return;

            var formData = new FormData();
            formData.append('tabel', table);
            if (bulanKey) {
                formData.append('bulan', bulanKey);
            }
            formData.append('bulan_num', $('#compare_bulan_persediaan').val() || '');
            formData.append('tahun', $('#compare_tahun_persediaan').val() || '');

            Swal.fire({
                title: 'Insert ke persediaan...',
                html: 'Memproses data dari tabel `' + escapeHtmlCompare(table) + '`.',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: function() { Swal.showLoading(); }
            });

            fetch(urlCompareInsertToPersediaan, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(parseJsonFetchResponse)
            .then(function(res) {
                Swal.close();
                if (!res || !res.ok) {
                    showCompareCsvAlertMessage(res, 'error', 'Insert ke persediaan gagal');
                    return;
                }
                Swal.fire({
                    icon: 'success',
                    title: 'Insert ke persediaan berhasil',
                    html: (res && res.message) ? escapeHtmlCompare(res.message) : 'Data berhasil diinsert ke tabel persediaan.',
                    confirmButtonText: 'OK'
                });
                refreshPersediaanAfterCompareInsert();
            })
            .catch(function(err) {
                Swal.close();
                showCompareCsvAlertMessage({
                    message: err && err.message ? err.message : 'Insert ke persediaan gagal. Periksa koneksi atau refresh halaman.'
                }, 'error', 'Insert ke persediaan gagal');
            });
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
        if (jenis === 'pembelian_tidak_manual') {
            return items.map(function(it, i) {
                return [
                    i + 1,
                    it.tgl_po || '',
                    it.uraian || '',
                    it.spop || '',
                    it.satuan || '',
                    it.harga_satuan || '',
                    it.jumlah || '',
                    it.keterangan || ''
                ];
            });
        }
        if (jenis === 'penjualan_tidak_manual') {
            return items.map(function(it, i) {
                return [
                    i + 1,
                    it.tgl_jual || '',
                    it.nama_barang || '',
                    it.spop || '',
                    it.satuan || '',
                    it.harga_satuan || '',
                    it.jumlah || '',
                    it.keterangan || ''
                ];
            });
        }
        if (jenis === 'produksi_tidak_manual') {
            return items.map(function(it, i) {
                return [
                    i + 1,
                    it.nama_barang_bahan || '',
                    it.satuan_bahan || '',
                    it.harga_satuan_bahan || '',
                    it.spop || '',
                    it.jumlah_bahan || '',
                    it.tgl_transaksi || ''
                ];
            });
        }
        if (jenis === 'pecah_tidak_manual') {
            return items.map(function(it, i) {
                return [
                    i + 1,
                    it.uraian || '',
                    it.satuan || '',
                    it.harga_satuan || '',
                    it.spop || '',
                    it.jumlah || ''
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
                it.p_sa || '',
                it.p_beli || '',
                it.p_total_10 || '',
                it.c_namabarang || '',
                it.c_satuan || '',
                it.c_hpp || '',
                it.c_spop || '',
                it.c_sa || '',
                it.c_beli || '',
                it.c_total_10 || ''
            ];
        });
    }

    function upsertCompareDataTable(selector, rows, orderCol) {
        var $sel = $(selector);
        ensureCompareTableTfoot($sel);
        var footerCb = buildCompareFooterCallback(selector);
        if ($.fn.DataTable.isDataTable(selector)) {
            var dt = $sel.DataTable();
            dt.clear();
            if (rows.length) dt.rows.add(rows);
            dt.draw(false);
            compareDtStore[selector] = dt;
            layoutCompareDataTableBox($sel);
            applyScrollYToDataTable(dt, $sel, 130);
            return dt;
        }
        compareDtStore[selector] = $sel.DataTable({
            data: rows,
            scrollY: getDataTableScrollY($sel, 130),
            scrollX: true,
            scrollCollapse: true,
            paging: true,
            pageLength: 25,
            order: orderCol !== undefined ? [[orderCol, 'asc']] : [],
            columnDefs: [{ targets: 0, orderable: false }],
            language: compareDtLang,
            autoWidth: false,
            deferRender: true,
            footerCallback: footerCb
        });
        layoutCompareDataTableBox($sel);
        applyScrollYToDataTable(compareDtStore[selector], $sel, 130);
        return compareDtStore[selector];
    }

    function renderCompareAllTables(res) {
        res = res || {};
        upsertCompareDataTable('#table-compare-total10', buildCompareRows('total10_kosong', res.items_total10_kosong || []), 1);
        upsertCompareDataTable('#table-compare-tidak', buildCompareRows('tidak_di_tabel', res.items_tidak_di_tabel || []), 1);
        upsertCompareDataTable('#table-compare-hanya', buildCompareRows('hanya_tabel', res.items_hanya_tabel || []), 6);
        upsertCompareDataTable('#table-compare-cocok', buildCompareRows('cocok', res.items_cocok || []), 1);
        upsertCompareDataTable('#table-compare-pembelian-tidak', buildCompareRows('pembelian_tidak_manual', res.items_pembelian_tidak_manual || []), 2);
        upsertCompareDataTable('#table-compare-penjualan-tidak', buildCompareRows('penjualan_tidak_manual', res.items_penjualan_tidak_manual || []), 2);
        upsertCompareDataTable('#table-compare-produksi-tidak', buildCompareRows('produksi_tidak_manual', res.items_produksi_tidak_manual || []), 1);
        upsertCompareDataTable('#table-compare-pecah-tidak', buildCompareRows('pecah_tidak_manual', res.items_pecah_tidak_manual || []), 1);
        setTimeout(function() {
            adjustAllPersediaanDataTableAreas();
        }, 200);
    }

    function updateTombolComparePersediaan() {
        var $btn = $('#btn-compare-tabel');
        var $btnExcelAll = $('#btn-compare-excel-all');
        if (!userCanComparePersediaan) {
            $btn.prop('disabled', true).removeClass('btn-info').addClass('btn-secondary');
            $btnExcelAll.prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
            setCompareStatus('warning', 'Compare hanya untuk <strong>admin.id@gmail.com</strong> dan <strong>iwanesia.id@gmail.com</strong>.');
            return;
        }
        $btn.prop('disabled', false).removeClass('btn-secondary').addClass('btn-info');
        $btnExcelAll.prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
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
                + 'Cocok: <strong>' + (s.cocok || 0) + '</strong>, '
                + 'Pembelian tidak di manual: <strong>' + (s.pembelian_tidak_manual || 0) + '</strong>, '
                + 'Penjualan tidak di manual: <strong>' + (s.penjualan_tidak_manual || 0) + '</strong>, '
                + 'Produksi tidak di manual: <strong>' + (s.produksi_tidak_manual || 0) + '</strong>, '
                + 'Pecah satuan tidak di manual: <strong>' + (s.pecah_tidak_manual || 0) + '</strong>.');
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
        loadCompareTableList(false, getSavedPersediaanCompareTabel() || null, function() {
            updateCompareInfoRingkas({
                bulan_label: getBulanKeyCompare(),
                table: $('#compare_tabel_pilihan').val() || getSavedPersediaanCompareTabel() || ''
            });
        });
        setTimeout(adjustAllPersediaanDataTableAreas, 150);
    });

    $('#compare_db_tabel_cek').on('change', function() {
        var tbl = ($(this).val() || '').trim();
        if (tbl) {
            $('#compare_tabel_pilihan').val(tbl);
        }
        savePersediaanCompareTabel(tbl);
        updateCompareDbTabelInfoBox();
        updateCompareInfoRingkas({ bulan_label: getBulanKeyCompare(), table: tbl });
    });

    $('#btn-compare-db-cek-data').on('click', function() {
        if (!userCanComparePersediaan || $(this).prop('disabled')) {
            return false;
        }
        var tbl = ($('#compare_db_tabel_cek').val() || '').trim();
        if (!tbl) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih tabel database terlebih dahulu.' });
            return;
        }
        openCompareCsvPreviewModal(tbl, 'Tabel database: ' + tbl);
    });

    $('#btn-compare-db-insert-persediaan').on('click', function() {
        if (!userCanComparePersediaan || $(this).prop('disabled')) {
            return false;
        }
        var tbl = ($('#compare_db_tabel_cek').val() || '').trim();
        if (!tbl) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih tabel database terlebih dahulu.' });
            return;
        }
        insertCompareTableToPersediaan(tbl);
    });

    $('#compare_csv_file').on('change', function() {
        var file = (this.files && this.files[0]) ? this.files[0] : null;
        var label = file ? file.name : 'Cari / pilih file CSV...';
        $(this).next('.custom-file-label').text(label);
        if (file) {
            importCompareCsvFile(file);
        }
    });

    $('#btn-compare-csv-lihat').on('click', function() {
        if (!compareCsvLastUpload || !compareCsvLastUpload.table) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Upload CSV terlebih dahulu.' });
            return;
        }
        openCompareCsvPreviewModal(compareCsvLastUpload.table, compareCsvLastUpload.file);
    });

    $('#btn-compare-csv-insert-persediaan').on('click', function() {
        insertCompareTableToPersediaan();
    });

    $('#modal-compare-csv-preview').on('hidden.bs.modal', function() {
        var $table = $('#table-compare-csv-preview');
        if ($.fn.DataTable.isDataTable($table)) {
            $table.DataTable().destroy();
            $table.find('thead tr').empty();
            $table.find('tbody').empty();
        }
        compareCsvPreviewDt = null;
    });

    $('#compare_bulan_persediaan, #compare_tahun_persediaan, #compare_tabel_pilihan').on('change', function() {
        if ($(this).attr('id') === 'compare_tabel_pilihan') {
            var tbl = ($(this).val() || '').trim();
            if (tbl) {
                $('#compare_db_tabel_cek').val(tbl);
            }
            savePersediaanCompareTabel(tbl);
            updateCompareDbTabelInfoBox();
        } else {
            savePersediaanCompareBulanTahun();
        }
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

    function exportCompareExcelAll() {
        if (!userCanComparePersediaan) {
            Swal.fire({
                icon: 'warning',
                title: 'Akses ditolak',
                text: 'Export Excel ALL hanya untuk admin.id@gmail.com dan iwanesia.id@gmail.com.'
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

        tampilkanSwalExcelProgress();
        fetch(urlCompareTabelExcelAll, {
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
                text: 'File Excel ALL berhasil diunduh.',
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
                text: err && err.message ? err.message : 'Export Excel ALL gagal.'
            });
        });
    }

    $('#btn-compare-excel-all').on('click', function() {
        if (!userCanComparePersediaan || $(this).prop('disabled')) {
            return false;
        }
        exportCompareExcelAll();
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

    $('#form-persediaan-bulan').on('submit', function() {
        saveCurrentPersediaanTabs();
        savePersediaanBulanData($('#bulan_persediaan').val());
    });

    $('#bulan_persediaan').on('change', function() {
        savePersediaanBulanData($(this).val());
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

    var dtPersediaanStore = {};
    var dtPersediaanFcStore = {};

    function relayoutPersediaanTabFixedColumns(dt) {
        if (!dt) {
            return;
        }
        try {
            if (dt.fixedColumns && typeof dt.fixedColumns === 'function') {
                var fcApi = dt.fixedColumns();
                if (fcApi && typeof fcApi.relayout === 'function') {
                    fcApi.relayout();
                    return;
                }
            }
            var settings = dt.settings ? dt.settings()[0] : null;
            if (settings && settings._oFixedColumns && typeof settings._oFixedColumns.fnRedrawLayout === 'function') {
                settings._oFixedColumns.fnRedrawLayout();
            }
        } catch (eFc) {
            /* abaikan */
        }
    }

    function initPersediaanTabDataTable(selector) {
        var $sel = $(selector);
        if (!$sel.length) {
            return null;
        }
        if ($.fn.DataTable.isDataTable(selector)) {
            $sel.DataTable().destroy();
        }
        dtPersediaanFcStore[selector] = null;
        var moneyCols = [];
        try {
            moneyCols = JSON.parse($sel.attr('data-money-cols') || '[]');
        } catch (eMoney) {
            moneyCols = [];
        }
        var orderCol = parseInt($sel.attr('data-order-col') || '2', 10);
        if (isNaN(orderCol) || orderCol < 0) {
            orderCol = 2;
        }
        var fixedLeft = parseInt($sel.attr('data-fixed-left') || '8', 10);
        if (isNaN(fixedLeft) || fixedLeft < 0) {
            fixedLeft = 8;
        }
        var columnDefs = [
            { targets: 0, orderable: false },
            { targets: orderCol, type: 'string' }
        ];
        if (moneyCols.length) {
            columnDefs.push({
                targets: moneyCols,
                className: 'text-right persediaan-col-money'
            });
        }
        var dtOpts = {
            scrollY: getDataTableScrollY($sel),
            scrollX: true,
            scrollCollapse: true,
            pageLength: 25,
            lengthMenu: [[25, 50, 100, 250, -1], [25, 50, 100, 250, 'Semua']],
            order: [[orderCol, 'asc']],
            columnDefs: columnDefs,
            language: {
                lengthMenu: 'Tampilkan _MENU_ baris',
                info: 'Menampilkan _START_–_END_ dari _TOTAL_ baris',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '(difilter dari _MAX_ total baris)',
                zeroRecords: 'Tidak ada data persediaan untuk bulan ini'
            }
        };
        var dt = $sel.DataTable(dtOpts);
        if (fixedLeft > 0 && $.fn.dataTable && $.fn.dataTable.FixedColumns) {
            try {
                dtPersediaanFcStore[selector] = new $.fn.dataTable.FixedColumns(dt, {
                    leftColumns: fixedLeft
                });
            } catch (eInitFc) {
                console.warn('FixedColumns persediaan:', eInitFc);
            }
        }
        dtPersediaanStore[selector] = dt;
        applyScrollYToDataTable(dt, $sel);
        setTimeout(function() {
            dt.columns.adjust();
            relayoutPersediaanTabFixedColumns(dt);
            if (selector === '#table-persediaan-jasa') {
                applyPersediaanJasaSavedSearch();
            }
        }, 100);
        return dt;
    }

    function adjustPersediaanTabDataTables() {
        Object.keys(dtPersediaanStore).forEach(function(sel) {
            var dt = dtPersediaanStore[sel];
            applyScrollYToDataTable(dt, $(sel));
            if (dt && dt.columns) {
                dt.columns.adjust();
            }
            relayoutPersediaanTabFixedColumns(dt);
        });
    }

    try {
        initPersediaanTabDataTable('#table-persediaan-barang');
        initPersediaanTabDataTable('#table-persediaan-jasa');
        setTimeout(adjustAllPersediaanDataTableAreas, 300);
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
     * Bersihkan sisa DataTable rekap (versi lama) tanpa menyentuh tabel persediaan tab Barang/Jasa.
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
        setTimeout(adjustRekapScrollArea, 50);
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
        savePersediaanMainTabKey('rekap');
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
                savePersediaanMainTabKey('rekap');
                savePersediaanBulanRekap(getBulanRekapAktif());
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
        var bulanKey = getBulanRekonsiliasiFromGenTab();
        if (!bulanKey) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih bulan dan tahun target di tab Generate & Recalculate terlebih dahulu.' });
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

    $(document).on('click', '.btn-gen-recalc-summary-excel', function() {
        exportGenRecalcSummaryExcel($(this).data('jenis') || '');
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

    function exportPersediaanTabExcel(filterKategori) {
        if (!urlExcelPersediaan) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'URL export Excel tidak tersedia.' });
            return;
        }
        var bulan = $('#bulan_persediaan').val() || '';
        var formData = new FormData();
        formData.append('bulan_persediaan', bulan);
        formData.append('filter_kategori', filterKategori || 'barang');

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
    }

    $(document).on('click', '.btn-cetak-excel-persediaan-tab', function(e) {
        e.preventDefault();
        exportPersediaanTabExcel($(this).data('filter') || 'barang');
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

    // Tab Rekap: ubah bulan hanya memengaruhi rekap (tidak mengubah datepicker tab Persediaan)
    $('#bulan_rekap').on('change', function() {
        savePersediaanBulanRekap($(this).val());
        if (!$(this).val() || rekapRecalcRunning) {
            return;
        }
        runRekapRecalcWithSwal({ showTabAfter: false, submitFormAfter: false });
    });

    $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
        var href = $(e.target).attr('href') || '';
        if ($(e.target).closest('#persediaan-tabs').length) {
            savePersediaanMainTabKey(persediaanMainTabKeyFromHref(href));
        } else if ($(e.target).closest('#persediaan-data-subtabs').length) {
            savePersediaanDataSubTabKey(persediaanDataSubTabKeyFromHref(href));
        }

        if (href === '#panel-rekap') {
            if (!rekapRecalcRunning && !rekapLoading && !rekapSkipNextPanelLoad) {
                loadRekapDataOnly();
            }
            setTimeout(adjustRekapScrollArea, 150);
        } else if (href === '#panel-generate-persediaan') {
            cekGeneratePersediaanBulan();
            initGenRecalcTableTemplateCache();
            restoreAllGenRecalcTableShells();
            setTimeout(function() {
                var bulanKey = getBulanTargetGenerate();
                loadGenRecalcHistoryFromServer(bulanKey);
                loadGenRecalcSummaryTablesFromServer(bulanKey);
                loadHistoryGenerateList(bulanKey);
                adjustAllPersediaanDataTableAreas();
            }, 150);
        } else if (href === '#panel-compare-manual') {
            updateTombolComparePersediaan();
            loadCompareTableList(false, getSavedPersediaanCompareTabel() || null, function() {
                updateCompareInfoRingkas({
                    bulan_label: getBulanKeyCompare(),
                    table: $('#compare_tabel_pilihan').val() || getSavedPersediaanCompareTabel() || ''
                });
            });
            setTimeout(adjustAllPersediaanDataTableAreas, 150);
        } else if (href === '#panel-data-persediaan') {
            activatePersediaanDataSubTabByKey(getSavedPersediaanDataSubTabKey());
            setTimeout(adjustAllPersediaanDataTableAreas, 150);
        }
    });

    $('#persediaan-data-subtabs a[data-toggle="pill"]').on('click', function() {
        persistPersediaanDataSubTabFromClick($(this));
    }).on('shown.bs.tab', function() {
        persistPersediaanDataSubTabFromClick($(this));
        setTimeout(adjustAllPersediaanDataTableAreas, 150);
    });

    setTimeout(function() {
        applySavedFilterControlsFromStorage();
        if (typeof updateInfoBulanTambahPersediaan === 'function') {
            updateInfoBulanTambahPersediaan();
        }
        restorePersediaanTabsFromStorage();
        setTimeout(function() {
            refreshPersediaanTabDataAfterFilterRestore();
        }, 150);
    }, 300);

    if (userCanGeneratePersediaan) {
        setTimeout(function() {
            if ($('#panel-generate-persediaan').hasClass('active') || $('#panel-generate-persediaan').hasClass('show')) {
                return;
            }
            var bulanKey = getBulanTargetGenerate();
            loadGenRecalcHistoryFromServer(bulanKey);
            loadGenRecalcSummaryTablesFromServer(bulanKey);
            loadHistoryGenerateList(bulanKey);
        }, 400);
    } else {
        updateTombolGeneratePersediaan('denied');
        setStatusGeneratePersediaan('warning', 'Generate &amp; Recalculate hanya untuk <strong>admin.id@gmail.com</strong> dan <strong>iwanesia.id@gmail.com</strong>.');
    }

    updateTombolComparePersediaan();

    setTimeout(function() {
        var mainKey = persediaanMainTabKeyFromHref($('#persediaan-tabs .nav-link.active').attr('href') || '');
        if (mainKey !== 'compare' && ($('#panel-compare-manual').hasClass('active') || $('#panel-compare-manual').hasClass('show'))) {
            var savedTabel = getSavedPersediaanCompareTabel();
            loadCompareTableList(false, savedTabel || null, function() {
                updateCompareInfoRingkas({
                    bulan_label: getBulanKeyCompare(),
                    table: $('#compare_tabel_pilihan').val() || savedTabel || ''
                });
            });
        }
        if (mainKey !== 'data') {
            adjustAllPersediaanDataTableAreas();
        }
    }, 500);
});
</script>
