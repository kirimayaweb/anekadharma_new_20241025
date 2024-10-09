<!-- Main content -->
<section class='content'>
    <div class='row'>
        <div class='col-xs-12'>
            <div class='box'>
                <div class='box-header btn-success' align="center">


                    <div class='col-xs-12'>
                        <h3 class='box-title'> <strong> LAPORAN PEMBAYARAN PASAR </strong> </h3>
                    </div>
                </div> <br>

                <div class='col-xs-12' align="center">
                    <?php echo anchor(site_url('tbl_pembayaran_pasar/excel'), ' <i class="fa fa-file-excel-o"></i> REKONSILIASI Excel', 'class="btn btn-primary btn-sm"'); ?>
                    <?php //echo anchor(site_url('tbl_pembayaran_pasar/word'), '<i class="fa fa-file-word-o"></i> Cetak ke Word', 'class="btn btn-primary btn-sm"'); 
                    ?>


                    <select name="combo_nama_pasar" id="combo_nama_pasar" class="form-control select2" style="width: 50%; height: 40px;">
                        <?php


                        $cek = $this->session->userdata('company');

                        if ($cek == 'pasar') {
                            $kodepasar = $this->session->userdata('kodepasar');
                            $namapasar = $this->db->get_where('sys_pasar', array('kode_pasar' => $kodepasar));
                        } elseif ($cek == 'lurahpasar') {
                            $kodepasar = $this->session->userdata('kodepasar');
                            $namapasar = $this->db->get_where('sys_pasar', array('kode_pasar' => $kodepasar));
                        } else {
                            $namapasar = $this->db->get('sys_pasar');
                        }

                        foreach ($namapasar->result() as $nama_pasar) {
                            echo "<option value='$nama_pasar->kode_pasar' ";
                            echo ">" .  strtoupper($nama_pasar->kode_pasar) . " = " .  strtoupper($nama_pasar->nama_pasar) . " (Tipe = " .  strtoupper($nama_pasar->tipe_pasar) . ")</option>";
                        }
                        ?>
                    </select>
                    <?php echo anchor('/Tbl_pasar_input_tagihan', 'Filter Pasar', array('class' => 'btn btn-success btn-sm')); ?>
                </div>
            </div>
            <br>

        </div><!-- /.box-header -->
        <div class='box-body'>
            <table class="table table-bordered table-striped" id="mytable">
                <thead>
                    <tr>
                        <th style="text-align:center" width=" 80px">NO</th>
                        <th style="text-align:center">KODE PASAR</th>
                        <th style="text-align:center">ID PEDAGANG</th>
                        <th style="text-align:center">KODE RETRIBUSI</th>
                        <th style="text-align:center">BUKTI PEMBAYARAN</th>
                        <th style="text-align:center">NAMA TAGIHAN</th>

                        <th style="text-align:center">TGL AWAL <br /> TGL AKHIR </th>

                        <th style="text-align:center">TGL BAYAR</th>

                        <th style="text-align:center">NOMINAL</th>
                        <!--<th>Tgl Akhir</th>-->

                        <!-- <th>Action</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $start = 0;
                    foreach ($tbl_pembayaran_pasar_data as $tbl_pembayaran_pasar) {
                    ?>
                        <tr>
                            <td><?php echo ++$start ?></td>
                            <td><?php
                                tampil($tbl_pembayaran_pasar->kodepasar);
                                print_r("<br/>");
                                $namapasar = $this->db->get_where('sys_pasar', array('kode_pasar' => $tbl_pembayaran_pasar->kodepasar));
                                tampil($namapasar->row()->nama_pasar);
                                ?></td>
                            <td><?php
                                tampil($tbl_pembayaran_pasar->nik);
                                echo "<br/>";
                                tampil(stripslashes($tbl_pembayaran_pasar->nama));
                                ?></td>
                            <td><?php tampil($tbl_pembayaran_pasar->koderetribusi) ?></td>


                            <td><?php echo anchor(site_url('Tbl_pembayaran_pasar/bukti_pembayaran_word'), '<i class="fa fa-file-word-o"></i> Bukti Pembayaran', 'class="btn btn-block btn-success btn-sm"'); ?></td>


                            <td><?php tampil($tbl_pembayaran_pasar->namatagihan) ?></td>

                            <td><?php
                                tampil($tbl_pembayaran_pasar->tgl_awal);
                                echo "<br/>";
                                tampil($tbl_pembayaran_pasar->tgl_akhir);
                                ?></td>


                            <td><?php tampil($tbl_pembayaran_pasar->tglbayar) ?></td>


                            <td><?php tampil($tbl_pembayaran_pasar->nominal) ?></td>

                            <!--<td><?php //echo $tbl_pembayaran_pasar->tgl_akhir 
                                    ?></td>-->

                            <!-- 		    <td style="text-align:center" width="140px">
			<?php
                        //echo anchor(site_url('tbl_pembayaran_pasar/read/'.$tbl_pembayaran_pasar->id),'<i class="fa fa-eye"></i>',array('title'=>'detail','class'=>'btn btn-danger btn-sm')); 
                        //echo '  '; 
                        //echo anchor(site_url('tbl_pembayaran_pasar/update/'.$tbl_pembayaran_pasar->id),'<i class="fa fa-pencil-square-o"></i>',array('title'=>'edit','class'=>'btn btn-danger btn-sm')); 
                        //echo '  '; 
                        //echo anchor(site_url('tbl_pembayaran_pasar/delete/'.$tbl_pembayaran_pasar->id),'<i class="fa fa-trash-o"></i>','title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
            ?>
		    </td> -->
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
            <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
            <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#mytable").dataTable({
                        scrollY: 600,
                        scrollX: true
                    });
                });
            </script>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
    <!-- /.col -->
    <!-- /.row -->
</section><!-- /.content -->