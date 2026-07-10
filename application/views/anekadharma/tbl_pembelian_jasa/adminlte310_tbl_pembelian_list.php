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

        if (!isset($excel_export_ids_str)) {
            $excel_export_ids_str = '';
        }
        if (!isset($row_count)) {
            $row_count = !empty($Tbl_pembelian_data) ? count($Tbl_pembelian_data) : 0;
        }

        ?>

        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary pembelian-jasa-data-card">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-md-2" text-align="left"> <strong>DATA PEMBELIAN JASA</strong></div>
                            <div class="col-md-2" text-align="left" align="left">
                                <?php echo anchor(site_url('tbl_pembelian_jasa/create'), 'Input Pembelian Jasa', 'class="btn btn-danger"'); ?>
                            </div>

                            <div class="col-md-6">

                                <?php
                                // $action_cari_between_date="cari_between_date" ;
                                $action_cari_between_date = site_url('tbl_pembelian_jasa/cari_between_date');

                                ?>

                                <form id="form-cari-pembelian-jasa" action="<?php echo $action_cari_between_date; ?>" method="post">
                                    <div class="row">

                                        <div class="col-md-1" text-align="right" align="right"></div>

                                        <div class="col-md-3" text-align="right">
                                            <div class="input-group date" id="tgl_awal" name="tgl_awal" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#tgl_awal" id="tgl_awal" name="tgl_awal" value="<?php echo $Get_date_awal; ?>" required />
                                                <div class="input-group-append" data-target="#tgl_awal" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-1" text-align="center" align="center">s/d</div>

                                        <div class="col-md-3" text-align="left" align="left">
                                            <div class="input-group date" id="tgl_akhir" name="tgl_akhir" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#tgl_akhir" id="tgl_akhir" name="tgl_akhir" value="<?php echo $Get_date_akhir; ?>" required />
                                                <div class="input-group-append" data-target="#tgl_akhir" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2" text-align="left" align="left">
                                            <strong>
                                                <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in" aria-hidden="true"></i> Cari</button>
                                            </strong>
                                        </div>

                                    </div>
                                </form>
                            </div>

                            <div class="col-md-2" text-align="right" align="right">
                                <input type="hidden" id="excel-export-source" value="tbl_pembelian_jasa" />
                                <input type="hidden" id="excel-export-ids" value="<?php echo htmlspecialchars($excel_export_ids_str, ENT_QUOTES, 'UTF-8'); ?>" />
                                <button type="button" class="btn btn-success btn-block" onclick="cetakExcelPembelianJasa(); return false;">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Cetak ke Excel
                                </button>
                            </div>
                        </div>

                    </div>



                    <div class="card-body">
                        <div id="pembelian-jasa-loading" class="text-center py-3" style="display:none;">
                            <i class="fa fa-spinner fa-spin fa-2x text-danger"></i>
                            <p class="mt-2 mb-0">Memuat data pembelian jasa...</p>
                        </div>
                        <p class="text-muted mb-2"><small>Menampilkan <strong><?php echo (int) $row_count; ?></strong> baris data.</small></p>

                        <table id="tblPembelianJasaList" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <th style="text-align:center">Tgl Po</th>
                                    <th style="text-align:center">Spop</th>
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
                                if (!isset($pengajuan_by_uuid_spop)) {
                                    $pengajuan_by_uuid_spop = array();
                                }
                                if (!isset($pengajuan_sum_by_uuid_spop)) {
                                    $pengajuan_sum_by_uuid_spop = array();
                                }
                                if (!function_exists('render_pengajuan_pembelian_jasa_cell')) {
                                    function render_pengajuan_pembelian_jasa_cell($uuid_spop, $compare_uuid_spop, $Total_per_SPOP, $list_spop_status_lu, $pengajuan_by_uuid_spop, $pengajuan_sum_by_uuid_spop)
                                    {
                                        $result_pengajuan_by_uuid_spop = isset($pengajuan_by_uuid_spop[$uuid_spop]) ? $pengajuan_by_uuid_spop[$uuid_spop] : array();
                                        $TOTAL_Nominal_pengajuan = isset($pengajuan_sum_by_uuid_spop[$uuid_spop]) ? $pengajuan_sum_by_uuid_spop[$uuid_spop] : 0;
                                        $url_pengajuan = site_url('tbl_pembelian_jasa/create_pembayaran/' . $compare_uuid_spop . '/pembelian');

                                        if ($result_pengajuan_by_uuid_spop) {
                                            $startx = 0;
                                            foreach ($result_pengajuan_by_uuid_spop as $list_data_pengajuan) {
                                                echo anchor(site_url('tbl_pembelian_jasa/cetak_pengajuan_bayar_per_spop/' . $list_data_pengajuan->uuid_pengajuan_bayar), '<i class="fa fa-pencil-square-o" aria-hidden="true">Cetak Pengajuan ' . ++$startx . '</i>', 'class="btn btn-success btn-xs" target="_blank"');
                                            }

                                            if ($TOTAL_Nominal_pengajuan < $Total_per_SPOP) {
                                                if ($list_spop_status_lu == "Hutang" or $list_spop_status_lu == "U") {
                                                    echo anchor($url_pengajuan, '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs" target="_blank"');
                                                }
                                            }
                                        } elseif ($list_spop_status_lu == "Hutang" or $list_spop_status_lu == "U") {
                                            echo anchor($url_pengajuan, '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs" target="_blank"');
                                        }
                                    }
                                }
                                $compare_spop = 0;
                                $compare_uuid_spop = 0;
                                $start = isset($start) ? $start : 0;
                                $Total_per_SPOP = 0;
                                $TOTAL_LUNAS = 0;
                                $TOTAL_HUTANG = 0;
                                $TOTAL_JUMLAH = 0;
                                $TOTAL_HARGA = 0;
                                $list_spop_status_lu = "";
                                $x_button = 0;
                                foreach ($Tbl_pembelian_data as $list_data) {

                                    // $list_spop_status_lu = $list_data->statuslu; // untuk cek kondisi di baris terakhir (SPOP)
                                    // if (($compare_spop <> $list_data->spop) and ($start >= 1)) {
                                    if (($compare_uuid_spop <> $list_data->uuid_spop) and ($start >= 1)) {
                                        // Buat 1 baris untuk total dan background = KUNING
                                ?>
                                        <tr>
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
                                                // echo anchor(site_url('tbl_pembelian_jasa/update_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                // echo anchor(site_url('tbl_pembelian_jasa/delete_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');
                                                // }
                                                ?>

                                            </td>
                                            <td><?php echo $compare_spop; ?></td>
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
                                                render_pengajuan_pembelian_jasa_cell($compare_uuid_spop, $compare_uuid_spop, $Total_per_SPOP, $list_spop_status_lu, $pengajuan_by_uuid_spop, $pengajuan_sum_by_uuid_spop);
                                                $list_spop_status_lu = $list_data->statuslu; // untuk cek kondisi di baris terakhir (SPOP) ==> Ubah status_lu dengan status data record yang baru.

                                                ?>
                                            </td>
                                            <td></td>
                                            <td></td>

                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr>
                                        <?php
                                        if ($compare_uuid_spop == $list_data->uuid_spop) {
                                        ?>
                                            <td><?php echo ++$start ?></td>
                                            <td>

                                                <?php

                                                // echo $list_data->spop;


                                                if (($compare_uuid_spop == $list_data->uuid_spop) and $x_button == 1) {
                                                    // echo anchor(site_url('tbl_pembelian_jasa/update_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                    // echo anchor(site_url('tbl_pembelian_jasa/delete_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');
                                                    $x_button_show = 1;
                                                    $x_button = $x_button + 1;

                                                    // echo "jghjghjghhhhh";
                                                } else {
                                                    // echo "oooooooooooo";
                                                    echo date("d M Y", strtotime($list_data->tgl_po));
                                                    echo "<br/>";
                                                    echo anchor(site_url('tbl_pembelian_jasa/create_add_uraian_update/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                    // echo " ";

                                                    // echo anchor(site_url('tbl_pembelian_jasa/delete_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-trash-o" aria-hidden="true">HAPUS SPOP</i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Anda Yakin akan Menghapus data SPOP ini?\')"');

                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $list_data->spop; ?></td>
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

                                                echo anchor(site_url('tbl_pembelian_jasa/create_add_uraian_update/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');


                                                // echo " ";

                                                // echo anchor(site_url('tbl_pembelian_jasa/delete_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-trash-o" aria-hidden="true">HAPUS SPOP</i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Anda Yakin akan Menghapus data SPOP ini?\')"');


                                                ?>


                                            </td>
                                            <td align="left">
                                                <?php
                                                echo $list_data->spop;
                                                $x_button = $x_button + 1;

                                                // echo "  ";
                                                // if ($list_data->status_spop) {
                                                //     echo anchor(site_url('tbl_pembelian_jasa/update_status_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">' . $list_data->status_spop . '</i>', 'class="btn btn-success btn-xs"');
                                                // } else {
                                                //     echo anchor(site_url('tbl_pembelian_jasa/update_status_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">STATUS</i>', 'class="btn btn-danger btn-xs"');
                                                // }

                                                ?>
                                            </td>

                                            <td align="center"><?php echo $list_data->nmrfakturkwitansi; ?></td>

                                            <td align="left"><?php echo $list_data->supplier_nama; ?></td>

                                        <?php
                                        }
                                        ?>



                                        <td align="center"><?php echo $list_data->kode_barang; ?></td>
                                        <td align="left"><?php echo $list_data->uraian; ?></td>
                                        <td align="right"><?php
                                            echo nominal($list_data->jumlah);
                                            $TOTAL_JUMLAH = $TOTAL_JUMLAH + (float) $list_data->jumlah;
                                        ?></td>
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
                                            $TOTAL_HARGA = $TOTAL_HARGA + $total_per_uraian;

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

                                <?php if (!empty($Tbl_pembelian_data) && isset($list_data) && is_object($list_data)) { ?>
                                <!-- TOTAL SPOP AKHIR -->
                                <tr>
                                    <td><?php echo ++$start ?></td>
                                    <td>
                                        <?php
                                        // if ($x_button == 1) {
                                        //     echo anchor(site_url('tbl_pembelian_jasa/update_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                        //     echo anchor(site_url('tbl_pembelian_jasa/delete_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');
                                        // }
                                        ?>
                                    </td>
                                    <!-- <td></td> -->
                                    <!-- <td></td> -->
                                    <td><?php echo isset($list_data->spop) ? $list_data->spop : ''; ?></td>
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
                                        render_pengajuan_pembelian_jasa_cell($list_data->uuid_spop, $compare_uuid_spop, $Total_per_SPOP, $list_spop_status_lu, $pengajuan_by_uuid_spop, $pengajuan_sum_by_uuid_spop);
                                        ?>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php } ?>
                            </tbody>

                            <tfoot>
                                <tr style="background-color:#fff3cd;">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align:right">TOTAL</th>
                                    <th style="text-align:right">
                                        <?php echo "<strong>" . nominal($TOTAL_JUMLAH) . "</strong>"; ?>
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align:right">
                                        <?php echo "<strong>" . number_format($TOTAL_HARGA, 2, ',', '.') . "</strong>"; ?>
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <!-- <th></th> -->
                                    <!-- <th></th> -->
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
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
                                    <!-- <th></th> -->
                                    <!-- <th></th> -->
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
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </section>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    .pembelian-jasa-data-card {
        border: 2px solid #ffc107 !important;
        box-shadow: none;
    }

    .pembelian-jasa-data-card > .card-header {
        border-bottom: 1px solid #ffc107 !important;
    }

    #tblPembelianJasaList {
        border: 1px solid #ffc107;
    }

    #tblPembelianJasaList th,
    #tblPembelianJasaList td {
        border-color: #ffe082 !important;
    }
</style>

<script>
    function initPembelianJasaDataTable() {
        var tableSel = '#tblPembelianJasaList';
        if (!window.jQuery || !jQuery.fn.DataTable || !jQuery(tableSel).length) {
            return;
        }
        if (jQuery.fn.DataTable.isDataTable(tableSel)) {
            jQuery(tableSel).DataTable().destroy();
        }
        jQuery(tableSel).DataTable({
            deferRender: true,
            processing: true,
            pageLength: 50,
            lengthMenu: [[25, 50, 100, 250, -1], [25, 50, 100, 250, 'Semua']],
            scrollY: 520,
            scrollX: true,
            scrollCollapse: true,
            order: [],
            language: {
                processing: 'Memproses...',
                lengthMenu: 'Tampilkan _MENU_ baris',
                zeroRecords: 'Data tidak ditemukan',
                info: 'Baris _START_ s/d _END_ dari _TOTAL_',
                infoEmpty: 'Tidak ada data',
                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            }
        });
    }

    if (document.readyState === 'complete') {
        initPembelianJasaDataTable();
    } else {
        window.addEventListener('load', initPembelianJasaDataTable);
    }

    function cetakExcelPembelianJasa() {
        var tglAwal = document.querySelector('input[name="tgl_awal"]').value;
        var tglAkhir = document.querySelector('input[name="tgl_akhir"]').value;
        if (!tglAwal || !tglAkhir) {
            alert('Pilih tanggal awal dan tanggal akhir terlebih dahulu.');
            return;
        }
        var sourceEl = document.getElementById('excel-export-source');
        var idsEl = document.getElementById('excel-export-ids');
        var source = sourceEl ? sourceEl.value : 'tbl_pembelian_jasa';
        var ids = idsEl ? idsEl.value : '';
        var url = '<?php echo site_url('Tbl_pembelian_jasa/excel'); ?>?source=' + encodeURIComponent(source) + '&ids=' + encodeURIComponent(ids) + '&tgl_awal=' + encodeURIComponent(tglAwal) + '&tgl_akhir=' + encodeURIComponent(tglAkhir);
        window.location.href = url;
    }

    (function() {
        var submitTimer = null;

        function submitCariPembelianJasaOtomatis() {
            clearTimeout(submitTimer);
            submitTimer = setTimeout(function() {
                var form = document.getElementById('form-cari-pembelian-jasa');
                if (!form) {
                    return;
                }
                var tglAwal = form.querySelector('input[name="tgl_awal"]');
                var tglAkhir = form.querySelector('input[name="tgl_akhir"]');
                if (tglAwal && tglAkhir && tglAwal.value && tglAkhir.value) {
                    var loader = document.getElementById('pembelian-jasa-loading');
                    if (loader) {
                        loader.style.display = 'block';
                    }
                    form.submit();
                }
            }, 400);
        }

        function initAutoCariPembelianJasa() {
            var form = document.getElementById('form-cari-pembelian-jasa');
            if (!form) {
                return;
            }
            form.addEventListener('submit', function() {
                var loader = document.getElementById('pembelian-jasa-loading');
                if (loader) {
                    loader.style.display = 'block';
                }
            });
            form.querySelectorAll('input[name="tgl_awal"], input[name="tgl_akhir"]').forEach(function(el) {
                el.addEventListener('change', submitCariPembelianJasaOtomatis);
            });
            if (window.jQuery) {
                jQuery('#tgl_awal, #tgl_akhir').on('change.datetimepicker hide.datetimepicker', submitCariPembelianJasaOtomatis);
            }
        }

        if (document.readyState === 'complete') {
            initAutoCariPembelianJasa();
        } else {
            window.addEventListener('load', initAutoCariPembelianJasa);
        }
    })();
</script>