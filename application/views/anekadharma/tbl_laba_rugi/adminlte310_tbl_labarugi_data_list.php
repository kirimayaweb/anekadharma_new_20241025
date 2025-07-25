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


                <div class="row">
                    <div class="col-6">
                        <div class="card card-primary">

                            <div class="card-header">
                                <div class="row">
                                    <div class="col-12">

                                        <form action="<?php echo $action_input_labarugi_baru; ?>" method="post">
                                            <div class="row">
                                                <?php
                                                if ($status_laporan == "bukan_laporan") {
                                                ?>
                                                    <div class="col-5" text-align="right"> <strong>INPUT LABA-RUGI TAHUNAN:</strong></div>
                                                <?php
                                                } else {
                                                ?>
                                                    <div class="col-5" text-align="right"> <strong>DATA LABA-RUGI TAHUNAN</strong></div>
                                                <?php
                                                }
                                                ?>

                                                <div class="col-4" text-align="left">

                                                    <?php
                                                    if ($status_laporan == "bukan_laporan") {
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

                                                    <?php
                                                    }
                                                    ?>

                                                </div>
                                                <div class="col-3" text-align="right">

                                                    <?php //echo anchor(site_url('Sys_supplier/stock/'), 'CARI', 'class="btn btn-danger"');
                                                    ?>
                                                    <?php

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

                                                    // if ($list_data->tahun_neraca == 0) {

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

                                                                echo anchor(site_url('Tbl_laba_rugi/labarugi_form/' . $list_data->tahun_neraca), '<i class="fa fa-pencil-square-o" aria-hidden="true">Update Data</i>', 'class="btn btn-warning btn-xs"');
                                                            }


                                                            $Get_Bulan = 0;
                                                            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$list_data->tahun_neraca' And `bulan_transaksi`='$Get_Bulan' ";

                                                            $GET_tbl_labarugi_data_RECORD = $this->db->query($sql);

                                                            if ($GET_tbl_labarugi_data_RECORD->num_rows() > 0) {

                                                                echo anchor(site_url('Tbl_laba_rugi/labarugi_print/' . $list_data->tahun_neraca), '<i class="fa fa-pencil-square-o" aria-hidden="true">Cetak Laba-Rugi</i>', 'class="btn btn-success btn-xs" target="_blank"');
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


                    <div class="col-6">
                        <div class="card card-primary">

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

                                        <table id="example" class="display nowrap" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center" width="10px">No</th>
                                                    <th>Tahun</th>
                                                    <th>Bulan</th>
                                                    <th>Action</th>

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

                                                $start = 0;
                                                foreach ($Tbl_BULAN_labarugi_data as $list_data) {
                                                    // if ($list_data->bulan_transaksi > 0) {
                                                ?>

                                                    <tr>

                                                        <td><?php echo ++$start ?></td>

                                                        <td align="left"><?php echo $list_data->tahun_neraca; ?></td>
                                                        <td align="left"><?php echo $list_data->bulan_neraca . " (" . bulan_teks($list_data->bulan_neraca) . ")"; ?></td>
                                                        <td align="left">
                                                            <?php

                                                            // if ($status_laporan == "bukan_laporan") {

                                                            // $this->session->userdata('id_user_level') == 1 //superadmin
                                                            // $this->session->userdata('id_user_level') == 2 //admin
                                                            // $this->session->userdata('id_user_level') == 888 //kabagkeuangan

                                                            // if ($this->session->userdata('id_user_level') == 1 or $this->session->userdata('id_user_level') == 2 or $this->session->userdata('id_user_level') == 888) {

                                                            //     echo anchor(site_url('Tbl_laba_rugi/labarugi_form/' . $list_data->uuid_data_neraca), '<i class="fa fa-pencil-square-o" aria-hidden="true">Update Data</i>', 'class="btn btn-warning btn-xs"');
                                                            // }

                                                            if ($this->session->userdata('id_user_level') == 1 or $this->session->userdata('id_user_level') == 2 or $this->session->userdata('id_user_level') == 9) {

                                                                echo anchor(site_url('Tbl_laba_rugi/labarugi_form/' . $list_data->tahun_neraca . '/' . $list_data->bulan_neraca), '<i class="fa fa-pencil-square-o" aria-hidden="true">Update Data</i>', 'class="btn btn-warning btn-xs"');
                                                            }



                                                            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$list_data->tahun_neraca' And `bulan_transaksi`='$list_data->bulan_neraca' ";

                                                            $GET_tbl_labarugi_data_RECORD = $this->db->query($sql);

                                                            if ($GET_tbl_labarugi_data_RECORD->num_rows() > 0) {
                                                                echo anchor(site_url('Tbl_laba_rugi/labarugi_print/' . $list_data->tahun_neraca . '/' . $list_data->bulan_neraca), '<i class="fa fa-pencil-square-o" aria-hidden="true">Cetak Laba-Rugi</i>', 'class="btn btn-success btn-xs" target="_blank"');
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
</style>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "scrollY": 250,
            "scrollX": true
        });
    });



    $(document).ready(function() {
        var table = $('#ExampleOnFile').DataTable({
            scrollX: true,
            scrollY: "400px",
            scrollCollapse: true,
            paging: true,
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