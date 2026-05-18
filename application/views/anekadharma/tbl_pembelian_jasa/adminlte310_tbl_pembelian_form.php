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

                                                        <select name="uuid_barang" id="uuid_barang" class="form-control select2" style="width: 100%; height: 80px;" onchange="if (window.loadDetailBarangPembelian) { loadDetailBarangPembelian(this.value); }" required>
                                                            <option value="">Pilih Jasa</option>
                                                            <?php

                                                            // $sql = "SELECT `uuid_barang`,`kode_barang`,`nama_barang` FROM `sys_nama_barang` ORDER by `nama_barang` ASC";
                                                            $select_harga_satuan = $this->db->field_exists('harga_satuan', 'sys_nama_barang') ? ", `harga_satuan`" : "";
                                                            $sql = "SELECT `uuid_barang`,`kode_barang`,`nama_barang`,`satuan` $select_harga_satuan FROM `sys_nama_barang`  GROUP by `nama_barang`";
                                                            foreach ($this->db->query($sql)->result() as $m) {
                                                                $harga_satuan_barang = isset($m->harga_satuan) ? $m->harga_satuan : "";
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
        var shouldReopenBarangBeliModal = false;
        var manualBackdropId = 'modal-input-barang-baru-backdrop';

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
                .text(message);
        }

        function refreshBarangOptions(selectedUuid) {
            return $.ajax({
                url: "<?php echo site_url('sys_nama_barang/list_barang_ajax'); ?>",
                type: "GET",
                dataType: "json",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).done(function(res) {
                if (!res || !res.success) {
                    return;
                }

                var select = $('#uuid_barang');
                var currentValue = selectedUuid || select.val();
                select.empty().append($('<option>', {
                    value: '',
                    text: 'Pilih Jasa'
                }));

                $.each(res.data || [], function(_, row) {
                    select.append($('<option>', {
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
                select.trigger('change');
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
            if (!uuidBarang) {
                $('#satuan').val('');
                $('#harga_satuan').val('');
                return;
            }

            var selectedOption = $('#uuid_barang option:selected');
            var satuanOption = selectedOption.attr('data-satuan') || '';
            var hargaSatuanOption = selectedOption.attr('data-harga-satuan') || '';
            if (satuanOption !== '') {
                $('#satuan').val(satuanOption);
            }
            if (hargaSatuanOption !== '') {
                $('#harga_satuan').val(formatHargaSatuanPembelian(hargaSatuanOption));
            }

            $.ajax({
                url: "<?php echo site_url('sys_nama_barang/detail_barang_ajax'); ?>",
                type: "GET",
                dataType: "json",
                data: {
                    uuid_barang: uuidBarang
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).done(function(res) {
                if (!res || !res.success || !res.data) {
                    return;
                }

                $('#satuan').val(res.data.satuan || '');
                $('#harga_satuan').val(formatHargaSatuanPembelian(res.data.harga_satuan));
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
                if (callback) {
                    callback();
                }
                return;
            }

            $('#modal-input-barang-baru-body').html('<div class="text-center text-muted py-4">Memuat form...</div>');
            $.ajax({
                url: "<?php echo site_url('sys_nama_barang/pembelian_modal_form'); ?>",
                type: "GET",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).done(function(html) {
                $('#modal-input-barang-baru-body').html(html);
                modalFormLoaded = true;
                if (callback) {
                    callback();
                }
            }).fail(function() {
                $('#modal-input-barang-baru-body').html('<div class="alert alert-danger mb-0">Form input barang baru gagal dimuat.</div>');
                if (callback) {
                    callback();
                }
            });
        }

        window.openModalInputBarangBaruPembelian = function() {
            shouldReopenBarangBeliModal = true;

            if ($.fn.modal) {
                $('#modal-xl-input-barang').one('hidden.bs.modal', function() {
                    loadInputBarangForm(function() {
                        showBootstrapOrManual('#modal-input-barang-baru');
                    });
                }).modal('hide');
                return;
            }

            loadInputBarangForm(function() {
                showModalManual('#modal-input-barang-baru');
            });
        };

        window.openModalInputBarangBaruPembelianDariHalaman = function() {
            shouldReopenBarangBeliModal = false;

            loadInputBarangForm(function() {
                showBootstrapOrManual('#modal-input-barang-baru');
            });
        };

        window.closeModalInputBarangBaruPembelian = function() {
            hideBootstrapOrManual('#modal-input-barang-baru');
            if (shouldReopenBarangBeliModal && !$('#modal-xl-input-barang').hasClass('show')) {
                showBootstrapOrManual('#modal-xl-input-barang');
            }
        };

        $('#modal-input-barang-baru').on('shown.bs.modal', function(e) {
            if (e.target.id !== 'modal-input-barang-baru') {
                return;
            }
            setTimeout(function() {
                initSelect2KategoriModal();
            }, 0);
        });

        $('#modal-input-barang-baru').on('hidden.bs.modal', function(e) {
            if (e.target.id !== 'modal-input-barang-baru') {
                return;
            }
            if (shouldReopenBarangBeliModal) {
                showBootstrapOrManual('#modal-xl-input-barang');
            }
        });

        $(document).on('submit', '#form-input-barang-baru-modal', function(e) {
            e.preventDefault();

            var form = $(this);
            var submitButton = $('#btn-submit-input-barang-baru');
            var submitButtonText = submitButton.data('original-text') || submitButton.text();
            submitButton.data('original-text', submitButtonText);
            submitButton.prop('disabled', true).text('Menyimpan...');
            showInputBarangInfo('Menyimpan barang baru...', 'warning');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).done(function(res) {
                if (res && res.success && res.data) {
                    refreshBarangOptions(res.data.uuid_barang).always(function() {
                        showInputBarangInfo(res.message || 'Barang berhasil ditambahkan.', 'success');
                        form[0].reset();
                        window.closeModalInputBarangBaruPembelian();
                    });
                    return;
                }

                if (res && res.duplicate && res.data) {
                    refreshBarangOptions(res.data.uuid_barang);
                }
                showInputBarangInfo((res && res.message) ? res.message : 'Barang baru gagal disimpan.', 'danger');
            }).fail(function() {
                showInputBarangInfo('Terjadi kesalahan saat menyimpan barang baru.', 'danger');
            }).always(function() {
                submitButton.prop('disabled', false).text(submitButtonText);
            });
        });

        $(document).on('change', '#uuid_barang', function() {
            loadDetailBarangPembelian($(this).val());
        });

        $(document).on('input keyup paste', '#harga_satuan', function() {
            var input = this;
            setTimeout(function() {
                applyFormatHargaSatuanPembelian(input);
            }, 0);
        });
    })(jQuery);
</script>