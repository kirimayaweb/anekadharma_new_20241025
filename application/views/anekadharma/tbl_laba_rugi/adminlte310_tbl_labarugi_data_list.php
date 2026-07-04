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



        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <!-- <div class="card card-primary"> -->


                <div class="row labarugi-list-row">
                    <div class="labarugi-col-tahunan">
                        <div class="card card-primary labarugi-card-tahunan">

                            <div class="card-header labarugi-card-header-tahunan">
                                <div class="row">
                                    <div class="col-12">

                                        <form action="<?php echo $action_input_labarugi_baru; ?>" method="post">
                                            <?php if ($status_laporan == "bukan_laporan") { ?>
                                            <div class="row">
                                                    <div class="col-5" text-align="right"> <strong>INPUT LABA-RUGI TAHUNAN:</strong></div>

                                                <div class="col-4" text-align="left">

                                                    <?php
                                                        $date_input = date("Y") + 1;
                                                        $year_10tahun_before = date("Y") - 10;


                                                    ?>
                                                        <select name="tahun_neraca" id="tahun_neraca" class="form-control select2" style="width: 100%; height: 60px;" required>
                                                            <option value="">Pilih Tahun </option>
                                                            <?php
                                                            while ($year_10tahun_before < $date_input) {
                                                            ?>
                                                                <option value="<?php echo $year_10tahun_before; ?>"> <?php echo $year_10tahun_before; ?> </option>
                                                            <?php
                                                                $year_10tahun_before++;
                                                            }

                                                            ?>
                                                        </select>

                                                </div>
                                                <div class="col-3" text-align="right">

                                                    <?php //echo anchor(site_url('Sys_supplier/stock/'), 'CARI', 'class="btn btn-danger"');
                                                    ?>
                                                        <button type="submit" class="btn btn-danger">Tambah</button>
                                                </div>
                                            </div>
                                            <?php } else { ?>
                                            <div class="labarugi-card-title-tahunan">
                                                <strong>DATA LABA-RUGI TAHUNAN</strong>
                                            </div>
                                            <?php } ?>

                                        </form>

                                    </div>
                                </div>
                            </div>



                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <table id="ExampleOnFile" class="display nowrap" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center" width="10px">No</th>
                                                    <th>Tahun</th>
                                                    <th>Action</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                foreach ($Tbl_TAHUN_labarugi_data as $list_data) {
                                                    if ((int) $list_data->tahun_neraca < 2026) {
                                                        continue;
                                                    }

                                                ?>

                                                    <tr>

                                                        <td><?php echo ++$start ?></td>

                                                        <td align="left"><?php echo $list_data->tahun_neraca; ?></td>
                                                        <td align="left">
                                                            <?php

                                                            // if ($status_laporan == "bukan_laporan") {


                                                            // $this->session->userdata('id_user_level') == 1 //superadmin
                                                            // $this->session->userdata('id_user_level') == 2 //admin
                                                            // $this->session->userdata('id_user_level') == 888 //KABAGKEUANGAN

                                                            if ($this->session->userdata('id_user_level') == 1 or $this->session->userdata('id_user_level') == 2 or $this->session->userdata('id_user_level') == 888) {

                                                                echo anchor(site_url('Tbl_laba_rugi/labarugi_form/' . $list_data->tahun_neraca), '<i class="fa fa-pencil-square-o"></i> Update Data', 'class="btn btn-warning btn-sm labarugi-action-btn"');
                                                            }


                                                            $Get_Bulan = 0;
                                                            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$list_data->tahun_neraca' And `bulan_transaksi`='$Get_Bulan' ";

                                                            $GET_tbl_labarugi_data_RECORD = $this->db->query($sql);

                                                            if ($GET_tbl_labarugi_data_RECORD->num_rows() > 0) {

                                                                echo anchor(site_url('Tbl_laba_rugi/labarugi_print/' . $list_data->tahun_neraca), '<i class="fa fa-print"></i> Cetak Laba-Rugi', 'class="btn btn-success btn-sm labarugi-action-btn" target="_blank"');
                                                            }

                                                            ?>
                                                        </td>

                                                    </tr>
                                                <?php
                                                    // }
                                                }
                                                ?>


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>


                    <div class="labarugi-col-bulanan">
                        <div class="card card-primary labarugi-card-bulanan">

                            <div class="card-header">
                                <div class="row">
                                    <div class="col-12">

                                        <form action="<?php echo $action_input_labarugi_baru_bulanan; ?>" method="post">
                                            <div class="row">
                                                <?php
                                                if ($status_laporan == "bukan_laporan") {
                                                ?>
                                                    <div class="col-5" text-align="right"> <strong>INPUT LABA-RUGI BULANAN:</strong></div>
                                                <?php
                                                } else {
                                                ?>
                                                    <div class="col-5" text-align="right"> <strong>LABA-RUGI BULANAN</strong></div>

                                                <?php
                                                }
                                                ?>
                                                <div class="col-4" text-align="left">
                                                    <?php
                                                    if ($status_laporan == "bukan_laporan") {
                                                    ?>
                                                        <div class="col-4" text-align="left">

                                                            <!-- <form action="/action_page.php"> -->
                                                            <!-- <label for="bulan">BULAN :</label> -->
                                                            <input type="month" id="bulan_neraca" name="bulan_neraca">
                                                            <!-- <input type="submit"> -->
                                                            <!-- </form> -->

                                                        </div>
                                                    <?php
                                                    }
                                                    ?>

                                                </div>
                                                <div class="col-3" text-align="right">

                                                    <?php //echo anchor(site_url('Sys_supplier/stock/'), 'CARI', 'class="btn btn-danger"');
                                                    if ($status_laporan == "bukan_laporan") {
                                                    ?>

                                                        <button type="submit" class="btn btn-danger">Tambah</button>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>


                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">

                                        <table id="example" class="display labarugi-table-bulanan" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center" class="col-no">No</th>
                                                    <th class="col-tahun">Tahun</th>
                                                    <th class="col-bulan">Bulan</th>
                                                    <th class="col-action">Action</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                function bulan_teks($angka_bulan)
                                                {
                                                    if ($angka_bulan == 1) {
                                                        $bulan_teks = "Januari";
                                                    } elseif ($angka_bulan == 2) {
                                                        $bulan_teks = "Februari";
                                                    } elseif ($angka_bulan == 3) {
                                                        $bulan_teks = "Maret";
                                                    } elseif ($angka_bulan == 4) {
                                                        $bulan_teks = "April";
                                                    } elseif ($angka_bulan == 5) {
                                                        $bulan_teks = "Mei";
                                                    } elseif ($angka_bulan == 6) {
                                                        $bulan_teks = "Juni";
                                                    } elseif ($angka_bulan == 7) {
                                                        $bulan_teks = "Juli";
                                                    } elseif ($angka_bulan == 8) {
                                                        $bulan_teks = "Agustus";
                                                    } elseif ($angka_bulan == 9) {
                                                        $bulan_teks = "September";
                                                    } elseif ($angka_bulan == 10) {
                                                        $bulan_teks = "Oktober";
                                                    } elseif ($angka_bulan == 11) {
                                                        $bulan_teks = "November";
                                                    } elseif ($angka_bulan == 12) {
                                                        $bulan_teks = "Desember";
                                                    } else {
                                                        $bulan_teks = "";
                                                    }
                                                    return $bulan_teks;
                                                }

                                                $this->load->helper('dashboard');
                                                $start = 0;
                                                foreach ($Tbl_BULAN_labarugi_data as $list_data) {
                                                    if (!dashboard_bulan_in_valid_range($list_data->tahun_neraca, $list_data->bulan_neraca)) {
                                                        continue;
                                                    }
                                                ?>

                                                    <tr>

                                                        <td><?php echo ++$start ?></td>

                                                        <td align="left"><?php echo $list_data->tahun_neraca; ?></td>
                                                        <td align="left" class="col-bulan"><?php echo $list_data->bulan_neraca . " (" . bulan_teks($list_data->bulan_neraca) . ")"; ?></td>
                                                        <td align="left" class="col-action">
                                                            <?php
                                                            $this->load->helper('dashboard');
                                                            $tahun_row = (int) $list_data->tahun_neraca;
                                                            $bulan_row = (int) $list_data->bulan_neraca;
                                                            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$tahun_row' And `bulan_transaksi`='$bulan_row' ";
                                                            $GET_tbl_labarugi_data_RECORD = $this->db->query($sql);
                                                            $can_update = in_array(dashboard_session_user_level($this), array(1, 2, 9), true);
                                                            $can_cetak = false;
                                                            if (dashboard_user_can_cetak_laporan($this) && $GET_tbl_labarugi_data_RECORD->num_rows() > 0) {
                                                                $this->load->helper('dashboard_laporan_publish');
                                                                $can_cetak = dashboard_laporan_is_published($this, 'laba_rugi', $tahun_row, $bulan_row);
                                                            }

                                                            if ($can_update || $can_cetak) {
                                                                echo '<div class="labarugi-bulanan-action-btns">';
                                                                echo '<div class="labarugi-bulanan-action-row">';
                                                                if ($can_update) {
                                                                    echo anchor(site_url('Tbl_laba_rugi/labarugi_form/' . $tahun_row . '/' . $bulan_row), '<i class="fa fa-pencil-square-o"></i> Update Data', 'class="btn btn-warning btn-sm labarugi-action-btn"');
                                                                }
                                                                if ($can_cetak) {
                                                                    echo anchor(site_url('Tbl_laba_rugi/labarugi_print/' . $tahun_row . '/' . $bulan_row), '<i class="fa fa-print"></i> Cetak Laba-Rugi', 'class="btn btn-success btn-sm labarugi-action-btn" target="_blank" title="Cetak Laba Rugi Konsolidasi"');
                                                                    echo anchor(site_url('Tbl_laba_rugi/labarugi_print_unit/' . $tahun_row . '/' . $bulan_row . '/rinci'), '<i class="fa fa-print"></i> Cetak LR per Unit (Rinci)', 'class="btn btn-info btn-sm labarugi-action-btn" target="_blank" title="Cetak Laba Rugi Per Unit Rinci"');
                                                                }
                                                                echo '</div>';
                                                                if ($can_cetak) {
                                                                    echo '<div class="labarugi-bulanan-action-row labarugi-bulanan-action-row-2">';
                                                                    echo anchor(site_url('Tbl_laba_rugi/labarugi_print_unit/' . $tahun_row . '/' . $bulan_row . '/sederhana'), '<i class="fa fa-print"></i> Cetak LR per Unit (Sederhana)', 'class="btn btn-primary btn-sm labarugi-action-btn" target="_blank" title="Cetak Laba Rugi Per Unit Sederhana"');
                                                                    echo '</div>';
                                                                }
                                                                echo '</div>';
                                                            }
                                                            ?>
                                                        </td>

                                                    </tr>
                                                <?php
                                                    // }
                                                }
                                                ?>


                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>













                <!-- /.card-body -->
                <!-- </div> -->
            </div>
        </div>
    </section>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }

    .labarugi-list-row {
        display: flex;
        flex-wrap: nowrap;
        align-items: flex-start;
        margin-left: 0;
        margin-right: 0;
    }

    .labarugi-col-tahunan {
        flex: 0 0 35%;
        max-width: 35%;
        padding-left: 0;
        padding-right: 8px;
    }

    .labarugi-col-bulanan {
        flex: 0 0 65%;
        max-width: 65%;
        padding-left: 8px;
        padding-right: 0;
    }

    @media (max-width: 992px) {
        .labarugi-list-row {
            flex-wrap: wrap;
        }

        .labarugi-col-tahunan,
        .labarugi-col-bulanan {
            flex: 0 0 100%;
            max-width: 100%;
            padding-left: 0;
            padding-right: 0;
        }

        .labarugi-col-bulanan {
            margin-top: 12px;
        }
    }

    .labarugi-card-tahunan .card-header strong,
    .labarugi-card-bulanan .card-header strong {
        font-size: 0.95rem;
    }

    .labarugi-card-header-tahunan {
        padding: 0.75rem 1rem;
    }

    .labarugi-card-title-tahunan {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.3;
    }

    .labarugi-card-title-tahunan strong {
        font-size: 1rem;
        letter-spacing: 0.01em;
        white-space: nowrap;
    }

    #example.labarugi-table-bulanan th.col-no,
    #example.labarugi-table-bulanan td:nth-child(1) {
        width: 36px;
        min-width: 36px;
        text-align: center;
        white-space: nowrap;
    }

    #example.labarugi-table-bulanan th.col-tahun,
    #example.labarugi-table-bulanan td:nth-child(2) {
        width: 52px;
        min-width: 52px;
        white-space: nowrap;
    }

    #example.labarugi-table-bulanan th.col-bulan,
    #example.labarugi-table-bulanan td:nth-child(3) {
        width: 120px;
        min-width: 110px;
        white-space: nowrap;
    }

    #example.labarugi-table-bulanan th.col-action,
    #example.labarugi-table-bulanan td.col-action {
        width: auto;
        min-width: 0;
        white-space: normal;
        vertical-align: middle;
    }

    #example.labarugi-table-bulanan {
        width: 100% !important;
    }

    .labarugi-bulanan-action-btns {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
        width: 100%;
    }

    .labarugi-bulanan-action-row {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        width: 100%;
    }

    .labarugi-action-btn {
        white-space: nowrap;
        font-size: 21px !important;
        padding: 10px 18px !important;
        margin: 0;
        line-height: 1.5 !important;
        font-weight: 500;
        border-radius: 6px;
    }

    .labarugi-action-btn i {
        margin-right: 7px;
        font-size: 20px !important;
    }

    .labarugi-col-tahunan td:last-child {
        white-space: normal;
    }

    .labarugi-col-tahunan .labarugi-action-btn {
        margin-right: 6px;
        margin-bottom: 4px;
    }

    #example_wrapper .dataTables_scrollBody {
        min-height: 520px;
    }

    #ExampleOnFile_wrapper .dataTables_scrollBody {
        min-height: 520px;
    }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            scrollY: "520px",
            scrollX: false,
            scrollCollapse: true,
            autoWidth: false,
            paging: true,
            pageLength: 15,
            lengthMenu: [[15, 25, 50, -1], [15, 25, 50, "Semua"]],
            order: [[1, 'desc'], [2, 'desc']],
            columnDefs: [
                { orderable: false, targets: [0, 3] },
                { width: "5%", targets: 0 },
                { width: "8%", targets: 1 },
                { width: "17%", targets: 2 },
                { width: "70%", targets: 3 }
            ]
        });
    });



    $(document).ready(function() {
        var table = $('#ExampleOnFile').DataTable({
            scrollX: true,
            scrollY: "520px",
            scrollCollapse: true,
            paging: true,
            pageLength: 15,
            lengthMenu: [[15, 25, 50, -1], [15, 25, 50, "Semua"]],
            // columnDefs: [
            //     { orderable: false, targets: 0 },
            //      { orderable: false, targets: -1 }
            //  ],
            //  ordering: [[ 1, 'asc' ]],
            // colReorder: {
            //     fixedColumnsLeft: 1,
            //      fixedColumnsRight: 1
            // }
        });

        new $.fn.dataTable.FixedColumns(table, {
            leftColumns: 3,
            // rightColumns: 1
        });
    });
</script>




<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 900,
            "scrollX": true
        });
    });
</script>