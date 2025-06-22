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
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>DATA PENJUALAN KE KONSUMEN</strong></div>
                                </div>
                            </div>
                            <div class="col-6">
                            </div>
                        </div>
                    </div>
                    <br />



                    <div class="card-body">

                        <table id="example" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <!-- <th style="text-align:center" width="100px">Action</th> -->
                                    <th style="text-align:center">Nama KONSUMEN/UNIT</th>
                                    <th style="text-align:center">Action</th>
                                    <th style="text-align:center">Total Pembayaran</th>
                                    <th style="text-align:center">Total Belanja</th>
                                    <th style="text-align:center">Total Accounting</th>
                                    <th style="text-align:center">Kekurangan</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                foreach ($Data_konsumen_tagihan as $list_data) {

                                    $uuid_konsumen_proses = $list_data->uuid_konsumen;

                                    $sql = "SELECT uuid_konsumen, SUM(tbl_penjualan_pembayaran.nominal_bayar) as nominal_bayar FROM tbl_penjualan_pembayaran WHERE tbl_penjualan_pembayaran.uuid_konsumen='$uuid_konsumen_proses' GROUP BY tbl_penjualan_pembayaran.uuid_konsumen";

                                    $Data_konsumen_pembayaran = $this->db->query($sql)->row();






                                    // CEK PENJUALAN ACCOUNTING
                                    $GET_KONSUMEN = $list_data->uuid_konsumen;
                                    $this->db->where('uuid_konsumen', $GET_KONSUMEN);
                                    //$this->db->where('password',  $test);
                                    $GET_tbl_penjualan_accounting = $this->db->get('tbl_penjualan_accounting');

                                    if ($GET_tbl_penjualan_accounting->num_rows() > 0) {

                                        $sql = "SELECT SUM( tbl_penjualan_accounting_a.jumlah *  tbl_penjualan_accounting_a.harga_satuan) as total_belanja_accounting, 
                                                        tbl_penjualan_accounting_a.uuid_konsumen as uuid_konsumen, 
                                                        tbl_penjualan_accounting_a.konsumen_nama as nama_konsumen                                                        
                                                        FROM  tbl_penjualan_accounting  tbl_penjualan_accounting_a
                                                        WHERE uuid_konsumen = '$GET_KONSUMEN' ";

                                        $Data_konsumen_tagihan_accounting = $this->db->query($sql)->row()->total_belanja_accounting;

                                        // echo $Data_konsumen_tagihan_accounting;
                                    } else {
                                        $Data_konsumen_tagihan_accounting = 0;
                                        // echo "0";
                                    }
                                    // CEK PENJUALAN ACCOUNTING



                                    // PEMBAYARAN ACCOUNTING

                                    // $uuid_konsumen_proses = $list_data->uuid_konsumen;

                                    $sql = "SELECT uuid_konsumen, SUM(tbl_penjualan_accounting_pembayaran.nominal_bayar) as nominal_bayar FROM tbl_penjualan_accounting_pembayaran WHERE tbl_penjualan_accounting_pembayaran.uuid_konsumen='$uuid_konsumen_proses' GROUP BY tbl_penjualan_accounting_pembayaran.uuid_konsumen";

                                    $Data_konsumen_pembayaran_accounting = $this->db->query($sql)->row();

                                    // TERBAYAR
                                    // $Total_terbayar_accounting = $Data_konsumen_pembayaran_accounting->nominal_bayar;

                                    // END OF PEMBAYARAN ACCOUNTING



                                    // TERBAYAR
                                    $Total_terbayar = $Data_konsumen_pembayaran->nominal_bayar + $Data_konsumen_pembayaran_accounting->nominal_bayar;


                                    $x_kekurangan = ($list_data->total_belanja + $Data_konsumen_tagihan_accounting) - ($Data_konsumen_pembayaran->nominal_bayar + $Data_konsumen_pembayaran_accounting->nominal_bayar);
                                ?>
                                    <tr>
                                        <td><?php echo ++$start ?></td>
                                        <td align="left">
                                            <?php
                                            echo $list_data->nama_konsumen;
                                            // echo "&nbsp &nbsp";
                                            // echo anchor(site_url('tbl_pembelian/pembayaran'), '<i class="fa fa-pencil-square-o" aria-hidden="true">PEMBAYARAN</i>', 'class="btn btn-warning btn-xs"');
                                            ?>
                                        </td>
                                        <td align="left">
                                            <?php

                                            if ($x_kekurangan <= 0) {
                                                echo anchor(site_url('tbl_pembelian/tagihan_per_uuid_konsumen/' . $list_data->uuid_konsumen), '<i class="fa fa-pencil-square-o" aria-hidden="true">LUNAS</i>', 'class="btn btn-success btn-xs"');
                                            } else {
                                                echo anchor(site_url('tbl_pembelian/tagihan_per_uuid_konsumen/' . $list_data->uuid_konsumen), '<i class="fa fa-pencil-square-o" aria-hidden="true">PEMBAYARAN</i>', 'class="btn btn-warning btn-xs"');
                                            }

                                            // echo $list_data->nama_konsumen;
                                            // echo "&nbsp &nbsp";

                                            ?>
                                        </td>
                                        <!-- <td align="left"><?php //echo $list_data->jumlah_belanja; 
                                                                ?></td> -->
                                        <td align="right"><?php
                                                            echo '<span style="color:green;text-align:right;"> ' . number_format($Total_terbayar, 2, ',', '.') . '</span>';
                                                            ?></td>

                                        <td align="right"><?php
                                                            // echo nominal($list_data->total_belanja); 
                                                            echo number_format($list_data->total_belanja, 2, ',', '.');
                                                            ?>
                                        </td>


                                        <td align="right">
                                            <?php
                                            // echo $Data_konsumen_tagihan_accounting;
                                            echo number_format($Data_konsumen_tagihan_accounting, 2, ',', '.');
                                            ?>
                                        </td>

                                        <td align="right">
                                            <?php


                                            if ($x_kekurangan > 0) {
                                                echo '<span style="color:red;text-align:right;"> -' . number_format($x_kekurangan, 2, ',', '.') . '</span>';
                                            } else {
                                                echo '<span style="color:green;text-align:right;"> ' . number_format($x_kekurangan, 2, ',', '.') . '</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>


                            </tbody>


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
            "scrollY": 900,
            "scrollX": true
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