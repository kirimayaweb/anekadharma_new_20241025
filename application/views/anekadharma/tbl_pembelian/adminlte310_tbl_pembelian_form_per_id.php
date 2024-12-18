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
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>
                                            Update Data Pembelian
                                        </strong></div>

                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>



                    </div>
                    <!-- <br /> -->



                    <div class="card-body">


                        <form action="<?php echo $action; ?>" method="post">





                            <div class="form-group">
                                <label for="datetime">Tgl Po <?php echo form_error('tgl_po') ?></label>
                                <div class="col-3">
                                    <!-- <input type="text" class="form-control" name="tgl_po" id="tgl_po" placeholder="Tgl Po" value="<?php //echo $tgl_po; 
                                                                                                                                        ?>" /> -->
                                    <?php

                                    if (date("Y", strtotime($tgl_po)) < 2020) {
                                        // print_r("Tahun kurang dari 2020");
                                        $date_po = date("d-m-Y");
                                    } else {
                                        // print_r("Tahun lebih dari 2020");
                                        $date_po = date("d-m-Y", strtotime($tgl_po));
                                    }

                                    // echo $date_po;

                                    ?>


                                    <div class="input-group date" id="tgl_po" name="tgl_po" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_po" id="tgl_po" name="tgl_po" value="<?php echo date("d-m-Y", strtotime($date_po)); ?>" required />
                                        <div class="input-group-append" data-target="#tgl_po" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <!-- <div class="col-12">
                                    Jika tanggal tidak di pilih, maka akan di isi = tanggal saat ini secara otomatis oleh sistem

                                </div> -->

                            </div>



                            <div class="form-group">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="supplier_nama">Nama Supplier <?php echo form_error('supplier_nama') ?></label>

                                        <select name="uuid_supplier" id="uuid_supplier" class="form-control select2" style="width: 100%; height: 40px;" required>
                                            <!-- <option value="">Pilih Supplier</option> -->
                                            <?php
                                            if ($uuid_supplier) {
                                            ?>
                                                <option value="<?php echo $uuid_supplier; ?>"><?php echo $supplier_nama; ?></option>
                                            <?php
                                            } else {
                                            ?>
                                                <option value="">pilih suplier</option>

                                            <?php
                                            }
                                            ?>
                                            <?php

                                            $sql = "select * from sys_supplier  order by  nama_supplier ASC ";


                                            foreach ($this->db->query($sql)->result() as $m) {
                                                // foreach ($data_produk as $m) {
                                                echo "<option value='$m->uuid_supplier' ";
                                                echo ">  " . strtoupper($m->nama_supplier) . " ==> ( " . strtoupper($m->alamat_supplier) . ")</option>";
                                            }
                                            ?>
                                        </select>


                                    </div>

                                    <div class="col-2">
                                        <label for="statuslu">Status <?php echo form_error('statuslu') ?></label>

                                        <select name="statuslu" id="statuslu" class="form-control select2" style="width: 100%; height: 60px;">
                                            <option value="<?php if ($statuslu == "L") {
                                                                echo "Lunas";
                                                            } else {
                                                                echo "Hutang";
                                                            }; ?>"><?php if ($statuslu == "L") {
                                                                        echo "Lunas";
                                                                    } else {
                                                                        echo "Hutang";
                                                                    }; ?></option>
                                            <option value="L">Lunas</option>
                                            <option value="U">Hutang</option>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label for="kas_bank">Kas / Bank <?php echo form_error('kas_bank') ?></label>

                                        <select name="kas_bank" id="kas_bank" class="form-control select2" style="width: 100%; height: 60px;">

                                            <option value="<?php if ($kas_bank == "kas") {
                                                                echo "Kas";
                                                            } else {
                                                                echo "Bank";
                                                            }; ?>"><?php if ($kas_bank == "kas") {
                                                                        echo "Kas";
                                                                    } else {
                                                                        echo "Bank";
                                                                    }; ?></option>

                                            <option value="kas">Kas</option>
                                            <option value="bank">Bank</option>
                                        </select>
                                    </div>

                                </div>

                            </div>


                            <div class="form-group">

                                <div class="row">
                                    <div class="col-3">
                                        <label for="nmrsj">Nomor SPOP <?php echo form_error('spop') ?></label>
                                        <input type="text" class="form-control" rows="3" name="spop" id="spop" placeholder="Nomor SPOP" value="<?php echo $spop; ?>" required>
                                    </div>

                                    <div class="col-4">
                                        <label for="nmrfakturkwitansi">Nomor faktur / kwitansi <?php echo form_error('nmrfakturkwitansi') ?></label>
                                        <input type="text" class="form-control" rows="3" name="nmrfakturkwitansi" id="nmrfakturkwitansi" placeholder="Nmrfakturkwitansi" value="<?php echo $nmrfakturkwitansi; ?>"><?php //echo $nmrfakturkwitansi; 
                                                                                                                                                                                                                    ?>
                                    </div>

                                    <div class="col-4">
                                        <!-- <label for="int">Nomor bpb <?php //echo form_error('nmrbpb') 
                                                                        ?></label>
                                        <input type="text" class="form-control" name="nmrbpb" id="nmrbpb" placeholder="Nmrbpb" value="<?php //echo $nmrbpb; 
                                                                                                                                        ?>" /> -->
                                    </div>

                                </div>

                            </div>


                            <hr>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12" text-align="left">
                                        Detail Barang Pembelian
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="uuid_gudang">Gudang <?php echo form_error('uuid_gudang') ?></label>

                                        <select name="uuid_gudang" id="uuid_gudang" class="form-control select2" style="width: 100%; height: 40px;" required>
                                            <!-- <option value="">Pilih Supplier</option> -->


                                            <option value="<?php echo $uuid_gudang; ?>"><?php echo $nama_gudang; ?></option>
                                            <?php

                                            $sql = "select * from sys_gudang order by nama_gudang ASC ";


                                            foreach ($this->db->query($sql)->result() as $m) {
                                                // foreach ($data_produk as $m) {
                                                echo "<option value='$m->uuid_gudang' ";
                                                echo ">  " . strtoupper($m->nama_gudang) . "</option>";
                                            }
                                            ?>
                                        </select>


                                    </div>

                                    <div class="col-2">
                                        <label for="statuslu">Nama Barang <?php echo form_error('uuid_barang') ?></label>

                                        <select name="uuid_barang" id="uuid_barang" class="form-control select2" style="width: 100%; height: 80px;" required>
                                            <option value="<?php echo $uuid_barang; ?>"><?php echo $uraian; ?></option>
                                            <?php

                                            $sql = "SELECT `uuid_barang`,`kode_barang`,`namabarang` FROM `persediaan` WHERE `namabarang`<>'' GROUP by `namabarang`,`satuan`";

                                            foreach ($this->db->query($sql)->result() as $m) {
                                                echo "<option value='$m->uuid_barang' ";
                                                echo ">  " . strtoupper($m->namabarang)  . "</option>";
                                            }
                                            ?>
                                        </select>


                                    </div>


                                </div>

                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-2">
                                        <label for="kas_bank">Jumlah <?php echo form_error('jumlah') ?></label>

                                        <input type="text" class="form-control" rows="3" name="jumlah" id="jumlah" placeholder="jumlah" value="<?php echo nominal($jumlah); ?>">

                                    </div>
                                    <div class="col-2">
                                        <label for="kas_bank">Satuan <?php echo form_error('satuan') ?></label>

                                        <input type="text" class="form-control" rows="3" name="satuan" id="satuan" placeholder="satuan" value="<?php echo $satuan; ?>">

                                    </div>
                                    <div class="col-2">
                                        <label for="kas_bank">Harga Satuan <?php echo form_error('harga_satuan') ?></label>

                                        <input type="text" class="form-control" rows="3" name="harga_satuan" id="harga_satuan" placeholder="harga_satuan" value="<?php echo nominal($harga_satuan); ?>">

                                    </div>
                                    <div class="col-2">
                                        <label for="kas_bank">Total </label>

                                        <input type="text" class="form-control" rows="3" name="total" id="total" placeholder="total" value="<?php echo nominal($jumlah * $harga_satuan); ?>">

                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <button type="submit" class="btn btn-primary"><?php echo $button; ?></button>
                            <a href="<?php echo site_url('tbl_pembelian') ?>" class="btn btn-default">Cancel</a>
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