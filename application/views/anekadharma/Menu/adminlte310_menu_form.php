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
                                    <div class="col-12" text-align="center"> <strong>INPUT MENU</strong></div>
                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>




                    </div>
                    <br />



                    <div class="card-body">
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-7">
                                <form action="<?php echo $action; ?>" method="post">



                                    <table class='table table-bordered'>
                                        <tr>
                                            <td width="220">Name <?php echo form_error('name') ?></td>
                                            <td><input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $name; ?>" />
                                            </td>
                                        <tr>
                                            <td>Link <?php echo form_error('link') ?></td>
                                            <td><input type="text" class="form-control" name="link" id="link" placeholder="Link" value="<?php echo $link; ?>" />
                                            </td>
                                        <tr>
                                            <td>Icon <?php echo form_error('icon') ?></td>
                                            <td><input type="text" class="form-control" name="icon" id="icon" placeholder="Icon" value="<?php echo $icon; ?>" />
                                            </td>
                                        <tr>
                                            <td>Is Active <?php echo form_error('is_active') ?></td>
                                            <td><?php echo form_dropdown('is_active', array('1' => 'AKTIF', '0' => 'TIDAK AKTIF'), $is_active, "class='form-control'"); ?>
                                            </td>
                                        <tr>
                                            <td>Is Parent <?php echo form_error('is_parent') ?></td>
                                            <td>
                                                <select name="is_parent" class="form-control">
                                                    <option value="0">YA</option>
                                                    <!-- <option value="0">NO</option> -->
                                                    <?php
                                                    $menu = $this->db->get('menu');
                                                    foreach ($menu->result() as $m) {
                                                        echo "<option value='$m->id' ";
                                                        echo $m->id == $is_parent ? 'selected' : '';
                                                        echo ">" .  strtoupper($m->name) . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                        <tr>
                                            <td colspan='2' align="center">
                                                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                                <a href="<?php echo site_url('menu') ?>" class="btn btn-default">Cancel</a>
                                            </td>
                                        </tr>

                                    </table>
                                </form>
                            </div>
                            <div class="col-3"></div>
                        </div>
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