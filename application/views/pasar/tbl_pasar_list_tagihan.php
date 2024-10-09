<section class='content'>
    <div class='row'>
        <div class='col-xs-12'>
            <div class='box' align="center">
                <div class='box-header btn-success'>


                    <div class='col-xs-12'>
                        <h3 class='box-title'> <b> LIST DATA TAGIHAN PASAR </b> </h3>
                    </div>

                </div> <br>
                <div class='col-xs-12' align="center">
                    <?php echo anchor(site_url('index.php/tbl_pasar/excel'), ' <i class="fa fa-file-excel-o"></i> Cetak Tagihan (Excel)', 'class="btn btn-primary btn-sm"'); ?></h3>
                    <!-- </div>
                    <div class='col-xs-6'> -->

                    <select name="combo_nama_pasar" id="combo_nama_pasar" class="form-control select2" style="width: 50%; height: 40px;">
                        <?php
                        $cek = $this->session->userdata('company');

                        if ($cek == 'pasar') {
                            $kodepasar = $this->session->userdata('kodepasar');
                            // $sql = "select tbl_pasar.*,tbl_identitas_pedagang_pasar.nama from tbl_pasar left join tbl_identitas_pedagang_pasar on tbl_identitas_pedagang_pasar.nik=tbl_pasar.nik where tbl_pasar.kodepasar=$kodepasar";

                            $namapasar = $this->db->get_where('sys_pasar', array('kode_pasar' => $kodepasar));
                        } elseif ($cek == 'lurahpasar') {
                            $kodepasar = $this->session->userdata('kodepasar');
                            // $sql = "select tbl_pasar.*,tbl_identitas_pedagang_pasar.nama from tbl_pasar left join tbl_identitas_pedagang_pasar on tbl_identitas_pedagang_pasar.nik=tbl_pasar.nik where tbl_pasar.kodepasar=$kodepasar";
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
                    <?php echo anchor('/tbl_pasar', 'Filter Pasar', array('class' => 'btn btn-success btn-sm')); ?>
                </div>

            </div> <br><!-- /.box-header -->

            <?php
            $variabel_tgl_awal = date("Y/m/02");
            $tgl_aktif_bulan_ini = date('Y-m-d', strtotime('-1 day', strtotime($variabel_tgl_awal))); //bulan depan
            ?>



            <div class='box-body'>
                <table class="table table-bordered table-striped" id="mytable">
                    <thead>
                        <tr>
                            <th style="text-align:center" width="5px">NO</th>
                            <!--<th style="text-align:center" width="240px">RETRIBUSI</th>-->
                            <th style="text-align:center" width="20px">PEDAGANG</th>
                            <th style="text-align:center" width="20px">ID PELANGGAN <br /> POSISI</th>
                            <th style="text-align:center" width="20px">LAPORAN TAGIHAN</th>
                            <th style="text-align:center" width="20px">TEGURAN</th>

                            <th style="text-align:center" width="20px">BULAN AKTIF</th>
                            <th style="text-align:center" width="5px">JUMLAH <br /> HARI</th>
                            <th style="text-align:center" width="20px">TAGIHAN /HARI <br /> SEWA x P x L</th>
                            <th style="text-align:center" width="5px">DENDA</th>
                            <th style="text-align:center" width="10px">TAGIHAN <br /> KEBERSIHAN</th>


                            <th style="text-align:center" width="20px">TOTAL <br /> TAGIHAN</th>
                            <th style="text-align:center" width="2px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $start = 0;
                        foreach ($tbl_pasar_data as $tbl_pasar) {
                        ?>
                            <tr>
                                <td><?php echo ++$start ?></td>
                                <td align="center">
                                    <?php
                                    if (strtotime($tbl_pasar->tgl_akhir) > strtotime('now')) {

                                        // <a href="#" type="button" class="btn btn-success btn-md" data-toggle="modal" data-target="#modal-success<?php echo $tbl_pasar->nik;  
                                        echo "<strong>";
                                        tampil($tbl_pasar->nama);
                                        echo "</strong>";
                                        echo "<br/>";
                                        tampil($tbl_pasar->nik);
                                        // </a>  
                                    } else {
                                        echo anchor(site_url('index.php/tbl_pasar/Assign_pedagang/' . $tbl_pasar->id), ' <i class="fa fa-file-excel-o"></i> HABIS/Aktivasi Sewa', 'class="btn btn-primary btn-sm"');
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if (strtotime($tbl_pasar->tgl_akhir) > strtotime('now')) {

                                        tampil($tbl_pasar->koderetribusi);
                                    } else {
                                        echo "<p style='font-size:14px; color:red;'>kosong</p>";
                                    }
                                    ?>

                                </td>

                                <td style="text-align:center" width="20px">
                                    <?php
                                    if (strtotime($tbl_pasar->tgl_akhir) > strtotime('now')) {
                                        echo anchor(site_url('Tbl_pasar/word_skrd_pasar/' . $tbl_pasar->koderetribusi . '/' . $tbl_pasar->tgl_aktif), '<i class="fa fa-file-word-o"></i> SKRD Tagihan Pasar', 'class="btn btn-block btn-warning btn-sm"');
                                    } else {
                                        echo "<p style='font-size:14px; color:red;'>kosong</p>";
                                    }
                                    ?>

                                </td>
                                <td style="text-align:center" width="20px">

                                    <?php
                                    if (strtotime($tbl_pasar->tgl_akhir) > strtotime('now')) {
                                        if ($tbl_pasar->tgl_aktif < $tgl_aktif_bulan_ini) {
                                            echo anchor(site_url('Tbl_pasar/tagihan_pasar'), '<i class="fa fa-file-word-o"></i> Surat Teguran', 'class="btn btn-block btn-danger btn-sm"');
                                        }
                                    } else {
                                        echo "<p style='font-size:14px; color:red;'>kosong</p>";
                                    }
                                    ?>


                                </td>

                                <td><?php
                                    if (strtotime($tbl_pasar->tgl_akhir) > strtotime('now')) {
                                        tampil($tbl_pasar->tgl_aktif);
                                    } else {
                                        echo "<p style='font-size:14px; color:red;'>kosong</p>";
                                    }
                                    ?>

                                </td>


                                <?php if ($tbl_pasar->jumlah_hari_aktif < 1) { ?>
                                    <td align="right"><?php
                                                        echo "<font color='red'>";
                                                        tampil(nominal($tbl_pasar->jumlah_hari_aktif));
                                                        echo "</font>";
                                                        ?></td>

                                <?php } else { ?>
                                    <td align="right"><?php
                                                        echo "<font color='black'>";
                                                        tampil(nominal($tbl_pasar->jumlah_hari_aktif));
                                                        echo "</font>";
                                                        ?></td>
                                <?php }  ?>



                                <!-- <td><?php echo nominal($tbl_pasar->totaltarifkelaspasar) ?></td> -->

                                <?php if ($tbl_pasar->jumlah_hari_aktif < 1) { ?>
                                    <!-- <td><?php echo "<font color='red'>" . nominal($tbl_pasar_input_tagihan->jumlah_hari) . "</font>" ?></td> -->
                                    <td align="right"><?php
                                                        echo "<font color='red'><strong>";
                                                        tampil(nominal($tbl_pasar->tarifkelaspasar * $tbl_pasar->panjang * $tbl_pasar->lebar));
                                                        echo "</strong></font>";
                                                        echo "<br/>";
                                                        echo "<font color='red'><strong>";
                                                        tampil(nominal($tbl_pasar->tarifkelaspasar));
                                                        echo " x ";
                                                        tampil(nominal($tbl_pasar->panjang));
                                                        echo " x ";
                                                        tampil(nominal($tbl_pasar->lebar));
                                                        echo "</strong></font>";
                                                        ?></td>
                                <?php } else { ?>
                                    <!-- <td><?php echo "<font color='black'>" . nominal($tbl_pasar_input_tagihan->jumlah_hari) . "</font>" ?></td> -->
                                    <td align="right"><?php
                                                        echo "<font color='black'><strong>";
                                                        tampil(nominal($tbl_pasar->tarifkelaspasar * $tbl_pasar->panjang * $tbl_pasar->lebar));
                                                        echo "</strong></font>";
                                                        echo "<br/>";
                                                        echo "<font color='black'><strong>";
                                                        tampil(nominal($tbl_pasar->tarifkelaspasar));
                                                        echo " x ";
                                                        tampil(nominal($tbl_pasar->panjang));
                                                        echo " x ";
                                                        tampil(nominal($tbl_pasar->lebar));
                                                        echo "</strong></font>";
                                                        ?></td>
                                <?php }  ?>




                                <td align="right"><?php
                                                    if ($tbl_pasar->tgl_aktif < $tgl_aktif_bulan_ini) {
                                                        echo "<font color='red'><strong>";
                                                        tampil(nominal($tbl_pasar->denda_per_bulan));
                                                        echo "</strong></font>";
                                                    }
                                                    ?></td>

                                <td align="right"><?php tampil(nominal($tbl_pasar->totaltarifkebersihan)) ?></td>



                                <?php if ($tbl_pasar->tgl_aktif < $tgl_aktif_bulan_ini) { ?>


                                    <td align="right"><?php
                                                        echo "<font color='red'><strong>";
                                                        tampil(nominal(($tbl_pasar->jumlah_hari_aktif * $tbl_pasar->tarifkelaspasar * $tbl_pasar->panjang * $tbl_pasar->lebar) + $tbl_pasar->totaltarifkebersihan + $tbl_pasar->denda_per_bulan));
                                                        echo "</strong></font>";
                                                        ?></td>
                                <?php } else { ?>
                                    <td align="right"><?php
                                                        echo "<font color='black'><strong>";
                                                        tampil(nominal($tbl_pasar->jumlah_hari_aktif * $tbl_pasar->tarifkelaspasar * $tbl_pasar->panjang * $tbl_pasar->lebar + $tbl_pasar->tarifkebersihan));
                                                        echo "</strong></font>";
                                                        ?></td>
                                <?php }  ?>

                                <!-- 
					<td><?php
                            if ($tbl_pasar->tgl_aktif < $tgl_aktif_bulan_ini) {
                                echo nominal($tbl_pasar->totalnominal + $tbl_pasar->denda_per_bulan);
                            } else {
                                echo nominal($tbl_pasar->totalnominal);
                            }
                        ?></td> -->
                                <td></td>

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
                            scrollY: 450,
                            scrollX: true
                        });
                    });
                </script>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
    <!-- /.row -->
</section><!-- /.content -->