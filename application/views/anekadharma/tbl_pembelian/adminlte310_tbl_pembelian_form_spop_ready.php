<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                                            Ubah Data Pembelian per SPOP
                                        </strong></div>

                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>



                    </div>
                    <!-- <br /> -->


                    <?php

                    // if (date("Y", strtotime($this->input->post('tgl_po', TRUE))) < 2020) {
                    //     // print_r("Tahun kurang dari 2020");
                    //     $date_po = date("Y-m-d H:i:s");
                    // } else {
                    //     // print_r("Tahun lebih dari 2020");
                    //     $date_po = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_po', TRUE)));
                    // }
                    // print_r($tgl_po);
                    // print_r("<br/>");
                    // $date_po = $tgl_po;
                    $date_po_X = date("d m Y", strtotime($tgl_po));
                    // print_r($date_po);
                    // print_r("<br/>");
                    // print_r($date_po_X);
                    // die;
                    ?>


                    <div class="card-body">


                        <form action="<?php echo $action_ubah_detail_spop; ?>" id="form_update_spop" method="post">
                            <div class="form-group">
                                <label for="datetime">Tgl Po <?php echo form_error('tgl_po') ?></label>
                                <div class="col-3">
                                    <!-- <div class="input-group date" id="tgl_po" name="tgl_po" data-target-input="nearest">
                                        <input type="text" class="form-control" rows="3" name="tgl_po" id="tgl_po" placeholder="tgl_po" value="<?php //echo $date_po_X; 
                                                                                                                                                ?>" >
                                    </div> -->

                                    <div class="input-group date" id="tgl_po" name="tgl_po" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_po" id="tgl_po" name="tgl_po" value="<?php echo $date_po_X; ?>" required />
                                        <div class="input-group-append" data-target="#tgl_po" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="supplier_nama">Nama Supplier <?php echo form_error('supplier_nama') ?></label>

                                        <!-- <input type="text" class="form-control" rows="3" name="Supplier" id="Supplier" placeholder="Supplier" value="<?php //echo $supplier_nama; 
                                                                                                                                                            ?>" disabled> -->


                                        <select name="uuid_supplier" id="uuid_supplier" class="form-control select2" style="width: 100%; height: 40px;" required>
                                            <option value="<?php echo $uuid_supplier; ?>"><?php echo $supplier_nama; ?></option>
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

                                        <select name="statuslu" id="statuslu" class="form-control select2" style="width: 100%; height: 40px;">
                                            <option value="<?php echo $statuslu; ?>">
                                                <?php
                                                if ($statuslu == "U") {
                                                    echo "Hutang";
                                                } else {
                                                    echo "Lunas";
                                                }
                                                ?>
                                            </option>
                                            <option value="L">Lunas</option>
                                            <option value="U">Hutang</option>
                                        </select>


                                    </div>
                                    <div class="col-2">
                                        <label for="kas_bank">Kas / Bank <?php echo form_error('kas_bank') ?></label>

                                        <!-- <input type="text" class="form-control" rows="3" name="kas_bank" id="kas_bank" placeholder="kas_bank" value="<?php echo $kas_bank; ?>" required> -->

                                        <select name="kas_bank" id="kas_bank" class="form-control select2" style="width: 100%; height: 40px;">
                                            <option value="<?php echo $kas_bank; ?>"><?php echo $kas_bank; ?></option>
                                            <option value="kas">Kas</option>
                                            <option value="bank">Bank</option>
                                        </select>
                                    </div>


                                </div>

                            </div>


                            <div class="form-group">

                                <div class="row">
                                    <div class="col-4">
                                        <label for="nmrsj">Nomor SPOP <?php echo form_error('spop') ?></label>

                                        <input type="text" class="form-control" rows="3" name="spop" id="spop" placeholder="spop" value="<?php echo $spop; ?>" required>

                                        <p id="info_spop"></p>
                                    </div>

                                    <div class="col-4">
                                        <label for="nmrfakturkwitansi">Nomor faktur / kwitansi <?php echo form_error('nmrfakturkwitansi') ?></label>
                                        <input type="text" class="form-control" rows="3" name="nmrfakturkwitansi" id="nmrfakturkwitansi" placeholder="nmrfakturkwitansi" value="<?php echo $nmrfakturkwitansi; ?>">

                                    </div>

                                    <div class="col-4">
                                        <!-- <label for="int">Nomor bpb <?php //echo form_error('nmrbpb') 
                                                                        ?></label>
                                        <input type="text" class="form-control" name="nmrbpb" id="nmrbpb" placeholder="Nmrbpb" value="<?php //echo $nmrbpb; 
                                                                                                                                        ?>" /> -->
                                    </div>

                                </div>

                            </div>


                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <input type="hidden" name="uuid_spop_proses" id="uuid_spop_proses" value="<?php echo $uuid_spop; ?>" />
                            <input type="hidden" name="spop_proses" id="spop_proses" value="<?php echo $spop; ?>" />

                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-4">
                                    <button type="submit" onclick="confirmUbahSPOP(event)" class="btn btn-primary"><?php echo $button; ?></button>
                                </div>
                                <div class="col-4"></div>
                            </div>


                            <!-- <button type="submit" onclick="myFunction()" class="btn btn-primary"><?php //echo $button; 
                                                                                                        ?></button> -->
                            <!-- <button type="submit" onclick="confirmCancel()" class="btn btn-primary"><?php //echo $button; 
                                                                                                            ?></button> -->

                            <!-- <a href="edit.php?id=1" onclick="return  confirm('do you want to delete Y/N')">Edit </a> -->

                            <!-- <button onclick="myFunction()">Try it</button> -->
                            <!-- <button onclick="CekKondisi()">Simpan Perubahan Detail SPOP</button> -->





                            <!-- Disabled button tombol setelah di klik -->
                            <!-- <script>
                                function disableButton() {
                                    var btn = document.getElementById('btn');
                                    btn.disabled = true;
                                    btn.innerText = 'Posting...'
                                }
                            </script> -->
                            <!-- End if Disabled button tombol setelah di klik -->






                            <script>
                                // function CekKondisi() {
                                //     Swal.fire({
                                //         title: "Do you want to save the changes?",
                                //         showDenyButton: true,
                                //         showCancelButton: true,
                                //         confirmButtonText: "Save",
                                //         denyButtonText: `Don't save`
                                //     }).then((result) => {
                                //         /* Read more about isConfirmed, isDenied below */
                                //         if (result.isConfirmed) {
                                //             Swal.fire("Saved!", "", "success");
                                //         } else if (result.isDenied) {
                                //             Swal.fire("Changes are not saved", "", "info");
                                //         }
                                //     });
                                // }

                                function myFunction() {




                                    // cek uuid_spop dan input text spop = apakah beda ?

                                    let input_spop = document.getElementById("spop").value;
                                    let input_spop_proses = document.getElementById("spop_proses").value;
                                    // let value = inputField.value;
                                    // alert("Input SPOP: " + input_spop);

                                    // var numbers = <?php //echo $get_awal 
                                                        ?>;

                                    if (input_spop != input_spop_proses) {
                                        let text = "SPOP terjadi perbedaan: \n SPOP awal:" + input_spop_proses + "\n SPOP baru: " + input_spop + "\n Apakah Tetap diproses ubah SPOP? ";


                                        if (confirm(text) === true) {
                                            text = "SPOP BERBEDA!<br/> dan di Proses? " + input_spop;
                                            document.getElementById("form_update_spop").submit();
                                        } else {
                                            // text = "SPOP tidak boleh beda";
                                            // alert("CANCEL");
                                            // window.fail;
                                            // document.getElementById("form_update_spop").submit();
                                            // history.back(-1);
                                            // break;
                                            document.getElementById("info_spop").innerHTML = text;
                                        }
                                        // document.getElementById("info_spop").innerHTML = text;
                                    }


                                }


                                function confirmUbahSPOP(e) {

                                    let input_spop = document.getElementById("spop").value;
                                    let input_spop_proses = document.getElementById("spop_proses").value;

                                    if (input_spop != input_spop_proses) {
                                        let text = "SPOP terjadi perbedaan: \n SPOP awal:" + input_spop_proses + "\n SPOP baru: " + input_spop + "\n Apakah Tetap diproses ubah SPOP? ";

                                        if (confirm(text))
                                            // alert('Proses Ubah SPOP !');
                                            // e.preventDefault();
                                            document.getElementById("form_update_spop").submit();
                                        else {
                                            // alert('Cancelled! \n harap SPOP dikembalikan ke: ' + input_spop_proses);
                                            e.preventDefault();
                                        }

                                    }


                                }

                                // function confirmCancel() {
                                //     var msj = 'Are you sure that you want to delete this comment?';
                                //     if (!confirm(msj)) {
                                //         return false;
                                //     } else {
                                //         window.location = $base_url() + 'index.php/Tbl_pembelian/create_action_detail_uuid_spop_update/' + input_spop;
                                //     }
                                // }
                            </script>















                        </form>






                        <br />


                        <div class="card card-success">
                            <div class="card-header">

                                <div class="row">
                                    <div class="col-2" text-align="center"> <strong>Detail Barang</strong></div>
                                    <div class="col-3" text-align="left">
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-xl-input-barang">
                                            Tambah Barang
                                        </button>
                                    </div>


                                </div>

                            </div>


                            <div class="card-body">

                                <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align:left" width="30px">No</th>
                                            <th style="text-align:left">Action</th>

                                            <th style="text-align:left">Gudang</th>
                                            <th style="text-align:left">Uraian</th>
                                            <th style="text-align:center">Jumlah</th>
                                            <th style="text-align:center">Satuan</th>
                                            <!-- <th>Konsumen</th> -->
                                            <th style="text-align:right">Harga Satuan</th>
                                            <th style="text-align:right">Harga Total</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $compare_spop = 0;
                                        $Total_per_SPOP = 0;
                                        $TOTAL_LUNAS = 0;
                                        $TOTAL_HUTANG = 0;
                                        // $TOTAL_HARGA=0;
                                        $start = 0;
                                        $jumlah_barang = 0;
                                        foreach ($data_ALL_per_SPOP as $list_data) {

                                        ?>


                                            <tr>

                                                <td style="text-align:center"><?php echo ++$start ?></td>

                                                <td align="left">

                                                    <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modal-xl-input-barang_<?php echo $list_data->id ?>">
                                                        UBAH <?php //echo $list_data->id 
                                                                ?>
                                                    </button>

                                                    <?php
                                                    // echo anchor(site_url('tbl_pembelian/create_add_uraian_update/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                    echo anchor(site_url('tbl_pembelian/delete_by_uuid_pembelian_from_per_spop_update/' . $list_data->uuid_pembelian . '/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');



                                                    ?>



                                                </td>
                                                <td align="left"><?php echo $list_data->nama_gudang; ?></td>
                                                <td align="left"><?php echo $list_data->uraian; ?></td>
                                                <td align="center">
                                                    <?php
                                                    // echo nominal($list_data->jumlah);                                                         
                                                    echo number_format($list_data->jumlah, 0, ',', '.');
                                                    $jumlah_barang = $jumlah_barang + $list_data->jumlah;

                                                    ?>
                                                </td>
                                                <td align="center"><?php echo $list_data->satuan; ?></td>

                                                <td align="right">
                                                    <?php
                                                    // echo nominal($list_data->harga_satuan); 
                                                    echo number_format($list_data->harga_satuan, 2, ',', '.');
                                                    ?>
                                                </td>
                                                <td align="right">
                                                    <?php
                                                    $total_per_uraian = $list_data->jumlah * $list_data->harga_satuan;

                                                    // echo nominal($total_per_uraian);

                                                    echo number_format($total_per_uraian, 2, ',', '.');

                                                    $Total_per_SPOP = $Total_per_SPOP + $total_per_uraian;


                                                    ?>
                                                </td>

                                            </tr>





                                        <?php
                                        }
                                        ?>

                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th style="text-align:left" width="30px"></th>
                                            <th style="text-align:left"></th>
                                            <th style="text-align:left"></th>
                                            <th style="text-align:right">TOTAL</th>
                                            <th style="text-align:center"><?php
                                                                            // echo $jumlah_barang; 
                                                                            echo number_format($jumlah_barang, 0, ',', '.');
                                                                            ?></th>
                                            <th style="text-align:center">

                                            </th>
                                            <th style="text-align:right"></th>
                                            <th style="text-align:right">
                                                <?php
                                                // echo $Total_per_SPOP; 
                                                echo number_format($Total_per_SPOP, 2, ',', '.');
                                                ?>
                                            </th>
                                        </tr>
                                    </tfoot>


                                </table>
                            </div>


                            <!-- <div class="card-body">

                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-xl-input-barang">
                                    Tambah Barang
                                </button>

                            </div> -->

                        </div>



                        <br />





                        <!-- TAMBAH BARANG MODAL EXTRA LARGE -->
                        <form action="<?php echo $action_tambah_barang_per_spop . $uuid_spop; ?>" method="post">
                            <div class="modal fade" id="modal-xl-input-barang">
                                <div class="modal-dialog modal-xl">
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
                                                        <label for="uuid_barang">Barang <?php echo form_error('uuid_barang') ?></label>

                                                        <select name="uuid_barang" id="uuid_barang" class="form-control select2" style="width: 100%; height: 80px;" required>
                                                            <option value="">Pilih Barang</option>
                                                            <?php

                                                            // $sql = "SELECT `uuid_barang`,`kode_barang`,`nama_barang` FROM `sys_nama_barang` ORDER by `nama_barang` ASC";
                                                            $sql = "SELECT `uuid_barang`,`kode_barang`,`namabarang` FROM `persediaan` WHERE `namabarang`<>'' GROUP by `namabarang`,`satuan`";
                                                            foreach ($this->db->query($sql)->result() as $m) {
                                                                echo "<option value='$m->uuid_barang' ";
                                                                echo ">  " . strtoupper($m->namabarang)  . "</option>";
                                                            }
                                                            ?>
                                                        </select>

                                                        <div class="row">
                                                            <div class="col-8">
                                                                <?php echo anchor(site_url('sys_nama_barang/create/pembelian'), 'Input Barang Baru', 'class="btn btn-block btn-danger"'); ?>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-4">
                                                        <label for="satuan">Satuan <?php echo form_error('satuan') ?></label>
                                                        <input type="text" name="satuan" id="satuan" placeholder="satuan" class="form-control" required>
                                                    </div>
                                                    <div class="col-4">
                                                        <label for="satuan">Harga Satuan <?php echo form_error('harga_satuan') ?></label>
                                                        <input type="text" name="harga_satuan" id="harga_satuan" placeholder="harga Satuan" class="form-control" required>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-4">
                                                        <label for="jumlah">Jumlah <?php //echo form_error('nmrpesan') 
                                                                                    ?></label>
                                                        <!-- <input type="text" class="form-control" rows="3" name="jumlah" id="jumlah" placeholder="Jumlah" required> -->
                                                        <input type="text" name="jumlah" id="jumlah" placeholder="Jumlah" class="form-control" required>
                                                    </div>
                                                    <div class="col-4">
                                                        <label for="uuid_gudang">Gudang <?php echo form_error('uuid_gudang') ?></label>
                                                        <select name="uuid_gudang" id="uuid_gudang" class="form-control select2" style="width: 100%; height: 80px;" required>
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
                                            <button type="submit" class="btn btn-primary">Simpan Tambah Barang Beli</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                        </form>
                        <!-- END OF MODAL EXTRA LARGE -->


                        <!-- <br /> -->





                        <div class="row">
                            <div class="col-4"></div>
                            <div class="col-4">
                                <a href="<?php echo site_url('tbl_pembelian') ?>" class="btn btn-success">Kembali ke data pembelian</a>
                            </div>
                            <div class="col-4"></div>
                        </div>


                    </div>

                    <!-- /.card-body -->
                </div>

            </div>

        </div>

    </section>
</div>


<?php

foreach ($data_ALL_per_SPOP as $list_data) {
?>
    <!-- MODAL EXTRA LARGE UPDATE PER ID -->
    <form action="<?php echo $action_ubah_per_id . $list_data->id; ?>" method="post">
        <div class="modal fade" id="modal-xl-input-barang_<?php echo $list_data->id ?>">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update Barang <?php echo $list_data->id ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <?php
                    // echo $action_ubah_per_id . $list_data->id;

                    $row = $this->Tbl_pembelian_model->get_by_id($list_data->id);

                    // print_r($row);

                    // if ($row) {
                    $data = array(
                        'id' => $row->id,
                        'tgl_po' => $row->tgl_po,
                        'nmrsj' => $row->nmrsj,
                        'nmrfakturkwitansi' => $row->nmrfakturkwitansi,
                        'nmrbpb' => $row->nmrbpb,
                        'spop' => $row->spop,
                        'supplier_kode' => $row->supplier_kode,
                        'supplier_nama' => $row->supplier_nama,
                        'uraian' => $row->uraian,
                        'jumlah' => $row->jumlah,
                        'satuan' => $row->satuan,
                        'konsumen' => $row->konsumen,
                        'harga_satuan' => $row->harga_satuan,
                        'harga_total' => $row->harga_total,
                        'statuslu' => $row->statuslu,
                        'kas_bank' => $row->kas_bank,
                        'tgl_bayar' => $row->tgl_bayar,
                        'id_usr' => $row->id_usr,
                    );


                    // `id`, `proses_input`, `date_input`, `uuid_pembelian`, `uuid_persediaan`, `id_persediaan_barang`, `uuid_barang`, `tgl_po`, `nmrsj`, `nmrfakturkwitansi`, `nmrbpb`, `uuid_spop`, `spop`, `status_spop`, `uuid_supplier`, `supplier_kode`, `supplier_nama`, `kode_barang`, `uraian`, `jumlah`, `satuan`, `uuid_konsumen`, `konsumen`, `uuid_gudang`, `nama_gudang`, `harga_satuan`, `harga_total`, `statuslu`, `kas_bank`, `tgl_bayar`, `id_usr`, `tgl_pengajuan_1`, `nominal_pengajuan_1`, `tgl_pengajuan_2`, `nominal_pengajuan_2`, `kode_akun`, `proses_transaksi`


                    ?>

                    <div class="modal-body">
                        <div class="form-group">


                            <div class="row">
                                <div class="col-4">
                                    <label for="konsumen_nama">Unit <?php echo form_error('konsumen_nama') ?></label>
                                    <select name="uuid_konsumen" id="uuid_konsumen" class="form-control select2" style="width: 100%; height: 40px;" required>
                                        <option value="<?php echo $row->uuid_konsumen; ?>"><?php echo $row->konsumen; ?> </option>
                                        <!-- <option value="">Pilih Konsumen/Unit </option> -->
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
                                    <label for="uuid_barang">Barang <?php echo form_error('uuid_barang') ?></label>

                                    <select name="uuid_barang" id="uuid_barang" class="form-control select2" style="width: 100%; height: 80px;" required>
                                        <option value="<?php echo $row->uuid_barang; ?>"><?php echo $row->uraian; ?> </option>
                                        <!-- <option value="">Pilih Barang</option> -->
                                        <?php

                                        // $sql = "SELECT `uuid_barang`,`kode_barang`,`nama_barang` FROM `sys_nama_barang` ORDER by `nama_barang` ASC";
                                        $sql = "SELECT `uuid_barang`,`kode_barang`,`namabarang` FROM `persediaan` WHERE `namabarang`<>'' GROUP by `namabarang`,`satuan`";
                                        foreach ($this->db->query($sql)->result() as $m) {
                                            echo "<option value='$m->uuid_barang' ";
                                            echo ">  " . strtoupper($m->namabarang)  . "</option>";
                                        }
                                        ?>
                                    </select>

                                    <div class="row">
                                        <div class="col-8">
                                            <?php echo anchor(site_url('sys_nama_barang/create/pembelian'), 'Input Barang Baru', 'class="btn btn-block btn-danger"'); ?>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-4">
                                    <label for="satuan">Satuan <?php echo form_error('satuan') ?></label>
                                    <input type="text" name="satuan" id="satuan" placeholder="satuan" class="form-control" value="<?php echo $row->satuan; ?>" required>
                                </div>
                                <div class="col-4">
                                    <label for="satuan">Harga Satuan <?php echo form_error('harga_satuan') ?></label>
                                    <?php
                                    $get_format_rupiah_harga_satuan = number_format($row->harga_satuan, 2, ',', '.');
                                    ?>
                                    <input type="text" name="harga_satuan" id="harga_satuan" placeholder="harga Satuan" class="form-control" value="<?php echo $get_format_rupiah_harga_satuan; ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <label for="jumlah">Jumlah <?php //echo form_error('nmrpesan') 
                                                                ?></label>
                                    <!-- <input type="text" class="form-control" rows="3" name="jumlah" id="jumlah" placeholder="Jumlah" required> -->
                                    <?php
                                    // $get_format_rupiah_jumlah = number_format($row->jumlah, 0, ',', '.');
                                    ?>

                                    <input type="text" name="jumlah" id="jumlah" placeholder="Jumlah" class="form-control" value="<?php echo $row->jumlah; ?>" required>
                                </div>
                                <div class="col-4">
                                    <label for="uuid_gudang">Gudang <?php echo form_error('uuid_gudang') ?></label>
                                    <select name="uuid_gudang" id="uuid_gudang" class="form-control select2" style="width: 100%; height: 80px;" required>
                                        <option value="<?php echo $row->uuid_gudang; ?>"><?php echo $row->nama_gudang; ?></option>
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
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>
    <!-- END OF MODAL EXTRA LARGE -->
<?php
}
?>


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