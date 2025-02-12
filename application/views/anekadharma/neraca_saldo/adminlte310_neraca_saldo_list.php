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

                            <div class="col-5">
                                <!-- <form> -->
                                <?php //echo form_open('neraca_saldo/cari_kode_akun'); 
                                ?>
                                <!-- 
                                <select name="kode_akun" id="kode_akun" class="form-control select2" style="width: 100%; height: 40px;" required>

                                    <?php
                                    // if ($uuid_kode_akun) {
                                    ?>
                                        <option value="<?php //echo $uuid_kode_akun; 
                                                        ?>"><?php //echo $kode_akun; 
                                                            ?></option>
                                    <?php
                                    // } else {
                                    ?>
                                        <option value="">Pilih Kode Akun</option>
                                    <?php
                                    // }
                                    ?>

                                    <?php
                                    // $sql = "select * from sys_kode_akun  order by  kode_akun ASC ";
                                    // foreach ($this->db->query($sql)->result() as $m) {
                                    //     // foreach ($data_produk as $m) {
                                    //     echo "<option value='$m->uuid_kode_akun' ";
                                    //     echo ">  " . strtoupper($m->kode_akun) . " ==> ( " . strtoupper($m->nama_akun) . ")</option>";
                                    // }
                                    ?>
                                </select> -->

                            </div>


                            <div class="col-2" text-align="left" align="left">
                                <!-- <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in" aria-hidden="true"></i> Cari</button> -->
                            </div>

                        </div>

                        <!-- </form> -->



                    </div>




                    <div class="card-body">

                        <table id="example" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <th>Kode Rek.</th>
                                    <th>Uraian</th>
                                    <th>debet_akhir_tahun_lalu</th>
                                    <th>kredit_akhir_tahun_lalu</th>
                                    <th>debet_penyesuaian</th>
                                    <th>kredit_penyesuaian</th>
                                    <th>debet_ns_setelah_penyesuaian</th>
                                    <th>kredit_ns_setelah_penyesuaian</th>
                                    <th>debet_laba_rugi</th>
                                    <th>kreditdebet_laba_rugi</th>

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