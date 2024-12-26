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
                                    Rollback Barang (Mengembalikan barang pecah satuan ke satuan awal)
                                </strong>
                            </div>
                        </div>
                    </div>
                    <!-- <br /> -->



                    <div class="card-body">


                        <form action="<?php echo $action; ?>" method="post">



                            <div class="row">
                                <div class="col-6">
                                    <!-- Barang Pecahan -->
                                    <!-- LIST DATA BARANG -->
                                    <div class="form-group">



                                        <div class="row">
                                            <div class="col-4">
                                                Nama Barang
                                            </div>
                                            <div class="col-8">
                                                <?php echo " : " .  $nama_barang ?>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-4">
                                                Persediaan (Stock)
                                            </div>
                                            <div class="col-8">
                                                <?php
                                                $Get_Sisa_Stock = $jumlah_persediaan + $jumlah_beli - $jumlah_jual;

                                                echo " : " .  nominal($Get_Sisa_Stock);
                                                ?>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-4">
                                                Satuan
                                            </div>
                                            <div class="col-8">
                                                <?php echo " : " .  $satuan ?>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-4">
                                                Harga Satuan
                                            </div>
                                            <div class="col-8">
                                                <?php echo " : " .  nominal($harga_satuan) ?>
                                            </div>
                                        </div>



                                    </div>

                                </div>
                                <div class="col-6">
                                    <!-- source sumber barang sebelum di pecah -->

                                    <div class="info-box  bg-success">
                                        <span class="info-box-icon bg-warning"><i class="far fa-flag"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text"> Sumber Barang Sebelum di pecah:</span>
                                            <span class="info-box-text">
                                                <div class="row">
                                                    <div class="col-4">
                                                        Nama Barang
                                                    </div>
                                                    <div class="col-8">
                                                        <?php echo " : " .  $nama_barang_source ?>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col-4">
                                                        Persediaan (Stock)
                                                    </div>
                                                    <div class="col-8">
                                                        <?php
                                                        $Get_Sisa_Stock_SOURCE = $jumlah_persediaan_source + $jumlah_beli_source - $jumlah_jual_source - $jumlah_di_pecah_satuan_source;

                                                        echo " : " .  nominal($Get_Sisa_Stock_SOURCE);
                                                        ?>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col-4">
                                                        Satuan
                                                    </div>
                                                    <div class="col-8">
                                                        <?php echo " : " .  $satuan_source ?>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-4">
                                                        Harga Satuan
                                                    </div>
                                                    <div class="col-8">
                                                        <?php echo " : " .  nominal($harga_satuan_source) ?>
                                                    </div>
                                                </div>



                                        </div></span>

                                        <!-- <span class="info-box-number">410</span> -->
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>



                            </div>
                    </div>


                    <div class="form-group">



                        <!-- 

                                 -->






                        <div class="card card-success">
                            <div class="card-header">

                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>Jumlah [<?php echo $nama_barang; ?>] ==> yang di kembalikan menjadi: <?php echo $nama_barang_source; ?> </strong></div>
                                </div>

                            </div>


                            <div class="card-body">


                                <!-- <div class="form-group">

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
                                            <label style="color:red;font-size: 16px;text-align: right;" align="right">Unit Harus di pilih.</label>
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

                                </div> -->


                                <div class="form-group">

                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col-3">
                                            <label for="nmrfakturkwitansi">Jumlah <?php echo $satuan_source; ?> ( Per <?php echo $jumlah_setelah_di_pecah_satuan . " " . $satuan  ?> )</label>

                                            <!-- <input type="text" class="form-control" rows="3" name="jumlah_barang_baru" id="jumlah_barang_baru" placeholder="Jumlah barang baru" required> -->

                                            <input type="number" class="form-control" onkeyup="sum();" name="jumlah_barang_dari_stock" id="jumlah_barang_dari_stock" placeholder="" value="" style="font-size:1.5vw;font-weight: bold;text-align:right;color:red;" min="1" max="<?php echo $Get_Sisa_Stock / $jumlah_setelah_di_pecah_satuan ?>" ; required/>


                                            <label style="color:red;font-size: 20px;">Max.: <?php echo nominal($Get_Sisa_Stock / $jumlah_setelah_di_pecah_satuan); ?></label>

                                        </div>
                                        <div class="col-4"></div>
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