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
                                    <div class="col-12" text-align="center"> <strong>DATA STOCK BARANG</strong></div>
                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>
                        <div class="row">
                            <div class="col-6">
                                <?php //echo anchor(site_url('tbl_pembelian/create'), 'Input Pembelian (Belanja Perusahaan)', 'class="btn btn-danger"'); ?>
                            </div>
                            <div class="col-4">

                            </div>
                            <div class="col-2">
                                <?php //echo anchor(site_url('tbl_pembelian/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); ?>
                            </div>



                        </div>



                    </div>
                    <br />



                    <div class="card-body">

                        <table id="exampleFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <!-- <th style="text-align:center" width="100px">Action</th> -->
                                    <th>Tgl Po</th>
                                    <th>nama_barang_beli</th>
                                    <th>harga_satuan_beli</th>
                                    <th>jumlah_belanja</th>
                                    
                                    <!-- <th>nama_barang_jual</th> -->
                                    <th>jumlah_terjual</th>
                                    <!-- <th>harga_satuan_jual</th> -->
                                    <!-- <th>margin</th> -->
                                    <th>Sisa Stock</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $compare_spop = 0;
                                $Total_per_SPOP = 0;
                                $TOTAL_LUNAS = 0;
                                $TOTAL_HUTANG = 0;
                                $start=0;
                                foreach ($Data_stock as $list_data) {
                                   
                                ?>
                                        <tr>
                                            <td><?php echo ++$start ?></td>
                                            <td><?php echo $list_data->tgl_po; ?></td>
                                            <td><?php echo $list_data->nama_barang_beli; ?></td>
                                            <td style="text-align:right"><?php echo nominal($list_data->harga_satuan_beli); ?></td>
                                            <td style="text-align:right"><?php echo $list_data->jumlah_belanja; ?></td>
                                            
                                            <!-- <td><?php //echo $list_data->nama_barang_jual; ?></td> -->
                                            <td style="text-align:right"><?php echo $list_data->jumlah_terjual; ?></td>
                                            <!-- <td><?php //echo nominal($list_data->harga_satuan_jual); ?></td> -->
                                            <!-- <td><?php //echo nominal($list_data->jumlah_terjual*($list_data->harga_satuan_jual-$list_data->harga_satuan_beli)); ?></td> -->
                                            <td style="text-align:center"><?php echo nominal($list_data->jumlah_belanja-$list_data->jumlah_terjual); ?></td>
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