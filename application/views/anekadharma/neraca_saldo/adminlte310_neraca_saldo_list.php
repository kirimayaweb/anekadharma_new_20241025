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
                                    <strong>NERACA SALDO</strong>
                                </div>
                            </div>

                            <div class="col-md-6">

                                <?php
                                // $action_cari_between_date = site_url('tbl_pembelian/cari_between_date');
                                $action_cari_between_date = site_url('neraca_saldo');
                                ?>

                                <form action="<?php echo $action_cari_between_date; ?>" method="post">
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
                                                <button type="submit" class="btn btn-danger btn-block btn-flat" disabled><i class="fa fa-sign-in" aria-hidden="true"></i> Cari</button>
                                            </strong>
                                        </div>

                                    </div>
                                </form>

                            </div>


                            <div class="col-2" text-align="left" align="left">
                                <!-- <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in" aria-hidden="true"></i> Cari</button> -->
                            </div>

                        </div>

                        <!-- </form> -->



                    </div>




                    <div class="card-body">

                        <table id="example" class="table table-striped dt-responsive w-100 table-bordered display nowrap table-hover mb-0" style="width:100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="text-align:center" width="10px">No</th>
                                    <th rowspan="2" style="text-align:center">Kode Rek.</th>
                                    <th rowspan="2" style="text-align:center">Uraian</th>
                                    <th colspan="2" style="text-align:center">NERACA SALDO 1 JANUARI 2023</th>
                                    <th colspan="2" style="text-align:center">PENYESUAIAN</th>
                                    <th colspan="2" style="text-align:center">NS SETELAH PENYESUAIAN</th>
                                    <th colspan="2" style="text-align:center">LABA/ RUGI</th>
                                </tr>
                                <tr>
                                    <th>debet</th>
                                    <th>kredit</th>
                                    <th>debet</th>
                                    <th>kredit</th>
                                    <th>debet</th>
                                    <th>kredit</th>
                                    <th>debet</th>
                                    <th>kredit</th>
                                </tr>


                            </thead>
                            <tbody>
                                <?php

                                // PEMBELIAN
                                $start = 0;
                                $TOTAL_DEBET = 0;
                                $TOTAL_KREDIT = 0;
                                $TOTAL_SALDO = 0;

                                foreach ($Data_Kode_Akun as $list_data) {

                                    $Get_Kode_akun = $list_data->kode_akun;

                                    // // GET KODE AKUN DARI TABEL PEMBELIAN : terbayar sebagai kredit / pengeluaran
                                    // $sql_pembelian = "SELECT sum(tbl_pembelian.jumlah*tbl_pembelian.harga_satuan) as kredit, tbl_pembelian.kode_akun as kode_akun
                                    // FROM tbl_pembelian    
                                    // WHERE tbl_pembelian.kode_akun='$Get_Kode_akun' AND tbl_pembelian.statuslu='L'
                                    // group BY tbl_pembelian.kode_akun";

                                    // // print_r($this->db->query($sql_pembelian)->result());
                                    // $Get_kode_akun_PEMBELIAN_kredit = $this->db->query($sql_pembelian)->row()->kredit;



                                    // PENJUALAN : 
                                    $Get_proses_bayar = "belum_bayar";
                                    $sql_pembelian = "SELECT sum(tbl_penjualan.jumlah*tbl_penjualan.harga_satuan) as kredit, tbl_penjualan.kode_akun as kode_akun
                                    FROM tbl_penjualan    
                                    WHERE tbl_penjualan.kode_akun='$Get_Kode_akun' AND tbl_penjualan.proses_bayar='$Get_proses_bayar'
                                    group BY tbl_penjualan.kode_akun";

                                    $Get_kode_akun_PENJUALAN_kredit = $this->db->query($sql_pembelian)->row()->kredit;



                                ?>
                                    <tr>
                                        <td align="left"><?php echo ++$start; ?></td>

                                        <td align="left">
                                            <?php
                                            echo $Get_Kode_akun;
                                            ?>
                                        </td>

                                        <!-- <td>Uraian</td> -->
                                        <td align="left">
                                            <?php
                                            // echo "uraian";
                                            echo $list_data->nama_akun;
                                            ?>
                                        </td>

                                        <!-- <td>debet_akhir_tahun_lalu</td> -->
                                        <td align="left">
                                            <?php
                                            echo "0";
                                            // echo $list_data->kode_akun;
                                            ?>
                                        </td>



                                        <!-- <td>kredit_akhir_tahun_lalu</td> -->
                                        <td align="left">
                                            <?php
                                            echo "0";
                                            // echo $Get_kode_akun_PEMBELIAN_kredit;
                                            ?>
                                        </td>


                                        <!-- <td>debet_penyesuaian</td> -->
                                        <td align="left">
                                            <?php
                                            echo "0";
                                            // echo $list_data->kode_akun;
                                            ?>
                                        </td>


                                        <!-- <td>kredit_penyesuaian</td> -->
                                        <td align="right">
                                            <?php
                                            // echo "kredit_penyesuaian";
                                            echo number_format($Get_kode_akun_PENJUALAN_kredit, 2, ',', '.');
                                            ?>
                                        </td>

                                        <!-- <td>debet_ns_setelah_penyesuaian</td> -->
                                        <td align="left">
                                            <?php
                                            echo "0";
                                            // echo $list_data->kode_akun;
                                            ?>
                                        </td>


                                        <!-- <td>kredit_ns_setelah_penyesuaian</td> -->
                                        <td align="left">
                                            <?php
                                            echo "0";
                                            // echo $list_data->kode_akun;
                                            ?>
                                        </td>

                                        <!-- <td>debet_laba_rugi</td> -->
                                        <td align="left">
                                            <?php
                                            echo "0";
                                            // echo $list_data->kode_akun;
                                            ?>
                                        </td>


                                        <!-- <td>kreditdebet_laba_rugi</td> -->
                                        <td align="left">
                                            <?php
                                            echo "0";
                                            // echo $list_data->kode_akun;
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
            "scrollY": 600,
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