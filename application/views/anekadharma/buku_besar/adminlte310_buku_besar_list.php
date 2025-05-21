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
                <div class="card card-primary">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-5" text-align="center"> <strong>BUKU BESAR</strong></div>

                                </div>
                            </div>




                            <div class="col-5">
                                <!-- <form> -->
                                <?php echo form_open('Buku_besar/cari_kode_akun'); ?>

                                <select name="kode_akun" id="kode_akun" class="form-control select2" style="width: 100%; height: 40px;" required>

                                    <?php
                                    if ($uuid_kode_akun) {
                                    ?>
                                        <option value="<?php echo $uuid_kode_akun; ?>"><?php echo $kode_akun; ?></option>
                                    <?php
                                    } else {
                                    ?>
                                        <option value="">Pilih Kode Akun</option>
                                    <?php
                                    }
                                    ?>
                                    <option value="">Tampil Semua Data</option>
                                    <?php
                                    $sql = "select * from sys_kode_akun  order by  kode_akun ASC ";
                                    foreach ($this->db->query($sql)->result() as $m) {
                                        // foreach ($data_produk as $m) {
                                        echo "<option value='$m->uuid_kode_akun' ";
                                        echo ">  " . strtoupper($m->kode_akun) . " ==> ( " . strtoupper($m->nama_akun) . ")</option>";
                                    }
                                    ?>
                                </select>

                            </div>


                            <div class="col-2" text-align="left" align="left">
                                <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in" aria-hidden="true"></i> Cari</button>
                            </div>

                        </div>

                        </form>



                    </div>




                    <div class="card-body">

                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <th>Tanggal</th>
                                    <th>PL</th>
                                    <th>Kode</th>
                                    <th>Kode Akun</th>
                                    <th>Nama Akun</th>
                                    <th>Keterangan</th>

                                    <th>Debet</th>
                                    <th>Kredit</th>
                                    <th>Saldo</th>
                                </tr>


                            </thead>
                            <tbody>
                                <?php


                                // PEMBELIAN
                                $start = 0;
                                $TOTAL_DEBET = 0;
                                $TOTAL_KREDIT = 0;
                                $TOTAL_SALDO = 0;

                                foreach ($Data_pembelian as $list_data) {
                                    if ($list_data->kode_akun) {
                                ?>
                                        <tr>
                                            <td align="left"><?php echo ++$start; ?></td>
                                            <td align="left">
                                                <?php
                                                echo date("d-M-Y", strtotime($list_data->tanggal));
                                                ?>
                                            </td>

                                            <!-- Kode pl -->
                                            <td align="left">
                                                <?php
                                                echo $list_data->kode_pl;
                                                // echo "kode";
                                                ?>
                                            </td>
                                            <!-- Kode -->
                                            <td align="left">
                                                <?php
                                                echo $list_data->kode_bb;
                                                // echo "kode";
                                                ?>
                                            </td>

                                            <!-- Kode akun -->
                                            <td align="left">
                                                <?php
                                                echo $list_data->kode_akun;
                                                ?>
                                            </td>

                                            <td align="left">
                                                <?php
                                                if ($list_data->nama_akun == "") {

                                                    $this->db->where('kode_akun', $list_data->kode_akun);
                                                    $data_akun = $this->db->get('sys_kode_akun');

                                                    if ($data_akun->num_rows() > 0) {

                                                        $Get_data_akun = $data_akun->row_array();
                                                        echo $Get_data_akun['nama_akun'];
                                                    }
                                                } else {
                                                    echo $list_data->nama_akun;
                                                }
                                                ?>
                                            </td>


                                            <td align="left">
                                                <?php
                                                echo  $list_data->keterangan;
                                                ?>
                                            </td>

                                            <td align="right">
                                                <?php
                                                // echo $list_data->debet;
                                                // echo "<br/>";
                                                // echo "debet";

                                                // echo number_format($list_data->debet, 2, ',', '.');
                                                // $TOTAL_DEBET = $TOTAL_DEBET + $list_data->debet;

                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php

                                                echo number_format($list_data->kredit, 2, ',', '.');

                                                $TOTAL_KREDIT = $TOTAL_KREDIT + $list_data->kredit;
                                                // $TOTAL_SALDO = $TOTAL_DEBET - $TOTAL_KREDIT;

                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                // echo "saldo";
                                                // echo number_format($TOTAL_SALDO, 2, ',', '.');
                                                ?>
                                            </td>


                                        </tr>


                                <?php
                                    }
                                }
                                ?>


                                <?php
                                // PENJUALAN
                                // $start = 0;
                                // $TOTAL_DEBET = 0;
                                // $TOTAL_KREDIT = 0;
                                // $TOTAL_SALDO = 0;

                                foreach ($Data_penjualan as $list_data) {
                                    if ($list_data->kode_akun) {
                                ?>
                                    <tr>
                                        <td align="left"><?php echo ++$start; ?></td>
                                        <td align="left">
                                            <?php
                                            echo date("d-M-Y", strtotime($list_data->tanggal));
                                            ?>
                                        </td>

                                        <!-- Kode pl -->
                                        <td align="left">
                                            <?php
                                            echo $list_data->kode_pl;
                                            // echo "kode";
                                            ?>
                                        </td>

                                        <td align="left">
                                            <?php
                                            echo $list_data->kode_bb;
                                            // echo "kode";
                                            ?>
                                        </td>

                                        <td align="left">
                                            <?php
                                            echo $list_data->kode_akun;
                                            ?>
                                        </td>

                                        <td align="left">
                                            <?php
                                            if ($list_data->nama_akun == "") {

                                                $this->db->where('kode_akun', $list_data->kode_akun);
                                                $data_akun = $this->db->get('sys_kode_akun');

                                                if ($data_akun->num_rows() > 0) {

                                                    $Get_data_akun = $data_akun->row_array();
                                                    echo $Get_data_akun['nama_akun'];
                                                }
                                            } else {
                                                echo $list_data->nama_akun;
                                            }
                                            ?>
                                        </td>

                                        <td align="left">
                                            <?php
                                            echo  $list_data->keterangan;
                                            ?>
                                        </td>


                                        <td align="right">
                                            <?php
                                            // echo $list_data->debet;
                                            // echo "<br/>";
                                            // echo "debet";

                                            echo number_format($list_data->debet, 2, ',', '.');
                                            $TOTAL_DEBET = $TOTAL_DEBET + $list_data->debet;

                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php

                                            // echo number_format($list_data->kredit, 2, ',', '.');

                                            // $TOTAL_KREDIT = $TOTAL_KREDIT + $list_data->kredit;
                                            // $TOTAL_SALDO = $TOTAL_DEBET - $TOTAL_KREDIT;

                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php
                                            // echo "saldo";
                                            // echo number_format($TOTAL_SALDO, 2, ',', '.');
                                            ?>
                                        </td>


                                    </tr>


                                <?php
                                    }
                                }
                                ?>





                            </tbody>

                            <tfoot>
                                <tr>
                                    <th style="text-align:center" width="10px"></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>

                                    <th style="text-align:right">
                                        <?php
                                        echo number_format($TOTAL_DEBET, 2, ',', '.');
                                        // $TOTAL_DEBET = $TOTAL_DEBET + $list_data->debet;
                                        ?>
                                    </th>

                                    <th style="text-align:right">
                                        <?php
                                        echo number_format($TOTAL_KREDIT, 2, ',', '.');
                                        // $TOTAL_DEBET = $TOTAL_DEBET + $list_data->debet;
                                        ?>
                                    </th>

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
            "scrollY": 1100,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 1100,
            "scrollX": true
        });
    });
</script>