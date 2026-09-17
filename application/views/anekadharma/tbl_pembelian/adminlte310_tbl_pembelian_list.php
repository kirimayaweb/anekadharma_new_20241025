<div class="content-wrapper">


    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v1</li> -->
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">

    <?php 
    // echo $date_awal; 
    // echo "<br/>";
    
    if (date("Y", strtotime($date_awal)) < 2020) {
        $Get_date_awal = date("d-m-Y");
    } else {
        $Get_date_awal = date("d-m-Y", strtotime($date_awal));
    }
    
    // echo $Get_date_awal;
    // echo "<br/>";
    // echo "<br/>";

    
    // echo $date_akhir; 
    // echo "<br/>";
    
    if (date("Y", strtotime($date_akhir)) < 2020) {
        $Get_date_akhir = date("d-m-Y");
    } else {
        $Get_date_akhir = date("d-m-Y", strtotime($date_akhir));
    }
    
    // echo $Get_date_akhir;
    // echo "<br/>";
    // echo "<br/>";

    $excel_export_ids = array();
    if (!empty($Tbl_pembelian_data)) {
        foreach ($Tbl_pembelian_data as $row_export) {
            if (!empty($row_export->id)) {
                $excel_export_ids[] = (int) $row_export->id;
            }
        }
    }
    $excel_export_ids_str = implode(',', $excel_export_ids);

    if (!isset($Tbl_pembelian_data_belum_persediaan)) {
        $Tbl_pembelian_data_belum_persediaan = array();
    }
    if (!isset($Tbl_pembelian_data_persediaan_manual)) {
        $Tbl_pembelian_data_persediaan_manual = array();
    }
    if (!isset($Tbl_pembelian_data_persediaan_otomatis)) {
        $Tbl_pembelian_data_persediaan_otomatis = array();
    }
    if (!isset($pembelian_count_belum_persediaan)) {
        $pembelian_count_belum_persediaan = count($Tbl_pembelian_data_belum_persediaan);
    }
    if (!isset($pembelian_count_persediaan_manual)) {
        $pembelian_count_persediaan_manual = count($Tbl_pembelian_data_persediaan_manual);
    }
    if (!isset($pembelian_count_persediaan_otomatis)) {
        $pembelian_count_persediaan_otomatis = count($Tbl_pembelian_data_persediaan_otomatis);
    }
    if (!isset($pembelian_active_tab) || $pembelian_active_tab === '') {
        $pembelian_active_tab = 'tab-pembelian-data';
    }

    $pembelian_main_tabs = array(
        array(
            'tab_id' => 'tab-pembelian-data',
            'link_id' => 'tab-pembelian-data-link',
            'label' => 'Data Pembelian',
            'count' => count($Tbl_pembelian_data),
            'badge_class' => 'badge-secondary',
        ),
        array(
            'tab_id' => 'tab-pembelian-verifikasi',
            'link_id' => 'tab-pembelian-verifikasi-link',
            'label' => 'Data Verifikasi',
            'count' => (int) $pembelian_count_belum_persediaan,
            'badge_class' => 'badge-warning',
        ),
    );

    ?>

        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">

                        <div class="row align-items-center">
                            <div class="col-md-3" text-align="left">
                                <strong>DATA PEMBELIAN</strong>
                            </div>
                            <div class="col-md-2" text-align="left" align="left">
                                <?php echo anchor(site_url('tbl_pembelian/create'), 'Input Pembelian', 'class="btn btn-danger"'); ?>
                            </div>

                            <div class="col-md-4 d-flex justify-content-center">
                                <?php 
                                $action_cari_between_date = site_url('tbl_pembelian/cari_between_date');
                                ?>

                                <?php
                                $__ts_filter_bulan = false;
                                if (!empty($date_awal)) {
                                    $__ts_filter_bulan = strtotime($date_awal);
                                } elseif (!empty($Get_date_awal)) {
                                    $__ts_filter_bulan = strtotime(str_replace('/', '-', $Get_date_awal));
                                }
                                if ($__ts_filter_bulan === false) {
                                    $__ts_filter_bulan = time();
                                }
                                $Get_filter_bulan = date('m/Y', $__ts_filter_bulan);
                                $Get_date_awal_hidden = date('d-m-Y', strtotime(date('Y-m-01', $__ts_filter_bulan)));
                                $Get_date_akhir_hidden = date('d-m-Y', strtotime(date('Y-m-t', $__ts_filter_bulan)));
                                ?>
                                <form id="form-cari-pembelian" action="<?php echo $action_cari_between_date; ?>" method="post" class="mb-0" style="width: 100%; max-width: 280px;">
                                    <input type="hidden" name="pembelian_active_tab" id="pembelian_active_tab_input" value="<?php echo htmlspecialchars($pembelian_active_tab, ENT_QUOTES, 'UTF-8'); ?>" />
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="input-group date" id="filter_bulan" data-target-input="nearest" style="width: 50%; min-width: 120px;">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#filter_bulan" id="filter_bulan_input" name="filter_bulan" value="<?php echo htmlspecialchars($Get_filter_bulan, ENT_QUOTES, 'UTF-8'); ?>" placeholder="MM/YYYY" required autocomplete="off" />
                                            <div class="input-group-append" data-target="#filter_bulan" data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="tgl_awal" id="tgl_awal_hidden" value="<?php echo htmlspecialchars($Get_date_awal_hidden, ENT_QUOTES, 'UTF-8'); ?>" />
                                        <input type="hidden" name="tgl_akhir" id="tgl_akhir_hidden" value="<?php echo htmlspecialchars($Get_date_akhir_hidden, ENT_QUOTES, 'UTF-8'); ?>" />
                                        <button type="submit" class="btn btn-danger btn-flat ml-2"><i class="fa fa-sign-in" aria-hidden="true"></i> Cari</button>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-3 text-right">
                                <input type="hidden" id="excel-export-source" value="tbl_pembelian" />
                                <input type="hidden" id="excel-export-ids" value="<?php echo htmlspecialchars($excel_export_ids_str, ENT_QUOTES, 'UTF-8'); ?>" />
                                <button type="button" class="btn btn-success btn-block" onclick="cetakExcelPembelian(); return false;">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Cetak ke Excel
                                </button>
                            </div>
                        </div>

                    </div>



                    <div class="card-body">

                        <style type="text/css">
                            #pembelian-main-tabs .nav-link.active {
                                font-weight: 700;
                                border-bottom: 3px solid #dc3545;
                            }
                            #pembelian-persediaan-subtabs .nav-link.active {
                                color: #fff !important;
                                font-weight: 700;
                                background: #0b3d91 !important;
                                border: 2px solid #ffc107 !important;
                            }
                            table.pembelian-persediaan-dt-table tfoot .pem-persediaan-total-row th {
                                background-color: #fff3cd;
                                font-weight: 700;
                                border-top: 2px solid #ffc107;
                            }
                            #modal-pem-isi-jumlah-refered { z-index: 1065 !important; }
                        </style>

                        <ul class="nav nav-tabs mb-3" id="pembelian-main-tabs" role="tablist">
                            <?php foreach ($pembelian_main_tabs as $tab_cfg) :
                                $tab_nav_active = ($pembelian_active_tab === $tab_cfg['tab_id']) ? ' active' : '';
                            ?>
                                <li class="nav-item">
                                    <a class="nav-link<?php echo $tab_nav_active; ?>" id="<?php echo htmlspecialchars($tab_cfg['link_id'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-toggle="tab" href="#<?php echo htmlspecialchars($tab_cfg['tab_id'], ENT_QUOTES, 'UTF-8'); ?>" role="tab">
                                        <?php echo htmlspecialchars($tab_cfg['label'], ENT_QUOTES, 'UTF-8'); ?>
                                        <span class="badge <?php echo htmlspecialchars($tab_cfg['badge_class'], ENT_QUOTES, 'UTF-8'); ?> badge-count ml-1"><?php echo (int) $tab_cfg['count']; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="tab-content" id="pembelian-main-tabs-content">
                            <div class="tab-pane fade<?php echo ($pembelian_active_tab === 'tab-pembelian-data') ? ' show active' : ''; ?>" id="tab-pembelian-data" role="tabpanel">

                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <th style="text-align:center">Tgl Po</th>
                                    <th style="text-align:center">Spop</th>
                                    <th style="text-align:center">Kategori</th>
                                    <th style="text-align:center">No. faktur/ kwitansi</th>
                                    <th style="text-align:center">Supplier</th>
                                    <th style="text-align:center">Kode Barang</th>
                                    <th style="text-align:center">Nama Barang</th>
                                    <th style="text-align:center">Jumlah</th>
                                    <th style="text-align:center">Satuan</th>
                                    <th style="text-align:center">Konsumen</th>
                                    <th style="text-align:center">Harga Satuan</th>
                                    <th style="text-align:center">Harga Total</th>
                                    <th style="text-align:center">Statuslu</th>
                                    <th style="text-align:center">Kas / Bank</th>
                                    <th style="text-align:center">Tgl Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $compare_spop = 0;
                                $compare_uuid_spop = 0;
                                $start = isset($start) ? $start : 0;
                                $Total_per_SPOP = 0;
                                $TOTAL_JUMLAH_BARANG = 0;
                                $TOTAL_LUNAS = 0;
                                $TOTAL_HUTANG = 0;
                                $list_spop_status_lu = "";
                                $x_button = 0;
                                foreach ($Tbl_pembelian_data as $list_data) {

                                    // $list_spop_status_lu = $list_data->statuslu; // untuk cek kondisi di baris terakhir (SPOP)
                                    // if (($compare_spop <> $list_data->spop) and ($start >= 1)) {
                                    if (($compare_uuid_spop <> $list_data->uuid_spop) and ($start >= 1)) {
                                        // Buat 1 baris untuk total dan background = KUNING
                                ?>
                                        <tr class="row-pembelian-subtotal">
                                            <td><?php
                                                echo ++$start;
                                                // echo "-compare : ";
                                                // echo $compare_spop;
                                                // echo "- spop : ";
                                                // echo $list_data->spop;
                                                // echo " ---- : ";
                                                // echo $list_spop_status_lu;
                                                // echo " ---- : ";
                                                // echo $list_data->statuslu;
                                                ?></td>
                                            <td>
                                                <?php
                                                // echo $compare_spop . " - " . $list_data->spop;


                                                // echo "baris x";
                                                // if ($x_button == 1) {
                                                // echo anchor(site_url('tbl_pembelian/update_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                // echo anchor(site_url('tbl_pembelian/delete_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');
                                                // }
                                                ?>

                                            </td>
                                            <td><?php echo $compare_spop; ?></td>
                                            <td></td>
                                            <td></td>
                                            <!-- <td></td> -->
                                            <!-- <td></td> -->
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td style="background-color:yellow;" align="right">
                                                <?php
                                                // echo "<font color='red'><strong>" . nominal($Total_per_SPOP) . "</strong></font>"; 
                                                echo "<font color='red'><strong>" . number_format($Total_per_SPOP, 2, ',', '.')  . "</strong></font>";
                                                ?>
                                            </td>
                                            <td>
                                                <?php

                                                $result_pengajuan_by_uuid_spop = $this->Tbl_pembelian_pengajuan_bayar_model->get_by_uuid_spop($compare_uuid_spop);

                                                $TOTAL_Nominal_pengajuan = $this->Tbl_pembelian_pengajuan_bayar_model->get_sumNominal_by_uuid_spop($compare_uuid_spop)->total_pengajuan;

                                                if ($result_pengajuan_by_uuid_spop) {
                                                    $startx = 0;
                                                    $total_nominal_pengajuan = 0;
                                                    foreach ($result_pengajuan_by_uuid_spop as $list_data_pengajuan) {
                                                        echo anchor(site_url('tbl_pembelian/cetak_pengajuan_bayar_per_spop/' . $list_data_pengajuan->uuid_pengajuan_bayar), '<i class="fa fa-pencil-square-o" aria-hidden="true">Cetak Pengajuan ' . ++$startx . '</i>', 'class="btn btn-success btn-xs" target="_blank"');

                                                        $total_nominal_pengajuan = $total_nominal_pengajuan + $list_data_pengajuan->nominal_pengajuan;
                                                    }

                                                    if ($TOTAL_Nominal_pengajuan < $Total_per_SPOP) {

                                                        if ($list_spop_status_lu == "Hutang"  or $list_spop_status_lu == "U") {
                                                            echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $compare_uuid_spop . '/pembelian'), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                                        }
                                                    }
                                                } else {

                                                    if ($list_spop_status_lu == "Hutang"  or $list_spop_status_lu == "U") {
                                                        echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $compare_uuid_spop . '/pembelian'), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                                    }
                                                }

                                                $list_spop_status_lu = $list_data->statuslu; // untuk cek kondisi di baris terakhir (SPOP) ==> Ubah status_lu dengan status data record yang baru.

                                                ?>
                                            </td>
                                            <td></td>
                                            <td></td>

                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr class="row-pembelian-data" data-pembelian-id="<?php echo (int) $list_data->id; ?>">
                                        <?php
                                        if ($compare_uuid_spop == $list_data->uuid_spop) {
                                        ?>
                                            <td><?php echo ++$start ?></td>
                                            <td>

                                                <?php

                                                // echo $list_data->spop;


                                                if (($compare_uuid_spop == $list_data->uuid_spop) and $x_button == 1) {
                                                    // echo anchor(site_url('tbl_pembelian/update_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                    // echo anchor(site_url('tbl_pembelian/delete_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');
                                                    $x_button_show = 1;
                                                    $x_button = $x_button + 1;

                                                    // echo "jghjghjghhhhh";
                                                } else {
                                                    // echo "oooooooooooo";
                                                    echo date("d M Y", strtotime($list_data->tgl_po));
                                                    echo "<br/>";
                                                    echo anchor(site_url('tbl_pembelian/create_add_uraian_update/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                    // echo " ";

                                                    // echo anchor(site_url('Tbl_pembelian/delete_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-trash-o" aria-hidden="true">HAPUS SPOP</i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Anda Yakin akan Menghapus data SPOP ini?\')"');

                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $list_data->spop; ?></td>
                                            <td align="left"><?php echo isset($list_data->kategori) ? htmlspecialchars($list_data->kategori, ENT_QUOTES, 'UTF-8') : ''; ?></td>
                                            <td align="center"><?php echo $list_data->nmrfakturkwitansi; ?></td>


                                            <td align="left"><?php echo $list_data->supplier_nama; ?></td>
                                            <!-- <td></td>
                                            <td></td> -->
                                        <?php
                                        } else {
                                            // SPOP baru , me NOL kan total SPOP
                                            $Total_per_SPOP = 0;
                                            $x_button = 0;
                                            $x_button_show = 0;
                                        ?>
                                            <td><?php echo ++$start ?></td>
                                            <td><?php
                                                echo date("d M Y", strtotime($list_data->tgl_po));
                                                echo "<br/>";

                                                echo anchor(site_url('Tbl_pembelian/create_add_uraian_update/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');


                                                // echo " ";

                                                // echo anchor(site_url('Tbl_pembelian/delete_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-trash-o" aria-hidden="true">HAPUS SPOP</i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Anda Yakin akan Menghapus data SPOP ini?\')"');


                                                ?>


                                            </td>
                                            <td align="left">
                                                <?php
                                                echo $list_data->spop;
                                                $x_button = $x_button + 1;

                                                // echo "  ";
                                                // if ($list_data->status_spop) {
                                                //     echo anchor(site_url('tbl_pembelian/update_status_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">' . $list_data->status_spop . '</i>', 'class="btn btn-success btn-xs"');
                                                // } else {
                                                //     echo anchor(site_url('tbl_pembelian/update_status_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">STATUS</i>', 'class="btn btn-danger btn-xs"');
                                                // }

                                                ?>
                                            </td>

                                            <td align="left"><?php echo isset($list_data->kategori) ? htmlspecialchars($list_data->kategori, ENT_QUOTES, 'UTF-8') : ''; ?></td>

                                            <td align="center"><?php echo $list_data->nmrfakturkwitansi; ?></td>

                                            <td align="left"><?php echo $list_data->supplier_nama; ?></td>

                                        <?php
                                        }
                                        ?>



                                        <td align="center"><?php echo $list_data->kode_barang; ?></td>
                                        <td align="left"><?php echo $list_data->uraian; ?></td>
                                        <td align="right">
                                            <?php 
                                            echo nominal($list_data->jumlah); 
                                            $TOTAL_JUMLAH_BARANG = $TOTAL_JUMLAH_BARANG + $list_data->jumlah;
                                            ?>
                                            </td>
                                        <td align="left"><?php echo $list_data->satuan; ?></td>
                                        <td align="left"><?php echo $list_data->konsumen; ?></td>
                                        <td align="right">
                                            <?php

                                            echo number_format($list_data->harga_satuan, 2, ',', '.');

                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php
                                            $total_per_uraian = $list_data->jumlah * $list_data->harga_satuan;

                                            echo number_format($total_per_uraian, 2, ',', '.');

                                            $Total_per_SPOP = $Total_per_SPOP + $total_per_uraian;

                                            ?>
                                        </td>
                                        <td align="center">
                                            <?php
                                            if ($list_data->statuslu == "U") {
                                                echo "<font color='red'>" . $list_data->statuslu . "</font>";
                                                $TOTAL_HUTANG = $TOTAL_HUTANG + $total_per_uraian;
                                            } else {
                                                echo $list_data->statuslu;
                                                $TOTAL_LUNAS = $TOTAL_LUNAS + $total_per_uraian;
                                            }


                                            ?>
                                        </td>

                                        <td align="center">
                                            <?php

                                            if ($list_data->statuslu == "Lunas"  or $list_data->statuslu == "L") {
                                                echo $list_data->kas_bank;
                                            }

                                            ?>
                                        </td>

                                        <td align="center">
                                            <?php


                                            if (date("Y", strtotime($this->input->post('tgl_po', TRUE))) < 2020) {
                                                echo "";
                                            } else {
                                                echo $list_data->tgl_bayar;
                                            }

                                            ?>
                                        </td>
                                        <?php
                                        $compare_spop = $list_data->spop;
                                        $compare_uuid_spop = $list_data->uuid_spop;
                                        $list_spop_status_lu = $list_data->statuslu;
                                        ?>
                                    </tr>
                                <?php
                                }
                                ?>

                                <?php if (!empty($Tbl_pembelian_data)) { ?>
                                <!-- TOTAL SPOP AKHIR -->
                                <tr class="row-pembelian-subtotal">
                                    <td><?php echo ++$start ?></td>
                                    <td>
                                        <?php
                                        // if ($x_button == 1) {
                                        //     echo anchor(site_url('tbl_pembelian/update_per_spop/' . $compare_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                        //     echo anchor(site_url('tbl_pembelian/delete_per_spop/' . $compare_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');
                                        // }
                                        ?>
                                    </td>
                                    <!-- <td></td> -->
                                    <!-- <td></td> -->
                                    <td><?php echo $compare_spop; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="background-color:yellow;" align="right">
                                        <?php
                                        // echo "<font color='red'><strong>" . nominal($Total_per_SPOP) . "</strong></font>";
                                        echo "<font color='red'><strong>" . number_format($Total_per_SPOP, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        // if ($list_spop_status_lu == "U") {
                                        //     echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $compare_uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                        // }


                                        $result_pengajuan_by_uuid_spop = $this->Tbl_pembelian_pengajuan_bayar_model->get_by_uuid_spop($compare_uuid_spop);

                                        $TOTAL_Nominal_pengajuan = $this->Tbl_pembelian_pengajuan_bayar_model->get_sumNominal_by_uuid_spop($compare_uuid_spop)->total_pengajuan;

                                        if ($result_pengajuan_by_uuid_spop) {
                                            $startx = 0;
                                            $total_nominal_pengajuan = 0;
                                            foreach ($result_pengajuan_by_uuid_spop as $list_data_pengajuan) {
                                                // echo $list_data_pengajuan->uuid_pengajuan_bayar;
                                                echo anchor(site_url('tbl_pembelian/cetak_pengajuan_bayar_per_spop/' . $list_data_pengajuan->uuid_pengajuan_bayar), '<i class="fa fa-pencil-square-o" aria-hidden="true">Cetak Pengajuan ' . ++$startx . '</i>', 'class="btn btn-success btn-xs" target="_blank"');

                                                $total_nominal_pengajuan = $total_nominal_pengajuan + $list_data_pengajuan->nominal_pengajuan;
                                            }
                                            // echo $TOTAL_Nominal_pengajuan;
                                            // echo " : ";
                                            // echo $Total_per_SPOP;
                                            // echo " : ";
                                            if ($TOTAL_Nominal_pengajuan < $Total_per_SPOP) {
                                                if ($list_spop_status_lu == "Hutang"  or $list_spop_status_lu == "U") {
                                                    echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $compare_uuid_spop . '/pembelian'), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                                }
                                            }
                                        } else {
                                            // if ($total_nominal_pengajuan < $Total_per_SPOP) {


                                            if ($list_spop_status_lu == "Hutang"  or $list_spop_status_lu == "U") {

                                                echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $compare_uuid_spop . '/pembelian'), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                            }

                                            // }else{
                                            //     echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $compare_uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs" disabled');
                                            // }

                                        }


                                        ?>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php } ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align:right">
                                        
                                    
                                    <?php
                                        // echo nominal($TOTAL_LUNAS);
                                        echo number_format($TOTAL_JUMLAH_BARANG, 0, ',', '.');
                                        ?>
                                
                                </th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align:right">TOTAL LUNAS</th>
                                    <th style="text-align:right">
                                        <?php
                                        // echo nominal($TOTAL_LUNAS);
                                        echo number_format($TOTAL_LUNAS, 2, ',', '.');
                                        ?>
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align:right"><?php echo "<font color='red'>TOTAL HUTANG</font>"; ?></th>
                                    <th style="text-align:right">
                                        <?php
                                        // echo "<font color='red'>" . nominal($TOTAL_HUTANG) . "</font>"; 
                                        echo "<font color='red'>" . number_format($TOTAL_HUTANG, 2, ',', '.') . "</font>";
                                        ?>
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>

                        </table>

                            </div>
                            <div class="tab-pane fade<?php echo ($pembelian_active_tab === 'tab-pembelian-verifikasi') ? ' show active' : ''; ?>" id="tab-pembelian-verifikasi" role="tabpanel">
                                <div class="alert alert-warning py-2 px-3 small mb-3">
                                    <strong>Verifikasi Persediaan Pembelian</strong> — sub-tab:
                                    <strong>Belum Terverifikasi</strong> (<code>verified_persediaan</code> kosong),
                                    <strong>Terverifikasi Manual</strong> (<code>refered manual</code>),
                                    <strong>Verifikasi Otomatis</strong> (<code>refered</code>).
                                    Klik <strong>Referensi</strong> untuk hubungkan ke persediaan bulan filter (menambah <code>beli</code> &amp; <code>total_10</code>).
                                    <?php
                                    if (!empty($pembelian_verified_sync) && is_array($pembelian_verified_sync) && !empty($pembelian_verified_sync['ok'])) {
                                        echo ' <span class="text-muted">Sync otomatis: refered='
                                            . (int) (isset($pembelian_verified_sync['refered']) ? $pembelian_verified_sync['refered'] : 0)
                                            . ', belum='
                                            . (int) (isset($pembelian_verified_sync['belum']) ? $pembelian_verified_sync['belum'] : 0)
                                            . '.</span>';
                                    }
                                    ?>
                                </div>
                                <?php
                                $pembelian_verifikasi_ns = 'pembelian';
                                $pembelian_table_prefix = '';
                                include __DIR__ . '/_adminlte310_tbl_pembelian_verifikasi_persediaan_fragment.php';
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </section>

<?php include __DIR__ . '/_adminlte310_pembelian_verifikasi_referensi_modals.php'; ?>

</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
    /**
     * Ambil id tbl_pembelian dari baris yang tampil di DataTable (urutan sort + filter search sama seperti layar).
     */
    function kumpulkanIdPembelianDariDataTable() {
        var ids = [];
        if (!window.jQuery || !jQuery.fn.DataTable || !jQuery.fn.DataTable.isDataTable('#tglSPOPFreeze')) {
            return ids;
        }
        var table = jQuery('#tglSPOPFreeze').DataTable();
        table.rows({ search: 'applied', order: 'applied' }).every(function() {
            var node = this.node();
            if (!node) {
                return;
            }
            var rawId = node.getAttribute('data-pembelian-id');
            if (!rawId) {
                return;
            }
            var id = parseInt(rawId, 10);
            if (!isNaN(id) && id > 0) {
                ids.push(id);
            }
        });
        return ids;
    }

    function cetakExcelPembelian() {
        if (window.syncFilterBulanHiddenDates) { window.syncFilterBulanHiddenDates(); }
        var tglAwalEl = document.querySelector('#form-cari-pembelian input[name="tgl_awal"]');
        var tglAkhirEl = document.querySelector('#form-cari-pembelian input[name="tgl_akhir"]');
        var tglAwal = tglAwalEl ? tglAwalEl.value : '';
        var tglAkhir = tglAkhirEl ? tglAkhirEl.value : '';
        if (!tglAwal || !tglAkhir) {
            alert('Pilih bulan terlebih dahulu.');
            return;
        }

        var ids = kumpulkanIdPembelianDariDataTable();
        if (!ids.length) {
            var idsEl = document.getElementById('excel-export-ids');
            if (idsEl && idsEl.value) {
                ids = idsEl.value.split(',').map(function(v) {
                    return parseInt(v, 10);
                }).filter(function(v) {
                    return !isNaN(v) && v > 0;
                });
            }
        }

        if (!ids.length) {
            alert('Tidak ada data pembelian untuk diekspor. Periksa filter/search DataTable atau rentang tanggal.');
            return;
        }

        var sourceEl = document.getElementById('excel-export-source');
        var source = sourceEl ? sourceEl.value : 'tbl_pembelian';
        var url = '<?php echo site_url('Tbl_pembelian/excel'); ?>'
            + '?source=' + encodeURIComponent(source)
            + '&from_datatable=1'
            + '&ids=' + encodeURIComponent(ids.join(','))
            + '&tgl_awal=' + encodeURIComponent(tglAwal)
            + '&tgl_akhir=' + encodeURIComponent(tglAkhir);
        window.location.href = url;
    }

    (function() {
        function syncPembelianActiveTabInput() {
            var tabInput = document.getElementById('pembelian_active_tab_input');
            if (!tabInput || !window.jQuery) return;
            jQuery('#pembelian-main-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                var href = jQuery(e.target).attr('href') || '';
                if (href.charAt(0) === '#') {
                    tabInput.value = href.slice(1);
                }
            });
        }
        if (document.readyState === 'complete') {
            syncPembelianActiveTabInput();
        } else {
            window.addEventListener('load', syncPembelianActiveTabInput);
        }
    })();
</script>

<script>
(function() {
    function parseBulanToRange(bulanStr) {
        var s = String(bulanStr || '').trim();
        var m = s.match(/^(\d{1,2})[\/\-](\d{4})$/);
        if (!m) {
            return null;
        }
        var month = parseInt(m[1], 10);
        var year = parseInt(m[2], 10);
        if (month < 1 || month > 12 || year < 2000) {
            return null;
        }
        var lastDay = new Date(year, month, 0).getDate();
        function pad(n) { return (n < 10 ? '0' : '') + n; }
        return {
            awal: pad(1) + '-' + pad(month) + '-' + year,
            akhir: pad(lastDay) + '-' + pad(month) + '-' + year,
            label: pad(month) + '/' + year
        };
    }

    function syncHiddenFromBulan() {
        var form = document.getElementById('form-cari-pembelian');
        if (!form) return null;
        var inpBulan = form.querySelector('input[name="filter_bulan"]');
        var inpAwal = form.querySelector('input[name="tgl_awal"]');
        var inpAkhir = form.querySelector('input[name="tgl_akhir"]');
        if (!inpBulan || !inpAwal || !inpAkhir) return null;
        var range = parseBulanToRange(inpBulan.value);
        if (!range) return null;
        inpAwal.value = range.awal;
        inpAkhir.value = range.akhir;
        return range;
    }

    window.syncFilterBulanHiddenDates = syncHiddenFromBulan;

    var submitTimer = null;
    function submitCariPembelianOtomatis() {
        clearTimeout(submitTimer);
        submitTimer = setTimeout(function() {
            var form = document.getElementById('form-cari-pembelian');
            if (!form) return;
            if (!syncHiddenFromBulan()) return;
            form.submit();
        }, 350);
    }
    window.submitCariPembelianOtomatis = submitCariPembelianOtomatis;

    function initFilterBulanPicker() {
        var form = document.getElementById('form-cari-pembelian');
        if (!form || !window.jQuery || !jQuery.fn.datetimepicker) {
            return;
        }
        var $picker = jQuery('#filter_bulan');
        if (!$picker.length) return;

        if ($picker.data('DateTimePicker') || $picker.data('datetimepicker')) {
            try { $picker.datetimepicker('destroy'); } catch (e) {}
        }

        $picker.datetimepicker({
            format: 'MM/YYYY',
            viewMode: 'months',
            minViewMode: 'months',
            useCurrent: false,
            icons: {
                time: 'far fa-clock',
                date: 'far fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-check',
                clear: 'far fa-trash-alt',
                close: 'far fa-times-circle'
            }
        });

        syncHiddenFromBulan();

        $picker.off('change.datetimepicker.filterBulan hide.datetimepicker.filterBulan');
        $picker.on('change.datetimepicker.filterBulan', function(e) {
            if (e && e.date) {
                var m = e.date.month() + 1;
                var y = e.date.year();
                var pad = function(n) { return (n < 10 ? '0' : '') + n; };
                form.querySelector('input[name="filter_bulan"]').value = pad(m) + '/' + y;
            }
            submitCariPembelianOtomatis();
        });

        var inp = form.querySelector('input[name="filter_bulan"]');
        if (inp) {
            inp.addEventListener('change', submitCariPembelianOtomatis);
        }
    }

    if (document.readyState === 'complete') {
        initFilterBulanPicker();
    } else {
        window.addEventListener('load', initFilterBulanPicker);
    }
})();
</script>
<?php include __DIR__ . '/_adminlte310_pembelian_verifikasi_referensi_init.php'; ?>