<!-- <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script> -->
<script src="<?php echo base_url() ?>js/jquery-1.8.2.min.js"></script>

<?php

$sql = "SELECT `uuid_barang`,`kode_barang`,`nama_barang` FROM `sys_nama_barang` ORDER by `nama_barang` ASC";

$data_barang = $this->db->query($sql)->result();

$x_list_data = "";
foreach ($this->db->query($sql)->result() as $list_data) {
    $x = $list_data->nama_barang;
    $x_list_data_X = "<option value='$x'>$x</option>";
    $x_list_data = $x_list_data . $x_list_data_X;
}

// print_r($x_list_data);
// die;

$get_list_data = $x_list_data;

?>

<script language="javascript">
    // <script type="text/javascript">
    function tambahuraian() {
        // var get_id_awal = <?php //echo $get_list_data; 
                                ?>; //ID AWAL
        // var list_data ="<option value='Buku'>Buku</option><option value='Elektronik'>Elektronik</option>";
        var list_data = "<?php echo $get_list_data; ?>";
        var iduraian = document.getElementById("iduraian").value;
        var stre;
        // stre = "<p id='srow" + iduraian + "'><select><option value=''>- Pilih Kategori -</option><option value='1'>Buku</option><option value='2'>Elektronik</option></select><input type='text' size='40' name='uraian[]' placeholder='uraian' /><input type='text' size='10' name='jumlah[]' placeholder='Jumlah' /><input type='text' size='10' name='satuan[]' placeholder='Satuan' /><input type='text' size='20' name='hargasatuan[]' placeholder='HargaSatuan' /><a href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow" + iduraian + "\"); return false;'>Hapus</a></p>";

        stre = "<p id='srow" + iduraian + "'><select name='uraian[]'><option value=''>- Pilih Nama Barang -</option>" + list_data + "</select><input type='text' size='10' name='jumlah[]' placeholder='Jumlah' /><input type='text' size='10' name='satuan[]' placeholder='Satuan' /><input type='text' size='20' name='hargasatuan[]' placeholder='HargaSatuan' /><a href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow" + iduraian + "\"); return false;'>Hapus</a></p>";

        $("#divuraian").append(stre);


        iduraian = (iduraian - 1) + 2;
        document.getElementById("iduraian").value = iduraian;



    }

    function hapusElemen(iduraian) {
        $(iduraian).remove();
    }
</script>


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
                                            Input Pembelian
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
                                    <!-- <input type="text" class="form-control" name="tgl_po" id="tgl_po" placeholder="Tgl Po" value="<?php echo $tgl_po; ?>" /> -->
                                    <div class="input-group date" id="tgl_po" name="tgl_po" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_po" id="tgl_po" name="tgl_po" required />
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
                                        <!-- <textarea class="form-control" rows="3" name="supplier_nama" id="supplier_nama" placeholder="Supplier Nama"><?php //echo $supplier_nama; 
                                                                                                                                                            ?></textarea> -->


                                        <select name="uuid_supplier" id="uuid_supplier" class="form-control select2" style="width: 100%; height: 40px;" required>
                                            <option value="">Pilih Supplier</option>
                                            <?php

                                            $sql = "select * from sys_supplier  order by  nama_supplier ASC ";


                                            foreach ($this->db->query($sql)->result() as $m) {
                                                // foreach ($data_produk as $m) {
                                                echo "<option value='$m->uuid_supplier' ";
                                                echo ">  " . strtoupper($m->nama_supplier) . strtoupper($m->nmr_kontak_supplier) . strtoupper($m->alamat_supplier) . "</option>";
                                            }
                                            ?>
                                        </select>


                                    </div>

                                    <div class="col-2">
                                        <label for="statuslu">Status <?php echo form_error('statuslu') ?></label>
                                        <!-- <input type="text" class="form-control" rows="3" name="statuslu" id="statuslu" placeholder="Statuslu"><?php //echo $statuslu; 
                                                                                                                                                    ?></textarea> -->


                                        <select name="statuslu" id="statuslu" class="form-control select2" style="width: 100%; height: 40px;">
                                            <option value="">Pilih Status</option>
                                            <option value="L">Lunas</option>
                                            <option value="U">Hutang</option>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label for="kas_bank">Kas / Bank <?php echo form_error('kas_bank') ?></label>
                                        <!-- <input type="text" class="form-control" rows="3" name="kas_bank" id="kas_bank" placeholder="Kas Bank"><?php echo $kas_bank; ?></textarea> -->
                                        <select name="kas_bank" id="kas_bank" class="form-control select2" style="width: 100%; height: 40px;">
                                            <option value="">Pilih Status</option>
                                            <option value="kas">Kas</option>
                                            <option value="bank">Bank</option>
                                        </select>
                                    </div>

                                    <div class="col-4">
                                        <!-- <label for="supplier_nama">Konsumen <?php //echo form_error('supplier_nama') 
                                                                                    ?></label>

                                        <select name="uuid_konsumen" id="uuid_konsumen" class="form-control select2" style="width: 100%; height: 40px;" required>
                                            <option value="">Pilih Konsumen</option>
                                            <?php
                                            // $sql = "select * from sys_konsumen order by nama_konsumen ASC ";
                                            // foreach ($this->db->query($sql)->result() as $m) {
                                            //     echo "<option value='$m->uuid_konsumen' ";
                                            //     echo ">  " . strtoupper($m->nama_konsumen) . strtoupper($m->nmr_kontak_konsumen) . strtoupper($m->alamat_konsumen) . "</option>";
                                            // }
                                            ?>
                                        </select>
                                        -->

                                    </div>

                                </div>

                            </div>


                            <div class="form-group">

                                <div class="row">
                                    <div class="col-4">
                                        <label for="nmrsj">Nomor SPOP <?php echo form_error('spop') ?></label>
                                        <input type="text" class="form-control" rows="3" name="spop" id="spop" placeholder="Nomor SPOP" required><?php //echo $spop; 
                                                                                                                                                    ?>
                                    </div>

                                    <div class="col-4">
                                        <label for="nmrfakturkwitansi">Nomor faktur / kwitansi <?php echo form_error('nmrfakturkwitansi') ?></label>
                                        <input type="text" class="form-control" rows="3" name="nmrfakturkwitansi" id="nmrfakturkwitansi" placeholder="Nmrfakturkwitansi"><?php //echo $nmrfakturkwitansi; 
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




                            <!-- <div class="form-group">
                                <div class="row">
                                    <div class="col-4">
                                        <label for="statuslu">Status <?php //echo form_error('statuslu') 
                                                                        ?></label>
                                        <input type="text" class="form-control" rows="3" name="statuslu" id="statuslu" placeholder="Statuslu"><?php //echo $statuslu; 
                                                                                                                                                ?></textarea>
                                    </div>
                                    <div class="col-4">
                                        <label for="kas_bank">Cash / Bank <?php //echo form_error('kas_bank') 
                                                                            ?></label>
                                        <input type="text" class="form-control" rows="3" name="kas_bank" id="kas_bank" placeholder="Kas Bank"><?php //echo $kas_bank; 
                                                                                                                                                ?></textarea>
                                    </div>
                                </div>
                            </div> -->

                            <div class="card card-success">
                                <div class="card-header">

                                    <div class="row">
                                        <div class="col-12" text-align="center"> <strong>Detail Barang</strong></div>
                                    </div>

                                </div>
                                <div class="card-body">

                                    <!-- <input id="iduraian" value="1" type="hidden" />
                                    <div id="divuraian"></div>
                                    <button type="button" onclick="tambahuraian(); return false;">Tambah Uraian</button> -->

                                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                                        Tambah Barang
                                    </button>


                                </div>
                            </div>



                            <br />

                            <!-- /.modal -->
                            <div class="modal fade" id="modal-default">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Input Data Barang</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">


                                                <div class="row">
                                                    <div class="col-12">
                                                        <label for="konsumen_nama">Unit <?php echo form_error('konsumen_nama') ?></label>
                                                        <select name="uuid_konsumen" id="uuid_konsumen" class="form-control select2" style="width: 100%; height: 40px;" required>
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
                                                </div>


                                                <div class="row">
                                                    <div class="col-12">
                                                        <label for="uuid_barang">Barang <?php echo form_error('uuid_barang') ?></label>
                                                        <select name="uuid_barang" id="uuid_barang" class="form-control select2" style="width: 100%; height: 80px;" required>
                                                            <option value="">Pilih Barang</option>
                                                            <?php

                                                            $sql = "SELECT `uuid_barang`,`kode_barang`,`nama_barang` FROM `sys_nama_barang` ORDER by `nama_barang` ASC";
                                                            foreach ($this->db->query($sql)->result() as $m) {
                                                                echo "<option value='$m->uuid_barang' ";
                                                                echo ">  " . strtoupper($m->kode_barang) . strtoupper($m->nama_barang)  . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <label for="jumlah">Jumlah <?php //echo form_error('nmrpesan') 
                                                                                    ?></label>
                                                        <!-- <input type="text" class="form-control" rows="3" name="jumlah" id="jumlah" placeholder="Jumlah" required> -->
                                                        <input type="text" name="jumlah" id="jumlah" placeholder="Jumlah" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label for="satuan">Satuan <?php echo form_error('satuan') ?></label>
                                                        <input type="text" name="satuan" id="satuan" placeholder="satuan" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label for="satuan">Harga Satuan <?php echo form_error('harga_satuan') ?></label>
                                                        <input type="text" name="harga_satuan" id="harga_satuan" placeholder="harga Satuan" class="form-control" required>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                            <!-- <button type="button" class="btn btn-primary">Simpan</button> -->
                                            <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->

                            <br />

                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <!-- <button type="submit" class="btn btn-primary"><?php //echo $button ?></button> -->
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