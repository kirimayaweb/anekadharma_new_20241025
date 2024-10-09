<div class="content-wrapper">

    <section class="content">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">INPUT DATA TBL_SALES</h3>
            </div>
            <form action="<?php echo $action; ?>" method="post">

                <table class='table table-bordered>' <tr>


                    <tr>
                        <td width='200'>Kode Sales <?php echo form_error('kode_sales') ?></td>
                        <td> <textarea class="form-control" rows="3" name="kode_sales" id="kode_sales" placeholder="kode sales"><?php echo $kode_sales; ?></textarea></td>
                    </tr>
                    <tr>
                        <td width='200'>Nama Sales <?php echo form_error('nama_sales') ?></td>
                        <td> <textarea class="form-control" rows="3" name="nama_sales" id="nama_sales" placeholder="Nama Sales"><?php echo $nama_sales; ?></textarea></td>
                    </tr>

                    <tr>
                        <td width='200'>Alamat Sales <?php echo form_error('alamat_sales') ?></td>
                        <td> <textarea class="form-control" rows="3" name="alamat_sales" id="alamat_sales" placeholder="Alamat Sales"><?php echo $alamat_sales; ?></textarea></td>
                    </tr>

                    <tr>
                        <td width='200'>Notelp Sales <?php echo form_error('notelp_sales') ?></td>
                        <td> <textarea class="form-control" rows="3" name="notelp_sales" id="notelp_sales" placeholder="Notelp Sales"><?php echo $notelp_sales; ?></textarea></td>
                    </tr>

                    <tr>
                        <td width='200'>Keterangan <?php echo form_error('keterangan') ?></td>
                        <td> <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button>
                            <a href="<?php echo site_url('tbl_sales') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
</div>
</div>