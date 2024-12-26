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
                                    <div class="col-12" text-align="center"> <strong>pemasukan_kas_kecil_action DATA KAS KECIL</strong></div>
                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>




                    </div>
                    <br />



                    <div class="card-body">

                        <form action="<?php echo $action; ?>" method="post">
                            <!-- <div class="form-group">
                                <label for="varchar">Uuid Kas Kecil <?php //echo form_error('uuid_kas_kecil') 
                                                                    ?></label>
                                <input type="text" class="form-control" name="uuid_kas_kecil" id="uuid_kas_kecil" placeholder="Uuid Kas Kecil" value="<?php //echo $uuid_kas_kecil; 
                                                                                                                                                        ?>" />
                            </div> -->



                            <div class="form-group">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="date">Tanggal <?php echo form_error('tanggal') ?></label>


                                        <?php

                                        if ($this->input->post('tanggal', TRUE)) {
                                            $get_tanggal=date("d-m-Y", strtotime($tanggal));
                                        } else {
                                            $get_tanggal = date("Y-m-d H:i:s");
                                        }

                                        ?>

                                        <div class="input-group date" id="tanggal" name="tanggal" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#tgl_po" id="tgl_po" name="tanggal" value="<?php echo date("d-m-Y", strtotime($get_tanggal)); ?>" required />
                                            <div class="input-group-append" data-target="#tgl_po" data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <label for="unit">Unit <?php echo form_error('unit') ?></label>
                                        <!-- <textarea class="form-control" rows="3" name="unit" id="unit" placeholder="Unit"><?php //echo $unit; 
                                                                                                                                ?></textarea> -->


                                        <select name="unit" id="unit" class="form-control select2" style="width: 100%; height: 40px;" required>

                                            <?php

                                            if ($unit) {
                                            ?>
                                                <option value="<?php echo $unit; ?>"><?php echo $unit; ?></option>
                                            <?php
                                            } else {
                                            ?>
                                                <option value="">Pilih Unit </option>
                                            <?php
                                            }


                                            ?>

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
                                    <div class="col-3">
                                        <label for="double">Debet <?php echo form_error('debet') ?></label>
                                        <input type="text" class="form-control uang" name="debet" id="debet" placeholder="Debet" value="<?php echo $debet; ?>" style="font-size:1.5vw;font-weight: bold;text-align:right;color:black;" min="1" max="9999999999999" ; required/>

                                    </div>
                                </div>
                            </div>
                          
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-3">
                                    </div>
                                    <div class="col-9">
                                        <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
                                        <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
                                    </div>


                                </div>
                            </div>

                            <!-- <div class="form-group">
                                <label for="double">Debet <?php //echo form_error('debet') 
                                                            ?></label>
                                <input type="text" class="form-control" name="debet" id="debet" placeholder="Debet" value="<?php //echo $debet; 
                                                                                                                            ?>" />
                            </div>
                            <div class="form-group">
                                <label for="double">Kredit <?php //echo form_error('kredit') 
                                                            ?></label>
                                <input type="text" class="form-control" name="kredit" id="kredit" placeholder="Kredit" value="<?php //echo $kredit; 
                                                                                                                                ?>" />
                            </div> -->
                            <!-- <div class="form-group">
                                <label for="double">Saldo <?php //echo form_error('saldo') 
                                                            ?></label>
                                <input type="text" class="form-control" name="saldo" id="saldo" placeholder="Saldo" value="<?php //echo $saldo; 
                                                                                                                            ?>" />
                            </div>
                            <div class="form-group">
                                <label for="int">Id Usr <?php //echo form_error('id_usr') 
                                                        ?></label>
                                <input type="text" class="form-control" name="id_usr" id="id_usr" placeholder="Id Usr" value="<?php //echo $id_usr; 
                                                                                                                                ?>" />
                            </div> -->

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-3">
                                    </div>
                                    <div class="col-3">

                                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                        <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                        <a href="<?php echo site_url('tbl_kas_kecil') ?>" class="btn btn-default">Cancel</a>

                                    </div>
                                    <div class="col-6">
                                    </div>


                                </div>
                            </div>



                            <!-- MODAL EXTRA LARGE -->

                            <div class="modal fade" id="modal-xl-select-unit">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Tambah Produk Barang</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">



                                            <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:center" width="10px">No</th>
                                                        <th style="text-align:center">Tgl Po</th>
                                                        <th style="text-align:center">Spop</th>
                                                        <th style="text-align:center">No. faktur/ kwitansi</th>
                                                        <th style="text-align:center">Supplier</th>
                                                        <th style="text-align:center">Kode Barang</th>
                                                        <th style="text-align:center">Nama Barang</th>
                                                        <th style="text-align:center">Jumlah</th>
                                                        <th style="text-align:center">Satuan</th>
                                                        <th style="text-align:center">Konsumen</th>
                                                        <th style="text-align:center">Harga Satuan</th>
                                                        <th style="text-align:center">Harga Total</th>
                                                        <th style="text-align:center">Statuslu</th>
                                                        <th style="text-align:center">Kas / Bank</th>
                                                        <th style="text-align:center">Tgl Bayar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $compare_spop = 0;
                                                    $compare_uuid_spop = 0;
                                                    $Total_per_SPOP = 0;
                                                    $TOTAL_LUNAS = 0;
                                                    $TOTAL_HUTANG = 0;
                                                    $list_spop_status_lu = "";
                                                    $x_button = 0;
                                                    $start = 0;
                                                    foreach ($Tbl_pembelian_data as $list_data) {

                                                        $list_spop_status_lu = $list_data->statuslu; // untuk cek kondisi di baris terakhir (SPOP)
                                                        if (($compare_spop <> $list_data->spop) and ($start >= 1)) {
                                                            // Buat 1 baris untuk total dan background = KUNING
                                                    ?>
                                                            <tr>
                                                                <td><?php echo ++$start ?></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <!-- <td></td> -->
                                                                <!-- <td></td> -->
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td style="background-color:yellow;" align="right"> <?php echo "<font color='red'><strong>" . nominal($Total_per_SPOP) . "</strong></font>" ?> </td>
                                                                <td>
                                                                    <?php

                                                                    $result_pengajuan_by_uuid_spop = $this->Tbl_pembelian_pengajuan_bayar_model->get_by_uuid_spop($compare_uuid_spop);

                                                                    $TOTAL_Nominal_pengajuan = $this->Tbl_pembelian_pengajuan_bayar_model->get_sumNominal_by_uuid_spop($compare_uuid_spop)->total_pengajuan;

                                                                    if ($result_pengajuan_by_uuid_spop) {
                                                                        $startx = 0;
                                                                        $total_nominal_pengajuan = 0;
                                                                        foreach ($result_pengajuan_by_uuid_spop as $list_data_pengajuan) {

                                                                            $total_nominal_pengajuan = $total_nominal_pengajuan + $list_data_pengajuan->nominal_pengajuan;
                                                                        }

                                                                        if ($TOTAL_Nominal_pengajuan < $Total_per_SPOP) {
                                                                        }
                                                                    } else {
                                                                        echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $compare_uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                                                    }

                                                                    ?>
                                                                </td>
                                                                <td></td>
                                                                <td></td>

                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                        <tr>
                                                            <?php

                                                            if ($compare_spop == $list_data->spop) {
                                                            ?>
                                                                <td><?php echo ++$start ?></td>
                                                                <td>

                                                                    <?php

                                                                    if (($compare_spop == $list_data->spop) and $x_button == 1) {
                                                                        $x_button_show = 1;
                                                                        $x_button = $x_button + 1;
                                                                    } else {

                                                                        echo date("d M Y", strtotime($list_data->tgl_po));
                                                                        // echo anchor(site_url('tbl_pembelian/update_per_id/' . $list_data->uuid_pembelian), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                                        // echo anchor(site_url('tbl_pembelian/delete_by_uuid_pembelian/' . $list_data->uuid_pembelian), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td><?php echo $list_data->spop; ?></td>
                                                                <td align="center"><?php echo $list_data->nmrfakturkwitansi; ?></td>


                                                                <td align="left"><?php echo $list_data->supplier_nama; ?></td>

                                                            <?php
                                                            } else {
                                                                $Total_per_SPOP = 0;
                                                                $x_button = 0;
                                                                $x_button_show = 0;
                                                            ?>
                                                                <td><?php echo ++$start ?></td>
                                                                <td><?php
                                                                    echo date("d M Y", strtotime($list_data->tgl_po));
                                                                    echo " ";

                                                                    // echo anchor(site_url('tbl_pembelian/update_per_id/' . $list_data->uuid_pembelian), '<i class="fa fa-pencil-square-o" aria-hidden="true">UPDATE</i>', 'class="btn btn-warning btn-xs"');

                                                                    // echo anchor(site_url('tbl_pembelian/delete_by_uuid_pembelian/' . $list_data->uuid_pembelian), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');

                                                                    ?>


                                                                </td>
                                                                <td align="center">
                                                                    <?php
                                                                    echo $list_data->spop;
                                                                    $x_button = $x_button + 1;
                                                                    echo "  ";
                                                                    if ($list_data->status_spop) {
                                                                        // echo anchor(site_url('tbl_pembelian/update_status_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">' . $list_data->status_spop . '</i>', 'class="btn btn-success btn-xs"');
                                                                    } else {
                                                                        // echo anchor(site_url('tbl_pembelian/update_status_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">STATUS</i>', 'class="btn btn-danger btn-xs"');
                                                                    }

                                                                    ?>
                                                                </td>

                                                                <td align="center"><?php echo $list_data->nmrfakturkwitansi; ?></td>

                                                                <td align="left"><?php echo $list_data->supplier_nama; ?></td>
                                                            <?php
                                                            }
                                                            ?>



                                                            <td align="center"><?php echo $list_data->kode_barang; ?></td>
                                                            <td align="left"><?php echo $list_data->uraian; ?></td>
                                                            <td align="right"><?php echo nominal($list_data->jumlah); ?></td>
                                                            <td align="left"><?php echo $list_data->satuan; ?></td>
                                                            <td align="left"><?php echo $list_data->konsumen; ?></td>
                                                            <td align="right"><?php echo nominal($list_data->harga_satuan); ?></td>
                                                            <td align="right">
                                                                <?php
                                                                $total_per_uraian = $list_data->jumlah * $list_data->harga_satuan;

                                                                echo nominal($total_per_uraian);

                                                                $Total_per_SPOP = $Total_per_SPOP + $total_per_uraian;


                                                                ?>
                                                            </td>
                                                            <td align="center">
                                                                <?php
                                                                if ($list_data->statuslu == "U") {
                                                                    echo "<font color='red'>" . $list_data->statuslu . "</font>";
                                                                    $TOTAL_HUTANG = $TOTAL_HUTANG + $total_per_uraian;
                                                                } else {
                                                                    echo $list_data->statuslu;
                                                                    $TOTAL_LUNAS = $TOTAL_LUNAS + $total_per_uraian;
                                                                }


                                                                ?>
                                                            </td>
                                                            <td align="center"><?php echo $list_data->kas_bank; ?></td>
                                                            <td align="center">
                                                                <?php


                                                                if (date("Y", strtotime($this->input->post('tgl_po', TRUE))) < 2020) {
                                                                    echo "";
                                                                } else {
                                                                    echo $list_data->tgl_bayar;
                                                                }

                                                                ?>
                                                            </td>
                                                            <?php
                                                            $compare_spop = $list_data->spop;
                                                            $compare_uuid_spop = $list_data->uuid_spop;
                                                            ?>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>

                                                    <!-- TOTAL SPOP AKHIR -->
                                                    <tr>
                                                        <td><?php echo ++$start ?></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td style="background-color:yellow;" align="right"> <?php echo "<font color='red'><strong>" . nominal($Total_per_SPOP) . "</strong></font>" ?> </td>
                                                        <td>
                                                            <?php
                                                            // if ($list_spop_status_lu == "U") {
                                                            //     echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                                            // }


                                                            $result_pengajuan_by_uuid_spop = $this->Tbl_pembelian_pengajuan_bayar_model->get_by_uuid_spop($list_data->uuid_spop);

                                                            $TOTAL_Nominal_pengajuan = $this->Tbl_pembelian_pengajuan_bayar_model->get_sumNominal_by_uuid_spop($list_data->uuid_spop)->total_pengajuan;

                                                            if ($result_pengajuan_by_uuid_spop) {
                                                                $startx = 0;
                                                                $total_nominal_pengajuan = 0;
                                                                foreach ($result_pengajuan_by_uuid_spop as $list_data_pengajuan) {
                                                                    // echo $list_data_pengajuan->uuid_pengajuan_bayar;
                                                                    echo anchor(site_url('tbl_pembelian/cetak_pengajuan_bayar_per_spop/' . $list_data_pengajuan->uuid_pengajuan_bayar), '<i class="fa fa-pencil-square-o" aria-hidden="true">Cetak Pengajuan ' . ++$startx . '</i>', 'class="btn btn-success btn-xs" target="_blank"');

                                                                    $total_nominal_pengajuan = $total_nominal_pengajuan + $list_data_pengajuan->nominal_pengajuan;
                                                                }
                                                                // echo $TOTAL_Nominal_pengajuan;
                                                                // echo " : ";
                                                                // echo $Total_per_SPOP;
                                                                // echo " : ";
                                                                if ($TOTAL_Nominal_pengajuan < $Total_per_SPOP) {
                                                                    echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $compare_uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                                                }
                                                            } else {
                                                                // if ($total_nominal_pengajuan < $Total_per_SPOP) {
                                                                echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $compare_uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                                                // }else{
                                                                //     echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs" disabled');
                                                                // }

                                                            }

                                                            ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>

                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>

                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th style="text-align:right">TOTAL LUNAS</th>
                                                        <th style="text-align:right"><?php echo nominal($TOTAL_LUNAS); ?></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>

                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th style="text-align:right"><?php echo "<font color='red'>TOTAL HUTANG</font>"; ?></th>
                                                        <th style="text-align:right"><?php echo "<font color='red'>" . nominal($TOTAL_HUTANG) . "</font>"; ?></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>

                                            </table>


                                        </div>

                                        <div class="modal-footer">

                                            <!-- <button type="submit" class="btn btn-primary">Proses</button> -->

                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>


                            <!-- END OF MODAL EXTRA LARGE -->


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