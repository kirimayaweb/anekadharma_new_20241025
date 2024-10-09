<link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE-3.1.0/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE-3.1.0/plugins/datatables-responsive/css/responsive.bootstrap4.css">



<div class="content-wrapper">
    <section class="content">






        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning box-solid">

                    <div class="box-header">
                        <h3 class="box-title">KELOLA DATA TBL_PRODUK</h3>
                    </div>

                    <div class="box-body">
                        <div class='row'>
                            <div class='col-md-9'>
                                <div style="padding-bottom: 10px;"'>
        <?php echo anchor(site_url('tbl_produk/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data Produk', 'class="btn btn-danger btn-sm"'); ?>
		<?php echo anchor(site_url('tbl_produk/excel'), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Ms Excel', 'class="btn btn-success btn-sm"'); ?>
		<?php echo anchor(site_url('tbl_produk/word'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Export Ms Word', 'class="btn btn-primary btn-sm"'); ?></div>
            </div>
            <div class=' col-md-3'>
                                    <form action="<?php echo site_url('tbl_produk/index'); ?>" class="form-inline" method="get">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                                            <span class="input-group-btn">
                                                <?php
                                                if ($q <> '') {
                                                ?>
                                                    <a href="<?php echo site_url('tbl_produk'); ?>" class="btn btn-default">Reset</a>
                                                <?php
                                                }
                                                ?>
                                                <button class="btn btn-primary" type="submit">Search</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>


                            <!-- <div class="row" style="margin-bottom: 10px">
                                <div class="col-md-4 text-center">
                                    <div style="margin-top: 8px" id="message">
                                        <?php //echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; 
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-1 text-right">
                                </div>
                                <div class="col-md-3 text-right">

                                </div>
                            </div> -->
                            <!-- <table id="mytable" class="table table-bordered" style="margin-bottom: 10px"> -->
                            <table id="mytable" class="table table-bordered table-striped">

                                <tr>
                                    <th>No</th>

                                    <th style="text-align:center" width="150px">Action</th>
                                    <th>Proses Transaksi</th>
                                    <th>Kode Produk</th>
                                    <th>Tingkat</th>
                                    <th>Kelas</th>
                                    <th>Mapel</th>
                                    <th>Semester</th>
                                    <th>Halaman</th>
                                    <th>Keterangan</th>
                                </tr><?php
                                        foreach ($tbl_produk_data as $tbl_produk) {
                                        ?>
                                    <tr>
                                        <td width="10px"><?php echo ++$start ?></td>
                                        <td style="text-align:center">
                                            <?php
                                            echo anchor(site_url('tbl_produk/read/' . $tbl_produk->id), '<i class="fa fa-eye" aria-hidden="true">Detail</i>', 'class="btn btn-success btn-sm"');
                                            echo '  ';
                                            echo anchor(site_url('tbl_produk/update/' . $tbl_produk->id), '<i class="fa fa-pencil-square-o" aria-hidden="true">Ubah Data</i>', 'class="btn btn-warning btn-sm"');
                                            echo '  ';
                                            echo anchor(site_url('tbl_produk/delete/' . $tbl_produk->id), '<i class="fa fa-trash-o" aria-hidden="true">Hapus Data</i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                            ?>
                                        </td>
                                        <td style="text-align:center">
                                            <?php

                                            echo anchor(site_url('Trans_cetak/create/' . $tbl_produk->uuid_produk), '<i class="fa fa-pencil-square-o" aria-hidden="true"> PESAN CETAK </i>', 'class="btn btn-success btn-sm"');

                                            ?>
                                        </td>
                                        <td>
                                            <?php //echo $tbl_produk->tingkat 
                                            ?>
                                            <img src="<?php echo site_url(); ?>/Barcode/set_barcode/<?php echo $tbl_produk->kode_produk; ?>">
                                        </td>
                                        <td><?php echo $tbl_produk->tingkat ?></td>
                                        <td><?php echo $tbl_produk->kelas ?></td>
                                        <td><?php echo $tbl_produk->mapel ?></td>
                                        <td><?php echo $tbl_produk->semester ?></td>
                                        <td><?php echo $tbl_produk->halaman ?></td>
                                        <td><?php echo $tbl_produk->keterangan ?></td>

                                    </tr>
                                <?php
                                        }
                                ?>
                            </table>



                            <!-- MESSAGE NOTIFIKASI -->
                            <div class="row" style="margin-bottom: 10px">
                                <div class="col-md-4 text-center">
                                    <div style="margin-top: 8px" id="message">
                                        <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                                    </div>
                                </div>
                                <div class="col-md-1 text-right">
                                </div>
                                <div class="col-md-3 text-right">

                                </div>
                            </div>
                            <!-- END OF MESSAGE NOTIFIKASI -->


                            <div class="row">
                                <div class="col-md-6">

                                </div>
                                <div class="col-md-6 text-right">
                                    <?php echo $pagination ?>
                                </div>
                            </div>


                            <script src="<?php echo base_url() ?>assets/AdminLTE-3.1.0/plugins/datatables/jquery.dataTables.min.js"></script>
                            <script src="<?php echo base_url() ?>assets/AdminLTE-3.1.0/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
                            <script src="<?php echo base_url() ?>assets/AdminLTE-3.1.0/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
                            <script src="<?php echo base_url() ?>assets/AdminLTE-3.1.0/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
                            <script src="<?php echo base_url() ?>assets/AdminLTE-3.1.0/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
                            <script src="<?php echo base_url() ?>assets/AdminLTE-3.1.0/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
                            <script src="<?php echo base_url() ?>assets/AdminLTE-3.1.0/plugins/jszip/jszip.min.js"></script>
                            <script src="<?php echo base_url() ?>assets/AdminLTE-3.1.0/plugins/pdfmake/pdfmake.min.js"></script>
                            <script src="<?php echo base_url() ?>assets/AdminLTE-3.1.0/plugins/pdfmake/vfs_fonts.js"></script>
                            <script src="<?php echo base_url() ?>assets/AdminLTE-3.1.0/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
                            <script src="<?php echo base_url() ?>assets/AdminLTE-3.1.0/plugins/datatables-buttons/js/buttons.print.min.js"></script>
                            <script src="<?php echo base_url() ?>assets/AdminLTE-3.1.0/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $("#mytable").dataTable({
                                        scrollY: 250,
                                        scrollX: true
                                    });
                                });
                            </script>




                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>