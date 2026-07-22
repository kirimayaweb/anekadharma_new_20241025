<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php $this->load->helper('pembelian_persediaan'); ?>

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

                                    <div class="input-group date" id="tgl_po_picker" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_po_picker" id="tgl_po" name="tgl_po" value="<?php echo $date_po_X; ?>" required />
                                        <div class="input-group-append" data-target="#tgl_po_picker" data-toggle="datetimepicker">
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
                                            <th style="text-align:left">Kategori</th>
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

                                            // CEK dipersediaan apakah sisa stock masih ada, jika masih ada  bisa di ubah / dihapus, jika sudah terjual tidak bisa di ubah / hapus
                                            // cek jumlah barang beli , jika lebih dari jumlah sisa stock , maka tombol ubah dan hapus bisa tampil

                                            $this->db->where('uuid_persediaan', $list_data->uuid_persediaan);
                                            //$this->db->where('password',  $test);
                                            $Get_data_barang_dipersediaan = $this->db->get('persediaan')->row();


                                            $GET_Sisa_Stock = $Get_data_barang_dipersediaan->total_10 - ($Get_data_barang_dipersediaan->penjualan + $Get_data_barang_dipersediaan->pecah_satuan + $Get_data_barang_dipersediaan->bahan_produksi);


                                        ?>


                                            <tr>

                                                <td style="text-align:center">
                                                    <?php
                                                    echo ++$start;
                                                    // echo "<br/>";
                                                    // // echo $list_data->uuid_persediaan;
                                                    // echo $Get_data_barang_dipersediaan->total_10;
                                                    // echo "<br/>";
                                                    // echo $Get_data_barang_dipersediaan->penjualan;
                                                    // echo "<br/>";
                                                    // echo $Get_data_barang_dipersediaan->pecah_satuan;
                                                    // echo "<br/>";
                                                    // echo $Get_data_barang_dipersediaan->bahan_produksi;
                                                    // echo "<br/>";
                                                    // echo $GET_Sisa_Stock;
                                                    ?></td>

                                                <td align="left">
                                                    <?php

                                                    // if ($GET_Sisa_Stock > 0) {
                                                    ?>

                                                        <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modal-xl-input-barang_<?php echo $list_data->id ?>" onclick="setTimeout(function(){ if (window.initSelect2BarangSearch) { window.initSelect2BarangSearch('#modal-xl-input-barang_<?php echo $list_data->id ?>'); } }, 300);">
                                                            UBAH <?php //echo $list_data->id 
                                                                    ?>
                                                        </button>

                                                        <?php
                                                        echo anchor(site_url('tbl_pembelian/delete_by_uuid_pembelian_from_per_spop_update/' . $list_data->uuid_pembelian . '/' . $list_data->uuid_spop), 'Hapus DATA', 'onclick="javascript: return confirm(\'Anda Yakin akan Menghapus Pembelian Barang ini ?\')"');
                                                        ?>


                                                    <?php
                                                    // } else {
                                                    //     echo "SOLD";
                                                    // }
                                                    ?>



                                                </td>
                                                <td align="left"><?php echo $list_data->nama_gudang; ?></td>
                                                <td align="left">
                                                    <?php
                                                    $kategori_barang_detail = '';
                                                    echo $kategori_barang_detail;
                                                    ?>
                                                </td>
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
                                    <select name="uuid_konsumen" id="uuid_konsumen_<?php echo $list_data->id; ?>" class="form-control select2 select2-pembelian-unit" style="width: 100%; height: 40px;" required>
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
                                <div class="col-5">
                                    <label for="uuid_barang">Barang <?php echo form_error('uuid_barang') ?></label>

                                    <select name="uuid_barang" id="uuid_barang" class="form-control select2 select2-update-barang" style="width: 100%; height: 80px;" onchange="if (window.loadDetailBarangPembelianSpopReady) { loadDetailBarangPembelianSpopReady(this); }" required>
                                        <?php
                                        $kategori_row = '';
                                        $label_row_barang = strtoupper($row->uraian);
                                        ?>
                                        <option value="<?php echo htmlspecialchars($row->uuid_barang, ENT_QUOTES, 'UTF-8'); ?>" data-kategori="<?php echo htmlspecialchars($kategori_row, ENT_QUOTES, 'UTF-8'); ?>" data-satuan="<?php echo htmlspecialchars($row->satuan, ENT_QUOTES, 'UTF-8'); ?>" data-harga-satuan="<?php echo htmlspecialchars($row->harga_satuan, ENT_QUOTES, 'UTF-8'); ?>"><?php echo $label_row_barang; ?> </option>
                                        <!-- <option value="">Pilih Barang</option> -->
                                        <?php

                                        foreach (pembelian_get_barang_list_rows($this) as $m) {
                                            $kategori_barang = '';
                                            $harga_satuan_barang = isset($m->harga_satuan) ? $m->harga_satuan : '';
                                            $label_barang = strtoupper($m->nama_barang);
                                            echo "<option value='" . htmlspecialchars($m->uuid_barang, ENT_QUOTES, 'UTF-8') . "' data-kategori='" . htmlspecialchars($kategori_barang, ENT_QUOTES, 'UTF-8') . "' data-satuan='" . htmlspecialchars($m->satuan, ENT_QUOTES, 'UTF-8') . "' data-harga-satuan='" . htmlspecialchars($harga_satuan_barang, ENT_QUOTES, 'UTF-8') . "' ";
                                            echo ">  " . $label_barang  . "</option>";
                                        }
                                        ?>
                                    </select>

                                    <div class="row">
                                        <div class="col-8">
                                            <button type="button" class="btn btn-block btn-danger" onclick="openModalInputBarangBaruPembelianDariModal('#modal-xl-input-barang_<?php echo $list_data->id ?>'); return false;">
                                                Input Barang Baru
                                            </button>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-2">
                                    <label for="kategori_barang_info">Kategori</label>
                                    <input type="text" name="kategori_barang_info" id="kategori_barang_info" placeholder="kategori" class="form-control" value="<?php echo htmlspecialchars($kategori_row, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                </div>
                                <div class="col-2">
                                    <label for="satuan">Satuan <?php echo form_error('satuan') ?></label>
                                    <input type="text" name="satuan" id="satuan" placeholder="satuan" class="form-control" value="<?php echo $row->satuan; ?>" required>
                                </div>
                                <div class="col-3">
                                    <label for="satuan">Harga Satuan <?php echo form_error('harga_satuan') ?></label>
                                    <?php
                                    $get_format_rupiah_harga_satuan = number_format($row->harga_satuan, 2, ',', '.');
                                    ?>
                                    <input type="text" name="harga_satuan" id="harga_satuan" placeholder="contoh: 1.250,50" class="form-control" value="<?php echo $get_format_rupiah_harga_satuan; ?>" inputmode="decimal" autocomplete="off" required>
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




<!-- SOLUSI MODAL DI TARUH DI PALING BAWAH HALAMAN PHP ==> AGAR MUNCUL SEARCH COMBO SELECT2 DI MODAL , SOLUSI SEMENTARA : MODAL TARUH DI PALING BAWAH FILE PHP-->

<!-- TAMBAH BARANG MODAL EXTRA LARGE -->
<form action="<?php echo $action_tambah_barang_per_spop . $uuid_spop; ?>" method="post">
    <div class="modal fade" id="modal-xl-input-barang" role="dialog" style="overflow:hidden;">
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
                                <select name="uuid_konsumen" id="uuid_konsumen_tambah" class="form-control select2 select2-pembelian-unit" style="width: 100%; height: 40px;" required>
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
                            <div class="col-5">
                                <label for="uuid_barang">Barang <?php echo form_error('uuid_barang') ?></label>




                                <select name="uuid_barang" id="uuid_barang" class="form-control select2" style="width: 100%; height: 80px;" onchange="if (window.loadDetailBarangPembelianSpopReady) { loadDetailBarangPembelianSpopReady(this); }" required>
                                    <option value="">pilih </option>
                                    <!-- <option value="">Pilih Barang</option> -->
                                    <?php

                                    foreach (pembelian_get_barang_list_rows($this) as $m) {
                                        $kategori_barang = '';
                                        $harga_satuan_barang = isset($m->harga_satuan) ? $m->harga_satuan : '';
                                        $label_barang = strtoupper($m->nama_barang);
                                        echo "<option value='" . htmlspecialchars($m->uuid_barang, ENT_QUOTES, 'UTF-8') . "' data-kategori='" . htmlspecialchars($kategori_barang, ENT_QUOTES, 'UTF-8') . "' data-satuan='" . htmlspecialchars($m->satuan, ENT_QUOTES, 'UTF-8') . "' data-harga-satuan='" . htmlspecialchars($harga_satuan_barang, ENT_QUOTES, 'UTF-8') . "' ";
                                        echo ">  " . $label_barang  . "</option>";
                                    }
                                    ?>
                                </select>







                                <div class="row">
                                    <div class="col-8">
                                        <?php
                                        // $Get_source_form = "/Tbl_pembelian/create_add_uraian/" . $uuid_spop;
                                        ?>
                                        <button type="button" class="btn btn-block btn-danger" onclick="openModalInputBarangBaruPembelianDariModal('#modal-xl-input-barang'); return false;">
                                            Input Barang Baru
                                        </button>
                                    </div>
                                </div>

                            </div>
                            <div class="col-2">
                                <label for="kategori_barang_info">Kategori</label>
                                <input type="text" name="kategori_barang_info" id="kategori_barang_info" placeholder="kategori" class="form-control" readonly>
                            </div>
                            <div class="col-2">
                                <label for="satuan">Satuan <?php echo form_error('satuan') ?></label>
                                <input type="text" name="satuan" id="satuan" placeholder="satuan" class="form-control" required>
                            </div>
                            <div class="col-3">
                                <label for="satuan">Harga Satuan <?php echo form_error('harga_satuan') ?></label>
                                <input type="text" name="harga_satuan" id="harga_satuan" placeholder="contoh: 1.250,50" class="form-control" inputmode="decimal" autocomplete="off" required>
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
















<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }

    .select2-search--dropdown {
        display: block !important;
    }

    #modal-xl-input-barang .modal-dialog,
    .modal[id^="modal-xl-input-barang_"] .modal-dialog {
        max-width: 95vw;
    }

    #modal-xl-input-barang .select2-pembelian-unit-wrap .select2-selection--single,
    .modal[id^="modal-xl-input-barang_"] .select2-pembelian-unit-wrap .select2-selection--single {
        min-height: 52px;
        height: auto;
    }

    #modal-xl-input-barang .select2-pembelian-unit-wrap .select2-selection__rendered,
    .modal[id^="modal-xl-input-barang_"] .select2-pembelian-unit-wrap .select2-selection__rendered {
        line-height: 1.35;
        padding-top: 8px;
        white-space: normal;
    }
</style>





<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/select2/js/select2.full.js"></script>
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
        var sourceModalSelector = '';
        var shouldReopenSourceModal = false;
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

        function getTanggalPoUntukFilter() {
            var val = $('#form_update_spop input[name="tgl_po"]').val();
            if (val && String(val).trim() !== '') {
                return String(val).trim();
            }
            return ($('input[name="tgl_po"]').val() || '').trim();
        }
        window.getTanggalPoUntukFilter = getTanggalPoUntukFilter;

        function refreshUnitOptions(modalSelector, selectedUuid) {
            var $modal = modalSelector ? $(modalSelector) : $('#modal-xl-input-barang');
            if (!$modal.length) {
                return $.Deferred().resolve().promise();
            }

            var $select = $modal.find('select[name="uuid_konsumen"]');
            var currentValue = selectedUuid || $select.val() || '';

            return $.ajax({
                url: "<?php echo site_url('tbl_pembelian/list_unit_ajax'); ?>",
                type: 'GET',
                dataType: 'json',
                cache: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).done(function(res) {
                if (!res || !res.success) {
                    return;
                }

                $select.empty().append($('<option>', {
                    value: '',
                    text: 'Pilih Konsumen/Unit'
                }));

                $.each(res.data || [], function(_, row) {
                    $select.append($('<option>', {
                        value: row.uuid_unit,
                        text: (row.nama_unit || '').toUpperCase()
                    }));
                });

                if (currentValue) {
                    $select.val(currentValue);
                }
            });
        }
        window.refreshUnitOptions = refreshUnitOptions;

        function refreshBarangOptions(modalSelector, selectedUuid) {
            modalSelector = modalSelector || '#modal-xl-input-barang';
            var $modal = $(modalSelector);
            if (!$modal.length) {
                return $.Deferred().resolve().promise();
            }

            return $.ajax({
                url: "<?php echo site_url('sys_nama_barang/list_barang_ajax'); ?>",
                type: 'GET',
                dataType: 'json',
                cache: false,
                data: {
                    tanggal_po: getTanggalPoUntukFilter()
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).done(function(res) {
                if (!res || !res.success) {
                    return;
                }

                var select = $modal.find('select[name="uuid_barang"]');
                var currentValue = selectedUuid || select.val() || '';
                select.empty().append($('<option>', {
                    value: '',
                    text: 'Pilih Barang'
                }));

                $.each(res.data || [], function(_, row) {
                    var kategori = row.kategori || '';
                    var namaBarang = (row.nama_barang || '').toUpperCase();
                    var optionText = kategori ? '[' + kategori.toUpperCase() + '] ' + namaBarang : namaBarang;
                    select.append($('<option>', {
                        value: row.uuid_barang,
                        text: optionText
                    }).attr({
                        'data-kategori': kategori,
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
        window.refreshBarangOptions = refreshBarangOptions;

        function refreshModalPembelianData(modalSelector) {
            var selector = modalSelector || '#modal-xl-input-barang';
            var $modal = $(selector);
            if (!$modal.length) {
                return $.Deferred().resolve().promise();
            }

            var selectedUnit = $modal.find('select[name="uuid_konsumen"]').val() || '';
            var selectedBarang = $modal.find('select[name="uuid_barang"]').val() || '';

            return $.when(
                refreshUnitOptions(selector, selectedUnit),
                refreshBarangOptions(selector, selectedBarang)
            ).always(function() {
                initSelect2BarangSearch(selector);
                var uuidBarang = $modal.find('select[name="uuid_barang"]').val();
                if (uuidBarang) {
                    loadDetailBarangPembelianSpopReady($modal.find('select[name="uuid_barang"]')[0]);
                }
            });
        }
        window.refreshModalPembelianData = refreshModalPembelianData;

        /**
         * Format harga satuan gaya Indonesia: titik = ribuan, koma = desimal.
         * Contoh tampilan: 6.826,75 (= 6826.75)
         * @param {*} value
         * @param {boolean} fromDatabase - true jika nilai dari DB/API (desimal titik Inggris)
         */
        function formatHargaSatuanPembelianSpopReady(value, fromDatabase) {
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

        function applyFormatHargaSatuanPembelianSpopReady(input) {
            var inputElement = $(input);
            var el = inputElement.get(0);
            var oldVal = inputElement.val();
            var oldLen = oldVal.length;
            var selStart = el && typeof el.selectionStart === 'number' ? el.selectionStart : oldLen;
            var newVal = formatHargaSatuanPembelianSpopReady(oldVal, false);
            inputElement.val(newVal);
            if (el && typeof el.setSelectionRange === 'function') {
                var newPos = Math.max(0, newVal.length - (oldLen - selStart));
                try {
                    el.setSelectionRange(newPos, newPos);
                } catch (e) {}
            }
        }

        function getBarangFormFromSelect(selectElement) {
            return $(selectElement).closest('form');
        }

        function setDetailBarangToForm(form, kategori, satuan, hargaSatuan) {
            form.find('input[name="kategori_barang_info"]').val(kategori || '');
            form.find('input[name="satuan"]').val(satuan || '');
            form.find('input[name="harga_satuan"]').val(formatHargaSatuanPembelianSpopReady(hargaSatuan, true));
        }

        function loadDetailBarangPembelianSpopReady(selectElement) {
            var select = $(selectElement);
            var uuidBarang = select.val();
            var form = getBarangFormFromSelect(selectElement);

            if (!uuidBarang) {
                setDetailBarangToForm(form, '', '', '');
                return;
            }

            var selectedOption = select.find('option:selected');
            var kategoriOption = selectedOption.attr('data-kategori') || '';
            var satuanOption = selectedOption.attr('data-satuan') || '';
            var hargaSatuanOption = selectedOption.attr('data-harga-satuan') || '';
            if (kategoriOption !== '' || satuanOption !== '' || hargaSatuanOption !== '') {
                setDetailBarangToForm(form, kategoriOption, satuanOption, hargaSatuanOption);
            }

            $.ajax({
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
                setDetailBarangToForm(form, res.data.kategori, res.data.satuan, res.data.harga_satuan);
            });
        }

        window.loadDetailBarangPembelianSpopReady = loadDetailBarangPembelianSpopReady;

        function initSelect2BarangSearch(scopeSelector) {
            if (!$.fn.select2) {
                return;
            }

            var scope = scopeSelector ? $(scopeSelector) : $('.modal');
            scope.find('select.select2').each(function() {
                var select = $(this);
                var parentModal = select.closest('.modal');
                var isUnit = select.hasClass('select2-pembelian-unit');
                var options = {
                    width: '100%',
                    minimumResultsForSearch: 0,
                    placeholder: select.find('option:first').text() || 'Pilih',
                    allowClear: false
                };

                if (parentModal.length) {
                    options.dropdownParent = parentModal;
                }

                if (isUnit) {
                    options.containerCssClass = 'select2-pembelian-unit-wrap';
                    options.dropdownCssClass = 'select2-pembelian-unit-dropdown';
                }

                if (select.data('select2')) {
                    select.select2('destroy');
                }
                select.next('.select2-container').remove();
                select.removeClass('select2-hidden-accessible').removeAttr('data-select2-id').removeAttr('aria-hidden').removeAttr('tabindex');
                select.select2(options);
            });
        }

        window.initSelect2BarangSearch = initSelect2BarangSearch;

        function initSelect2InModal() {
            if ($.fn.select2) {
                $('#modal-input-barang-baru .select2').select2({
                    dropdownParent: $('#modal-input-barang-baru')
                });
            }
        }

        function loadInputBarangForm(callback) {
            if (modalFormLoaded) {
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
                initSelect2InModal();
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

        window.openModalInputBarangBaruPembelianDariModal = function(modalSelector) {
            sourceModalSelector = modalSelector || '';
            shouldReopenSourceModal = sourceModalSelector !== '';

            if ($.fn.modal && sourceModalSelector) {
                $(sourceModalSelector).one('hidden.bs.modal', function() {
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

        window.closeModalInputBarangBaruPembelian = function() {
            hideBootstrapOrManual('#modal-input-barang-baru');
            if (shouldReopenSourceModal && sourceModalSelector && !$(sourceModalSelector).hasClass('show')) {
                showBootstrapOrManual(sourceModalSelector);
            }
        };

        $('#modal-input-barang-baru').on('hidden.bs.modal', function(e) {
            if (e.target.id !== 'modal-input-barang-baru') {
                return;
            }
            if (shouldReopenSourceModal && sourceModalSelector) {
                showBootstrapOrManual(sourceModalSelector);
            }
        });

        $(document).on('submit', '#form-input-barang-baru-modal', function(e) {
            e.preventDefault();

            var form = $(this);
            var submitButton = $('#btn-submit-input-barang-baru');
            var submitButtonText = submitButton.data('original-text') || submitButton.text();
            var tglPo = getTanggalPoUntukFilter();

            if (!tglPo) {
                showInputBarangInfo('Silakan pilih <strong>Tgl PO</strong> di form pembelian terlebih dahulu.', 'warning');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tgl PO belum dipilih',
                        text: 'Pilih tanggal PO (datepicker) agar bulan persediaan ditentukan sebelum menyimpan barang baru.'
                    });
                }
                $('#form_update_spop input[name="tgl_po"]').focus();
                return false;
            }

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
                    var modalAsal = sourceModalSelector || '#modal-xl-input-barang';
                    refreshBarangOptions(modalAsal, res.data.uuid_barang).always(function() {
                        showInputBarangInfo(res.message || 'Barang berhasil ditambahkan.', 'success');
                        form[0].reset();
                        window.closeModalInputBarangBaruPembelian();
                    });
                    return;
                }

                if (res && res.duplicate && res.data) {
                    refreshBarangOptions(sourceModalSelector || '#modal-xl-input-barang', res.data.uuid_barang);
                }
                showInputBarangInfo((res && res.message) ? res.message : 'Barang baru gagal disimpan.', 'danger');
            }).fail(function() {
                showInputBarangInfo('Terjadi kesalahan saat menyimpan barang baru.', 'danger');
            }).always(function() {
                submitButton.prop('disabled', false).text(submitButtonText);
            });
        });

        $(document).on('change', 'select[name="uuid_barang"]', function() {
            loadDetailBarangPembelianSpopReady(this);
        });

        $(document).on('input keyup paste', '#modal-xl-input-barang input[name="harga_satuan"], .modal[id^="modal-xl-input-barang_"] input[name="harga_satuan"]', function() {
            var input = this;
            setTimeout(function() {
                applyFormatHargaSatuanPembelianSpopReady(input);
            }, 0);
        });

        $(document).ready(function() {
            initSelect2BarangSearch('#form_update_spop');
        });

        $('#modal-xl-input-barang').on('show.bs.modal', function() {
            refreshModalPembelianData('#modal-xl-input-barang');
        });

        $(document).on('show.bs.modal', '.modal[id^="modal-xl-input-barang_"]', function() {
            refreshModalPembelianData('#' + this.id);
        });

        $(document).on('shown.bs.modal', '.modal[id^="modal-xl-input-barang"]', function() {
            initSelect2BarangSearch('#' + this.id);
        });

        $('#form_update_spop input[name="tgl_po"]').on('change blur', function() {
            refreshBarangOptions('#modal-xl-input-barang');
        });
    })(jQuery);
</script>