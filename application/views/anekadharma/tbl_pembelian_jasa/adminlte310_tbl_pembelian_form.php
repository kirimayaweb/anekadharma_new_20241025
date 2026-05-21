<!-- <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script> -->
<script src="<?php echo base_url() ?>js/jquery-1.8.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
$this->load->helper('pembelian_persediaan');
$filter_bulan_pembelian = pembelian_get_filter_tanggal($this);
$data_barang = pembelian_get_barang_list_rows($this);

$x_list_data = "";
foreach ($data_barang as $list_data) {
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
<!-- ===== -->

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
                <div class="card card-warning pembelian-jasa-form-card">
                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>
                                            Input Pembelian Jasa
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
                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_po" name="tgl_po" value="<?php echo htmlspecialchars(isset($tgl_po) ? $tgl_po : date('j-n-Y'), ENT_QUOTES, 'UTF-8'); ?>" required />
                                        <div class="input-group-append" data-target="#tgl_po" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1" id="info-bulan-persediaan-barang">
                                    Daftar jasa/barang (persediaan) bulan: <strong><?php echo htmlspecialchars($filter_bulan_pembelian['bulan_label'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                    — mengikuti <em>Tgl PO</em>
                                </small>

                            </div>



                            <div class="form-group">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="supplier_nama">Nama Supplier <?php echo form_error('supplier_nama') ?></label>

                                        <!-- <textarea class="form-control" rows="3" name="supplier_nama" id="supplier_nama" placeholder="Supplier Nama"><?php //echo $supplier_nama; 
                                                                                                                                                            ?></textarea> -->


                                        <select name="uuid_supplier" id="uuid_supplier" class="form-control select2-pembelian-jasa" style="width: 100%;" required>
                                            <option value="">Pilih Supplier</option>
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
                                        <!-- <input type="text" class="form-control" rows="3" name="statuslu" id="statuslu" placeholder="Statuslu"><?php //echo $statuslu; 
                                                                                                                                                    ?></textarea> -->


                                        <select name="statuslu" id="statuslu" class="form-control select2-pembelian-jasa" style="width: 100%;">
                                            <option value=""></option>
                                            <option value="L">Lunas</option>
                                            <option value="U">Hutang</option>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label for="kas_bank">Kas / Bank <?php echo form_error('kas_bank') ?></label>
                                        <!-- <input type="text" class="form-control" rows="3" name="kas_bank" id="kas_bank" placeholder="Kas Bank"><?php echo $kas_bank; ?></textarea> -->
                                        <select name="kas_bank" id="kas_bank" class="form-control select2-pembelian-jasa" style="width: 100%;">
                                            <option value=""></option>
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
                                        <input type="text" class="form-control" rows="3" name="spop" id="spop" placeholder="Nomor SPOP" required autocomplete="off"><?php //echo $spop; 
                                                                                                                                                    ?>
                                        <small class="text-muted d-block mt-1" id="info-cek-spop-persediaan"></small>
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

                            <div class="card card-success pembelian-jasa-form-detail-card">
                                <div class="card-header">

                                    <div class="row">
                                        
                                    <div class="col-4" text-align="center"> </div>
                                        
                                        <div class="col-4" text-align="center">
                                            <strong>Detail Jasa</strong>
                                        </div>



                                        <div class="col-2" text-align="center">
                                            <button type="button" class="btn btn-block btn-warning" id="btn-open-modal-barang-baru-halaman" onclick="openModalInputBarangBaruPembelianDariHalaman(); return false;">
                                                Input Jasa Baru
                                            </button>
                                        </div>

                                        <div class="col-2" text-align="center"> </div>

                                    </div>



                                </div>
                                <div class="card-body">

                                    <!-- <input id="iduraian" value="1" type="hidden" />
                                    <div id="divuraian"></div>
                                    <button type="button" onclick="tambahuraian(); return false;">Tambah Uraian</button> -->

                                    <!-- <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default"> -->
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-xl-input-barang">
                                        Tambah Jasa Pembelian
                                    </button>


                                </div>
                            </div>



                            <br />




                            <!-- MODAL EXTRA LARGE -->

                            <div class="modal fade" id="modal-xl-input-barang">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Tambah Pembelian Jasa</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group">


                                                <div class="row">
                                                    <div class="col-4">
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
                                                    <div class="col-4">
                                                    </div>
                                                    <div class="col-4">
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col-4">
                                                        <label for="uuid_barang">Jasa <?php echo form_error('uuid_barang') ?></label>
                                                        <small class="text-muted d-block mb-1" id="info-bulan-persediaan-modal">
                                                            Bulan persediaan: <strong><?php echo htmlspecialchars($filter_bulan_pembelian['bulan_label'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                                        </small>

                                                        <select name="uuid_barang" id="uuid_barang" class="form-control select2" style="width: 100%; height: 80px;" onchange="if (window.loadDetailBarangPembelian) { loadDetailBarangPembelian(this.value); }" required>
                                                            <option value="">Pilih Jasa</option>
                                                            <?php

                                                            foreach (pembelian_get_barang_list_rows($this) as $m) {
                                                                $harga_satuan_barang = isset($m->harga_satuan) ? $m->harga_satuan : '';
                                                                echo "<option value='" . htmlspecialchars($m->uuid_barang, ENT_QUOTES, 'UTF-8') . "' data-satuan='" . htmlspecialchars($m->satuan, ENT_QUOTES, 'UTF-8') . "' data-harga-satuan='" . htmlspecialchars($harga_satuan_barang, ENT_QUOTES, 'UTF-8') . "' ";
                                                                echo ">  " . strtoupper($m->nama_barang)  . "</option>";
                                                            }
                                                            ?>
                                                        </select>

                                                        <div class="row">
                                                            <div class="col-8">
                                                                <button type="button" class="btn btn-block btn-danger" id="btn-open-modal-barang-baru" onclick="openModalInputBarangBaruPembelian(); return false;">
                                                                    Input Jasa Baru
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-4">
                                                        <label for="satuan">Satuan <?php echo form_error('satuan') ?></label>
                                                        <input type="text" name="satuan" id="satuan" placeholder="satuan" class="form-control" required>
                                                    </div>
                                                    <div class="col-4">
                                                        <label for="satuan">Harga Satuan <?php echo form_error('harga_satuan') ?></label>
                                                        <input type="text" name="harga_satuan" id="harga_satuan" placeholder="harga Satuan" class="form-control" inputmode="numeric" autocomplete="off" required>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-4">
                                                        <label for="jumlah">Jumlah <?php //echo form_error('nmrpesan') 
                                                                                    ?></label>
                                                        <!-- <input type="text" class="form-control" rows="3" name="jumlah" id="jumlah" placeholder="Jumlah" required> -->
                                                        <input type="text" name="jumlah" id="jumlah" placeholder="Jumlah" class="form-control" required>
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

                            <!-- END OF MODAL EXTRA LARGE -->


                            <br />

                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <!-- <button type="submit" class="btn btn-primary"><?php //echo $button 
                                                                                ?></button> -->
                            <a href="<?php echo site_url('tbl_pembelian_jasa') ?>" class="btn btn-default">Cancel</a>
                        </form>

                        <div class="modal fade" id="modal-input-barang-baru" tabindex="-1" role="dialog" aria-labelledby="modalInputBarangBaruLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h4 class="modal-title" id="modalInputBarangBaruLabel">Input Jasa Baru</h4>
                                        <button type="button" class="close" onclick="closeModalInputBarangBaruPembelian(); return false;" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="modal-input-barang-baru-body">
                                        <div class="text-center text-muted py-4">Memuat form...</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modalBarangDuplikatPersediaan" tabindex="-1" role="dialog" aria-labelledby="modalBarangDuplikatPersediaanJasaLabel" aria-hidden="true" data-backdrop="true" data-keyboard="true">
                            <div class="modal-dialog modal-dialog-pembelian-referensi modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-info">
                                        <h5 class="modal-title" id="modalBarangDuplikatPersediaanJasaLabel">Nama Jasa Sudah Ada di Sistem</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-info mb-2" id="modal_barang_duplikat_info">
                                            Nama jasa belum ada di bulan terpilih (sesuai <strong>Tgl PO</strong>), tetapi sudah ada di sistem pada bulan lain. Klik <strong>Pilih dan gunakan</strong> untuk menyalin ke form Input Jasa Baru.
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm table-striped mb-0" id="tabel_barang_duplikat_persediaan">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th style="width:50px;">No</th>
                                                        <th style="width:90px;">Bulan</th>
                                                        <th>Nama Jasa</th>
                                                        <th style="width:100px;">Satuan</th>
                                                        <th style="width:120px;">HPP</th>
                                                        <th style="width:150px;">Pilih dan gunakan</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- /.card-body -->
                </div>

            </div>

        </div>
    </section>
</div>





<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/AdminLTE310/plugins/select2/css/select2.min.css">
<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }

    /* Form Input Pembelian Jasa: border biru */
    .pembelian-jasa-form-card,
    .pembelian-jasa-form-detail-card {
        border: 2px solid #007bff !important;
        box-shadow: none;
    }

    .pembelian-jasa-form-card > .card-header,
    .pembelian-jasa-form-detail-card > .card-header {
        border-bottom: 1px solid #007bff !important;
    }

    .pembelian-jasa-form-card .form-control,
    .pembelian-jasa-form-detail-card .form-control,
    .pembelian-jasa-form-card select,
    .pembelian-jasa-form-detail-card select,
    .pembelian-jasa-form-card textarea,
    .pembelian-jasa-form-detail-card textarea {
        border-color: #90caf9;
    }

    .pembelian-jasa-form-card .form-control:focus,
    .pembelian-jasa-form-detail-card .form-control:focus,
    .pembelian-jasa-form-card select:focus,
    .pembelian-jasa-form-detail-card select:focus,
    .pembelian-jasa-form-card textarea:focus,
    .pembelian-jasa-form-detail-card textarea:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.15rem rgba(0, 123, 255, 0.25);
    }

    .pembelian-jasa-form-card table,
    .pembelian-jasa-form-detail-card table {
        border: 1px solid #007bff;
    }

    .pembelian-jasa-form-card table th,
    .pembelian-jasa-form-card table td,
    .pembelian-jasa-form-detail-card table th,
    .pembelian-jasa-form-detail-card table td {
        border-color: #90caf9 !important;
    }

    /* Select2 form pembelian jasa + modal: kotak search selalu tampil */
    .pembelian-jasa-form-card .select2-search--dropdown,
    #modal-input-barang-baru .select2-search--dropdown {
        display: block !important;
    }

    .pembelian-jasa-form-card .select2-container--default .select2-search--dropdown .select2-search__field,
    #modal-input-barang-baru .select2-container--default .select2-search--dropdown .select2-search__field {
        width: 100%;
        padding: 6px 8px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    #modal-input-barang-baru,
    #modalBarangDuplikatPersediaan {
        z-index: 1060 !important;
    }

    #modalBarangDuplikatPersediaan {
        z-index: 10080 !important;
    }

    #modalBarangDuplikatPersediaan .modal-dialog-pembelian-referensi {
        max-width: 96vw;
        width: 96vw;
        margin: 4vh auto;
    }

    body.modal-pembelian-duplikat-open .modal-backdrop:last-of-type {
        z-index: 10070 !important;
    }

    .pembelian-jasa-form-card .select2-dropdown,
    #modal-input-barang-baru .select2-dropdown {
        z-index: 10060 !important;
    }

    /* Combobox kategori modal: ~setengah lebar modal, mengikuti wrapper */
    #modal-input-barang-baru .modal-kategori-select-wrap {
        width: 50%;
        max-width: 22rem;
        min-width: 11rem;
        margin-right: 8px;
        flex: 0 0 auto;
    }

    #modal-input-barang-baru .modal-kategori-select-wrap .select2-container {
        width: 100% !important;
        max-width: 100%;
    }

    /* Kotak pilihan: tinggi cukup agar teks terpilih tidak terpotong (supplier, status, kas/bank, kategori) */
    .pembelian-jasa-form-card .select2-container--default .select2-selection--single,
    #modal-input-barang-baru .select2-container--default .select2-selection--single {
        min-height: 40px;
        height: auto !important;
        padding: 4px 28px 4px 8px;
        display: flex;
        align-items: center;
    }

    .pembelian-jasa-form-card .select2-container--default .select2-selection--single .select2-selection__rendered,
    #modal-input-barang-baru .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.35;
        padding: 4px 0;
        white-space: normal;
        word-break: break-word;
        overflow: visible;
    }

    .pembelian-jasa-form-card .select2-container--default .select2-selection--single .select2-selection__arrow,
    #modal-input-barang-baru .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        top: 0;
        right: 4px;
    }

    .pembelian-jasa-form-card .select2-container .select2-selection--single,
    #modal-input-barang-baru .select2-container .select2-selection--single {
        border-color: #ced4da;
    }
</style>





<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="<?php echo base_url(); ?>assets/AdminLTE310/plugins/select2/js/select2.full.min.js"></script>
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
<script>
    (function($) {
        var modalFormLoaded = false;
        var manualBackdropId = 'modal-input-barang-baru-backdrop';
        var cekNamaBarangAjaxPending = false;
        var pilihGunakanBarangInProgress = false;
        window.barangDuplikatPersediaanCache = [];

        jQuery('#modal-input-barang-baru, #modalBarangDuplikatPersediaan').appendTo('body');

        function getTopModalZIndex() {
            var maxZIndex = 1050;
            $('.modal.show:visible').each(function() {
                var zIndex = parseInt($(this).css('z-index'), 10);
                if (!isNaN(zIndex) && zIndex >= maxZIndex) {
                    maxZIndex = zIndex + 20;
                }
            });
            return maxZIndex;
        }

        function showModalManual(selector) {
            var modal = $(selector);
            if (!modal.length) {
                return;
            }

            var zIndex = getTopModalZIndex();
            $('#' + manualBackdropId).remove();
            $('<div/>', {
                id: manualBackdropId,
                class: 'modal-backdrop fade show'
            }).css('z-index', zIndex - 10).appendTo(document.body);

            modal.css({
                display: 'block',
                'z-index': zIndex
            }).addClass('show').attr({
                'aria-modal': 'true'
            }).removeAttr('aria-hidden');

            $('body').addClass('modal-open');
        }

        function hideModalManual(selector) {
            var modal = $(selector);
            modal.removeClass('show').css('display', 'none').attr('aria-hidden', 'true').removeAttr('aria-modal');
            $('#' + manualBackdropId).remove();

            if ($('.modal.show:visible').length) {
                $('body').addClass('modal-open');
            } else {
                $('body').removeClass('modal-open');
            }
        }

        function showBootstrapOrManual(selector) {
            if ($.fn.modal) {
                $(selector).modal('show');
            } else {
                showModalManual(selector);
            }
            if (selector === '#modal-input-barang-baru') {
                setTimeout(function() {
                    initSelect2KategoriModal();
                }, 100);
            }
        }

        function hideBootstrapOrManual(selector) {
            if ($.fn.modal) {
                $(selector).modal('hide');
            } else {
                hideModalManual(selector);
            }
        }

        function showInputBarangInfo(message, type) {
            var info = $('#input_barang_baru_info');
            if (!info.length) {
                return;
            }
            info.removeClass('d-none alert-success alert-danger alert-warning')
                .addClass('alert-' + type)
                .html(message);
        }

        function normalizeNamaBarangInput(val) {
            return String(val || '')
                .replace(/[\r\n\t]+/g, ' ')
                .replace(/\s+/g, ' ')
                .trim();
        }

        function isiModalTambahJasaDariDataBarang(data) {
            if (!data) {
                return;
            }
            var $modal = jQuery('#modal-xl-input-barang');
            if (data.uuid_barang) {
                $modal.find('select[name="uuid_barang"]').val(data.uuid_barang).trigger('change');
            }
            if (data.nama_barang && !data.uuid_barang) {
                $modal.find('input[name="satuan"]').val(data.satuan || '');
                $modal.find('input[name="harga_satuan"]').val(formatHargaSatuanPembelian(data.harga_satuan || ''));
            } else {
                $modal.find('input[name="satuan"]').val(data.satuan || '');
                $modal.find('input[name="harga_satuan"]').val(formatHargaSatuanPembelian(data.harga_satuan || ''));
            }
        }

        function renderTabelBarangDuplikatPersediaanParent(rows, bulanLabel) {
            window.barangDuplikatPersediaanCache = rows || [];
            var tbody = document.querySelector('#tabel_barang_duplikat_persediaan tbody');
            if (!tbody) {
                return;
            }
            tbody.innerHTML = '';
            window.barangDuplikatPersediaanCache.forEach(function(row, idx) {
                var hppTampil = formatHargaSatuanPembelian(row.harga_satuan || '');
                var tr = document.createElement('tr');
                tr.innerHTML = '<td>' + (idx + 1) + '</td>'
                    + '<td>' + (row.bulan_label || '') + '</td>'
                    + '<td>' + (row.nama_barang || '') + '</td>'
                    + '<td>' + (row.satuan || '') + '</td>'
                    + '<td class="text-right">' + hppTampil + '</td>'
                    + '<td><button type="button" class="btn btn-primary btn-sm btn-pilih-gunakan-barang-persediaan" data-idx="' + idx + '">Pilih dan gunakan</button></td>';
                tbody.appendChild(tr);
            });
            var info = document.getElementById('modal_barang_duplikat_info');
            if (info) {
                info.innerHTML = 'Nama jasa belum ada di bulan <strong>' + (bulanLabel || '-') + '</strong>, tetapi sudah tercatat di bulan lain. Klik <strong>Pilih dan gunakan</strong> untuk menyalin ke form.';
            }
        }

        function bukaModalDuplikatBarang() {
            var $m = jQuery('#modalBarangDuplikatPersediaan');
            if (!$m.length || !jQuery.fn.modal) {
                return;
            }
            $m.appendTo('body');
            $m.modal({ backdrop: true, keyboard: true, show: true });
            jQuery('body').addClass('modal-pembelian-duplikat-open');
        }

        function tutupModalDuplikatBarang(callback) {
            var $m = jQuery('#modalBarangDuplikatPersediaan');
            if (!$m.length || !$m.hasClass('show')) {
                if (callback) {
                    callback();
                }
                return;
            }
            if (callback) {
                $m.one('hidden.bs.modal', callback);
            }
            $m.modal('hide');
            jQuery('body').removeClass('modal-pembelian-duplikat-open');
        }

        function cekNamaBarangPersediaanModal() {
            if (window.skipCekNamaBarangModalPilih || pilihGunakanBarangInProgress) {
                return;
            }
            var elNama = document.getElementById('modal_nama_barang');
            if (!elNama) {
                return;
            }
            var nama = normalizeNamaBarangInput(elNama.value);
            if (nama !== elNama.value) {
                elNama.value = nama;
            }
            if (nama === '' || cekNamaBarangAjaxPending) {
                return;
            }

            cekNamaBarangAjaxPending = true;
            jQuery.ajax({
                url: "<?php echo site_url('sys_nama_barang/cek_nama_barang_persediaan_ajax'); ?>",
                type: 'GET',
                dataType: 'json',
                timeout: 20000,
                data: {
                    nama_barang: nama,
                    tanggal_po: getTanggalPoUntukFilter()
                },
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).done(function(res) {
                if (!res || !res.success) {
                    return;
                }
                if (res.exists_in_month) {
                    showInputBarangInfo(res.message || 'Nama jasa sudah ada di bulan terpilih.', 'warning');
                    return;
                }
                if (res.show_referensi_modal && res.data && res.data.length > 0) {
                    renderTabelBarangDuplikatPersediaanParent(res.data, res.bulan_label);
                    window.setTimeout(bukaModalDuplikatBarang, 50);
                    showInputBarangInfo(res.message || 'Pilih referensi di daftar.', 'warning');
                    return;
                }
                showInputBarangInfo(res.message || 'Nama jasa dapat digunakan.', 'success');
            }).fail(function() {
                showInputBarangInfo('Gagal mengecek nama jasa.', 'danger');
            }).always(function() {
                cekNamaBarangAjaxPending = false;
            });
        }

        function applyPilihGunakanBarangKeFormInput(data) {
            if (!data) {
                return;
            }
            var elNama = document.getElementById('modal_nama_barang');
            var elSatuan = document.getElementById('modal_satuan_barang');
            var elHpp = document.getElementById('modal_harga_satuan_barang');
            if (elNama) {
                elNama.value = data.nama_barang || '';
            }
            if (elSatuan) {
                elSatuan.value = data.satuan || '';
            }
            if (elHpp) {
                elHpp.value = formatHargaSatuanPembelian(data.harga_satuan || '');
            }
        }

        function pilihDanGunakanBarangDariPersediaan(btn) {
            if (pilihGunakanBarangInProgress) {
                return false;
            }
            var idx = parseInt(btn.getAttribute('data-idx'), 10);
            var data = window.barangDuplikatPersediaanCache[idx];
            if (!data) {
                return false;
            }
            pilihGunakanBarangInProgress = true;
            window.skipCekNamaBarangModalPilih = true;
            cekNamaBarangAjaxPending = false;
            var payload = {
                nama_barang: data.nama_barang || '',
                satuan: data.satuan || '',
                harga_satuan: data.harga_satuan || '',
                bulan_label: data.bulan_label || ''
            };
            if (jQuery('#modalBarangDuplikatPersediaan').hasClass('show')) {
                tutupModalDuplikatBarang(function() {
                    applyPilihGunakanBarangKeFormInput(payload);
                    showInputBarangInfo('Data referensi disalin ke form Input Jasa Baru.', 'success');
                    pilihGunakanBarangInProgress = false;
                    window.setTimeout(function() { window.skipCekNamaBarangModalPilih = false; }, 2000);
                });
            } else {
                applyPilihGunakanBarangKeFormInput(payload);
                showInputBarangInfo('Data referensi disalin ke form Input Jasa Baru.', 'success');
                pilihGunakanBarangInProgress = false;
                window.setTimeout(function() { window.skipCekNamaBarangModalPilih = false; }, 2000);
            }
            return false;
        }
        window.pilihDanGunakanBarangDariPersediaan = pilihDanGunakanBarangDariPersediaan;

        jQuery(document).on('click', '.btn-pilih-gunakan-barang-persediaan', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            pilihDanGunakanBarangDariPersediaan(this);
            return false;
        });

        window.initCekNamaBarangModalInput = function() {
            window.barangDuplikatPersediaanCache = [];
            var u = document.getElementById('modal_uuid_barang_referensi');
            var p = document.getElementById('modal_persediaan_id_referensi');
            if (u) {
                u.value = '';
            }
            if (p) {
                p.value = '';
            }
        };

        function getTanggalPoUntukFilter() {
            return jQuery.trim(jQuery('input[name="tgl_po"]').val() || '');
        }
        window.getTanggalPoUntukFilter = getTanggalPoUntukFilter;

        var spopDuplikatPersediaan = false;
        var cekSpopPersediaanTimer = null;
        var cekSpopPersediaanAjax = null;

        function setInfoCekSpop(teks, kelas) {
            var $info = jQuery('#info-cek-spop-persediaan');
            if (!$info.length) {
                return;
            }
            $info.removeClass('text-muted text-success text-danger text-warning');
            if (kelas) {
                $info.addClass(kelas);
            } else {
                $info.addClass('text-muted');
            }
            $info.html(teks || '');
        }

        function cekNomorSpopPersediaan() {
            var spop = jQuery.trim(jQuery('#spop').val());
            var tanggalPo = getTanggalPoUntukFilter();

            spopDuplikatPersediaan = false;
            setInfoCekSpop('', 'text-muted');

            if (!spop) {
                return jQuery.Deferred().resolve().promise();
            }

            if (!tanggalPo) {
                setInfoCekSpop('Isi Tgl PO terlebih dahulu untuk pengecekan SPOP.', 'text-warning');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tgl PO belum diisi',
                        text: 'Isi Tgl PO terlebih dahulu agar pengecekan nomor SPOP mengikuti tahun yang benar.'
                    });
                }
                return jQuery.Deferred().reject().promise();
            }

            if (cekSpopPersediaanAjax && cekSpopPersediaanAjax.abort) {
                cekSpopPersediaanAjax.abort();
            }

            setInfoCekSpop('Memeriksa nomor SPOP...', 'text-muted');

            cekSpopPersediaanAjax = jQuery.ajax({
                url: "<?php echo site_url('tbl_pembelian_jasa/cek_spop_persediaan_ajax'); ?>",
                type: "GET",
                dataType: "json",
                data: {
                    spop: spop,
                    tanggal_po: tanggalPo
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            return cekSpopPersediaanAjax.done(function(res) {
                if (!res || !res.success) {
                    setInfoCekSpop((res && res.message) ? res.message : 'Pengecekan SPOP gagal.', 'text-warning');
                    return;
                }

                if (res.exists) {
                    spopDuplikatPersediaan = true;
                    setInfoCekSpop(res.message, 'text-danger');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Nomor SPOP sudah ada',
                            html: 'Nomor SPOP <strong>' + jQuery('<div/>').text(spop).html() + '</strong> sudah terdaftar di <strong>persediaan</strong> pada tahun <strong>' + res.tahun + '</strong>.<br><br>Silakan ubah nomor SPOP.',
                            confirmButtonText: 'Ubah Nomor SPOP',
                            confirmButtonColor: '#d33'
                        }).then(function() {
                            jQuery('#spop').focus().select();
                        });
                    }
                    return;
                }

                spopDuplikatPersediaan = false;
                setInfoCekSpop(res.message, 'text-success');
            }).fail(function(xhr, status) {
                if (status === 'abort') {
                    return;
                }
                setInfoCekSpop('Terjadi kesalahan saat memeriksa nomor SPOP.', 'text-warning');
            });
        }
        window.cekNomorSpopPersediaan = cekNomorSpopPersediaan;

        function jadwalkanCekNomorSpopPersediaan() {
            window.clearTimeout(cekSpopPersediaanTimer);
            cekSpopPersediaanTimer = window.setTimeout(function() {
                cekNomorSpopPersediaan();
            }, 450);
        }

        function updateInfoBulanPersediaan(bulanLabel) {
            if (!bulanLabel) {
                return;
            }
            var teks = 'Bulan persediaan: <strong>' + bulanLabel + '</strong>';
            jQuery('#info-bulan-persediaan-modal').html(teks);
            jQuery('#info-bulan-persediaan-barang').html(
                'Daftar jasa/barang (persediaan) bulan: <strong>' + bulanLabel + '</strong> — mengikuti <em>Tgl PO</em>'
            );
        }

        function refreshBarangOptions(selectedUuid) {
            var tanggalPo = getTanggalPoUntukFilter();
            if (!tanggalPo) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tgl PO belum diisi',
                        text: 'Isi Tgl PO terlebih dahulu agar daftar jasa/barang mengikuti bulan persediaan yang benar.'
                    });
                }
                return jQuery.Deferred().reject().promise();
            }

            return jQuery.ajax({
                url: "<?php echo site_url('sys_nama_barang/list_barang_ajax'); ?>",
                type: "GET",
                dataType: "json",
                data: {
                    tanggal_po: tanggalPo
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).done(function(res) {
                if (!res || !res.success) {
                    return;
                }

                if (res.bulan_label) {
                    updateInfoBulanPersediaan(res.bulan_label);
                }

                var select = jQuery('#modal-xl-input-barang select[name="uuid_barang"]');
                var currentValue = selectedUuid || select.val();
                select.empty().append(jQuery('<option>', {
                    value: '',
                    text: 'Pilih Jasa'
                }));

                jQuery.each(res.data || [], function(_, row) {
                    select.append(jQuery('<option>', {
                        value: row.uuid_barang,
                        text: (row.nama_barang || '').toUpperCase()
                    }).attr({
                        'data-satuan': row.satuan || '',
                        'data-harga-satuan': row.harga_satuan || ''
                    }));
                });

                if (currentValue) {
                    select.val(currentValue);
                }
                if (currentValue && window.loadDetailBarangPembelian) {
                    loadDetailBarangPembelian(currentValue);
                } else {
                    select.trigger('change');
                }
            });
        }

        function formatHargaSatuanPembelian(value) {
            if (value === null || typeof value === 'undefined' || value === '') {
                return '';
            }

            var valueString = String(value).trim();
            if (/^(\d{1,3}\.)+\d{3}$/.test(valueString)) {
                valueString = valueString.replace(/\./g, '');
            } else if (/^\d+(\.\d+)?$/.test(valueString)) {
                var numericValue = parseFloat(valueString);
                if (!isNaN(numericValue)) {
                    valueString = String(Math.round(numericValue));
                }
            } else {
                valueString = valueString.replace(/[^0-9]/g, '');
            }

            return valueString.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function applyFormatHargaSatuanPembelian(input) {
            var inputElement = $(input);
            var angka = inputElement.val().replace(/[^0-9]/g, '');
            inputElement.val(formatHargaSatuanPembelian(angka));
        }

        function loadDetailBarangPembelian(uuidBarang) {
            var $modal = jQuery('#modal-xl-input-barang');
            uuidBarang = jQuery.trim(uuidBarang || '');

            if (!uuidBarang) {
                $modal.find('input[name="satuan"]').val('');
                $modal.find('input[name="harga_satuan"]').val('');
                return;
            }

            var selectedOption = $modal.find('select[name="uuid_barang"] option:selected');
            var satuanOption = jQuery.trim(selectedOption.attr('data-satuan') || '');
            var hargaSatuanOption = jQuery.trim(selectedOption.attr('data-harga-satuan') || '');
            $modal.find('input[name="satuan"]').val(satuanOption);
            $modal.find('input[name="harga_satuan"]').val(hargaSatuanOption ? formatHargaSatuanPembelian(hargaSatuanOption) : '');

            jQuery.ajax({
                url: "<?php echo site_url('sys_nama_barang/detail_barang_ajax'); ?>",
                type: "GET",
                dataType: "json",
                data: {
                    uuid_barang: uuidBarang,
                    tanggal_po: getTanggalPoUntukFilter()
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).done(function(res) {
                if (!res || !res.success || !res.data) {
                    return;
                }

                $modal.find('input[name="satuan"]').val(res.data.satuan || '');
                $modal.find('input[name="harga_satuan"]').val(formatHargaSatuanPembelian(res.data.harga_satuan));
            });
        }

        window.loadDetailBarangPembelian = loadDetailBarangPembelian;

        function initSelect2KategoriModal() {
            if (!$.fn.select2) {
                return;
            }

            var $modal = $('#modal-input-barang-baru');
            var $select = $('#modal_kategori');
            if (!$select.length) {
                return;
            }

            destroySelect2IfAny($select);

            $select.select2({
                dropdownParent: $modal,
                width: '100%',
                minimumResultsForSearch: 0,
                placeholder: '-- Pilih Kategori --',
                allowClear: true,
                language: {
                    noResults: function() {
                        return 'Tidak ditemukan';
                    },
                    searching: function() {
                        return 'Mencari...';
                    }
                }
            });
        }

        function initSelect2InModal() {
            initSelect2KategoriModal();
        }

        window.initSelect2KategoriModal = initSelect2KategoriModal;

        function destroySelect2IfAny($select) {
            if ($select.data('select2')) {
                $select.select2('destroy');
            }
            $select.next('.select2-container').remove();
            $select.removeClass('select2-hidden-accessible').removeAttr('data-select2-id').removeAttr('aria-hidden').removeAttr('tabindex');
        }

        function initSelect2PembelianJasaField(selector, placeholder) {
            if (!$.fn.select2) {
                return;
            }

            var $select = $(selector);
            if (!$select.length) {
                return;
            }

            destroySelect2IfAny($select);

            $select.select2({
                width: '100%',
                minimumResultsForSearch: 0,
                placeholder: placeholder,
                allowClear: true,
                language: {
                    noResults: function() {
                        return 'Tidak ditemukan';
                    },
                    searching: function() {
                        return 'Mencari...';
                    }
                }
            });
        }

        function initSelect2FormUtama() {
            initSelect2PembelianJasaField('select[name="uuid_supplier"]', 'Pilih Supplier');
            initSelect2PembelianJasaField('select[name="statuslu"]', 'Pilih Status');
            initSelect2PembelianJasaField('select[name="kas_bank"]', 'Pilih Kas / Bank');
        }

        window.initSelect2FormUtama = initSelect2FormUtama;

        function runInitSelect2FormUtama() {
            initSelect2FormUtama();
        }

        $(document).ready(runInitSelect2FormUtama);
        $(window).on('load', runInitSelect2FormUtama);

        function loadInputBarangForm(callback) {
            if (modalFormLoaded) {
                initSelect2KategoriModal();
                if (window.initCekNamaBarangModalInput) {
                    window.initCekNamaBarangModalInput();
                }
                if (callback) {
                    callback();
                }
                return;
            }

            jQuery('#modal-input-barang-baru-body').html('<div class="text-center text-muted py-4">Memuat form...</div>');
            jQuery.ajax({
                url: "<?php echo site_url('sys_nama_barang/pembelian_modal_form'); ?>",
                type: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).done(function(html) {
                jQuery('#modal-input-barang-baru-body').html(html);
                modalFormLoaded = true;
                initSelect2KategoriModal();
                if (window.initCekNamaBarangModalInput) {
                    window.initCekNamaBarangModalInput();
                }
                if (callback) {
                    callback();
                }
            }).fail(function() {
                jQuery('#modal-input-barang-baru-body').html('<div class="alert alert-danger mb-0">Form input jasa baru gagal dimuat.</div>');
                if (callback) {
                    callback();
                }
            });
        }

        function tampilkanModalInputJasaBaru() {
            jQuery(document).off('focusin.modal');
            var $m = jQuery('#modal-input-barang-baru');
            $m.appendTo('body');
            if (jQuery.fn.modal) {
                $m.modal({ backdrop: true, keyboard: true, show: true });
            } else {
                showBootstrapOrManual('#modal-input-barang-baru');
            }
        }

        window.openModalInputBarangBaruPembelian = function() {
            if (!getTanggalPoUntukFilter()) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tgl PO belum diisi',
                        text: 'Pilih Tgl PO terlebih dahulu agar bulan persediaan ditentukan.'
                    });
                }
                return false;
            }
            loadInputBarangForm(function() {
                tampilkanModalInputJasaBaru();
            });
            return false;
        };

        window.openModalInputBarangBaruPembelianDariHalaman = function() {
            if (!getTanggalPoUntukFilter()) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tgl PO belum diisi',
                        text: 'Pilih Tgl PO terlebih dahulu.'
                    });
                }
                return false;
            }
            loadInputBarangForm(function() {
                tampilkanModalInputJasaBaru();
            });
            return false;
        };

        window.closeModalInputBarangBaruPembelian = function() {
            hideBootstrapOrManual('#modal-input-barang-baru');
            jQuery('body').addClass('modal-open');
        };

        jQuery('#modal-input-barang-baru').on('shown.bs.modal', function(e) {
            if (e.target.id !== 'modal-input-barang-baru') {
                return;
            }
            jQuery(document).off('focusin.modal');
            window.setTimeout(function() {
                initSelect2KategoriModal();
            }, 0);
        });

        jQuery(document).on('input keyup paste', '#modal_harga_satuan_barang', function() {
            var input = this;
            window.setTimeout(function() {
                applyFormatHargaSatuanPembelian(input);
            }, 0);
        });

        var cekNamaBarangBlurTimer = null;
        jQuery(document).on('blur', '#modal_nama_barang', function(e) {
            if (window.skipCekNamaBarangModalPilih || pilihGunakanBarangInProgress) {
                return;
            }
            var target = e.relatedTarget || document.activeElement;
            if (target && jQuery(target).closest('#modalBarangDuplikatPersediaan, .btn-pilih-gunakan-barang-persediaan').length) {
                return;
            }
            window.clearTimeout(cekNamaBarangBlurTimer);
            cekNamaBarangBlurTimer = window.setTimeout(function() {
                cekNamaBarangPersediaanModal();
            }, 350);
        });

        jQuery(document).on('submit', '#form-input-barang-baru-modal', function(e) {
            e.preventDefault();

            var form = jQuery(this);
            var submitButton = jQuery('#btn-submit-input-barang-baru');
            var submitButtonText = submitButton.data('original-text') || submitButton.text();
            var tglPo = getTanggalPoUntukFilter();
            var namaBarang = normalizeNamaBarangInput(jQuery('#modal_nama_barang').val());

            if (!tglPo) {
                showInputBarangInfo('Pilih <strong>Tgl PO</strong> di form pembelian jasa terlebih dahulu.', 'warning');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'warning', title: 'Tgl PO belum dipilih', text: 'Pilih tanggal PO sebelum menyimpan jasa baru.' });
                }
                return false;
            }

            if (!namaBarang) {
                showInputBarangInfo('Nama jasa wajib diisi.', 'warning');
                jQuery('#modal_nama_barang').focus();
                return false;
            }

            jQuery('#modal_nama_barang').val(namaBarang);
            submitButton.data('original-text', submitButtonText);
            submitButton.prop('disabled', true).text('Menyimpan...');
            showInputBarangInfo('Menyimpan jasa baru ke persediaan...', 'warning');

            jQuery.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize() + '&tanggal_po=' + encodeURIComponent(tglPo),
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).done(function(res) {
                if (res && res.success && res.data) {
                    refreshBarangOptions(res.data.uuid_barang).always(function() {
                        isiModalTambahJasaDariDataBarang(res.data);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Jasa tersimpan',
                                text: 'Nama, satuan, dan HPP sudah diisi di form Tambah Pembelian Jasa.',
                                showConfirmButton: false,
                                timer: 2200
                            });
                        }
                        form[0].reset();
                        window.closeModalInputBarangBaruPembelian();
                    });
                    return;
                }

                if (res && res.duplicate && res.data) {
                    refreshBarangOptions(res.data.uuid_barang).always(function() {
                        isiModalTambahJasaDariDataBarang(res.data);
                    });
                }
                showInputBarangInfo((res && res.message) ? res.message : 'Gagal menyimpan jasa baru.', 'danger');
            }).fail(function() {
                showInputBarangInfo('Terjadi kesalahan saat menyimpan jasa baru.', 'danger');
            }).always(function() {
                submitButton.prop('disabled', false).text(submitButtonText);
            });

            return false;
        });

        jQuery(document).on('change', '#modal-xl-input-barang select[name="uuid_barang"]', function() {
            loadDetailBarangPembelian(jQuery(this).val());
        });

        jQuery('#tgl_po').on('change.datetimepicker', function() {
            refreshBarangOptions();
            if (jQuery.trim(jQuery('#spop').val())) {
                jadwalkanCekNomorSpopPersediaan();
            }
        });
        jQuery('input[name="tgl_po"]').on('change blur', function() {
            refreshBarangOptions();
            if (jQuery.trim(jQuery('#spop').val())) {
                jadwalkanCekNomorSpopPersediaan();
            }
        });

        jQuery('#spop').on('blur', function() {
            jadwalkanCekNomorSpopPersediaan();
        });
        jQuery('#spop').on('input', function() {
            spopDuplikatPersediaan = false;
            setInfoCekSpop('', 'text-muted');
        });

        jQuery('form[action*="create_action_uuid_spop"]').on('submit', function(e) {
            var $form = jQuery(this);

            if ($form.data('spop-cek-lulus')) {
                $form.removeData('spop-cek-lulus');
                return true;
            }

            e.preventDefault();

            if (!jQuery.trim(jQuery('#spop').val())) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Nomor SPOP belum diisi',
                        text: 'Isi nomor SPOP terlebih dahulu.'
                    });
                }
                return false;
            }

            cekNomorSpopPersediaan().always(function() {
                if (spopDuplikatPersediaan) {
                    return;
                }
                $form.data('spop-cek-lulus', true);
                $form[0].submit();
            });

            return false;
        });

        jQuery('#modal-xl-input-barang').on('show.bs.modal', function() {
            if (spopDuplikatPersediaan) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Nomor SPOP duplikat',
                        text: 'Ubah nomor SPOP terlebih dahulu sebelum menambah jasa.'
                    });
                }
                return false;
            }
            if (!getTanggalPoUntukFilter()) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tgl PO belum diisi',
                        text: 'Isi Tgl PO terlebih dahulu.'
                    });
                }
                return false;
            }
            if (!jQuery.trim(jQuery('#spop').val())) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Nomor SPOP belum diisi',
                        text: 'Isi nomor SPOP terlebih dahulu.'
                    });
                }
                return false;
            }
            refreshBarangOptions();
        });

        jQuery(document).on('input keyup paste', '#modal-xl-input-barang input[name="harga_satuan"]', function() {
            var input = this;
            setTimeout(function() {
                applyFormatHargaSatuanPembelian(input);
            }, 0);
        });
    })(jQuery);
</script>