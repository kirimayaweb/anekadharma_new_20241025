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
                            <div class="col-2">
                                <div class="col-12" text-align="center"> <strong>BUKU KAS</strong></div>
                            </div>
                            <div class="col-6">
                                <form action="<?php echo $action_by_bulan; ?>" method="post">
                                    <div class="row">
                                        <div class="col-1" text-align="right"> <strong> </strong></div>
                                        <div class="col-6" text-align="left">

                                            <!-- <form action="/action_page.php"> -->
                                            <label for="bulan">BULAN :</label>
                                            <input type="month" id="bulan_pembelian" name="bulan_pembelian">
                                            <!-- <input type="submit"> -->
                                            <!-- </form> -->

                                        </div>
                                        <div class="col-2" text-align="right">

                                            <?php //echo anchor(site_url('Sys_supplier/stock/'), 'CARI', 'class="btn btn-danger"');
                                            ?>

                                            <button type="submit" class="btn btn-danger"> Cari</button>

                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="col-2">
                                <?php //echo anchor(site_url('Tbl_neraca_data/create'), 'Input Pembelian (Belanja Perusahaan)', 'class="btn btn-danger"');
                                ?>
                            </div>



                        </div>
                        <div class="row">
                            <div class="col-6">

                            </div>
                            <div class="col-4">

                            </div>
                            <div class="col-2">
                                <?php //echo anchor(site_url('Tbl_neraca_data/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); 
                                ?>
                            </div>



                        </div>



                    </div>
                    <br />



                    <div class="card-body">


                        <table id="ExampleOnFile" class="table table-bordered" style="width:100%">
                            <!-- <table class="table table-bordered table-striped" id="mytable"> -->
                            <thead>
                                <tr>
                                    <th width="80px">No</th>
                                    <!-- <th>Id</th> -->
                                    <th>Tanggal</th>
                                    <th>proses_transaksi</th>
                                    <th>Kode</th>
                                    <th>nama_barang</th>
                                    <th>Penjualan</th>
                                    <th>Pembelian</th>
                                    <th>Saldo</th>


                                </tr>

                            </thead>
                            <tbody>
                                <?php
                                $start=0;
                                $saldo_transaksi=0;
                                foreach ($buku_kas_data as $buku_kas) {
                                ?>
                                    <tr>
                                        <td width="80px"><?php echo ++$start ?></td>
                                        <td><?php echo date("d M Y", strtotime($buku_kas->tgl_transaksi)) ?></td>
                                        <td><?php echo $buku_kas->proses_transaksi ?></td>
                                        <td><?php echo $buku_kas->kode_akun ?></td>
                                        <td><?php echo $buku_kas->nama_barang ?></td>
                                        <td>
                                            <?php
                                            if ($buku_kas->proses_transaksi == "penjualan") {
                                                $x_nominal=$buku_kas->jumlah*$buku_kas->harga_satuan;
                                                echo nominal($x_nominal);
                                                $saldo_transaksi=$saldo_transaksi+$x_nominal;
                                            } else {
                                                echo "";
                                            }

                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($buku_kas->proses_transaksi == "pembelian") {
                                                $x_nominal_pembelian=$buku_kas->jumlah*$buku_kas->harga_satuan;
                                                echo nominal($x_nominal_pembelian);
                                                $saldo_transaksi=$saldo_transaksi-$x_nominal_pembelian;
                                            } else {
                                                echo "";
                                            }


                                            
                                            ?>
                                        </td>
                                        <td><?php echo $saldo_transaksi ?></td>


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