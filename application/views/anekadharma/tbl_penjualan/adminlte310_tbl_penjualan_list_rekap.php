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
                                    <div class="col-12" text-align="center"> <strong>REKAP PENJUALAN</strong></div>
                                </div>


                            </div>


                            <div class="col-6">
                                <form action="<?php echo $action_cari_konsumen; ?>" method="post">
                                    <div class="row">
                                        <div class="col-4" text-align="right"> <strong>KONSUMEN</strong></div>
                                        <div class="col-6" text-align="left">

<!--  
                                            <select name="uuid_konsumen" id="uuid_konsumen" class="form-control select2" style="width: 100%; height: 60px;" required>

                                                <?php //if (isset($data_selection)) {
                                                ?>
                                                    <option value="<?php //echo $data_selection; ?>"><?php //echo $nama_konsumen_selection; ?></option>
                                                <?php
                                                // } else {
                                                ?>
                                                    <option value="">Pilih Konsumen</option>
                                                <?php
                                                // }
                                                ?>


                                                <option value="semua">TAMPIL SEMUA</option>
                                                <?php

                                                // $sql = "SELECT `uuid_konsumen`,`konsumen_nama` FROM `tbl_penjualan` GROUP by `uuid_konsumen` order by konsumen_nama ASC ";
                                                // foreach ($this->db->query($sql)->result() as $m) {
                                                //     echo "<option value='$m->uuid_konsumen' ";
                                                //     echo ">  " . strtoupper($m->konsumen_nama)  . "</option>";
                                                // }
                                                ?>
                                            </select> -->

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
                                <?php echo anchor(site_url('tbl_penjualan/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); ?>
                            </div>



                        </div>




                    </div>




                    <div class="card-body">

                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <!-- <tr>
                                    <th rowspan="2" style="text-align:center" width="10px">No</th>
                                    <th rowspan="2">Tgl Jual</th>
                                    <th rowspan="2">nmrpesan</th>
                                    <th rowspan="2">nmrkirim</th>
                                    <th rowspan="2">Konsumen</th>
                                    <th rowspan="2">Kode</th>
                                    <th rowspan="2">Nama Barang</th>
                                    <th rowspan="2">Unit</th>
                                    <th rowspan="2">Satuan</th>
                                    <th rowspan="2">Harga Satuan</th>
                                    <th rowspan="2">Jumlah</th>

                                    <th colspan="2" style="text-align:center">Debit</th>
                                    <th colspan="2" style="text-align:center">Kredit</th> -->



                                <tr>
                                    <th>No</th>
                                    
                                    <th>Nama Barang <br/> Persediaan</th>
                                    <th>Tanggal Jual</th>
                                    <th>Nomor Kirim</th>
                                    <th>Konsumen</th>
                                    <th>Nama Barang <br/> Penjualan</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                </tr>

                                <!-- -------------- -->

                                <!-- </tr> -->

                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                foreach ($Tbl_penjualan_data as $list_data) {

// get data penjualan filter uuid_barang dan spop


                                ?>
                                    <tr>
                                        <td><?php echo ++$start; ?></td>
                                        
                                        <td><?php echo $list_data->namabarang_persediaan; ?></td>
                                        <td><?php echo $list_data->tgl_jual_penjualan; ?></td>
                                        <td><?php echo $list_data->nmrkirim_penjualan; ?></td>
                                        <td><?php echo $list_data->konsumen_nama_penjualan; ?></td>
                                        <td><?php echo $list_data->nama_barang_penjualan; ?></td>
                                        <td><?php echo $list_data->jumlah_penjualan; ?></td>
                                        <td><?php echo $list_data->harga_satuan_penjualan; ?></td>
                                       
                                       



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