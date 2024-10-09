<!-- Main content -->
        <section class='content'>
          <div class='row'>
            <div class='col-xs-12'>
              <div class='box'>
                <div class='box-header'>
                
                  <h3 class='box-title'>TBL_PEMBAYARAN_PASAR</h3>
                      <div class='box box-primary'>
        <form action="<?php echo $action; ?>" method="post"><table class='table table-bordered'>
	    <tr><td>Kodepasar <?php echo form_error('kodepasar') ?></td>
            <td><input type="text" class="form-control" name="kodepasar" id="kodepasar" placeholder="Kodepasar" value="<?php tampil($kodepasar); ?>" />
        </td>
	    <tr><td>Idpedagang <?php echo form_error('idpedagang') ?></td>
            <td><input type="text" class="form-control" name="idpedagang" id="idpedagang" placeholder="Idpedagang" value="<?php tampil($idpedagang); ?>" />
        </td>
	    <tr><td>Koderetribusi <?php echo form_error('koderetribusi') ?></td>
            <td><textarea class="form-control" rows="3" name="koderetribusi" id="koderetribusi" placeholder="Koderetribusi"><?php tampil($koderetribusi); ?></textarea>
        </td></tr>
	    <tr><td>Namatagihan <?php echo form_error('namatagihan') ?></td>
            <td><textarea class="form-control" rows="3" name="namatagihan" id="namatagihan" placeholder="Namatagihan"><?php tampil($namatagihan); ?></textarea>
        </td></tr>
	    <tr><td>Nominal <?php echo form_error('nominal') ?></td>
            <td><input type="text" class="form-control" name="nominal" id="nominal" placeholder="Nominal" value="<?php tampil($nominal); ?>" />
        </td>
	    <tr><td>Tglbayar <?php echo form_error('tglbayar') ?></td>
            <td><input type="text" class="form-control" name="tglbayar" id="tglbayar" placeholder="Tglbayar" value="<?php tampil($tglbayar); ?>" />
        </td>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <tr><td colspan='2'><button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_pembayaran_pasar') ?>" class="btn btn-default">Cancel</a></td></tr>
	
    </table>

<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
  </form>
    </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->