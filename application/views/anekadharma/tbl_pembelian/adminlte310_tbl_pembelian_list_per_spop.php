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
                                    <div class="col-12" text-align="center"> <strong>DATA PEMBELIAN SPOP</strong></div>
                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>
                        <div class="row">
                            <div class="col-6">
                                <?php echo anchor(site_url('tbl_pembelian/create'), 'Input Pembelian (Belanja Perusahaan)', 'class="btn btn-danger"'); ?>
                            </div>
                            <div class="col-4">

                            </div>
                            <div class="col-2">
                                <?php echo anchor(site_url('tbl_pembelian/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); ?>
                            </div>



                        </div>



                    </div>
                    <br />



                    <div class="card-body">

                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <!-- <th style="text-align:center" width="100px">Action</th> -->
                                    <th>Tgl Po</th>
                                    <th>Nmrsj</th>
                                    <th>Nmrfakturkwitansi</th>
                                    <th>Nmrbpb</th>
                                    <th>Spop</th>
                                    <!-- <th>Supplier Kode</th> -->
                                    <th>Supplier Nama</th>
                                    <th>Uraian</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Konsumen</th>
                                    <th>Harga Satuan</th>
                                    <th>Harga Total</th>
                                    <th>Statuslu</th>
                                    <th>Kas / Bank</th>
                                    <th>Tgl Bayar</th>
                                    <!-- <th>Id Usr</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $compare_spop = 0;
                                $Total_per_SPOP = 0;
                                $TOTAL_LUNAS = 0;
                                $TOTAL_HUTANG = 0;
                                foreach ($Tbl_pembelian_data as $list_data) {
                                    if (($compare_spop <> $list_data->spop) and $start > 1) {
                                        // Buat 1 baris untuk total dan background = KUNING
                                ?>
                                        <tr>
                                            <td><?php echo ++$start ?></td>
                                            <td>
                                                <?php

                                                echo anchor(site_url('tbl_pembelian/update_per_spop/' . $list_data->id), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                echo anchor(site_url('tbl_pembelian/delete_per_spop/' . $list_data->id), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');

                                                ?>

                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <?php
                                                // if ($start >= 2 and $start = 2) {
                                                //     echo "Pursiti";
                                                // }else{
                                                //     echo "";
                                                // }
                                                ?>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td style="background-color:yellow;" align="right"> <?php echo "<font color='red'><strong>" . nominal($Total_per_SPOP) . "</strong></font>" ?> </td>
                                            <td></td>
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
                                                echo anchor(site_url('tbl_pembelian/update_per_spop/' . $list_data->id), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                echo anchor(site_url('tbl_pembelian/delete_per_spop/' . $list_data->id), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');
                                                ?>

                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        <?php
                                        } else {
                                            // SPOP baru , me NOL kan total SPOP
                                            $Total_per_SPOP = 0;
                                        ?>
                                            <td><?php echo ++$start ?></td>
                                            <td><?php
                                                // echo date("d M Y", strtotime($list_data->tgl_po)); 
                                                echo anchor(site_url('tbl_pembelian/update_per_spop/' . $list_data->id), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                echo anchor(site_url('tbl_pembelian/delete_per_spop/' . $list_data->id), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');
                                                ?></td>
                                            <td align="center"><?php echo $list_data->nmrsj; ?></td>
                                            <td align="center"><?php echo $list_data->nmrfakturkwitansi; ?></td>
                                            <td align="center"><?php echo $list_data->nmrbpb; ?></td>
                                            <td align="center">
                                                <?php
                                                echo $list_data->spop;
                                                // echo "<br/>";
                                                // echo anchor(site_url('tbl_pembelian/update_per_spop/'.$list_data->spop),'Update');
                                                // echo anchor(site_url('tbl_pembelian/delete_per_spop/'.$list_data->spop),'Hapus');
                                                ?>
                                            </td>
                                            <td align="left"><?php echo $list_data->supplier_nama; ?></td>
                                        <?php
                                        }
                                        ?>
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
                                    <td></td>
                                    <td style="background-color:yellow;" align="right"> <?php echo "<font color='red'><strong>" . nominal($Total_per_SPOP) . "</strong></font>" ?> </td>
                                    <td></td>
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