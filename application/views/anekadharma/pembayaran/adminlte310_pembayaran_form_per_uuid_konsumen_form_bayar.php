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
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>DATA TAGIHAN DAN FORM PEMBAYARAN: <?php echo $nama_konsumen . ", " . $alamat_konsumen ?> </strong></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <br /> -->



                    <div class="card-body">

                        <table id="example" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <th style="text-align:center" width="100px">Action</th>
                                    <th>tgl_jual</th>
                                    <th>nmrpesan</th>
                                    <th>nmrkirim</th>
                                    <!-- <th>konsumen_nama</th> -->
                                    <th>kode_barang</th>
                                    <th>nama_barang</th>
                                    <th>jumlah</th>
                                    <th>satuan</th>
                                    <th>harga_satuan</th>
                                    <th>total_nominal</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                foreach ($Data_konsumen_tagihan as $list_data) {

                                ?>

                                    <tr>
                                        <td><?php echo ++$start ?></td>

                                        <td align="left">
                                            <?php
                                            // echo $list_data->nama_konsumen;
                                            // echo "&nbsp &nbsp";
                                            echo anchor(site_url('tbl_pembelian/tagihan_per_uuid_konsumen/'.$list_data->uuid_konsumen), '<i class="fa fa-pencil-square-o" aria-hidden="true">PEMBAYARAN</i>', 'class="btn btn-warning btn-xs"');
                                            ?>
                                        </td>

                                        <td align="left"><?php echo $list_data->tgl_jual; ?></td>
                                        <td align="left"><?php echo $list_data->nmrpesan; ?></td>
                                        <td align="left"><?php echo $list_data->nmrkirim; ?></td>
                                        <!-- <td align="left"><?php //echo $list_data->konsumen_nama; ?></td> -->
                                        <td align="left"><?php echo $list_data->kode_barang; ?></td>
                                        <td align="left"><?php echo $list_data->nama_barang; ?></td>
                                        <td align="left"><?php echo $list_data->jumlah; ?></td>
                                        <td align="left"><?php echo $list_data->satuan; ?></td>
                                        <td align="left"><?php echo $list_data->harga_satuan; ?></td>
                                        <td align="left"><?php echo $list_data->total_nominal; ?></td>                            

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
            "scrollY": 300,
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