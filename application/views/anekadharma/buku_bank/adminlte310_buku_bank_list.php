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
                                    <div class="col-5" text-align="center"> <strong>BUKU BANK</strong></div>
                                    <div class="col-7" text-align="center"> <strong><?php //echo anchor(site_url('tbl_penjualan/create'), 'Input PENJUALAN BARU', 'class="btn btn-danger"'); 
                                                                                    ?></strong></div>

                                </div>


                            </div>
                            <div class="col-4">

                            </div>

                            <div class="col-2">
                                <?php echo anchor(site_url('Bukubank/create'), 'Input Buku Bank', 'class="btn btn-success"');
                                ?>
                            </div>

                            <div class="col-2">
                                <?php //echo anchor(site_url('tbl_penjualan/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); 
                                ?>
                            </div>


                        </div>




                    </div>




                    <div class="card-body">

                        <table id="tglSPOPFreeze" class="table table-striped dt-responsive w-100 table-bordered display nowrap table-hover mb-0" style="width:100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="text-align:center" width="10px">No</th>
                                    <th rowspan="2"style="text-align:center" width="100px">Action</th>
                                    <th rowspan="2">Tanggal</th>

                                    <th colspan="2" style="text-align:center">Rekening</th>

                                    <th rowspan="2">Keterangan</th>
                                    <th rowspan="2">Kode</th>
                                    <th rowspan="2">Debet</th>
                                    <th rowspan="2">Kredit</th>
                                    <th rowspan="2">Saldo</th>



                                </tr>
                                <tr>
                                    <th>Bank</th>
                                    <th>Nomor rekening</th>
                                </tr>

                                <!-- -------------- -->


                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // print_r($data_buku_bank);
                                $start = 0;
                                $TOTAL_DEBET = 0;
                                $TOTAL_KREDIT = 0;
                                $TOTAL_SALDO = 0;

                                foreach ($data_buku_bank as $list_data) {
                                ?>
                                    <tr>
                                        <td align="left"><?php echo ++$start; ?></td>
                                        <td style="text-align:left">
                                            <?php
                                            // if ($list_data->debet > 0) {
                                            echo anchor(site_url('Bukubank/update/' . $list_data->id), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));
                                            // } else {
                                            //     if ($list_data->uuid_spop) {
                                            //         echo anchor(site_url('Bukubank/update/' . $list_data->id . '/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));
                                            //     } else {
                                            //         echo anchor(site_url('Bukubank/update/' . $list_data->id), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));
                                            //     }
                                            // }
                                            echo ' ';
                                            echo anchor(site_url('Bukubank/delete/' . $list_data->id), '<i class="fa fa-trash-o">Hapus</i>', 'title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Anda Yakin Akan Menghapus Data ini ?\')"');
                                            ?>
                                        </td>
                                        <td align="left">
                                            <?php
                                            echo date("d-M-Y", strtotime($list_data->tanggal));
                                            ?>
                                        </td>
                                        <td align="left">
                                            <?php
                                            echo $list_data->bank;
                                            ?>
                                        </td>
                                        <td align="left">
                                            <?php
                                            echo $list_data->norek;
                                            ?>
                                        </td>
                                        <td align="left">
                                            <?php
                                            echo $list_data->keterangan;
                                            ?>
                                        </td>
                                        <td align="center">
                                            <?php
                                            echo $list_data->kode;
                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php
                                            // echo $list_data->debet;
                                            // echo "<br/>";
                                            echo number_format($list_data->debet, 2, ',', '.');

                                            $TOTAL_DEBET = $TOTAL_DEBET + $list_data->debet;

                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php
                                            echo number_format($list_data->kredit, 2, ',', '.');
                                            $TOTAL_KREDIT = $TOTAL_KREDIT + $list_data->kredit;
                                            $TOTAL_SALDO = $TOTAL_DEBET - $TOTAL_KREDIT;

                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php
                                            echo number_format($TOTAL_SALDO, 2, ',', '.');
                                            ?>
                                        </td>


                                    </tr>


                                <?php
                                }
                                ?>
                            </tbody>

                            <tfoot>

                                <tr>
                                    <th style="text-align:center"></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align:right">
                                        <?php
                                        echo number_format($TOTAL_DEBET, 2, ',', '.');
                                        ?>
                                    </th>
                                    <th style="text-align:right">
                                        <?php
                                        echo number_format($TOTAL_KREDIT, 2, ',', '.');
                                        ?></th>
                                    <th style="text-align:right">
                                        <?php
                                        echo number_format($TOTAL_SALDO, 2, ',', '.');
                                        ?>
                                    </th>

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
            "scrollY": 1100,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 1100,
            "scrollX": true
        });
    });
</script>