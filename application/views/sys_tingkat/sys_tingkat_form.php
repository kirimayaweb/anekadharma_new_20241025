<!-- Main content -->
        <section class='content'>
          <div class='row'>
            <div class='col-xs-12'>
              <div class='box'>
                <div class='box-header'>
                
                  <h3 class='box-title'>SYS_TINGKAT</h3>
                      <div class='box box-primary'>
        <form action="<?php echo $action; ?>" method="post"><table class='table table-bordered'>
	    <tr><td>Uuid Tingkat <?php echo form_error('uuid_tingkat') ?></td>
            <td><input type="text" class="form-control" name="uuid_tingkat" id="uuid_tingkat" placeholder="Uuid Tingkat" value="<?php echo $uuid_tingkat; ?>" />
        </td>
	    <tr><td>Tingkat System <?php echo form_error('tingkat_system') ?></td>
            <td><textarea class="form-control" rows="3" name="tingkat_system" id="tingkat_system" placeholder="Tingkat System"><?php echo $tingkat_system; ?></textarea>
        </td></tr>
	    <tr><td>Tingkat <?php echo form_error('tingkat') ?></td>
            <td><input type="text" class="form-control" name="tingkat" id="tingkat" placeholder="Tingkat" value="<?php echo $tingkat; ?>" />
        </td>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <tr><td colspan='2'><button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('sys_tingkat') ?>" class="btn btn-default">Cancel</a></td></tr>
	
    </table></form>
    </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->