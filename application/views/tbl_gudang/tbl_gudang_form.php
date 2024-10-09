<div class="content-wrapper">

    <section class="content">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">INPUT DATA TBL_GUDANG</h3>
            </div>
            <form action="<?php echo $action; ?>" method="post">

                <table class='table table-bordered>' <tr>


                    <!-- <tr>
                        <td width='200'>KODE GUDANG </td>
                        <td> <textarea class="form-control" rows="3" name="kode_gudang" id="kode_gudang" placeholder="kode gudang"><?php //echo $kode_gudang; 
                                                                                                                                    ?></textarea>
                            <?php //echo form_error('kode_gudang') 
                            ?></td>
                    </tr> -->
                    <tr>
                        <td width='200'>Nama Gudang </td>
                        <td> <textarea class="form-control" rows="3" name="nama_gudang" id="nama_gudang" placeholder="Nama Gudang"><?php echo $nama_gudang; ?></textarea>
                            <?php echo form_error('nama_gudang') ?></td>
                    </tr>

                    <tr>
                        <td width='200'>Alamat Gudang </td>
                        <td> <textarea class="form-control" rows="3" name="alamat_gudang" id="alamat_gudang" placeholder="Alamat Gudang"><?php echo $alamat_gudang; ?></textarea>
                            <?php echo form_error('alamat_gudang') ?></td>
                    </tr>

                    <tr>
                        <td width='200'>Notelp Gudang </td>
                        <td> <textarea class="form-control" rows="3" name="notelp_gudang" id="notelp_gudang" placeholder="Notelp Gudang"><?php echo $notelp_gudang; ?></textarea>
                            <?php echo form_error('notelp_gudang') ?></td>
                    </tr>

                    <tr>
                        <td width='200'>Keterangan </td>
                        <td> <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
                            <?php echo form_error('keterangan') ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button>
                            <a href="<?php echo site_url('tbl_gudang') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
</div>
</div>