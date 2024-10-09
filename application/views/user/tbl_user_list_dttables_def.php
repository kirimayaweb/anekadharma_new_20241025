<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning box-solid">

                    <div class="box-header">
                        <h3 class="box-title">STOCK BARANG</h3>
                    </div>

                    <div class="box-body">
                        <div style="padding-bottom: 10px;"'>
        <?php echo anchor(site_url('user/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data Barang', 'class="btn btn-danger btn-sm"'); ?>
		<?php echo anchor(site_url('user/excel'), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export STOCK ke Ms Excel', 'class="btn btn-success btn-sm"'); ?>
		<?php echo anchor(site_url('user/word'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Export STOCK Ms Word', 'class="btn btn-primary btn-sm"'); ?></div>
        <table class="table table-bordered table-striped" id="mytable">
            <thead>
                                    <tr>
                                        <th style="text-align:center" width="30px">NO</th>
                                        <th style="text-align:center">AKSI</th>
                                        <th style="text-align:center">id_users</th>
                                    </tr>
            </thead>
	                                    <tbody>
                                    <?php
                                    $start = 0;
                                    foreach ($tbl_penyewa_data as $tbl_penyewa) {
                                    ?>
                                        <tr>
                                            <td><?php echo ++$start ?></td>
                                            <td style="text-align:center" width="40px">
                                                <?php
                                                // echo anchor(site_url('tbl_penyewa/read/'.$tbl_penyewa->id),'<i class="fa fa-eye"></i>',array('title'=>'detail','class'=>'btn btn-danger btn-sm')); 
                                                // echo '  '; 
                                                echo anchor(site_url('tbl_penyewa/update/' . $tbl_penyewa->id_users), '<i class="fa fa-pencil-square-o">Ubah Data</i>', array('title' => 'edit', 'class' => 'btn btn-danger btn-sm'));
                                                // echo '  '; 
                                                // echo anchor(site_url('tbl_penyewa/delete/'.$tbl_penyewa->id),'<i class="fa fa-trash-o"></i>','title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
                                                ?>
                                            </td>
                                            <td><?php echo $tbl_penyewa->id_users ?></td>

                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
        </table>
        </div>
                    </div>
            </div>
            </div>
    </section>
</div>
        <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>
             <script type="text/javascript">
                                $(document).ready(function() {
                                    $("#mytable").dataTable({
                                        scrollY: 500,
                                        scrollX: true
                                    });
                                });
                            </script>