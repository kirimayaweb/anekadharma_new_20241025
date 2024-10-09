<?php



?>

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
                                            Pengajuan Pembayaran
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


                        <form action="<?php echo $action; ?>" method="post">
                            <div class="form-group">
                                <div class="row">

                                    <div class="col-2">
                                        <label for="datetime">Tgl Po <?php echo form_error('tgl_po') ?></label>
                                        <div class="input-group date" id="tgl_po" name="tgl_po" data-target-input="nearest">
                                            <input type="text" class="form-control" rows="3" name="tgl_po" id="tgl_po" placeholder="tgl_po" value="<?php echo $date_po_X; ?>" disabled>
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <label for="supplier_nama">Nama Supplier <?php echo form_error('supplier_nama') ?></label>

                                        <input type="text" class="form-control" rows="3" name="Supplier" id="Supplier" placeholder="Supplier" value="<?php echo $supplier_nama; ?>" disabled>

                                    </div>

                                    <div class="col-2">
                                        <label for="statuslu">Status <?php echo form_error('statuslu') ?></label>

                                        <input type="text" class="form-control" rows="3" name="statuslu" id="statuslu" placeholder="statuslu" value="<?php if ($statuslu == "L") {
                                                                                                                                                            echo "LUNAS";
                                                                                                                                                        } else {
                                                                                                                                                            echo "HUTANG";
                                                                                                                                                        } ?>" disabled>


                                    </div>
                                    <div class="col-2">
                                        <label for="kas_bank">Kas / Bank <?php echo form_error('kas_bank') ?></label>

                                        <input type="text" class="form-control" rows="3" name="kas_bank" id="kas_bank" placeholder="kas_bank" value="<?php echo $kas_bank; ?>" disabled>

                                    </div>

                                </div>
                            </div>




                            <div class="form-group">

                                <div class="row">
                                    <div class="col-3">
                                        <label for="nmrsj">Nomor SPOP <?php echo form_error('spop') ?></label>

                                        <input type="text" class="form-control" rows="3" name="spop" id="spop" placeholder="spop" value="<?php echo $spop; ?>" disabled>

                                    </div>

                                    <div class="col-2">
                                        <label for="nmrfakturkwitansi">Nomor faktur / kwitansi <?php echo form_error('nmrfakturkwitansi') ?></label>
                                        <input type="text" class="form-control" rows="3" name="nmrfakturkwitansi" id="nmrfakturkwitansi" placeholder="nmrfakturkwitansi" value="<?php echo $nmrfakturkwitansi; ?>" disabled>

                                    </div>



                                </div>

                            </div>




                            <div class="card card-default">
                                <div class="card-header">

                                    <div class="row">
                                        <!-- <div class="col-12" text-align="center"> <strong>Detail Barang</strong></div> -->


                                    </div>

                                </div>


                                <div class="card-body">

                                    <table id="exampleFreeze" class="display nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th style="text-align:center" width="10px">No</th>

                                                <th>Uraian</th>
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
                                            $start = 0;
                                            foreach ($data_ALL_per_SPOP as $list_data) {

                                            ?>


                                                <tr>

                                                    <td><?php echo ++$start ?></td>

                                                    <td align="left"><?php echo $list_data->uraian; ?></td>
                                                    <td align="center"><?php echo nominal($list_data->jumlah); ?></td>
                                                    <td align="center"><?php echo $list_data->satuan; ?></td>

                                                    <td align="right"><?php echo nominal($list_data->harga_satuan); ?></td>
                                                    <td align="right">
                                                        <?php
                                                        $total_per_uraian = $list_data->jumlah * $list_data->harga_satuan;

                                                        echo nominal($total_per_uraian);

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
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align:right"><?php echo  nominal($Total_per_SPOP); ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>


                                <div class="card-body">

                                    <!-- <input id="iduraian" value="1" type="hidden" />
                                    <div id="divuraian"></div>
                                    <button type="button" onclick="tambahuraian(); return false;">Tambah Uraian</button> -->

                                    <!-- <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                                        Tambah Barang
                                    </button> -->

                                    <div class="card card-success">
                                        <div class="card-header">

                                            <div class="row">
                                                <div class="col-12" text-align="center"> <strong><label for="nmrsj">Jumlah Pembayaran </label></strong></div>




                                                <div class="form-group">

                                                    <div class="row">
                                                        <div class="col-3">


                                                            <input type="text" class="form-control" rows="3" name="jumlahpembayaran" id="jumlahpembayaran" placeholder="Nominal Pembayaran" value="">

                                                        </div>
                                                        <div class="col-2">
                                                            <a href="<?php echo site_url('tbl_pembelian/simpan_data_spop/' . $uuid_spop) ?>" class="btn btn-primary">Ajukan Pembayaran</a>



                                                        </div>
                                                    </div>

                                                </div>


                                            </div>

                                        </div>
                                    </div>





                                </div>
                            </div>



                            <!-- <input type="hidden" name="id" value="<?php //echo $id; ?>" /> -->
                            <!-- <button type="submit" class="btn btn-primary"><?php //echo $button 
                                                                                ?></button> -->

                            <!-- <a href="<?php //echo site_url('tbl_pembelian/simpan_data_spop/' . $uuid_spop) ?>" class="btn btn-primary"><?php //echo $button ?></a>

                            <a href="<?php //echo site_url('tbl_pembelian/cetak_belanja_per_spop/' . $uuid_spop) ?>" class="btn btn-primary" target="_blank">Cetak PDF</a> -->

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