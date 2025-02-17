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
                            <div class="col-3" text-align="left"> <strong>DATA PEMBELIAN</strong></div>
                            <div class="col-6" text-align="left" align="left">
                                <?php //echo anchor(site_url('tbl_pembelian/jurnal_pembelian2_cekBelumAdaKodeAkun'), 'Pembelian Belum Ada Kode Akun', 'class="btn btn-danger"'); 
                                ?>
                            </div>
                            <div class="col-2" text-align="right" align="right">
                                <?php //echo anchor(site_url('tbl_pembelian/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); 
                                ?>
                            </div>
                        </div>

                    </div>



                    <div class="card-body">

                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <th style="text-align:center">Tanggal</th>
                                    <th style="text-align:center">Bukti</th>
                                    <th style="text-align:center">Keterangan</th>
                                    <th style="text-align:center">Kode Rekening</th>
                                    <th style="text-align:center">debet</th>
                                    <th style="text-align:center">Kredit</th>
                                    <th style="text-align:center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                $TOTAL_debet=0;
                                $TOTAL_kredit=0;
                                $TOTAL_saldo=0;
                                foreach ($Data_kas as $list_data) {
                                    // [0] => stdClass Object ( [nomor] => 4280 [tanggal] => 30/09/2024 [bukti] => BKK [keterangan] => Biaya PU/ATK : Putro Bengkel (Pembayaran SPOP No 558 Tgl 30/09/2024) [kode_rekening] => 4 [debet] => [kredit] => 1.750.000,00 )
                                ?>

                                    <tr>
                                        <td><?php
                                            echo ++$start;
                                            ?></td>
                                        <td> <?php echo date("d-m-Y", strtotime($list_data->tanggal)); ?></td>
                                        <td></td>
                                        <td>
                                            <?php
                                            echo $list_data->keterangan;
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $list_data->kode_rekening;
                                            ?>
                                        </td>
                                        <td>
                                        <?php
                                            echo number_format($list_data->debet, 2, ',', '.');
                                            $TOTAL_debet=$TOTAL_debet+$list_data->debet;
                                            ?>
                                        </td>
                                        <td>
                                        <?php
                                            echo number_format($list_data->kredit, 2, ',', '.');
                                            $TOTAL_kredit=$TOTAL_kredit+$list_data->kredit;
                                            
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo number_format($TOTAL_debet-$TOTAL_kredit, 2, ',', '.');
                                            ?>
                                        </td>

                                    </tr>

                                <?php
                                }
                                ?>

                            </tbody>

                            <!-- tfoot -->

                            <tfoot>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <th style="text-align:center">Tanggal</th>
                                    <th style="text-align:center">Bukti</th>
                                    <th style="text-align:center">Keterangan</th>
                                    <th style="text-align:center">Kode Rekening</th>
                                    <th style="text-align:center">debet</th>
                                    <th style="text-align:center">Kredit</th>
                                    <th style="text-align:center">Action</th>

                                </tr>

                            </tfoot>



                            <!-- end of tfoot -->


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