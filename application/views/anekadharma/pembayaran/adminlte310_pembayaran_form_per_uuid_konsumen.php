<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <div class="tagihan-help-hotspot" tabindex="0" aria-describedby="tagihanHelpBalloon">
                        <h1 class="m-0 tagihan-page-title">
                            Tagihan &amp; Pembayaran
                            <i class="fa fa-info-circle tagihan-help-icon" aria-hidden="true"></i>
                        </h1>
                        <p class="tagihan-page-sub mb-0">
                            <strong><?php echo htmlspecialchars($nama_konsumen, ENT_QUOTES, 'UTF-8'); ?></strong>
                            <?php if (!empty($alamat_konsumen)) { ?>
                                <span class="text-muted"> — <?php echo htmlspecialchars($alamat_konsumen, ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php } ?>
                            <br>
                            <span class="text-muted" style="font-size:0.88rem;">
                                Bayar dari data penjualan (Tab 1) atau input nominal langsung (Tab 2). Lihat riwayat di Tab 3.
                            </span>
                        </p>
                        <div class="tagihan-help-balloon" id="tagihanHelpBalloon" role="tooltip">
                            <div class="tagihan-help-balloon-title"><i class="fa fa-credit-card"></i> Petunjuk halaman pembayaran</div>
                            <div class="tagihan-help-balloon-text">
                                Halaman ini untuk mencatat pembayaran konsumen dengan <strong>2 cara</strong>:
                                <strong>1)</strong> Berdasarkan data penjualan
                                (klik <span class="badge badge-warning">Pilih Bayar</span> di tabel tagihan → Tab 1),
                                atau
                                <strong>2)</strong> Input nominal langsung tanpa memilih penjualan (Tab 2).
                                Riwayat pembayaran ada di Tab 3.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="tagihan-help-hotspot tagihan-help-hotspot-breadcrumb float-sm-right" tabindex="0" aria-describedby="tagihanHelpBalloonBreadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?php echo site_url('tbl_pembelian/pembayaran_dari_konsumen'); ?>">Daftar Tagihan</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                        <div class="tagihan-help-balloon tagihan-help-balloon-right" id="tagihanHelpBalloonBreadcrumb" role="tooltip">
                            <div class="tagihan-help-balloon-title"><i class="fa fa-info-circle"></i> Petunjuk halaman</div>
                            <div class="tagihan-help-balloon-text">
                                Halaman ini untuk mencatat pembayaran konsumen dengan <strong>2 cara</strong>:
                                <strong>1)</strong> Berdasarkan data penjualan
                                (klik <span class="badge badge-warning">Pilih Bayar</span> di tabel tagihan → Tab 1),
                                atau
                                <strong>2)</strong> Input nominal langsung tanpa memilih penjualan (Tab 2).
                                Riwayat pembayaran ada di Tab 3.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid tagihan-page-wrap">

            <?php
            $count_proses_bayar = !empty($Data_konsumen_proses_bayar) ? count($Data_konsumen_proses_bayar) : 0;
            $count_riwayat_bayar = !empty($Data_konsumen_pembayaran) ? count($Data_konsumen_pembayaran) : 0;
            ?>

            <!-- 1. DATA TAGIHAN (atas) -->
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card card-outline card-danger tagihan-card">
                        <div class="card-header tagihan-card-header d-flex flex-wrap align-items-start justify-content-between">
                            <div>
                                <h3 class="card-title mb-1">
                                    <i class="fa fa-file-text-o"></i>
                                    DATA TAGIHAN DAN FORM PEMBAYARAN:
                                    <span class="tagihan-konsumen-name"><?php echo htmlspecialchars($nama_konsumen . (!empty($alamat_konsumen) ? ', ' . $alamat_konsumen : ''), ENT_QUOTES, 'UTF-8'); ?></span>
                                </h3>
                                <p class="tagihan-pay-desc mb-0">
                                    Daftar tagihan penjualan yang belum dibayar.
                                    Klik <span class="badge badge-warning">Pilih Bayar</span> untuk bayar per dokumen penjualan (masuk Tab 1),
                                    atau <span class="badge badge-danger">Edit Penjualan</span> untuk ubah/tambah barang.
                                </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive tagihan-table-wrap tagihan-dt-yellow-border">
                                <table id="tglSPOPFreeze" class="display nowrap table table-sm table-bordered table-hover tagihan-dt" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center" width="10px">No</th>
                                            <th style="text-align:center" width="170px">Action</th>
                                            <th style="text-align:center">Tgl Jual</th>
                                            <th style="text-align:center">No Pesan</th>
                                            <th style="text-align:center">No Kirim</th>
                                            <th style="text-align:center">Kode</th>
                                            <th style="text-align:center">Nama Barang</th>
                                            <th style="text-align:center">Jumlah</th>
                                            <th style="text-align:center">Satuan</th>
                                            <th style="text-align:center">Harga Satuan</th>
                                            <th style="text-align:center">Total Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $start = 0;
                                        $TOTAL_NOMINAL_TAGIHAN_ALL = 0;

                                        foreach ($Data_konsumen_tagihan as $list_data) {
                                            $total_nominal_row = (float) $list_data->jumlah * (float) $list_data->harga_satuan;
                                            $TOTAL_NOMINAL_TAGIHAN_ALL += $total_nominal_row;
                                            $pesan_row = trim((string) (isset($list_data->nmrpesan) ? $list_data->nmrpesan : ''));
                                            $kirim_row = trim((string) (isset($list_data->nmrkirim) ? $list_data->nmrkirim : ''));
                                        ?>
                                            <tr data-uuid-penjualan="<?php echo htmlspecialchars($list_data->uuid_penjualan, ENT_QUOTES, 'UTF-8'); ?>"
                                                data-nmrpesan="<?php echo htmlspecialchars($pesan_row, ENT_QUOTES, 'UTF-8'); ?>"
                                                data-nmrkirim="<?php echo htmlspecialchars($kirim_row, ENT_QUOTES, 'UTF-8'); ?>"
                                                data-total-line="<?php echo htmlspecialchars((string) $total_nominal_row, ENT_QUOTES, 'UTF-8'); ?>"
                                                data-total-group="<?php echo htmlspecialchars((string) $total_nominal_row, ENT_QUOTES, 'UTF-8'); ?>">
                                                <td><?php echo ++$start ?></td>
                                                <td class="tagihan-action-cell" align="center">
                                                    <div class="tagihan-action-inline">
                                                        <?php
                                                        echo anchor(
                                                            site_url('tbl_pembelian/pilih_proses_bayar_pertransaksi/' . $list_data->uuid_konsumen . '/' . $list_data->uuid_penjualan_proses),
                                                            '<i class="fa fa-check-square-o"></i> Pilih Bayar',
                                                            'class="btn btn-warning btn-xs"'
                                                        );
                                                        ?>
                                                        <button type="button"
                                                            class="btn btn-danger btn-xs btn-edit-penjualan-tagihan"
                                                            data-uuid-penjualan="<?php echo htmlspecialchars($list_data->uuid_penjualan, ENT_QUOTES, 'UTF-8'); ?>"
                                                            data-uuid-penjualan-proses="<?php echo htmlspecialchars($list_data->uuid_penjualan_proses, ENT_QUOTES, 'UTF-8'); ?>"
                                                            data-total-nominal="<?php echo htmlspecialchars((string) $total_nominal_row, ENT_QUOTES, 'UTF-8'); ?>"
                                                            data-nmrpesan="<?php echo htmlspecialchars($pesan_row, ENT_QUOTES, 'UTF-8'); ?>"
                                                            data-nmrkirim="<?php echo htmlspecialchars($kirim_row, ENT_QUOTES, 'UTF-8'); ?>"
                                                            data-nama-barang="<?php echo htmlspecialchars($list_data->nama_barang, ENT_QUOTES, 'UTF-8'); ?>"
                                                            onclick="if(window.openEditPenjualanTagihan){window.openEditPenjualanTagihan(this);} return false;">
                                                            <i class="fa fa-pencil"></i> Edit Penjualan
                                                        </button>
                                                    </div>
                                                </td>
                                                <td><?php echo date("d M Y", strtotime($list_data->tgl_jual)); ?></td>
                                                <td><?php echo htmlspecialchars($list_data->nmrpesan, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data->nmrkirim, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data->kode_barang, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data->nama_barang, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td style="text-align:right"><?php echo nominal($list_data->jumlah); ?></td>
                                                <td><?php echo htmlspecialchars($list_data->satuan, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td style="text-align:right"><?php echo nominal($list_data->harga_satuan); ?></td>
                                                <td style="text-align:right" class="td-total-group-tagihan">
                                                    <strong class="text-danger"><?php echo nominal($total_nominal_row); ?></strong>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th style="text-align:right">TOTAL TAGIHAN</th>
                                            <th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                                            <th style="text-align:right"><?php echo nominal($TOTAL_NOMINAL_TAGIHAN_ALL); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Tab area pembayaran -->
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card tagihan-card tagihan-tabs-card">
                        <div class="card-header tagihan-tabs-card-header">
                            <div class="tagihan-tabs-heading">
                                <strong><i class="fa fa-folder-open-o"></i> Area Proses Pembayaran</strong>
                                <span class="text-muted">— pilih salah satu tab di bawah</span>
                            </div>
                            <ul class="nav nav-tabs tagihan-pay-tabs" id="tagihanPayTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab-bayar-transaksi-link" data-toggle="tab" href="#tab-bayar-transaksi" role="tab" aria-controls="tab-bayar-transaksi" aria-selected="true">
                                        <span class="tagihan-tab-num">1</span>
                                        <span class="tagihan-tab-copy">
                                            <span class="tagihan-tab-title">Form Bayar per Transaksi Penjualan</span>
                                            <span class="tagihan-tab-sub">Bayar dari data penjualan yang dipilih</span>
                                        </span>
                                        <?php if ($count_proses_bayar > 0) { ?>
                                            <span class="badge badge-success tagihan-tab-count"><?php echo (int) $count_proses_bayar; ?></span>
                                        <?php } ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-bayar-nominal-link" data-toggle="tab" href="#tab-bayar-nominal" role="tab" aria-controls="tab-bayar-nominal" aria-selected="false">
                                        <span class="tagihan-tab-num">2</span>
                                        <span class="tagihan-tab-copy">
                                            <span class="tagihan-tab-title">Form Pembayaran Total Nominal</span>
                                            <span class="tagihan-tab-sub">Input nominal langsung tanpa pilih penjualan</span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-riwayat-bayar-link" data-toggle="tab" href="#tab-riwayat-bayar" role="tab" aria-controls="tab-riwayat-bayar" aria-selected="false">
                                        <span class="tagihan-tab-num">3</span>
                                        <span class="tagihan-tab-copy">
                                            <span class="tagihan-tab-title">DATA RIWAYAT PEMBAYARAN</span>
                                            <span class="tagihan-tab-sub">Histori pembayaran yang sudah tercatat</span>
                                        </span>
                                        <?php if ($count_riwayat_bayar > 0) { ?>
                                            <span class="badge badge-info tagihan-tab-count"><?php echo (int) $count_riwayat_bayar; ?></span>
                                        <?php } ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body tagihan-tabs-body">
                            <div class="tab-content" id="tagihanPayTabsContent">

                                <!-- TAB 1 -->
                                <div class="tab-pane fade show active" id="tab-bayar-transaksi" role="tabpanel" aria-labelledby="tab-bayar-transaksi-link">
                                    <div class="tagihan-tab-pane-inner tagihan-tab-pane-success">
                                        <div class="tagihan-tab-pane-intro">
                                            <span class="tagihan-step-badge tagihan-step-badge-success">Tab 1</span>
                                            <strong>Bayar berdasarkan data penjualan</strong>
                                            <p class="mb-0 mt-1">
                                                Record di bawah muncul setelah Anda klik <span class="badge badge-warning">Pilih Bayar</span>
                                                pada tabel <strong>DATA TAGIHAN</strong> di atas (semua item No Pesan + No Kirim yang sama ikut terpilih).
                                            </p>
                                        </div>
                                        <form action="<?php echo $action_pertransaksi; ?>" method="post" class="tagihan-pay-form">
                                            <?php if (!empty($Data_konsumen_proses_bayar)) { ?>
                                                <div class="tagihan-selected-bar mb-3">
                                                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                                                        <div>
                                                            <strong><i class="fa fa-check-square-o text-success"></i> Penjualan dipilih</strong>
                                                            <span class="text-muted ml-1">— siap diproses pembayaran</span>
                                                        </div>
                                                        <div class="small text-muted">
                                                            Tip: tombol <strong>BATAL</strong> mengembalikan seluruh dokumen (No Pesan + No Kirim) ke daftar tagihan.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="table-responsive tagihan-table-wrap tagihan-dt-yellow-border">
                                                    <table id="tglSPOPFreeze2" class="display nowrap table table-sm table-bordered table-striped tagihan-dt" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="text-align:center" width="10px">No</th>
                                                                <th style="text-align:center" width="90px">Action</th>
                                                                <th style="text-align:center">Total</th>
                                                                <th style="text-align:center">Tgl Jual</th>
                                                                <th style="text-align:center">No Pesan</th>
                                                                <th style="text-align:center">No Kirim</th>
                                                                <th style="text-align:center">Kode</th>
                                                                <th style="text-align:center">Nama Barang</th>
                                                                <th style="text-align:center">Jumlah</th>
                                                                <th style="text-align:center">Satuan</th>
                                                                <th style="text-align:center">Harga</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $start = 0;
                                                            $TOTAL_NOMINAL_TRANSAKSI_PILIH = 0;
                                                            foreach ($Data_konsumen_proses_bayar as $list_data) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo ++$start ?></td>
                                                                    <td align="center">
                                                                        <?php
                                                                        echo anchor(
                                                                            site_url('tbl_pembelian/batal_proses_bayar_pertransaksi/' . $list_data->uuid_konsumen . '/' . $list_data->uuid_penjualan_proses),
                                                                            '<i class="fa fa-times"></i> BATAL',
                                                                            'class="btn btn-outline-warning btn-xs btn-block"'
                                                                        );
                                                                        ?>
                                                                    </td>
                                                                    <td align="right">
                                                                        <?php
                                                                        echo nominal($list_data->total_nominal);
                                                                        $TOTAL_NOMINAL_TRANSAKSI_PILIH = $TOTAL_NOMINAL_TRANSAKSI_PILIH + $list_data->total_nominal;
                                                                        ?>
                                                                    </td>
                                                                    <td><?php echo date("d M Y", strtotime($list_data->tgl_jual)); ?></td>
                                                                    <td><?php echo htmlspecialchars($list_data->nmrpesan, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                    <td><?php echo htmlspecialchars($list_data->nmrkirim, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                    <td><?php echo htmlspecialchars($list_data->kode_barang, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                    <td><?php echo htmlspecialchars($list_data->nama_barang, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                    <td align="right"><?php echo nominal($list_data->jumlah); ?></td>
                                                                    <td><?php echo htmlspecialchars($list_data->satuan, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                    <td align="right"><?php echo nominal($list_data->harga_satuan); ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th></th>
                                                                <th style="text-align:right">TOTAL</th>
                                                                <th style="text-align:right"><?php echo nominal($TOTAL_NOMINAL_TRANSAKSI_PILIH); ?></th>
                                                                <th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>

                                                <div class="tagihan-summary mt-3 mb-3">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-6">
                                                            <div class="mb-1">Accounting: <strong><?php echo number_format($GET_Data_konsumen_tagihan_accounting, 2, ',', '.'); ?></strong></div>
                                                            <div class="tagihan-total-label mb-0">
                                                                TOTAL YANG AKAN DIBAYAR:
                                                                <span class="tagihan-total-value"><?php echo number_format($GET_Data_konsumen_tagihan_accounting + $TOTAL_NOMINAL_TRANSAKSI_PILIH, 2, ',', '.'); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 text-md-right mt-2 mt-md-0">
                                                            <span class="badge badge-success p-2" style="font-size:0.85rem;">
                                                                <?php echo (int) $start; ?> baris dipilih
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tagihan-pay-fields-box">
                                                    <div class="tagihan-pay-fields-title mb-2">
                                                        <i class="fa fa-pencil-square-o text-success"></i> Lengkapi data pembayaran
                                                    </div>
                                                    <div class="row align-items-end">
                                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                                            <div class="form-group mb-2 mb-lg-0">
                                                                <label class="tagihan-field-label"><i class="fa fa-calendar text-success"></i> Tanggal Bayar</label>
                                                                <div class="input-group date" id="tanggal_bayar_input" data-target-input="nearest">
                                                                    <input type="text" class="form-control datetimepicker-input" data-target="#tanggal_bayar_input" id="tanggal_bayar_input" name="tanggal_bayar_input" placeholder="Pilih tanggal" required />
                                                                    <div class="input-group-append" data-target="#tanggal_bayar_input" data-toggle="datetimepicker">
                                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                                            <div class="form-group mb-2 mb-lg-0">
                                                                <label class="tagihan-field-label"><i class="fa fa-hashtag text-success"></i> Nomor Pembayaran</label>
                                                                <input type="text" class="form-control" name="nomor_bayar_input_per_transaksi" id="nomor_bayar_input_per_transaksi" placeholder="Contoh: BKM-001 / transfer" value="" />
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-8 col-sm-12">
                                                            <div class="form-group mb-2 mb-lg-0">
                                                                <label class="tagihan-field-label"><i class="fa fa-university text-success"></i> Kode Bank / Akun</label>
                                                                <select name="uuid_kode_akun" id="uuid_kode_akun" class="form-control select2" style="width: 100%;" required>
                                                                    <option value="">Pilih Kode Akun</option>
                                                                    <?php
                                                                    $sql = "select * from sys_kode_akun order by nama_akun ASC ";
                                                                    foreach ($this->db->query($sql)->result() as $m) {
                                                                        echo "<option value='$m->uuid_kode_akun'>" . strtoupper($m->kode_akun) . " - " . strtoupper($m->nama_akun) . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-4 col-sm-12">
                                                            <?php if ($start > 0) { ?>
                                                                <div class="form-group mb-0">
                                                                    <label class="d-none d-lg-block tagihan-field-label">&nbsp;</label>
                                                                    <button type="submit" class="btn btn-success btn-block tagihan-btn-simpan">
                                                                        <i class="fa fa-save"></i> <?php echo $button ?>
                                                                    </button>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div class="tagihan-empty-pay">
                                                    <div class="tagihan-empty-pay-icon"><i class="fa fa-hand-pointer-o"></i></div>
                                                    <div class="tagihan-empty-pay-title">Belum ada penjualan dipilih</div>
                                                    <div class="tagihan-empty-pay-text">
                                                        Di tabel <strong>DATA TAGIHAN</strong> di atas, klik tombol kuning
                                                        <span class="badge badge-warning">Pilih Bayar</span>
                                                        pada dokumen yang ingin dibayar.
                                                        Semua barang dengan No Pesan &amp; No Kirim yang sama akan masuk ke tab ini.
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>

                                <!-- TAB 2 -->
                                <div class="tab-pane fade" id="tab-bayar-nominal" role="tabpanel" aria-labelledby="tab-bayar-nominal-link">
                                    <div class="tagihan-tab-pane-inner tagihan-tab-pane-primary">
                                        <div class="tagihan-tab-pane-intro">
                                            <span class="tagihan-step-badge tagihan-step-badge-primary">Tab 2</span>
                                            <strong>Bayar dengan input nominal langsung</strong>
                                            <p class="mb-0 mt-1">
                                                Tidak perlu memilih baris penjualan. Isi tanggal, nomor bukti, kode bank, dan nominal bayar, lalu klik <strong>Simpan</strong>.
                                            </p>
                                        </div>
                                        <form action="<?php echo $action_nominal; ?>" method="post" class="tagihan-pay-form">
                                            <div class="row align-items-end">
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <div class="form-group mb-2 mb-lg-0">
                                                        <label class="tagihan-field-label"><i class="fa fa-calendar text-primary"></i> Tanggal Bayar</label>
                                                        <div class="input-group date" id="tanggal_bayar_input_nominal" data-target-input="nearest">
                                                            <input type="text" class="form-control datetimepicker-input" data-target="#tanggal_bayar_input_nominal" id="tanggal_bayar_input_nominal_field" name="tanggal_bayar_input" placeholder="Pilih tanggal" required />
                                                            <div class="input-group-append" data-target="#tanggal_bayar_input_nominal" data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <div class="form-group mb-2 mb-lg-0">
                                                        <label class="tagihan-field-label"><i class="fa fa-hashtag text-primary"></i> Nomor Pembayaran</label>
                                                        <input type="text" class="form-control" name="nomor_bayar_input" id="nomor_bayar_input" placeholder="Contoh: BKM-001 / transfer" value="" />
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <div class="form-group mb-2 mb-lg-0">
                                                        <label class="tagihan-field-label"><i class="fa fa-university text-primary"></i> Kode Bank / Akun</label>
                                                        <select name="uuid_kode_akun_nominal" id="uuid_kode_akun_nominal" class="form-control select2" style="width: 100%;" required>
                                                            <option value="">Pilih Kode Akun</option>
                                                            <?php
                                                            $sql = "select * from sys_kode_akun order by nama_akun ASC ";
                                                            foreach ($this->db->query($sql)->result() as $m) {
                                                                echo "<option value='$m->uuid_kode_akun'>" . strtoupper($m->kode_akun) . " - " . strtoupper($m->nama_akun) . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-4 col-sm-8">
                                                    <div class="form-group mb-2 mb-lg-0">
                                                        <label class="tagihan-field-label"><i class="fa fa-money text-danger"></i> Nominal Bayar <?php echo form_error('nominal_bayar_input') ?></label>
                                                        <input type="text" class="form-control uang tagihan-nominal-input" onkeyup="sum();" name="nominal_bayar_input" id="nominal_bayar_input" placeholder="0" value="" />
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-2 col-sm-4">
                                                    <div class="form-group mb-0">
                                                        <label class="d-none d-lg-block tagihan-field-label">&nbsp;</label>
                                                        <button type="submit" class="btn btn-primary btn-block tagihan-btn-simpan">
                                                            <i class="fa fa-save"></i> <?php echo $button ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tagihan-pay-note mt-3 mb-0">
                                                <i class="fa fa-info-circle"></i>
                                                Cocok untuk pembayaran sebagian / pelunasan yang tidak terikat satu per satu ke baris penjualan.
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- TAB 3 -->
                                <div class="tab-pane fade" id="tab-riwayat-bayar" role="tabpanel" aria-labelledby="tab-riwayat-bayar-link">
                                    <div class="tagihan-tab-pane-inner tagihan-tab-pane-info">
                                        <div class="tagihan-tab-pane-intro">
                                            <span class="tagihan-step-badge tagihan-step-badge-info">Tab 3</span>
                                            <strong>DATA RIWAYAT PEMBAYARAN</strong>
                                            <p class="mb-0 mt-1">
                                                Daftar pembayaran yang sudah tersimpan untuk
                                                <span class="tagihan-konsumen-name"><?php echo htmlspecialchars($nama_konsumen, ENT_QUOTES, 'UTF-8'); ?></span>.
                                            </p>
                                        </div>
                                        <div class="table-responsive tagihan-table-wrap tagihan-dt-yellow-border">
                                            <table id="tglSPOPFreeze1" class="display nowrap table table-sm table-bordered table-striped tagihan-dt" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:center" width="10px">No</th>
                                                        <th>Tgl Bayar</th>
                                                        <th>Nominal</th>
                                                        <th>No Bukti</th>
                                                        <th>Kode</th>
                                                        <th>Nama Barang</th>
                                                        <th>Jumlah</th>
                                                        <th>Satuan</th>
                                                        <th>Harga Satuan</th>
                                                        <th>Total Nominal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $start = 0;
                                                    $total_nominal_ALL = 0;
                                                    foreach ($Data_konsumen_pembayaran as $list_data) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo ++$start ?></td>
                                                            <td><?php echo date("d M Y", strtotime($list_data->tgl_bayar)); ?></td>
                                                            <td align="right">
                                                                <?php
                                                                echo nominal($list_data->nominal_bayar);
                                                                $total_nominal_ALL = $total_nominal_ALL + $list_data->nominal_bayar;
                                                                ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($list_data->nmr_bukti_bayar, ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td><?php echo htmlspecialchars($list_data->kode_barang, ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td><?php echo htmlspecialchars($list_data->nama_barang, ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td align="right"><?php echo nominal($list_data->jumlah); ?></td>
                                                            <td><?php echo htmlspecialchars($list_data->satuan, ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td align="right"><?php echo nominal($list_data->harga_satuan); ?></td>
                                                            <td align="right"><?php echo nominal($list_data->total_nominal); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th style="text-align:right">TOTAL NOMINAL</th>
                                                        <th style="text-align:right"><?php echo nominal($total_nominal_ALL); ?></th>
                                                        <th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
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

<!-- Modal Edit Penjualan: full width, 1cm gap atas-bawah -->
<div class="modal fade" id="modalEditPenjualanTagihan" tabindex="-1" role="dialog" aria-labelledby="modalEditPenjualanTagihanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-edit-penjualan-tagihan" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger modal-header-edit-penjualan-compact">
                <h4 class="modal-title" id="modalEditPenjualanTagihanLabel">
                    <i class="fa fa-pencil"></i> Edit Penjualan
                    <small id="modalEditPenjualanMeta" class="d-inline-block text-white-50 ml-2" style="font-size:0.78rem;"></small>
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modalEditPenjualanValidasi" class="mb-2"></div>
                <div class="row mb-2 modal-edit-penjualan-totals">
                    <div class="col-md-4">
                        <div class="info-box-sm">
                            <span class="label">Nominal Record Diklik</span>
                            <strong id="modalTotalRecord">—</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box-sm">
                            <span class="label">Total Detail Penjualan</span>
                            <strong id="modalTotalDetail">—</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box-sm">
                            <span class="label">Jumlah × Harga (hitung ulang)</span>
                            <strong id="modalTotalHitung">—</strong>
                        </div>
                    </div>
                </div>

                <div class="card card-outline card-secondary mb-3" id="panelFormPenjualanTagihan">
                    <div class="card-header py-2">
                        <strong id="judulFormPenjualanTagihan">Form Data Penjualan</strong>
                    </div>
                    <div class="card-body py-2">
                        <form id="formPenjualanTagihanCrud" autocomplete="off">
                            <input type="hidden" id="pj_id" name="id" value="">
                            <input type="hidden" id="pj_uuid_penjualan" name="uuid_penjualan" value="">
                            <input type="hidden" id="pj_mode" name="mode" value="update">
                            <div class="row">
                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group mb-2">
                                        <label>Tgl Jual</label>
                                        <input type="date" class="form-control form-control-sm" id="pj_tgl_jual" name="tgl_jual">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group mb-2">
                                        <label>No Pesan</label>
                                        <input type="text" class="form-control form-control-sm" id="pj_nmrpesan" name="nmrpesan">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group mb-2">
                                        <label>No Kirim</label>
                                        <input type="text" class="form-control form-control-sm" id="pj_nmrkirim" name="nmrkirim">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group mb-2">
                                        <label>Kode</label>
                                        <input type="text" class="form-control form-control-sm" id="pj_kode_barang" name="kode_barang">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label>Nama Barang / Jasa</label>
                                        <input type="text" class="form-control form-control-sm" id="pj_nama_barang" name="nama_barang" required>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4">
                                    <div class="form-group mb-2">
                                        <label>Jumlah</label>
                                        <input type="text" inputmode="decimal" class="form-control form-control-sm" id="pj_jumlah" name="jumlah" placeholder="contoh: 1 atau 1,5" required>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4">
                                    <div class="form-group mb-2">
                                        <label>Satuan</label>
                                        <input type="text" class="form-control form-control-sm" id="pj_satuan" name="satuan">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="form-group mb-2">
                                        <label>Harga Satuan</label>
                                        <input type="text" inputmode="decimal" class="form-control form-control-sm" id="pj_harga_satuan" name="harga_satuan" placeholder="contoh: 8000,45" required>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group mb-2">
                                        <label>Total baris (otomatis)</label>
                                        <input type="text" class="form-control form-control-sm" id="pj_total_preview" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 d-flex align-items-end">
                                    <div class="form-group mb-2 w-100">
                                        <button type="submit" class="btn btn-primary btn-sm btn-block" id="btnSimpanPenjualanTagihan">
                                            <i class="fa fa-save"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                    <strong class="mb-1">Data Barang Penjualan</strong>
                    <button type="button" class="btn btn-danger btn-sm mb-1" id="btnTambahBarangPenjualanTagihan">
                        <i class="fa fa-plus"></i> Tambah Barang
                    </button>
                </div>

                <div class="table-responsive table-edit-penjualan-wrap">
                    <table id="tableEditPenjualanTagihan" class="display nowrap table table-sm table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Aksi</th>
                                <th>Tgl Jual</th>
                                <th>No Pesan</th>
                                <th>No Kirim</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="10" style="text-align:right">TOTAL</th>
                                <th id="modalFooterTotal" style="text-align:right">—</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-between modal-footer-edit-penjualan-compact py-1">
                <div>
                    <a href="#" id="btnBukaHalamanEditPenjualan" class="btn btn-outline-danger btn-sm" target="_blank" rel="noopener">
                        <i class="fa fa-external-link"></i> Buka Form Update Penjualan
                    </a>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnReloadPenjualanTagihan">
                        <i class="fa fa-refresh"></i> Muat Ulang
                    </button>
                </div>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit 1 Baris Data Barang Penjualan -->
<div class="modal fade" id="modalEditRowBarangPenjualanTagihan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:760px;">
        <div class="modal-content">
            <form id="formEditRowBarangPenjualanTagihan" autocomplete="off">
                <div class="modal-header bg-warning">
                    <h4 class="modal-title text-dark"><i class="fa fa-pencil"></i> Edit Data Barang Penjualan</h4>
                    <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="pjm_id" name="id" value="">
                    <input type="hidden" id="pjm_uuid_penjualan" name="uuid_penjualan" value="">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="form-group mb-2">
                                <label>Tgl Jual</label>
                                <input type="date" class="form-control form-control-sm" id="pjm_tgl_jual" name="tgl_jual">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="form-group mb-2">
                                <label>No Pesan</label>
                                <input type="text" class="form-control form-control-sm" id="pjm_nmrpesan" name="nmrpesan">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="form-group mb-2">
                                <label>No Kirim</label>
                                <input type="text" class="form-control form-control-sm" id="pjm_nmrkirim" name="nmrkirim">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="form-group mb-2">
                                <label>Kode</label>
                                <input type="text" class="form-control form-control-sm" id="pjm_kode_barang" name="kode_barang">
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group mb-2">
                                <label>Nama Barang / Jasa</label>
                                <input type="text" class="form-control form-control-sm" id="pjm_nama_barang" name="nama_barang" required>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group mb-2">
                                <label>Jumlah</label>
                                <input type="text" inputmode="decimal" class="form-control form-control-sm" id="pjm_jumlah" name="jumlah" required>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group mb-2">
                                <label>Satuan</label>
                                <input type="text" class="form-control form-control-sm" id="pjm_satuan" name="satuan">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group mb-2">
                                <label>Harga Satuan</label>
                                <input type="text" inputmode="decimal" class="form-control form-control-sm" id="pjm_harga_satuan" name="harga_satuan" required>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group mb-2">
                                <label>Total baris (otomatis)</label>
                                <input type="text" class="form-control form-control-sm" id="pjm_total_preview" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpanRowBarangPenjualanTagihan">
                        <i class="fa fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pilih Barang dari Persediaan -->
<div class="modal fade" id="modalPilihPersediaanTagihan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-pilih-persediaan-tagihan" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">
                    <i class="fa fa-cubes"></i> Pilih Barang dari Persediaan
                    <small id="modalPilihPersediaanBulanLabel" class="d-block" style="font-size:0.85rem;opacity:.9;"></small>
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modalPilihPersediaanInfo" class="mb-2"></div>
                <div class="table-responsive">
                    <table id="tablePilihPersediaanTagihan" class="display nowrap table table-sm table-bordered table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Aksi</th>
                                <th>Tgl</th>
                                <th>SPOP</th>
                                <th>Kategori</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Satuan</th>
                                <th>Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Isi Jumlah setelah pilih barang -->
<div class="modal fade" id="modalIsiJumlahPersediaanTagihan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:520px;">
        <div class="modal-content">
            <form id="formIsiJumlahPersediaanTagihan" autocomplete="off">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white"><i class="fa fa-edit"></i> Isi Jumlah Penjualan</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="isi_id_persediaan" value="">
                    <input type="hidden" id="isi_uuid_persediaan" value="">
                    <div class="form-group">
                        <label>Barang / Jasa</label>
                        <input type="text" class="form-control" id="isi_nama_barang" readonly>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Satuan</label>
                                <input type="text" class="form-control" id="isi_satuan" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Sisa Stok</label>
                                <input type="text" class="form-control" id="isi_sisa_stock" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Harga Satuan</label>
                                <input type="text" inputmode="decimal" class="form-control" id="isi_harga_satuan" placeholder="contoh: 8000,45" required>
                                <small class="text-muted">Boleh pakai koma desimal, contoh: 8000,45</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Jumlah <span class="text-danger">*</span></label>
                                <input type="text" inputmode="decimal" class="form-control" id="isi_jumlah" placeholder="contoh: 1 atau 1,5" required>
                                <small class="text-muted" id="isi_jumlah_hint"></small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label>Total (otomatis)</label>
                        <input type="text" class="form-control font-weight-bold text-danger" id="isi_total_preview" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="btnSimpanIsiJumlahTagihan">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style type="text/css">
    .tagihan-page-wrap {
        padding-bottom: 1.5rem;
    }
    .tagihan-page-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1f3a5f;
    }
    .tagihan-page-sub {
        font-size: 0.95rem;
        margin-top: 0.15rem;
    }
    .tagihan-card {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(31, 58, 95, 0.08);
        overflow: hidden;
    }
    .tagihan-card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #eef4fb 100%);
        border-bottom: 1px solid #dbe5f0;
    }
    .tagihan-card-header .card-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1f3a5f;
        white-space: normal;
        line-height: 1.35;
    }
    .tagihan-konsumen-name {
        color: #c0392b;
        font-weight: 700;
    }
    .tagihan-hint {
        font-size: 0.92rem;
        line-height: 1.45;
    }
    .tagihan-summary {
        background: #f4faf6;
        border: 1px solid #d4edda;
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }
    .tagihan-total-label {
        margin-top: 0.35rem;
        color: #1e7e34;
        font-weight: 600;
    }
    .tagihan-total-value {
        color: #c0392b;
        font-size: 1.15rem;
        font-weight: 800;
        margin-left: 0.35rem;
    }
    .tagihan-help-hotspot {
        position: relative;
        display: inline-block;
        max-width: 100%;
        cursor: help;
    }
    .tagihan-help-hotspot-breadcrumb {
        display: block;
        text-align: right;
    }
    .tagihan-help-hotspot-breadcrumb .breadcrumb {
        background: transparent;
        padding: 0.35rem 0;
        margin: 0;
        display: inline-flex;
        cursor: help;
    }
    .tagihan-help-icon {
        font-size: 0.85rem;
        color: #3c8dbc;
        margin-left: 0.35rem;
        opacity: 0.85;
    }
    .tagihan-help-balloon {
        position: absolute;
        left: 0;
        top: calc(100% + 8px);
        width: min(520px, 92vw);
        background: #ffffff;
        border: 1px solid #b7d0e6;
        border-left: 4px solid #2f80ed;
        border-radius: 10px;
        padding: 0.75rem 0.9rem;
        box-shadow: 0 10px 28px rgba(31, 58, 95, 0.18);
        z-index: 1050;
        opacity: 0;
        visibility: hidden;
        transform: translateY(6px);
        transition: opacity 0.16s ease, transform 0.16s ease, visibility 0.16s ease;
        pointer-events: none;
        text-align: left;
    }
    .tagihan-help-balloon-right {
        left: auto;
        right: 0;
    }
    .tagihan-help-balloon::before {
        content: '';
        position: absolute;
        top: -7px;
        left: 22px;
        width: 12px;
        height: 12px;
        background: #fff;
        border-left: 1px solid #b7d0e6;
        border-top: 1px solid #b7d0e6;
        transform: rotate(45deg);
    }
    .tagihan-help-balloon-right::before {
        left: auto;
        right: 28px;
    }
    .tagihan-help-hotspot:hover .tagihan-help-balloon,
    .tagihan-help-hotspot:focus .tagihan-help-balloon,
    .tagihan-help-hotspot:focus-within .tagihan-help-balloon {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    .tagihan-help-balloon-title {
        font-size: 0.92rem;
        font-weight: 800;
        color: #1f3a5f;
        margin-bottom: 0.35rem;
    }
    .tagihan-help-balloon-text {
        font-size: 0.86rem;
        color: #4b5d70;
        line-height: 1.5;
    }
    .tagihan-help-balloon-text .badge {
        font-size: 0.72rem;
        vertical-align: baseline;
    }

    /* Tab shell */
    .tagihan-tabs-card {
        border: 1px solid #d7e2ee;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 14px rgba(31, 58, 95, 0.08);
    }
    .tagihan-tabs-card-header {
        background: linear-gradient(180deg, #f8fbfe 0%, #eef4fa 100%);
        border-bottom: 1px solid #d5e0ec;
        padding: 0.75rem 0.85rem 0;
    }
    .tagihan-tabs-heading {
        font-size: 0.95rem;
        color: #1f3a5f;
        margin-bottom: 0.55rem;
        padding: 0 0.25rem;
    }
    .tagihan-tabs-body {
        background: #fbfcfe;
        padding: 0.85rem;
    }
    .tagihan-pay-tabs {
        border-bottom: none;
        gap: 0.35rem;
        flex-wrap: wrap;
    }
    .tagihan-pay-tabs .nav-item {
        margin-bottom: 0;
    }
    .tagihan-pay-tabs .nav-link {
        display: flex;
        align-items: flex-start;
        gap: 0.55rem;
        border: 1px solid #cfdceb !important;
        border-radius: 10px 10px 0 0 !important;
        background: #eef2f6;
        color: #6a7a8c;
        padding: 0.55rem 0.75rem;
        margin-right: 0.2rem;
        min-width: 210px;
        transition: all 0.18s ease;
        opacity: 0.82;
        box-shadow: inset 0 -1px 0 rgba(255,255,255,0.4);
    }
    .tagihan-pay-tabs .nav-link .tagihan-tab-title {
        display: block;
        font-size: 0.84rem;
        font-weight: 600;
        line-height: 1.25;
        color: #667788;
    }
    .tagihan-pay-tabs .nav-link .tagihan-tab-sub {
        display: block;
        font-size: 0.72rem;
        font-weight: 500;
        color: #8a97a6;
        margin-top: 0.12rem;
        line-height: 1.25;
    }
    .tagihan-pay-tabs .nav-link .tagihan-tab-num {
        flex: 0 0 auto;
        width: 1.45rem;
        height: 1.45rem;
        border-radius: 50%;
        background: #d5dde6;
        color: #5d6b7a;
        font-size: 0.78rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-top: 0.1rem;
    }
    .tagihan-pay-tabs .nav-link .tagihan-tab-count {
        align-self: center;
        margin-left: 0.15rem;
    }
    .tagihan-pay-tabs .nav-link:hover {
        background: #f5f8fb;
        color: #3d5268;
        opacity: 1;
        border-color: #b7c9db !important;
    }
    .tagihan-pay-tabs .nav-link.active {
        background: #ffffff !important;
        border-color: #7eb6e8 !important;
        border-bottom-color: #ffffff !important;
        color: #1f3a5f !important;
        opacity: 1;
        box-shadow: 0 -2px 0 #3c8dbc inset, 0 4px 12px rgba(60, 141, 188, 0.12);
        transform: translateY(1px);
        z-index: 2;
    }
    .tagihan-pay-tabs .nav-link.active .tagihan-tab-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: #16324f;
    }
    .tagihan-pay-tabs .nav-link.active .tagihan-tab-sub {
        font-size: 0.78rem;
        color: #3c8dbc;
        font-weight: 600;
    }
    .tagihan-pay-tabs .nav-link.active .tagihan-tab-num {
        background: #3c8dbc;
        color: #fff;
        width: 1.65rem;
        height: 1.65rem;
        font-size: 0.88rem;
    }
    #tab-bayar-transaksi-link.active {
        border-color: #7dcb93 !important;
        box-shadow: 0 -2px 0 #28a745 inset, 0 4px 12px rgba(40, 167, 69, 0.12);
    }
    #tab-bayar-transaksi-link.active .tagihan-tab-num {
        background: #28a745;
    }
    #tab-bayar-transaksi-link.active .tagihan-tab-sub {
        color: #1e7e34;
    }
    #tab-bayar-nominal-link.active {
        border-color: #7eb6e8 !important;
    }
    #tab-riwayat-bayar-link.active {
        border-color: #7ebfd4 !important;
        box-shadow: 0 -2px 0 #17a2b8 inset, 0 4px 12px rgba(23, 162, 184, 0.12);
    }
    #tab-riwayat-bayar-link.active .tagihan-tab-num {
        background: #17a2b8;
    }
    #tab-riwayat-bayar-link.active .tagihan-tab-sub {
        color: #117a8b;
    }

    .tagihan-tab-pane-inner {
        background: #fff;
        border: 1px solid #d9e4ef;
        border-radius: 0 10px 10px 10px;
        padding: 1rem 1.05rem 1.15rem;
        box-shadow: 0 2px 10px rgba(31, 58, 95, 0.04);
    }
    .tagihan-tab-pane-success {
        border-color: #c5e6cf;
        border-top: 3px solid #28a745;
    }
    .tagihan-tab-pane-primary {
        border-color: #c5daf0;
        border-top: 3px solid #3c8dbc;
    }
    .tagihan-tab-pane-info {
        border-color: #c5e4ec;
        border-top: 3px solid #17a2b8;
    }
    .tagihan-tab-pane-intro {
        margin-bottom: 0.9rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px dashed #d7e2ee;
        color: #3d5268;
        font-size: 0.9rem;
        line-height: 1.45;
    }
    .tagihan-step-badge-info {
        background: #e6f7fb;
        color: #117a8b;
        border: 1px solid #b6e0ea;
    }

    /* Yellow soft outer border for datatables */
    .tagihan-dt-yellow-border {
        border: 1.5px solid #f0d27a;
        border-radius: 10px;
        padding: 0.45rem;
        background: linear-gradient(180deg, #fffdf6 0%, #ffffff 40%);
        box-shadow: 0 0 0 1px rgba(240, 210, 122, 0.28), 0 2px 10px rgba(196, 149, 30, 0.08);
    }
    .tagihan-dt-yellow-border .dataTables_wrapper {
        padding: 0.15rem;
    }
    .tagihan-dt-yellow-border table.tagihan-dt {
        border-color: #f3e2a8 !important;
    }
    .tagihan-dt-yellow-border table.tagihan-dt thead th {
        background: #fff8e1;
        border-bottom-color: #f0d27a !important;
    }

    .tagihan-nominal-input {
        font-size: 1.35rem !important;
        font-weight: 700 !important;
        text-align: right !important;
        color: #c0392b !important;
    }
    .tagihan-pay-card .card-body {
        padding: 1rem 1.15rem 1.15rem;
    }
    .tagihan-pay-header-nominal {
        border-left: 4px solid #3c8dbc;
    }
    .tagihan-pay-header-transaksi {
        border-left: 4px solid #28a745;
    }
    .tagihan-step-badge {
        display: inline-block;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        padding: 0.2rem 0.55rem;
        border-radius: 999px;
        line-height: 1.2;
    }
    .tagihan-step-badge-primary {
        background: #e8f4fc;
        color: #1d6fa5;
        border: 1px solid #b6d7ea;
    }
    .tagihan-step-badge-success {
        background: #e8f8ee;
        color: #1e7e34;
        border: 1px solid #b7e0c2;
    }
    .tagihan-pay-desc {
        font-size: 0.9rem;
        color: #5a6a7a;
        line-height: 1.45;
        max-width: 920px;
        margin-top: 0.15rem;
    }
    .tagihan-field-label {
        font-size: 0.84rem;
        font-weight: 600;
        color: #31465f;
        margin-bottom: 0.3rem;
    }
    .tagihan-btn-simpan {
        font-weight: 700;
        min-height: 38px;
        white-space: nowrap;
    }
    .tagihan-pay-note {
        background: #f0f7fc;
        border: 1px dashed #9fc4dd;
        border-radius: 8px;
        padding: 0.55rem 0.8rem;
        color: #355970;
        font-size: 0.86rem;
    }
    .tagihan-selected-bar {
        background: #f3fbf5;
        border: 1px solid #c8e6d0;
        border-radius: 8px;
        padding: 0.55rem 0.85rem;
    }
    .tagihan-pay-fields-box {
        background: #fbfcfe;
        border: 1px solid #e2eaf2;
        border-radius: 8px;
        padding: 0.85rem 1rem;
    }
    .tagihan-pay-fields-title {
        font-weight: 700;
        color: #1f3a5f;
        font-size: 0.95rem;
    }
    .tagihan-empty-pay {
        text-align: center;
        padding: 1.4rem 1rem;
        background: linear-gradient(180deg, #f8fbf9 0%, #f3f7f4 100%);
        border: 1px dashed #b7d7c0;
        border-radius: 10px;
    }
    .tagihan-empty-pay-icon {
        font-size: 2rem;
        color: #28a745;
        margin-bottom: 0.4rem;
    }
    .tagihan-empty-pay-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1f3a5f;
        margin-bottom: 0.35rem;
    }
    .tagihan-empty-pay-text {
        font-size: 0.9rem;
        color: #5a6a7a;
        line-height: 1.5;
        max-width: 720px;
        margin: 0 auto;
    }
    .tagihan-table-wrap {
        width: 100%;
        overflow-x: auto;
    }
    .tagihan-dt th,
    .tagihan-dt td {
        white-space: nowrap;
        vertical-align: middle !important;
        font-size: 0.88rem;
    }
    .tagihan-action-cell {
        min-width: 168px;
        white-space: nowrap !important;
        padding-top: 0.25rem !important;
        padding-bottom: 0.25rem !important;
    }
    .tagihan-action-inline {
        display: inline-flex;
        flex-wrap: nowrap;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
    }
    .tagihan-action-inline .btn {
        white-space: nowrap;
        margin: 0 !important;
        padding: 0.15rem 0.4rem;
        line-height: 1.25;
        font-size: 0.75rem;
    }
    #tglSPOPFreeze tbody td {
        padding-top: 0.28rem !important;
        padding-bottom: 0.28rem !important;
    }
    .btn-edit-penjualan-tagihan {
        background: #c0392b !important;
        border-color: #a93226 !important;
        color: #fff !important;
    }
    .btn-edit-penjualan-tagihan:hover {
        background: #a93226 !important;
        border-color: #922b21 !important;
        color: #fff !important;
    }

    /* Modal: full width kiri-kanan, sela 1cm atas & bawah */
    #modalEditPenjualanTagihan .modal-dialog-edit-penjualan-tagihan {
        max-width: 100%;
        width: 100%;
        margin: 1cm 0;
        height: calc(100vh - 2cm);
    }
    #modalEditPenjualanTagihan .modal-content {
        height: 100%;
        border-radius: 0;
        border: none;
        display: flex;
        flex-direction: column;
    }
    /* Header lebih tipis agar area data penjualan lebih luas */
    #modalEditPenjualanTagihan .modal-header,
    #modalEditPenjualanTagihan .modal-header-edit-penjualan-compact {
        border-radius: 0;
        flex-shrink: 0;
        padding: 0.35rem 0.75rem !important;
        min-height: 0;
        align-items: center;
    }
    #modalEditPenjualanTagihan .modal-header .modal-title {
        font-size: 1rem;
        margin: 0;
        line-height: 1.25;
        font-weight: 700;
    }
    #modalEditPenjualanTagihan .modal-header .close {
        padding: 0.25rem 0.5rem;
        margin: 0;
        font-size: 1.35rem;
        line-height: 1;
    }
    #modalEditPenjualanTagihan .modal-body {
        flex: 1 1 auto;
        overflow: auto;
        padding: 0.55rem 0.85rem;
    }
    /* Footer lebih tipis */
    #modalEditPenjualanTagihan .modal-footer,
    #modalEditPenjualanTagihan .modal-footer-edit-penjualan-compact {
        flex-shrink: 0;
        border-radius: 0;
        padding: 0.3rem 0.75rem !important;
        min-height: 0;
    }
    #modalEditPenjualanTagihan .modal-edit-penjualan-totals {
        margin-bottom: 0.4rem !important;
    }
    #modalEditPenjualanTagihan .modal-edit-penjualan-totals .info-box-sm {
        padding: 0.35rem 0.6rem;
        margin-bottom: 0.25rem;
    }
    #modalEditPenjualanTagihan .modal-edit-penjualan-totals .info-box-sm .label {
        font-size: 0.7rem;
        margin-bottom: 0;
    }
    #modalEditPenjualanTagihan .modal-edit-penjualan-totals .info-box-sm strong {
        font-size: 0.95rem;
    }
    #modalEditPenjualanTagihan #panelFormPenjualanTagihan {
        margin-bottom: 0.5rem !important;
    }
    #modalEditPenjualanTagihan #panelFormPenjualanTagihan .card-header {
        padding: 0.3rem 0.65rem !important;
    }
    #modalEditPenjualanTagihan #panelFormPenjualanTagihan .card-body {
        padding: 0.4rem 0.65rem !important;
    }
    #modalEditPenjualanTagihan #modalEditPenjualanValidasi {
        margin-bottom: 0.4rem !important;
    }
    #modalEditPenjualanTagihan #modalEditPenjualanValidasi .alert {
        padding: 0.35rem 0.65rem;
        margin-bottom: 0;
        font-size: 0.88rem;
    }
    #modalEditPenjualanTagihan .table-edit-penjualan-wrap {
        max-height: calc(100vh - 7.5cm);
    }
    /* Modal pilih persediaan: lebar hampir penuh, di atas modal edit */
    #modalPilihPersediaanTagihan {
        z-index: 1060;
    }
    #modalPilihPersediaanTagihan .modal-dialog-pilih-persediaan-tagihan {
        max-width: 96%;
        width: 96%;
        margin: 1cm auto;
    }
    #modalPilihPersediaanTagihan .modal-content {
        max-height: calc(100vh - 2cm);
        display: flex;
        flex-direction: column;
    }
    #modalPilihPersediaanTagihan .modal-body {
        overflow: auto;
        flex: 1 1 auto;
    }
    #modalIsiJumlahPersediaanTagihan {
        z-index: 1070;
    }
    #modalEditRowBarangPenjualanTagihan {
        z-index: 1080;
    }
    #modalEditRowBarangPenjualanTagihan .modal-dialog {
        z-index: 1081;
    }
    .modal-backdrop.backdrop-edit-row-penjualan {
        z-index: 1075 !important;
    }
    .modal-backdrop + .modal-backdrop {
        z-index: 1055;
    }
    .modal-edit-penjualan-totals .info-box-sm {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.65rem 0.85rem;
        margin-bottom: 0.5rem;
    }
    .modal-edit-penjualan-totals .info-box-sm .label {
        display: block;
        font-size: 0.78rem;
        color: #64748b;
        margin-bottom: 0.15rem;
    }
    .modal-edit-penjualan-totals .info-box-sm strong {
        font-size: 1.05rem;
        color: #1f3a5f;
    }
    #tableEditPenjualanTagihan tbody tr.row-clicked-penjualan {
        background: #fff3cd !important;
    }
    #tableEditPenjualanTagihan tbody tr.row-line-invalid {
        background: #f8d7da !important;
    }
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
</style>

<script>
/* Script modal Edit Penjualan dipasang di footer (page_footer_scripts) agar jQuery sudah siap. */
function sum() { return true; }

(function() {
    function adjustTagihanTables() {
        if (typeof window.jQuery === 'undefined' || !window.jQuery.fn || !window.jQuery.fn.DataTable) {
            return;
        }
        var $ = window.jQuery;
        ['#tglSPOPFreeze', '#tglSPOPFreeze1', '#tglSPOPFreeze2'].forEach(function(sel) {
            if ($.fn.DataTable.isDataTable(sel)) {
                try {
                    $(sel).DataTable().columns.adjust();
                } catch (e) {}
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.jQuery === 'undefined') {
            return;
        }
        var $ = window.jQuery;
        $('a[data-toggle="tab"]').on('shown.bs.tab', function() {
            window.setTimeout(adjustTagihanTables, 80);
        });
        <?php if (!empty($Data_konsumen_proses_bayar)) { ?>
        // Setelah Pilih Bayar: fokus ke Tab 1
        $('#tab-bayar-transaksi-link').tab('show');
        var tabsCard = document.querySelector('.tagihan-tabs-card');
        if (tabsCard && tabsCard.scrollIntoView) {
            window.setTimeout(function() {
                tabsCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 250);
        }
        <?php } ?>
    });
})();
</script>
