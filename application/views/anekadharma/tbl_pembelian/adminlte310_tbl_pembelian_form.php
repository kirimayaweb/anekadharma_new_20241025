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


                        <form action="<?php echo $action; ?>" method="post" id="form-pembelian-create" novalidate>





                            <div class="form-group">
                                <label for="datetime">Tgl Po <?php echo form_error('tgl_po') ?></label>
                                <div class="col-3">
                                    <!-- <input type="text" class="form-control" name="tgl_po" id="tgl_po" placeholder="Tgl Po" value="<?php echo $tgl_po; ?>" /> -->
                                    <div class="input-group date" id="tgl_po" name="tgl_po" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_po" name="tgl_po" value="<?php echo htmlspecialchars(isset($tgl_po) ? $tgl_po : '', ENT_QUOTES, 'UTF-8'); ?>" required />
                                        <div class="input-group-append" data-target="#tgl_po" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1" id="info-bulan-persediaan-barang">
                                    Daftar barang (persediaan) bulan: <strong><?php echo htmlspecialchars($filter_bulan_pembelian['bulan_label'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                    — mengikuti <em>Tgl PO</em>
                                </small>

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
                                                echo ">  " . strtoupper($m->nama_supplier) . " ==> ( " . strtoupper($m->alamat_supplier) . ")</option>";
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
                                        
                                    <div class="col-4" text-align="center"> </div>
                                        
                                        <div class="col-4" text-align="center">
                                            <strong>Detail Barang</strong>
                                        </div>



                                        <div class="col-2" text-align="center">
                                            <button type="button" class="btn btn-block btn-warning" id="btn-open-modal-barang-baru-halaman" onclick="openModalInputBarangBaruPembelianDariHalaman(); return false;">
                                                Input Barang Baru
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
                                        Tambah Barang Pembelian
                                    </button>


                                </div>
                            </div>



                            <br />




                            <!-- MODAL EXTRA LARGE -->

                            <div class="modal fade" id="modal-xl-input-barang">
                                <div class="modal-dialog modal-xl modal-dialog-pembelian-tambah-barang">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Tambah Barang Beli</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group">


                                                <div class="row">
                                                    <div class="col-4">
                                                        <label for="konsumen_nama">Unit <?php echo form_error('konsumen_nama') ?></label>
                                                        <select name="uuid_konsumen" id="uuid_konsumen_beli" class="form-control select2-pembelian-beli-unit" style="width: 100%;" required>
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
                                                    <div class="col-6 pembelian-barang-combobox-wrap">
                                                        <label for="uuid_barang">Barang <?php echo form_error('uuid_barang') ?></label>
                                                        <small class="text-muted d-block mb-1" id="info-bulan-persediaan-modal">
                                                            Daftar barang dari seluruh data persediaan (group: nama, satuan, harga satuan)
                                                        </small>

                                                        <div class="pembelian-barang-select2-anchor">
                                                        <select name="uuid_barang" id="uuid_barang_beli" class="form-control select2" style="width: 100%;" required>
                                                            <option value="">Pilih Barang</option>
                                                            <?php

                                                            foreach (pembelian_get_barang_combobox_modal_rows($this) as $m) {
                                                                $harga_satuan_barang = isset($m->harga_satuan) ? $m->harga_satuan : '';
                                                                $opt_uuid = !empty($m->uuid_persediaan) ? trim((string) $m->uuid_persediaan) : trim((string) $m->uuid_barang);
                                                                $label_barang = pembelian_format_barang_combobox_label(
                                                                    $m->nama_barang,
                                                                    isset($m->satuan) ? $m->satuan : '',
                                                                    $harga_satuan_barang
                                                                );
                                                                echo "<option value='" . htmlspecialchars($opt_uuid, ENT_QUOTES, 'UTF-8') . "' data-satuan='" . htmlspecialchars($m->satuan, ENT_QUOTES, 'UTF-8') . "' data-harga-satuan='" . htmlspecialchars($harga_satuan_barang, ENT_QUOTES, 'UTF-8') . "' ";
                                                                echo ">  " . htmlspecialchars($label_barang, ENT_QUOTES, 'UTF-8') . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-8">
                                                                <button type="button" class="btn btn-block btn-danger" id="btn-open-modal-barang-baru" onclick="openModalInputBarangBaruPembelian(); return false;">
                                                                    Input Barang Baru
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-3">
                                                        <label for="satuan">Satuan <?php echo form_error('satuan') ?></label>
                                                        <input type="text" name="satuan" id="satuan_beli" placeholder="satuan" class="form-control" required>
                                                    </div>
                                                    <div class="col-3">
                                                        <label for="satuan">Harga Satuan <?php echo form_error('harga_satuan') ?></label>
                                                        <input type="text" name="harga_satuan" id="harga_satuan_beli" placeholder="contoh: 1.250,50" class="form-control" inputmode="decimal" autocomplete="off" required>
                                                    </div>
                                                </div>

                                                <div class="row pembelian-row-jumlah-gudang">
                                                    <div class="col-4 pembelian-row-jumlah-wrap">
                                                        <label for="jumlah">Jumlah <?php //echo form_error('nmrpesan') 
                                                                                    ?></label>
                                                        <!-- <input type="text" class="form-control" rows="3" name="jumlah" id="jumlah" placeholder="Jumlah" required> -->
                                                        <input type="text" name="jumlah" id="jumlah_beli" placeholder="Jumlah" class="form-control" inputmode="numeric" required>
                                                    </div>
                                                    <div class="col-4 pembelian-row-gudang-wrap">
                                                        <label for="uuid_gudang">Gudang <?php echo form_error('uuid_gudang') ?></label>
                                                        <select name="uuid_gudang" id="uuid_gudang_beli" class="form-control select2-pembelian-beli-gudang" style="width: 100%;" required>
                                                            <option value="">Pilih Gudang</option>
                                                            <?php

                                                            $sql = "SELECT `uuid_gudang`,`kode_gudang`,`nama_gudang` FROM `sys_gudang` ORDER by `nama_gudang` ASC";
                                                            foreach ($this->db->query($sql)->result() as $m) {
                                                                echo "<option value='$m->uuid_gudang' ";
                                                                echo ">  " . strtoupper($m->kode_gudang) . strtoupper($m->nama_gudang)  . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-4">
                                                    </div>
                                                </div>





                                            </div>

                                        </div>

                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                            <!-- <button type="button" class="btn btn-primary">Simpan</button> -->
                                            <button type="button" class="btn btn-primary" id="btn-simpan-tambah-barang-beli"><?php echo $button ?></button>
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
                            <a href="<?php echo site_url('tbl_pembelian') ?>" class="btn btn-default">Cancel</a>
                        </form>

                        <div class="modal fade" id="modal-input-barang-baru" tabindex="-1" role="dialog" aria-labelledby="modalInputBarangBaruLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h4 class="modal-title" id="modalInputBarangBaruLabel">Input Barang Baru</h4>
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

                        <div class="modal fade" id="modalBarangDuplikatPersediaan" tabindex="-1" role="dialog" aria-labelledby="modalBarangDuplikatPersediaanLabel" aria-hidden="true" data-backdrop="true" data-keyboard="true">
                            <div class="modal-dialog modal-dialog-pembelian-referensi modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-info">
                                        <h5 class="modal-title" id="modalBarangDuplikatPersediaanLabel">Nama Barang Sudah Ada di Sistem</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-info mb-2" id="modal_barang_duplikat_info">
                                            Nama barang belum ada di bulan terpilih (sesuai <strong>Tgl PO</strong>), tetapi sudah ada di sistem pada bulan lain. Pilih <strong>Pilih dan gunakan</strong> pada salah satu baris untuk menyalin Nama, Satuan, dan HPP ke form Input Barang Baru.
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm table-striped mb-0" id="tabel_barang_duplikat_persediaan">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th style="width:50px;">No</th>
                                                        <th style="width:90px;">Bulan</th>
                                                        <th>Nama Barang</th>
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
<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }

    #modal-input-barang-baru .modal-kategori-select-wrap {
        width: 100%;
        min-width: 0;
    }

    #modal-input-barang-baru .modal-kategori-select-wrap .select2-container {
        width: 100% !important;
        max-width: 100%;
    }

    /* Modal Input Barang Baru: di atas modal Tambah Barang Beli & backdrop agar semua field bisa diklik */
    #modal-input-barang-baru {
        z-index: 10060 !important;
    }

    #modal-input-barang-baru .modal-dialog,
    #modal-input-barang-baru .modal-content,
    #modal-input-barang-baru-body,
    #modal-input-barang-baru input,
    #modal-input-barang-baru textarea,
    #modal-input-barang-baru select,
    #modal-input-barang-baru button,
    #modal-input-barang-baru .select2-container {
        pointer-events: auto !important;
    }

    #modal-input-barang-baru .select2-dropdown {
        z-index: 10070 !important;
    }

    #modal-input-barang-baru .select2-container--open {
        z-index: 10069 !important;
    }

    #modalBarangTambahKategori,
    #modalBarangDaftarKategori {
        z-index: 10090 !important;
    }

    /* Modal referensi nama barang: di depan semua modal, lebar hampir penuh, tinggi dikurangi */
    #modalBarangDuplikatPersediaan {
        z-index: 10080 !important;
        padding-left: 2vw !important;
        padding-right: 2vw !important;
    }

    #modalBarangDuplikatPersediaan .modal-dialog-pembelian-referensi {
        max-width: 96vw;
        width: 96vw;
        margin: 4vh auto;
        max-height: 72vh;
    }

    #modalBarangDuplikatPersediaan .modal-content {
        max-height: 72vh;
        display: flex;
        flex-direction: column;
    }

    #modalBarangDuplikatPersediaan .modal-body {
        overflow-y: auto;
        flex: 1 1 auto;
        max-height: calc(72vh - 8rem);
    }

    body.modal-pembelian-duplikat-open .modal-backdrop:last-of-type {
        z-index: 10070 !important;
        opacity: 0.35;
    }

    #modal-xl-input-barang {
        z-index: 1055 !important;
        overflow-x: hidden !important;
        padding-left: 15px !important;
        padding-right: 15px !important;
    }

    #modal-xl-input-barang .modal-dialog {
        margin-left: auto !important;
        margin-right: auto !important;
        left: auto !important;
        right: auto !important;
        transform: none !important;
        position: relative;
    }

    #modal-xl-input-barang .modal-dialog-pembelian-tambah-barang {
        max-width: 1440px;
        width: 98%;
    }

    #modal-xl-input-barang .modal-content {
        overflow: visible;
        position: relative;
    }

    #modal-xl-input-barang .modal-footer {
        position: relative;
        z-index: 1;
        background: #fff;
    }

    #modal-xl-input-barang .modal-body {
        overflow-x: hidden;
        overflow-y: visible;
        position: relative;
        padding: 1.25rem 1.75rem;
    }

    #modal-xl-input-barang .pembelian-barang-combobox-wrap {
        min-width: 0;
        position: relative;
        z-index: 2;
    }

    #modal-xl-input-barang .pembelian-barang-combobox-wrap.is-select2-barang-open {
        z-index: 20;
    }

    #modal-xl-input-barang .pembelian-barang-select2-anchor {
        position: relative;
        width: 100%;
    }

    #modal-xl-input-barang .pembelian-row-jumlah-gudang,
    #modal-xl-input-barang .pembelian-row-jumlah-wrap,
    #modal-xl-input-barang .pembelian-row-gudang-wrap {
        position: relative;
        z-index: 1;
    }

    #modal-xl-input-barang .select2-container {
        width: 100% !important;
    }

    #modal-xl-input-barang .select2-pembelian-barang-wrap.select2-container--open {
        z-index: 1061 !important;
    }

    #modal-xl-input-barang .select2-pembelian-barang-dropdown {
        z-index: 1062 !important;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.12);
        background: #fff;
    }

    #modal-xl-input-barang.is-barang-dropdown-open .pembelian-row-gudang-wrap .select2-container,
    #modal-xl-input-barang.is-barang-dropdown-open .select2-pembelian-gudang-wrap {
        z-index: 1 !important;
    }

    /* Combobox Unit: tinggi ~1.3x agar teks nama unit tidak terpotong */
    #modal-xl-input-barang .select2-pembelian-unit-wrap .select2-selection--single {
        min-height: 52px;
        height: auto !important;
        display: flex;
        align-items: center;
    }

    #modal-xl-input-barang .select2-pembelian-unit-wrap .select2-selection__rendered {
        line-height: 1.35;
        padding: 10px 34px 10px 12px;
        white-space: normal;
        word-break: break-word;
        overflow: visible;
    }

    #modal-xl-input-barang .select2-pembelian-unit-wrap .select2-selection__arrow {
        height: 100%;
        top: 0;
        display: flex;
        align-items: center;
    }

    #modal-xl-input-barang .select2-pembelian-unit-dropdown .select2-results__option {
        padding: 10px 12px;
        line-height: 1.35;
        white-space: normal;
        word-break: break-word;
    }

    /* Combobox Pilih Barang — tampilan standar Select2, nyaman dibaca */
    #modal-xl-input-barang .select2-pembelian-barang-wrap .select2-selection--single {
        min-height: calc(2.25rem + 2px);
        height: auto !important;
        display: flex;
        align-items: center;
        font-size: 1rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    #modal-xl-input-barang .select2-pembelian-barang-wrap .select2-selection__rendered {
        line-height: 1.5;
        padding: 0.375rem 2rem 0.375rem 0.75rem;
        font-size: 1rem;
        white-space: normal;
        word-break: break-word;
    }

    #modal-xl-input-barang .select2-pembelian-barang-wrap .select2-selection__arrow {
        height: 100%;
        top: 0;
        display: flex;
        align-items: center;
    }

    #modal-xl-input-barang .select2-pembelian-barang-dropdown .select2-search--dropdown,
    #modal-xl-input-barang .select2-pembelian-barang-dropdown .select2-search--dropdown.select2-search--hide {
        display: block !important;
        padding: 10px;
        border-bottom: 1px solid #dee2e6;
        background: #f8f9fa;
    }

    #modal-xl-input-barang .select2-pembelian-barang-dropdown .select2-search__field {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding: 10px 14px !important;
        height: 42px !important;
        min-height: 42px !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
        font-size: 1rem !important;
        line-height: 1.5 !important;
        margin: 0 !important;
    }

    #modal-xl-input-barang .select2-pembelian-barang-dropdown .select2-results > .select2-results__options {
        max-height: 300px;
        overflow-y: auto;
    }

    #modal-xl-input-barang .select2-pembelian-barang-dropdown .select2-results__option {
        padding: 8px 12px;
        line-height: 1.5;
        font-size: 1rem;
        white-space: normal;
        word-break: break-word;
    }

    #modal-xl-input-barang .select2-pembelian-barang-wrap {
        width: 100% !important;
        display: block;
    }

    .select2-search--dropdown {
        display: block !important;
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
<script>
    (function($) {
        var modalFormLoaded = false;
        var shouldReopenBarangBeliModal = false;
        var manualBackdropId = 'modal-input-barang-baru-backdrop';
        var skipBackdropCloseAll = false;

        /* Jangan pindahkan modal-xl-input-barang ke body — field harus tetap di dalam form-pembelian-create */
        jQuery('#modal-input-barang-baru, #modalBarangDuplikatPersediaan').appendTo('body');

        function tutupSemuaModalPembelian() {
            skipBackdropCloseAll = true;
            shouldReopenBarangBeliModal = false;
            jQuery('#modalBarangDuplikatPersediaan').modal('hide');
            jQuery('#modal-input-barang-baru').modal('hide');
            jQuery('#modal-xl-input-barang').modal('hide');
            jQuery('.modal-backdrop').remove();
            jQuery('body').removeClass('modal-open modal-pembelian-duplikat-open').css({
                paddingRight: '',
                overflow: ''
            });
            window.setTimeout(function() {
                skipBackdropCloseAll = false;
            }, 350);
        }
        window.tutupSemuaModalPembelian = tutupSemuaModalPembelian;

        jQuery(document).on('click.pembelianTutupSemuaModal', '.modal-backdrop', function() {
            if (skipBackdropCloseAll || jQuery('.select2-container--open').length) {
                return;
            }
            tutupSemuaModalPembelian();
        });

        jQuery('#modalBarangDuplikatPersediaan').on('click.pembelianTutupSemuaModal', function(e) {
            if (e.target === this) {
                tutupSemuaModalPembelian();
            }
        });

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

        function aktifkanSemuaInputModalBarangBaru() {
            var $wrap = jQuery('#modal-input-barang-baru');
            $wrap.find('input, textarea, select, button').each(function() {
                var $el = jQuery(this);
                if ($el.attr('type') === 'hidden') {
                    return;
                }
                $el.prop('disabled', false).prop('readonly', false).removeAttr('aria-disabled');
            });
            $wrap.find('.select2-container').css('pointer-events', 'auto');
            jQuery('#modal-input-barang-baru-body').find('input, textarea, select').prop('disabled', false).prop('readonly', false);
        }
        window.aktifkanSemuaInputModalBarangBaru = aktifkanSemuaInputModalBarangBaru;

        function tampilkanModalInputBarangBaru() {
            jQuery(document).off('focusin.modal');
            var $m = jQuery('#modal-input-barang-baru');
            $m.appendTo('body');
            jQuery('#modalBarangTambahKategori, #modalBarangDaftarKategori').appendTo('body');

            if (jQuery.fn.modal) {
                $m.modal({
                    backdrop: true,
                    keyboard: true,
                    show: true
                });
            } else {
                showModalManual('#modal-input-barang-baru');
            }

            window.setTimeout(function() {
                aktifkanSemuaInputModalBarangBaru();
                initSelect2KategoriModal();
                var elNama = document.getElementById('modal_nama_barang');
                if (elNama) {
                    elNama.focus();
                }
            }, 80);
        }

        function showBootstrapOrManual(selector) {
            if (selector === '#modal-input-barang-baru') {
                tampilkanModalInputBarangBaru();
                return;
            }
            if ($.fn.modal) {
                $(selector).modal('show');
            } else {
                showModalManual(selector);
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
        window.showInputBarangInfo = showInputBarangInfo;

        function getTanggalPoUntukFilter() {
            return $('input[name="tgl_po"]').val() || '';
        }
        window.getTanggalPoUntukFilter = getTanggalPoUntukFilter;

        function simpanTglPoCreateSession() {
            var tgl = getTanggalPoUntukFilter();
            if (!jQuery.trim(tgl)) {
                return;
            }
            jQuery.ajax({
                url: "<?php echo site_url('tbl_pembelian/save_tgl_po_create_ajax'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    tanggal_po: tgl
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).done(function(res) {
                if (res && res.success && res.bulan_label) {
                    updateInfoBulanPersediaan(res.bulan_label);
                }
            });
        }
        window.simpanTglPoCreateSession = simpanTglPoCreateSession;

        function simpanTambahBarangBeliDariModal() {
            var $form = jQuery('#form-pembelian-create');
            var $modal = jQuery('#modal-xl-input-barang');
            var pesan = [];

            if (!jQuery.trim(jQuery('input[name="tgl_po"]').val())) {
                pesan.push('Tgl PO (datepicker)');
            }
            if (!jQuery.trim(jQuery('#spop').val())) {
                pesan.push('Nomor SPOP');
            }
            if (!jQuery('#uuid_supplier').val()) {
                pesan.push('Supplier');
            }
            if (!$modal.find('select[name="uuid_konsumen"]').val()) {
                pesan.push('Unit');
            }
            if (!$modal.find('select[name="uuid_barang"]').val()) {
                pesan.push('Barang');
            }
            if (!jQuery.trim($modal.find('input[name="satuan"]').val())) {
                pesan.push('Satuan');
            }
            if (!jQuery.trim($modal.find('input[name="harga_satuan"]').val())) {
                pesan.push('Harga Satuan');
            }
            if (!jQuery.trim($modal.find('input[name="jumlah"]').val())) {
                pesan.push('Jumlah');
            }
            if (!$modal.find('select[name="uuid_gudang"]').val()) {
                pesan.push('Gudang');
            }

            if (pesan.length) {
                var teks = 'Lengkapi data berikut: <ul style="text-align:left;margin:8px 0 0;padding-left:18px;">'
                    + pesan.map(function(p) {
                        return '<li>' + p + '</li>';
                    }).join('') + '</ul>';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data belum lengkap',
                        html: teks
                    });
                } else {
                    alert('Lengkapi: ' + pesan.join(', '));
                }
                return false;
            }

            skipBackdropCloseAll = true;
            $modal.modal('hide');
            window.setTimeout(function() {
                skipBackdropCloseAll = false;
                if ($form.length) {
                    $form[0].submit();
                }
            }, 200);

            return false;
        }
        window.simpanTambahBarangBeliDariModal = simpanTambahBarangBeliDariModal;

        jQuery(document).on('click', '#btn-simpan-tambah-barang-beli', function(e) {
            e.preventDefault();
            e.stopPropagation();
            simpanTambahBarangBeliDariModal();
            return false;
        });

        function updateInfoBulanPersediaan(bulanLabel) {
            if (!bulanLabel) {
                return;
            }
            $('#info-bulan-persediaan-barang').html(
                'Daftar barang (persediaan) bulan: <strong>' + bulanLabel + '</strong> — mengikuti <em>Tgl PO</em>'
            );
        }

        function getJqPembelianModal() {
            return window.jQuery || window.$;
        }

        function destroySelect2Element($select) {
            if (!$select || !$select.length) {
                return;
            }
            try {
                if ($select.data('select2')) {
                    $select.select2('destroy');
                }
            } catch (e) {}
            $select.next('.select2-container').remove();
            $select.removeClass('select2-hidden-accessible').removeAttr('data-select2-id').removeAttr('aria-hidden').removeAttr('tabindex');
        }

        function sesuaikanDropdownBarangBeliModal($select) {
            var $ = getJqPembelianModal();
            if (!$ || !$select || !$select.length) {
                return;
            }
            var $container = $select.next('.select2-pembelian-barang-wrap');
            if (!$container.length) {
                $container = $select.next('.select2-container');
            }
            var lebar = $container.outerWidth();
            if (!lebar) {
                return;
            }

            var $dropdown = $('.select2-pembelian-barang-dropdown');
            if (!$dropdown.length) {
                return;
            }

            $dropdown.css({
                width: lebar + 'px',
                minWidth: lebar + 'px'
            });

            $dropdown.find('.select2-search__field').css({
                width: '100%',
                maxWidth: '100%',
                boxSizing: 'border-box',
                height: '42px',
                minHeight: '42px',
                fontSize: '1rem',
                padding: '10px 14px'
            });
        }

        function initSelect2BarangBeliModal(attempt) {
            attempt = attempt || 0;
            var $ = getJqPembelianModal();
            if (!$ || !$.fn || !$.fn.select2) {
                if (attempt < 30) {
                    window.setTimeout(function() {
                        initSelect2BarangBeliModal(attempt + 1);
                    }, 100);
                }
                return;
            }

            var $modal = $('#modal-xl-input-barang');
            var $select = $('#uuid_barang_beli');
            if (!$modal.length || !$select.length) {
                return;
            }

            destroySelect2Element($select);

            $select.select2({
                dropdownParent: $modal,
                width: '100%',
                minimumResultsForSearch: 0,
                placeholder: 'Pilih Barang',
                allowClear: false,
                containerCssClass: 'select2-pembelian-barang-wrap',
                dropdownCssClass: 'select2-pembelian-barang-dropdown',
                language: {
                    noResults: function() {
                        return 'Nama barang tidak ditemukan';
                    },
                    searching: function() {
                        return 'Mencari...';
                    }
                }
            });

            $select.off('select2:open.barangSearchFix select2:close.barangSearchFix select2:select.pembelianBarang change.pembelianBarang');
            $select.on('select2:open.barangSearchFix', function() {
                $modal.addClass('is-barang-dropdown-open');
                $select.closest('.pembelian-barang-combobox-wrap').addClass('is-select2-barang-open');
                window.setTimeout(function() {
                    sesuaikanDropdownBarangBeliModal($select);
                    var $searchWrap = $modal.find('.select2-pembelian-barang-dropdown .select2-search--dropdown');
                    $searchWrap.removeClass('select2-search--hide').show();
                    var $field = $modal.find('.select2-pembelian-barang-dropdown .select2-search__field');
                    if ($field.length) {
                        $field.attr('placeholder', 'Ketik nama barang untuk mencari...').trigger('focus');
                    }
                }, 0);
            });
            $select.on('select2:close.barangSearchFix', function() {
                $modal.removeClass('is-barang-dropdown-open');
                $select.closest('.pembelian-barang-combobox-wrap').removeClass('is-select2-barang-open');
            });
            $select.on('change.pembelianBarang select2:select.pembelianBarang', function() {
                loadDetailBarangPembelian($(this).val());
            });
        }

        function initSelect2UnitGudangBeliModal() {
            var $ = getJqPembelianModal();
            if (!$ || !$.fn || !$.fn.select2) {
                return;
            }

            var $modal = $('#modal-xl-input-barang');
            if (!$modal.length) {
                return;
            }

            var $dropdownParentUnit = $modal;
            $('#uuid_konsumen_beli, #uuid_gudang_beli').each(function() {
                var $select = $(this);
                var isGudang = $select.attr('id') === 'uuid_gudang_beli';
                destroySelect2Element($select);
                $select.select2({
                    dropdownParent: $dropdownParentUnit,
                    width: '100%',
                    minimumResultsForSearch: 0,
                    placeholder: $select.find('option:first').text() || 'Pilih',
                    allowClear: false,
                    containerCssClass: isGudang ? 'select2-pembelian-gudang-wrap' : 'select2-pembelian-unit-wrap',
                    dropdownCssClass: isGudang ? 'select2-pembelian-gudang-dropdown' : 'select2-pembelian-unit-dropdown'
                });
            });
        }

        function initSelect2ModalTambahBarangBeli() {
            initSelect2UnitGudangBeliModal();
            initSelect2BarangBeliModal(0);
        }
        window.initSelect2ModalTambahBarangBeli = initSelect2ModalTambahBarangBeli;

        function resetSelect2ModalBeliAwal() {
            var $ = getJqPembelianModal();
            if (!$) {
                return;
            }
            destroySelect2Element($('#uuid_barang_beli'));
            destroySelect2Element($('#uuid_konsumen_beli'));
            destroySelect2Element($('#uuid_gudang_beli'));
        }

        function formatLabelBarangComboboxModal(row) {
            if (row && row.label_barang) {
                return row.label_barang;
            }
            var nama = (row.nama_barang || '').toUpperCase();
            var satuan = jQuery.trim(row.satuan || '');
            var harga = formatHargaSatuanPembelian(row.harga_satuan || '', true);
            return nama + ' ( satuan : ' + satuan + ', harga satuan : ' + harga + ' )';
        }

        function refreshBarangOptions(selectedUuid) {
            var jq = getJqPembelianModal();
            if (!jq) {
                return jq;
            }
            return jq.ajax({
                url: "<?php echo site_url('persediaan/list_barang_combobox_modal_ajax'); ?>",
                type: "GET",
                dataType: "json",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).done(function(res) {
                if (!res || !res.success) {
                    return;
                }

                var $modal = jQuery('#modal-xl-input-barang');
                var select = $modal.find('select[name="uuid_barang"]');
                var currentValue = selectedUuid || select.val();
                select.empty().append(jQuery('<option>', {
                    value: '',
                    text: 'Pilih Barang'
                }));

                jQuery.each(res.data || [], function(_, row) {
                    var optUuid = (row.uuid_persediaan && String(row.uuid_persediaan).trim() !== '')
                        ? row.uuid_persediaan : row.uuid_barang;
                    var label = formatLabelBarangComboboxModal(row);
                    select.append(jQuery('<option>', {
                        value: optUuid,
                        text: label
                    }).attr({
                        'data-satuan': row.satuan || '',
                        'data-harga-satuan': row.harga_satuan || ''
                    }));
                });

                if (currentValue) {
                    select.val(currentValue);
                }
                initSelect2ModalTambahBarangBeli();
                if (currentValue) {
                    loadDetailBarangPembelian(currentValue);
                }
            });
        }
        window.refreshBarangOptions = refreshBarangOptions;

        /**
         * Format harga satuan gaya Indonesia: titik = ribuan, koma = desimal.
         * Contoh tampilan: 6.826,75 (= 6826.75)
         * @param {*} value
         * @param {boolean} fromDatabase - true jika nilai dari DB/API (desimal titik Inggris)
         */
        function formatHargaSatuanPembelian(value, fromDatabase) {
            if (value === null || typeof value === 'undefined') {
                return '';
            }

            var raw = String(value).trim();
            if (raw === '') {
                return '';
            }

            // Dari DB/API: 6826.75 → 6.826,75
            if (fromDatabase === true && /^\d+(\.\d+)?$/.test(raw)) {
                var partsDb = raw.split('.');
                var intDb = partsDb[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                if (partsDb.length > 1 && partsDb[1] !== '') {
                    var decDb = partsDb[1].substring(0, 4).replace(/0+$/, '');
                    return decDb !== '' ? (intDb + ',' + decDb) : intDb;
                }
                return intDb;
            }

            // Input pengguna: titik selalu pemisah ribuan, koma selalu desimal
            var trailingComma = /,$/.test(raw.replace(/\s/g, ''));
            var cleaned = raw.replace(/[^\d.,]/g, '');
            var intPart = '';
            var decPart = '';

            if (cleaned.indexOf(',') !== -1) {
                var commaIdx = cleaned.indexOf(',');
                intPart = cleaned.substring(0, commaIdx).replace(/\./g, '').replace(/[^\d]/g, '');
                decPart = cleaned.substring(commaIdx + 1).replace(/[^\d]/g, '').substring(0, 4);
            } else {
                // Semua titik = ribuan (jangan anggap desimal Inggris)
                intPart = cleaned.replace(/\./g, '').replace(/[^\d]/g, '');
            }

            intPart = intPart.replace(/^0+(?=\d)/, '');
            if (intPart === '') {
                intPart = '0';
            }

            var intFmt = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            if (trailingComma && decPart === '') {
                return intFmt + ',';
            }
            if (decPart !== '') {
                return intFmt + ',' + decPart;
            }
            return intFmt;
        }
        window.formatHargaSatuanPembelian = formatHargaSatuanPembelian;

        function applyFormatHargaSatuanPembelian(input) {
            var inputElement = $(input);
            var el = inputElement.get(0);
            var oldVal = inputElement.val();
            var oldLen = oldVal.length;
            var selStart = el && typeof el.selectionStart === 'number' ? el.selectionStart : oldLen;
            var newVal = formatHargaSatuanPembelian(oldVal, false);
            inputElement.val(newVal);
            if (el && typeof el.setSelectionRange === 'function') {
                var newPos = Math.max(0, newVal.length - (oldLen - selStart));
                try {
                    el.setSelectionRange(newPos, newPos);
                } catch (e) {}
            }
        }

        function isiSatuanHargaDariOpsiBarang($modal, uuidBarang) {
            var $select = $modal.find('select[name="uuid_barang"]');
            var $opt = $select.find('option:selected');

            if (uuidBarang) {
                var $byVal = $select.find('option').filter(function() {
                    return jQuery(this).val() === uuidBarang;
                });
                if ($byVal.length) {
                    $opt = $byVal.first();
                }
            }

            var satuan = jQuery.trim($opt.attr('data-satuan') || '');
            var harga = jQuery.trim($opt.attr('data-harga-satuan') || '');

            $modal.find('input[name="satuan"]').val(satuan);
            $modal.find('input[name="harga_satuan"]').val(harga ? formatHargaSatuanPembelian(harga, true) : '');
        }

        function loadDetailBarangPembelian(uuidBarang) {
            var $modal = jQuery('#modal-xl-input-barang');
            uuidBarang = jQuery.trim(uuidBarang || '');

            if (!uuidBarang) {
                $modal.find('input[name="satuan"]').val('');
                $modal.find('input[name="harga_satuan"]').val('');
                return;
            }

            isiSatuanHargaDariOpsiBarang($modal, uuidBarang);
        }

        window.loadDetailBarangPembelian = loadDetailBarangPembelian;

        function destroySelect2IfAny($select) {
            if ($select.data('select2')) {
                $select.select2('destroy');
            }
            $select.next('.select2-container').remove();
            $select.removeClass('select2-hidden-accessible').removeAttr('data-select2-id').removeAttr('aria-hidden').removeAttr('tabindex');
        }

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
                allowClear: true
            });
        }
        window.initSelect2KategoriModal = initSelect2KategoriModal;

        function initSelect2InModal() {
            initSelect2KategoriModal();
        }

        var cekNamaBarangAjaxPending = false;
        var pilihGunakanBarangInProgress = false;
        window.barangDuplikatPersediaanCache = [];

        function renderTabelBarangDuplikatPersediaanParent(rows, bulanLabel) {
            window.barangDuplikatPersediaanCache = rows || [];
            var tbody = document.querySelector('#tabel_barang_duplikat_persediaan tbody');
            if (!tbody) {
                return;
            }
            tbody.innerHTML = '';
            window.barangDuplikatPersediaanCache.forEach(function(row, idx) {
                var hppTampil = formatHargaSatuanPembelian(row.harga_satuan || '', true);
                var tr = document.createElement('tr');
                var tdNo = document.createElement('td');
                tdNo.textContent = String(idx + 1);
                var tdBulan = document.createElement('td');
                tdBulan.textContent = row.bulan_label || '';
                var tdNama = document.createElement('td');
                tdNama.textContent = row.nama_barang || '';
                var tdSatuan = document.createElement('td');
                tdSatuan.textContent = row.satuan || '';
                var tdHpp = document.createElement('td');
                tdHpp.className = 'text-right';
                tdHpp.textContent = hppTampil;
                var tdAksi = document.createElement('td');
                var btnPilih = document.createElement('button');
                btnPilih.type = 'button';
                btnPilih.className = 'btn btn-primary btn-sm btn-pilih-gunakan-barang-persediaan';
                btnPilih.setAttribute('data-idx', String(idx));
                btnPilih.setAttribute('title', 'Salin nama, satuan, dan HPP ke form');
                btnPilih.textContent = 'Pilih dan gunakan';
                tdAksi.appendChild(btnPilih);
                tr.appendChild(tdNo);
                tr.appendChild(tdBulan);
                tr.appendChild(tdNama);
                tr.appendChild(tdSatuan);
                tr.appendChild(tdHpp);
                tr.appendChild(tdAksi);
                tbody.appendChild(tr);
            });
            var info = document.getElementById('modal_barang_duplikat_info');
            if (info) {
                info.innerHTML = 'Nama barang belum ada di bulan <strong>' + (bulanLabel || '-') + '</strong>, tetapi sudah tercatat di bulan lain. Klik <strong>Pilih dan gunakan</strong> untuk menyalin Nama, Satuan, dan HPP ke form.';
            }
        }

        function aturZIndexModalDuplikatDiDepan() {
            var $m = jQuery('#modalBarangDuplikatPersediaan');
            if (!$m.length || !$m.hasClass('show')) {
                return;
            }
            var maxZ = 1050;
            jQuery('.modal.show').each(function() {
                var z = parseInt(jQuery(this).css('z-index'), 10);
                if (!isNaN(z) && z >= maxZ) {
                    maxZ = z;
                }
            });
            var zModal = Math.max(maxZ + 50, 10080);
            $m.css('z-index', zModal);
            jQuery('body').addClass('modal-pembelian-duplikat-open');
            jQuery('.modal-backdrop').each(function(i, el) {
                var $bd = jQuery(el);
                if (i === jQuery('.modal-backdrop').length - 1) {
                    $bd.css('z-index', zModal - 15);
                }
            });
        }

        function bukaModalDuplikatBarang() {
            var el = document.getElementById('modalBarangDuplikatPersediaan');
            if (!el || !window.jQuery || !jQuery.fn.modal) {
                return;
            }
            var $m = jQuery(el);
            $m.appendTo('body');
            $m.off('shown.bs.modal.pembelianDuplikat').on('shown.bs.modal.pembelianDuplikat', function() {
                aturZIndexModalDuplikatDiDepan();
            });
            $m.modal({
                backdrop: true,
                keyboard: true,
                show: true
            });
            window.setTimeout(aturZIndexModalDuplikatDiDepan, 80);
            window.setTimeout(aturZIndexModalDuplikatDiDepan, 250);
        }

        function tutupModalDuplikatBarang(callback) {
            var el = document.getElementById('modalBarangDuplikatPersediaan');
            if (!el || !window.jQuery || !jQuery.fn.modal) {
                if (callback) {
                    callback();
                }
                return;
            }
            var $m = jQuery(el);
            if (!$m.hasClass('show')) {
                if (callback) {
                    callback();
                }
                return;
            }
            if (callback) {
                var selesai = false;
                var selesaiFn = function() {
                    if (selesai) {
                        return;
                    }
                    selesai = true;
                    callback();
                };
                $m.one('hidden.bs.modal', selesaiFn);
                $m.modal('hide');
                window.setTimeout(selesaiFn, 450);
                return;
            }
            $m.modal('hide');
            jQuery('body').removeClass('modal-pembelian-duplikat-open');
        }

        function normalizeNamaBarangInput(val) {
            return String(val || '')
                .replace(/[\r\n\t]+/g, ' ')
                .replace(/\s+/g, ' ')
                .trim();
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
                url: "<?php echo site_url('persediaan/cek_nama_barang_persediaan_ajax'); ?>",
                type: 'GET',
                dataType: 'json',
                timeout: 20000,
                data: {
                    nama_barang: nama,
                    tanggal_po: getTanggalPoUntukFilter()
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).done(function(res) {
                if (!res || !res.success) {
                    return;
                }

                if (res.exists_in_month) {
                    if (res.data_in_month) {
                        window.skipCekNamaBarangModalPilih = true;
                        applyPilihGunakanBarangKeFormInput({
                            id: res.data_in_month.id || '',
                            uuid_barang: res.data_in_month.uuid_barang || '',
                            kode_barang: res.data_in_month.kode_barang || '',
                            nama_barang: res.data_in_month.nama_barang || nama,
                            satuan: res.data_in_month.satuan || '',
                            harga_satuan: res.data_in_month.harga_satuan || '',
                            bulan_label: res.bulan_label || ''
                        });
                        window.setTimeout(function() {
                            window.skipCekNamaBarangModalPilih = false;
                        }, 2000);
                        showInputBarangInfo(
                            (res.message || ('Nama barang sudah ada di persediaan bulan ' + (res.bulan_label || 'terpilih') + '.'))
                            + ' <strong>Satuan</strong> dan <strong>Harga Satuan (HPP)</strong> diisi otomatis dari record terakhir di bulan yang sama.'
                            + ' Silakan sesuaikan satuan dan HPP untuk input barang baru ini dengan nilai yang sesuai — boleh berbeda dengan referensi yang sudah dimasukkan.',
                            'warning'
                        );
                    } else {
                        showInputBarangInfo(res.message || 'Nama barang sudah ada di bulan terpilih.', 'warning');
                    }
                    return;
                }

                if (res.show_referensi_modal && res.data && res.data.length > 0) {
                    renderTabelBarangDuplikatPersediaanParent(res.data, res.bulan_label);
                    window.setTimeout(function() {
                        bukaModalDuplikatBarang();
                    }, 50);
                    showInputBarangInfo(res.message || 'Pilih referensi barang di daftar.', 'warning');
                    return;
                }

                showInputBarangInfo(res.message || ('Nama barang dapat digunakan untuk bulan ' + (res.bulan_label || '') + '.'), 'success');
            }).fail(function() {
                showInputBarangInfo('Gagal mengecek nama barang. Silakan coba lagi.', 'danger');
            }).always(function() {
                cekNamaBarangAjaxPending = false;
            });
        }
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

        function applyPilihGunakanBarangKeFormInput(data) {
            if (!data) {
                return;
            }
            var elNama = document.getElementById('modal_nama_barang');
            var elSatuan = document.getElementById('modal_satuan_barang');
            var elHpp = document.getElementById('modal_harga_satuan_barang');
            var elKode = document.getElementById('modal_kode_barang');
            var elUuid = document.getElementById('modal_uuid_barang_referensi');
            var elId = document.getElementById('modal_persediaan_id_referensi');

            if (elNama) {
                elNama.value = data.nama_barang || '';
            }
            if (elSatuan) {
                elSatuan.value = data.satuan || '';
            }
            if (elHpp) {
                elHpp.value = formatHargaSatuanPembelian(data.harga_satuan || '', true);
            }
            if (elKode && data.kode_barang) {
                elKode.value = data.kode_barang;
            }
            if (elUuid) {
                elUuid.value = data.uuid_barang || '';
            }
            if (elId) {
                elId.value = data.id ? String(data.id) : '';
            }
        }

        function tampilkanSwalPilihGunakanBerhasil(bulanLabel) {
            if (typeof Swal === 'undefined') {
                return;
            }
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Pilih dan gunakan berhasil',
                text: 'Nama barang, Satuan, dan Harga Satuan (HPP) sudah disalin ke form.'
                    + (bulanLabel ? ' Referensi dari bulan ' + bulanLabel + '.' : ''),
                showConfirmButton: false,
                timer: 1500
            });
        }

        function lakukanPilihGunakanBarang(data) {
            applyPilihGunakanBarangKeFormInput(data);
            var elSatuan = document.getElementById('modal_satuan_barang');
            var elHpp = document.getElementById('modal_harga_satuan_barang');
            if (elSatuan) {
                elSatuan.focus();
            } else if (elHpp) {
                elHpp.focus();
            }
            showInputBarangInfo(
                'Data referensi disalin: <strong>' + (data.nama_barang || '') + '</strong>'
                + (data.bulan_label ? ' (bulan ' + data.bulan_label + ')' : '')
                + '. Satuan dan Harga Satuan (HPP) sudah diisi. Lengkapi jika perlu lalu klik Simpan.',
                'success'
            );
            window.setTimeout(function() {
                tampilkanSwalPilihGunakanBerhasil(data.bulan_label);
            }, 80);
            pilihGunakanBarangInProgress = false;
            window.setTimeout(function() {
                window.skipCekNamaBarangModalPilih = false;
            }, 2000);
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
                id: data.id || '',
                uuid_barang: data.uuid_barang || '',
                kode_barang: data.kode_barang || '',
                nama_barang: data.nama_barang || '',
                satuan: data.satuan || '',
                harga_satuan: data.harga_satuan || '',
                bulan_label: data.bulan_label || ''
            };

            var modalDuplikat = document.getElementById('modalBarangDuplikatPersediaan');
            if (modalDuplikat && modalDuplikat.classList.contains('show')) {
                tutupModalDuplikatBarang(function() {
                    lakukanPilihGunakanBarang(payload);
                });
            } else {
                lakukanPilihGunakanBarang(payload);
            }

            return false;
        }
        window.pilihDanGunakanBarangDariPersediaan = pilihDanGunakanBarangDariPersediaan;

        jQuery(document).on('mousedown', '.btn-pilih-gunakan-barang-persediaan', function() {
            window.skipCekNamaBarangModalPilih = true;
        });

        jQuery(document).on('click', '.btn-pilih-gunakan-barang-persediaan', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            pilihDanGunakanBarangDariPersediaan(this);
            return false;
        });

        window.applyFormatHargaSatuanPembelianModal = applyFormatHargaSatuanPembelian;

        function loadInputBarangForm(callback) {
            if (modalFormLoaded) {
                jQuery('#modalBarangTambahKategori, #modalBarangDaftarKategori').appendTo('body');
                aktifkanSemuaInputModalBarangBaru();
                if (callback) {
                    callback();
                }
                return;
            }

            $('#modal-input-barang-baru-body').html('<div class="text-center text-muted py-4">Memuat form...</div>');
            $.ajax({
                url: "<?php echo site_url('persediaan/pembelian_modal_form'); ?>",
                type: "GET",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).done(function(html) {
                $('#modal-input-barang-baru-body').html(html);
                modalFormLoaded = true;
                jQuery('#modalBarangTambahKategori, #modalBarangDaftarKategori').appendTo('body');
                initSelect2InModal();
                aktifkanSemuaInputModalBarangBaru();
                if (window.initCekNamaBarangModalInput) {
                    window.initCekNamaBarangModalInput();
                }
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
                jQuery('#modal-xl-input-barang').one('hidden.bs.modal.pembelianInputBaru', function() {
                    jQuery('body').removeClass('modal-open');
                    jQuery('.modal-backdrop').remove();
                    loadInputBarangForm(function() {
                        tampilkanModalInputBarangBaru();
                    });
                }).modal('hide');
                return;
            }

            loadInputBarangForm(function() {
                tampilkanModalInputBarangBaru();
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

        jQuery('#modal-input-barang-baru').on('shown.bs.modal', function(e) {
            if (e.target.id !== 'modal-input-barang-baru') {
                return;
            }
            jQuery(document).off('focusin.modal');
            aktifkanSemuaInputModalBarangBaru();
            window.setTimeout(function() {
                initSelect2KategoriModal();
            }, 0);
        });

        $('#modal-input-barang-baru').on('hidden.bs.modal', function(e) {
            if (e.target.id !== 'modal-input-barang-baru') {
                return;
            }
            if (shouldReopenBarangBeliModal) {
                jQuery(document).off('focusin.modal');
                if ($.fn.modal) {
                    jQuery('#modal-xl-input-barang').modal('show');
                } else {
                    showModalManual('#modal-xl-input-barang');
                }
                window.setTimeout(function() {
                    jQuery('#modal-xl-input-barang').on('shown.bs.modal', function() {
                        jQuery(document).off('focusin.modal');
                        initSelect2ModalTambahBarangBeli();
                    });
                }, 0);
            }
        });

        $(document).on('submit', '#form-input-barang-baru-modal', function(e) {
            e.preventDefault();

            var form = $(this);
            var submitButton = $('#btn-submit-input-barang-baru');
            var submitButtonText = submitButton.data('original-text') || submitButton.text();
            var tglPo = getTanggalPoUntukFilter();
            var namaBarang = normalizeNamaBarangInput($('#modal_nama_barang').val());

            if (!tglPo || String(tglPo).trim() === '') {
                showInputBarangInfo('Silakan pilih <strong>Tgl PO</strong> di form pembelian terlebih dahulu.', 'warning');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tgl PO belum dipilih',
                        text: 'Pilih tanggal PO (datepicker) agar bulan persediaan ditentukan sebelum menyimpan barang baru.'
                    });
                }
                $('input[name="tgl_po"]').focus();
                return false;
            }

            if (!namaBarang) {
                showInputBarangInfo('Nama barang wajib diisi.', 'warning');
                $('#modal_nama_barang').focus();
                return false;
            }

            $('#modal_nama_barang').val(namaBarang);
            // Input Barang Baru = record persediaan baru; referensi hanya untuk mengisi form, bukan uuid yang dipakai ulang
            $('#modal_uuid_barang_referensi').val('');
            $('#modal_persediaan_id_referensi').val('');

            submitButton.data('original-text', submitButtonText);
            submitButton.prop('disabled', true).text('Menyimpan...');
            showInputBarangInfo('Menyimpan barang baru...', 'warning');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize() + '&tanggal_po=' + encodeURIComponent(tglPo),
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

                showInputBarangInfo((res && res.message) ? res.message : 'Barang baru gagal disimpan.', 'danger');
            }).fail(function() {
                showInputBarangInfo('Terjadi kesalahan saat menyimpan barang baru.', 'danger');
            }).always(function() {
                submitButton.prop('disabled', false).text(submitButtonText);
            });

            return false;
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

        jQuery('#tgl_po').on('change.datetimepicker hide.datetimepicker', function() {
            simpanTglPoCreateSession();
        });
        jQuery('input[name="tgl_po"]').on('change blur', function() {
            simpanTglPoCreateSession();
        });

        jQuery(document).on('input keyup paste', '#modal-xl-input-barang input[name="harga_satuan"]', function() {
            var input = this;
            setTimeout(function() {
                applyFormatHargaSatuanPembelian(input);
            }, 0);
        });
    })(jQuery);
</script>
<script>
    /* Select2 modal: init setelah jQuery + Select2 dari layout AdminLTE dimuat */
    (function bootSelect2ModalPembelianBeli() {
        var jq = window.jQuery;
        if (!jq || !jq.fn || !jq.fn.select2) {
            window.setTimeout(bootSelect2ModalPembelianBeli, 50);
            return;
        }

        jq(document).off('shown.bs.modal.pembelianSelect2', '#modal-xl-input-barang');
        jq(document).on('shown.bs.modal.pembelianSelect2', '#modal-xl-input-barang', function() {
            jq(document).off('focusin.modal');
            jq('body').css({
                paddingRight: '',
                overflow: 'hidden'
            });
            jq('#modal-xl-input-barang').css('padding-right', '');

            if (typeof window.initSelect2ModalTambahBarangBeli === 'function') {
                window.initSelect2ModalTambahBarangBeli();
            }

            if (typeof window.refreshBarangOptions === 'function') {
                window.refreshBarangOptions().always(function() {
                    if (typeof window.initSelect2ModalTambahBarangBeli === 'function') {
                        window.initSelect2ModalTambahBarangBeli();
                    }
                });
            }
        });

        /* Hancurkan init default layout (tanpa dropdownParent) pada select modal */
        jq('#modal-xl-input-barang select.select2').each(function() {
            var $el = jq(this);
            if ($el.data('select2')) {
                try {
                    $el.select2('destroy');
                } catch (e) {}
            }
            $el.next('.select2-container').remove();
        });
    })();
</script>