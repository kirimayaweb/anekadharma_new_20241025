<div class="content-wrapper">

    <section class="content">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">INPUT DATA PERUSAHAAN FINISHING</h3>
            </div>
            <form action="<?php echo $action; ?>" method="post">

                <table class='table table-bordered>' <tr>
                    <!-- <td width='200'>Uuid Company Finishing <?php //echo form_error('uuid_company_finishing') 
                                                                ?></td>
                    <td><input type="text" class="form-control" name="uuid_company_finishing" id="uuid_company_finishing" placeholder="Uuid Company Finishing" value="<?php //echo $uuid_company_finishing; 
                                                                                                                                                                        ?>" /></td>
                    </tr>
                    <tr>
                        <td width='200'>Date Input <?php //echo form_error('date_input') 
                                                    ?></td>
                        <td><input type="text" class="form-control" name="date_input" id="date_input" placeholder="Date Input" value="<?php //echo $date_input; 
                                                                                                                                        ?>" /></td>
                    </tr>
                    <tr>
                        <td width='200'>Id User Input <?php //echo form_error('id_user_input') 
                                                        ?></td>
                        <td><input type="text" class="form-control" name="id_user_input" id="id_user_input" placeholder="Id User Input" value="<?php //echo $id_user_input; 
                                                                                                                                                ?>" /></td>
                    </tr> -->

                    <tr>
                        <td width='200'>Nama Perusahaan <?php echo form_error('nama_perusahaan') ?></td>
                        <td> <textarea class="form-control" rows="3" name="nama_perusahaan" id="nama_perusahaan" placeholder="Nama Perusahaan"><?php echo $nama_perusahaan; ?></textarea></td>
                    </tr>

                    <tr>
                        <td width='200'>Alamat Perusahaan <?php echo form_error('alamat_perusahaan') ?></td>
                        <td> <textarea class="form-control" rows="3" name="alamat_perusahaan" id="alamat_perusahaan" placeholder="Alamat Perusahaan"><?php echo $alamat_perusahaan; ?></textarea></td>
                    </tr>

                    <tr>
                        <td width='200'>Notelp Perusahaan <?php echo form_error('notelp_perusahaan') ?></td>
                        <td> <textarea class="form-control" rows="3" name="notelp_perusahaan" id="notelp_perusahaan" placeholder="Notelp Perusahaan"><?php echo $notelp_perusahaan; ?></textarea></td>
                    </tr>

                    <tr>
                        <td width='200'>Keterangan <?php echo form_error('keterangan') ?></td>
                        <td> <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button>
                            <a href="<?php echo site_url('tbl_company_finishing') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
</div>
</div>