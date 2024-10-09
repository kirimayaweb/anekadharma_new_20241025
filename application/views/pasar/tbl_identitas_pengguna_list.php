<!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">-->

<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
<link rel="stylesheet" href="<?php echo base_url() ?>template/bootstrap337/bootstrap.css">


<!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">-->
<link rel="stylesheet" href="<?php echo base_url() ?>template/plugins/datatables/dataTables.bootstrap.css">






<!-- Main content -->
<section class='content'>
  <div class='row'>
    <div class='col-xs-12'>
      <div class='box'>
        <div class='box-header btn-success' align="center">
          <h3 class='box-title'> <strong> LIST DATA PENGGUNA </strong> </h3>
        </div>
        <div class="center">
          <?php echo anchor('index.php/tbl_identitas_pengguna/create/', 'Tambah Data Pengguna', array('class' => 'btn btn-danger btn-sm')); ?>
          <?php echo anchor(site_url('tbl_identitas_pengguna/excel'), ' <i class="fa fa-file-excel-o"></i> Excel', 'class="btn btn-primary btn-sm"'); ?>

          <?php echo anchor(site_url('tbl_identitas_pengguna/word'), '<i class="fa fa-file-word-o"></i> Word', 'class="btn btn-primary btn-sm"');
          ?>
          <?php echo anchor(site_url('tbl_identitas_pengguna/pdf'), '<i class="fa fa-file-pdf-o"></i> PDF', 'class="btn btn-primary btn-sm"', 'target="blank"');
          ?>
          </h3>
        </div>
      </div><!-- /.box-header -->



      <div class='box-body'>
        <!--<table class="table table-bordered table-striped" id="mytable">-->
        <table class="table table-striped table-bordered" id="mytable">
          <thead>
            <tr>
              <th style="text-align:center" width="30px">NO</th>
              <th style="text-align:center">AKSI</th>
              <th style="text-align:center">NIK</th>
              <!--<th>Idpedagang</th>-->
              <th style="text-align:center">NAMA</th>
              <th style="text-align:center">ALAMAT</th>
              <th style="text-align:center">PEDUKUHAN</th>
              <th style="text-align:center">DESA</th>
              <th style="text-align:center">KECAMATAN</th>
              <th style="text-align:center">KABUPATEN</th>
              <th style="text-align:center">JENIS KELAMIN</th>
              <th style="text-align:center">STATUS</th>
              <th style="text-align:center">NO HP</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $start = 0;
            foreach ($tbl_identitas_pengguna_data as $tbl_identitas_pengguna) {
            ?>
              <tr>
                <td><?php echo ++$start ?></td>
                <td style="text-align:center" width="80px">
                  <?php
                  // echo anchor(site_url('index.php/tbl_identitas_pengguna/read/'.$tbl_identitas_pengguna->id),'<i class="fa fa-eye"></i>',array('title'=>'detail','class'=>'btn btn-danger btn-sm')); 
                  // echo '  '; 
                  echo anchor(site_url('index.php/tbl_identitas_pengguna/update/' . $tbl_identitas_pengguna->id), '<i class="fa fa-pencil-square-o">Ubah Data</i>', array('title' => 'edit', 'class' => 'btn btn-danger btn-sm'));
                  // echo '  '; 
                  // echo anchor(site_url('index.php/tbl_identitas_pengguna/delete/'.$tbl_identitas_pengguna->id),'<i class="fa fa-trash-o"></i>','title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
                  ?>
                </td>
                <td><?php tampil($tbl_identitas_pengguna->nik) ?></td>
                <!--<td><?php //echo $tbl_identitas_pengguna->idpedagang 
                        ?></td>-->
                <td><?php tampil(stripslashes($tbl_identitas_pengguna->nama)) ?></td>
                <td><?php tampil($tbl_identitas_pengguna->alamat) ?></td>
                <td><?php
                    tampil($tbl_identitas_pengguna->padukuhan);
                    echo ", ";
                    tampil($tbl_identitas_pengguna->rt);
                    ?></td>
                <td><?php tampil($tbl_identitas_pengguna->desa) ?></td>
                <td><?php tampil($tbl_identitas_pengguna->kecamatan) ?></td>
                <td><?php tampil($tbl_identitas_pengguna->kabupaten) ?></td>
                <td><?php tampil($tbl_identitas_pengguna->jeniskelamin) ?></td>
                <td><?php tampil($tbl_identitas_pengguna->status) ?></td>
                <td><?php tampil($tbl_identitas_pengguna->no_hp) ?></td>

              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>


        <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>


        <!-- ORI	
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
-->

        <script type="text/javascript">
          $(document).ready(function() {
            $("#mytable").dataTable({
              scrollY: 550,
              scrollX: true
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

  </div><!-- /.row -->
</section><!-- /.content -->