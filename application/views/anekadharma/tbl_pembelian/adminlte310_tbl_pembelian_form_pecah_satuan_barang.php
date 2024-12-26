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
                <div class="card card-warning">
                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-12" text-align="center"> <strong>
                                    Update Satuan (Pecah Barang menjadi satuan lain)
                                </strong>
                            </div>
                        </div>
                    </div>
                    <!-- <br /> -->



                    <div class="card-body">


                        <form action="<?php echo $action; ?>" method="post">

                            <!-- LIST DATA BARANG -->
                            <div class="form-group">

                                <!-- <div class="row">
                                    <div class="col-2">
                                        Gudang
                                    </div>
                                    <div class="col-8">
                                        <?php //echo " : " .  $nama_gudang 
                                        ?>
                                    </div>
                                </div> -->


                                <div class="row">
                                    <div class="col-2">
                                        kode_barang
                                    </div>
                                    <div class="col-8">
                                        <?php echo " : " .  $kode_barang ?>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-2">
                                        nama_barang
                                    </div>
                                    <div class="col-8">
                                        <?php echo " : " .  $nama_barang ?>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-2">
                                        jumlah
                                    </div>
                                    <div class="col-8">
                                        <?php 
                                        $Get_Sisa_Stock = $jumlah_persediaan + $jumlah_beli - $jumlah_jual;

                                        echo " : " .  nominal($Get_Sisa_Stock) ;
                                        ?>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-2">
                                        satuan
                                    </div>
                                    <div class="col-8">
                                        <?php echo " : " .  $satuan ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-2">
                                        harga_satuan
                                    </div>
                                    <div class="col-8">
                                        <?php echo " : " .  nominal($harga_satuan) ?>
                                    </div>
                                </div>



                            </div>






                            <div class="card card-success">
                                <div class="card-header">

                                    <div class="row">
                                        <div class="col-12" text-align="center"> <strong>Detail Barang Dengan Satuan Baru</strong></div>
                                    </div>

                                </div>


                                <div class="card-body">


                                    <div class="form-group">

                                        <div class="row">

                                            <div class="col-4">
                                                <label for="konsumen_nama">Unit <?php echo form_error('konsumen_nama') ?></label>
                                                <select name="uuid_unit" id="uuid_unit" class="form-control select2" style="width: 100%; height: 40px;" required>
                                                    <option value="">Pilih Konsumen/Unit </option>
                                                    <?php

                                                    // $sql = "select * from sys_konsumen order by nama_konsumen ASC ";
                                                    $sql = "select * from sys_unit order by nama_unit ASC ";
                                                    foreach ($this->db->query($sql)->result() as $m) {
                                                        echo "<option value='$m->uuid_unit' ";
                                                        echo ">  " . strtoupper($m->nama_unit) .  "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                          

                                            <div class="col-4">
                                                <label for="nmrfakturkwitansi">Nama Barang Baru</label>
                                                <input type="text" class="form-control" rows="3" name="nama_barang_baru" id="nama_barang_baru" placeholder="nama barang baru" required>
                                            </div>

                                            <div class="col-4">
                                                <label for="nmrfakturkwitansi">kode Barang Baru</label>
                                                <input type="text" class="form-control" rows="3" name="kode_barang_baru" id="kode_barang_baru" placeholder="kode barang baru">
                                                <label style="color:red;font-size: 16px;">Jika dikosongkan maka akan di generate otomatis oleh aplikasi.</label>
                                            </div>

                                        </div>

                                    </div>


                                    <div class="form-group">

                                        <div class="row">
                                            <div class="col-2">
                                                <label for="nmrfakturkwitansi">Jumlah dari Stock</label>

                                                <!-- <input type="text" class="form-control" rows="3" name="jumlah_barang_baru" id="jumlah_barang_baru" placeholder="Jumlah barang baru" required> -->

                                                <input type="number" class="form-control" onkeyup="sum();" name="jumlah_barang_dari_stock" id="jumlah_barang_dari_stock" placeholder="" value="" style="font-size:1.5vw;font-weight: bold;text-align:right;color:red;" min="1" max="<?php echo $Get_Sisa_Stock ?>" ; />

                                                Max.: <?php echo nominal($Get_Sisa_Stock); ?>

                                            </div>
                                            <div class="col-2">
                                                <label for="nmrfakturkwitansi">Jumlah Baru</label>

                                                <!-- <input type="text" class="form-control" rows="3" name="jumlah_barang_baru" id="jumlah_barang_baru" placeholder="Jumlah barang baru" required> -->

                                                <input type="text" class="form-control uang" name="jumlah_barang_baru" id="jumlah_barang_baru" placeholder="" value="" style="font-size:1.5vw;font-weight: bold;text-align:right;color:red;"  required />

                                                <!-- Max.: <?php //echo nominal($jumlah); 
                                                            ?> -->

                                            </div>
                                            <div class="col-4">
                                                <label for="nmrfakturkwitansi">Satuan</label>
                                                <input type="text" class="form-control" rows="3" name="satuan_barang_baru" id="satuan_barang_baru" placeholder="satuan barang baru" required>
                                            </div>
                                            <div class="col-4">
                                                <label for="nmrfakturkwitansi">Harga Satuan</label>

                                                <!-- <input type="text" class="form-control" rows="3" name="harga_satuan_barang_baru" id="harga_satuan_barang_baru" placeholder="harga satuan barang baru" required> -->

                                                <input type="text" class="form-control uang" onkeyup="sum();" name="harga_satuan_barang_baru" id="harga_satuan_barang_baru" placeholder="" value="" style="font-size:1.5vw;font-weight: bold;text-align:right;color:red;" ; />
                                                <label style="color:red;font-size: 16px;">Jika dikosongkan maka akan di generate otomatis dikalkulasi dari harga satuan dari satuan stock.</label>

                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>

                            <div class="form-group">

                                <div class="row">
                                    <div class="col-4"></div>
                                    <div class="col-4">

                                        <input type="hidden" name="id" value="<?php echo $uuid_persediaan; ?>" />
                                        <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                        <a href="<?php echo site_url('tbl_pembelian/pecah_satuan/') ?>" class="btn btn-default">Cancel</a>

                                    </div>
                                    <div class="col-4"></div>
                                </div>
                            </div>


                        </form>


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
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>