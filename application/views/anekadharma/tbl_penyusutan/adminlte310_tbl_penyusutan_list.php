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
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4" align="left">
                                        <div class="row">
                                            DAFTAR AKTIVA TETAP: <br />Harga Perolehan, Depresiasi, Ak Depresiasi, & Nilai Buku
                                        </div>
                                    </div>

                                    <div class="col-6" align="center">
                                        <div class="row">
                                            <div class="col-3" align="left">
                                                <?php
                                                if ($this->session->userdata('id_user_level') == 1 or $this->session->userdata('id_user_level') == 2 or $this->session->userdata('id_user_level') == 9) {
                                                    echo anchor(site_url('Tbl_penyusutan/Input_list_data_baru'), 'INPUT DATA', 'class="btn btn-danger"');
                                                }

                                                ?>

                                            </div>
                                            <div class="col-9" align="center">

                                                <?php
                                                // $action_cari_between_date = site_url('tbl_pembelian/cari_between_date');
                                                $action_cari_between_date = site_url('Tbl_penyusutan/cari_between_date');
                                                ?>

                                                <form action="<?php echo $action_cari_between_date; ?>" method="post">

                                                    <div class="row">
                                                        <div class="col-md-4" text-align="right" align="right">
                                                            <input type="month" id="bulan_ns" name="bulan_ns">
                                                        </div>
                                                        <div class="col-md-2" text-align="left" align="left">
                                                            <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in" aria-hidden="true"></i> Cari</button>
                                                        </div>

                                                    </div>



                                                </form>


                                            </div>
                                        </div>



                                    </div>


                                    <div class="col-2" text-align="left" align="right">
                                        <?php echo anchor(site_url('jurnal_kas/excel'), 'Cetak', 'class="btn btn-success"'); ?>
                                    </div>
                                </div>







                            </div>
                        </div>
                    </div>
                </div>




                <div class="card-body">

                    <!-- <table id="example" class="table table-striped dt-responsive w-100 table-bordered display nowrap table-hover mb-0" style="width:100%"> -->
                    <table id="example9" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" width="10px">No</th>
                                <th style="text-align:center" width="10px">Action</th>
                                <th style="text-align:center">Kelompok/Jenis Harta</th>
                                <th style="text-align:center">Bulan/Tahun <br />Perolehan</th>
                                <th style="text-align:center">Harga Perolehan <br /> Rupiah</th>
                                <th style="text-align:center">User</th>
                                <th style="text-align:center">Amorts Penyst <br />31/12/2023</th>
                                <th style="text-align:center">Nilai Buku<br />31/12/2023</th>
                                <th style="text-align:center">Penyusutan<br />tahun 2024</th>
                                <th style="text-align:center">Amorts Penyst<br />tahun 2024</th>
                                <th style="text-align:center">Nilai Buku<br />tahun 2024</th>
                            </tr>


                        </thead>
                        <tbody>
                            <?php

                            // PEMBELIAN
                            $start = 0;
                            $TOTAL_DEBET = 0;
                            $TOTAL_KREDIT = 0;
                            $TOTAL_SALDO = 0;

                            $GET_GroupName = "group_1";

                            $Total_Harga_Perolehan = 0;
                            $Total_Armost_Penyusutan_tahun_lalu = 0;
                            $TOTAL_Nilai_buku_tahun_lalu = 0;
                            $TOTAL_penyusutan_bulan_ini = 0;
                            $Total_armost_penyusutan_bulan_ini = 0;
                            $TOTAL_Nilai_buku_bulan_ini = 0;

                            $TOTAL_BANGUNAN_TETAP_HARGA_PEROLEHAN = 0;
                            $TOTAL_BANGUNAN_TIDAK_TETAP_HARGA_PEROLEHAN = 0;
                            $TOTAL_INVENTARIS_GOL_1_HARGA_PEROLEHAN = 0;
                            $TOTAL_INVENTARIS_GOL_2_HARGA_PEROLEHAN = 0;
                            $TOTAL_INVENTARIS_GOL_3_HARGA_PEROLEHAN = 0;

                            foreach ($Data_penyusutan as $list_data) {

                                if ($start == 0) {
                                    // GROUP MASIH SAMA
                            ?>

                                    <!-- NAMA GROUP -->

                                    <tr>
                                        <td align="left"><?php echo ++$start; ?></td>
                                        <td align="left"></td>
                                        <td align="left" colspan="3">
                                            <?php

                                            $sql = "SELECT * FROM `sys_group_penyusutan` WHERE `kode_group_penyusutan`='$list_data->group_kelompok_harta' ";
                                            // $GET_Penyusutan_data_RECORD = $this->db->query($sql)->row()->group_penyusutan;

                                            echo "<strong>" . $this->db->query($sql)->row()->group_penyusutan . "</strong>";

                                            $GET_GroupName = $list_data->group_kelompok_harta;
                                            ?>

                                        </td>


                                        <td align="left"></td>
                                        <td align="left"></td>
                                        <td align="left"></td>
                                        <td align="left"></td>
                                        <td align="left"></td>
                                        <td align="left"> </td>
                                        <td align="left"> </td>
                                        <td align="left"> </td>
                                    </tr>

                                    <!-- END OF NAMA GROUP -->





                                    <!-- Record awal group baru -->
                                    <tr>
                                        <td align="left"><?php echo ++$start; ?></td>
                                        <td align="left">
                                            <?php
                                            // echo "Action"; 



                                            echo anchor(site_url('Tbl_penyusutan/update_list_data/' . $list_data->uuid_penyusutan), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-sm"');
                                            echo '  ';
                                            echo anchor(site_url('Tbl_penyusutan/delete/' . $list_data->id), '<i class="fa fa-trash-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');


                                            ?>
                                        </td>
                                        <td align="left">
                                            <?php
                                            // echo $list_data->kelompok_harta;
                                            echo number_format($list_data->kelompok_harta, 2, ',', '.');
                                            $GET_GroupName = $list_data->group_kelompok_harta; //Ubah Variabel group ke GROUP BARU
                                            ?>
                                        </td>
                                        <td align="left">
                                            <?php
                                            if ($list_data->tanggal_perolehan) {
                                                // echo $list_data->tanggal_perolehan; 
                                                echo date("d-M-Y", strtotime($list_data->tanggal_perolehan));
                                            }
                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php
                                            // echo $list_data->harga_perolehan;
                                            echo number_format($list_data->harga_perolehan, 2, ',', '.');
                                            $Total_Harga_Perolehan = $Total_Harga_Perolehan + $list_data->harga_perolehan;
                                            ?>
                                        </td>
                                        <td align="center"><?php echo $list_data->user; ?></td>
                                        <td align="right">
                                            <?php
                                            // echo $list_data->armorst_penyusutan_thn_lalu; 
                                            echo number_format($list_data->armorst_penyusutan_thn_lalu, 2, ',', '.');
                                            $Total_Armost_Penyusutan_tahun_lalu = $Total_Armost_Penyusutan_tahun_lalu + $list_data->armorst_penyusutan_thn_lalu;

                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php
                                            // echo $list_data->nilai_buku_thn_lalu; 
                                            echo number_format($list_data->nilai_buku_thn_lalu, 2, ',', '.');
                                            $TOTAL_Nilai_buku_tahun_lalu = $TOTAL_Nilai_buku_tahun_lalu + $list_data->nilai_buku_thn_lalu;
                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php
                                            // echo $list_data->penyusutan_bulan_ini; 
                                            echo number_format($list_data->penyusutan_bulan_ini, 2, ',', '.');
                                            $TOTAL_penyusutan_bulan_ini = $TOTAL_penyusutan_bulan_ini + $list_data->penyusutan_bulan_ini;
                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php
                                            // echo $list_data->armorst_penyusutan_bulan_ini; 
                                            echo number_format($list_data->armorst_penyusutan_bulan_ini, 2, ',', '.');
                                            $Total_armost_penyusutan_bulan_ini = $Total_armost_penyusutan_bulan_ini + $list_data->armorst_penyusutan_bulan_ini;
                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php
                                            // echo $list_data->nilai_buku_bulan_ini; 
                                            echo number_format($list_data->nilai_buku_bulan_ini, 2, ',', '.');
                                            $TOTAL_Nilai_buku_bulan_ini = $TOTAL_Nilai_buku_bulan_ini + $list_data->nilai_buku_bulan_ini;
                                            ?>
                                        </td>
                                    </tr>






                                <?php
                                } else {
                                    // RECORD KE 3 DST
                                ?>


                                    <?php
                                    if ($GET_GroupName == $list_data->group_kelompok_harta) {
                                    ?>

                                        <!-- Record awal group baru -->
                                        <tr>
                                            <td align="left"><?php echo ++$start; ?></td>
                                            <td align="left">
                                                <?php
                                                // echo "Action"; 

                                                echo anchor(site_url('Tbl_penyusutan/update_list_data/' . $list_data->uuid_penyusutan), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-sm"');
                                                echo '  ';
                                                echo anchor(site_url('Tbl_penyusutan/delete/' . $list_data->id), '<i class="fa fa-trash-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');


                                                ?>
                                            </td>
                                            <td align="left">
                                                <?php
                                                echo $list_data->kelompok_harta;
                                                $GET_GroupName = $list_data->group_kelompok_harta;
                                                ?>
                                            </td>
                                            <td align="left">
                                                <?php
                                                if ($list_data->tanggal_perolehan) {
                                                    // echo $list_data->tanggal_perolehan; 
                                                    echo date("d-M-Y", strtotime($list_data->tanggal_perolehan));
                                                }
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                // echo $list_data->harga_perolehan;
                                                echo number_format($list_data->harga_perolehan, 2, ',', '.');
                                                $Total_Harga_Perolehan = $Total_Harga_Perolehan + $list_data->harga_perolehan;
                                                ?>
                                            </td>
                                            <td align="center"><?php echo $list_data->user; ?></td>
                                            <td align="right">
                                                <?php
                                                // echo $list_data->armorst_penyusutan_thn_lalu; 
                                                echo number_format($list_data->armorst_penyusutan_thn_lalu, 2, ',', '.');
                                                $Total_Armost_Penyusutan_tahun_lalu = $Total_Armost_Penyusutan_tahun_lalu + $list_data->armorst_penyusutan_thn_lalu;
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                // echo $list_data->nilai_buku_thn_lalu; 
                                                echo number_format($list_data->nilai_buku_thn_lalu, 2, ',', '.');
                                                $TOTAL_Nilai_buku_tahun_lalu = $TOTAL_Nilai_buku_tahun_lalu + $list_data->nilai_buku_thn_lalu;
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                // echo $list_data->penyusutan_bulan_ini; 
                                                echo number_format($list_data->penyusutan_bulan_ini, 2, ',', '.');
                                                $TOTAL_penyusutan_bulan_ini = $TOTAL_penyusutan_bulan_ini + $list_data->penyusutan_bulan_ini;
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                // echo $list_data->armorst_penyusutan_bulan_ini; 
                                                echo number_format($list_data->armorst_penyusutan_bulan_ini, 2, ',', '.');
                                                $Total_armost_penyusutan_bulan_ini = $Total_armost_penyusutan_bulan_ini + $list_data->armorst_penyusutan_bulan_ini;
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                // echo $list_data->nilai_buku_bulan_ini; 
                                                echo number_format($list_data->nilai_buku_bulan_ini, 2, ',', '.');
                                                $TOTAL_Nilai_buku_bulan_ini = $TOTAL_Nilai_buku_bulan_ini + $list_data->nilai_buku_bulan_ini;
                                                ?>
                                            </td>
                                        </tr>


                                    <?php
                                    } else {


                                    ?>


                                        <!-- TOTAL PER GROUP SEBELUM DATA GROUP BARU -->

                                        <tr>
                                            <td align="left"><?php echo ++$start; ?></td>
                                            <td align="left"><?php //echo "Action"; 
                                                                ?></td>
                                            <td align="left">
                                                <!-- TOTAL -->
                                            </td>

                                            <td align="right"></td>
                                            <td align="right">
                                                <?php
                                                // echo $Total_Harga_Perolehan;
                                                echo "<strong>" . number_format($Total_Harga_Perolehan, 2, ',', '.') . "</strong>";

                                                if ($GET_GroupName == "group_1") {
                                                    $TOTAL_BANGUNAN_TETAP_HARGA_PEROLEHAN = $Total_Harga_Perolehan;
                                                } else if ($GET_GroupName == "group_2") {
                                                    $TOTAL_BANGUNAN_TIDAK_TETAP_HARGA_PEROLEHAN = $Total_Harga_Perolehan;
                                                } else if ($GET_GroupName == "group_3") {
                                                    $TOTAL_INVENTARIS_GOL_1_HARGA_PEROLEHAN = $Total_Harga_Perolehan;
                                                } else if ($GET_GroupName == "group_4") {
                                                    $TOTAL_INVENTARIS_GOL_2_HARGA_PEROLEHAN = $Total_Harga_Perolehan;
                                                } else if ($GET_GroupName == "group_5") {
                                                    $TOTAL_INVENTARIS_GOL_3_HARGA_PEROLEHAN = $Total_Harga_Perolehan;
                                                }






                                                $Total_Harga_Perolehan = 0;
                                                ?>
                                            </td>
                                            <td align="right"></td>
                                            <td align="right">
                                                <?php
                                                // echo $Total_Armost_Penyusutan_tahun_lalu;
                                                echo "<strong>" . number_format($Total_Armost_Penyusutan_tahun_lalu, 2, ',', '.') . "</strong>";
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                // echo $TOTAL_Nilai_buku_tahun_lalu;
                                                echo "<strong>" . number_format($TOTAL_Nilai_buku_tahun_lalu, 2, ',', '.') . "</strong>";
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                // echo $TOTAL_penyusutan_bulan_ini;
                                                echo "<strong>" . number_format($TOTAL_penyusutan_bulan_ini, 2, ',', '.') . "</strong>";
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                // echo $Total_armost_penyusutan_bulan_ini;
                                                echo "<strong>" . number_format($Total_armost_penyusutan_bulan_ini, 2, ',', '.') . "</strong>";
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                // echo $TOTAL_Nilai_buku_bulan_ini;
                                                echo "<strong>" . number_format($TOTAL_Nilai_buku_bulan_ini, 2, ',', '.') . "</strong>";
                                                ?>
                                            </td>
                                        </tr>


                                        <!-- END OF TOTAL PER GROUP SEBELUM DATA GROUP BARU -->

                                        <!-- NAMA GROUP -->
                                        <tr>
                                            <td align="left"><?php echo ++$start; ?></td>
                                            <td align="left"><?php //echo "Action"; 
                                                                ?></td>
                                            <td align="left" colspan="3">
                                                <?php
                                                // echo "<strong>" . $list_data->group_kelompok_harta . "</strong>";

                                                $sql = "SELECT * FROM `sys_group_penyusutan` WHERE `kode_group_penyusutan`='$list_data->group_kelompok_harta' ";
                                                // $GET_Penyusutan_data_RECORD = $this->db->query($sql)->row()->group_penyusutan;

                                                echo "<strong>" . $this->db->query($sql)->row()->group_penyusutan . "</strong>";


                                                $GET_GroupName = $list_data->group_kelompok_harta;
                                                ?>
                                            </td>


                                            <td align="left"><?php //echo $list_data->user; 
                                                                ?></td>
                                            <td align="left"><?php //echo $list_data->armorst_penyusutan_thn_lalu; 
                                                                ?></td>
                                            <td align="left"><?php //echo $list_data->nilai_buku_thn_lalu; 
                                                                ?></td>
                                            <td align="left"><?php //echo $list_data->penyusutan_bulan_ini; 
                                                                ?></td>
                                            <td align="left"><?php //echo $list_data->armorst_penyusutan_bulan_ini; 
                                                                ?></td>
                                            <td align="left"></td>
                                            <td align="left"></td>
                                            <td align="left"></td>
                                        </tr>

                                        <!-- END OF NAMA GROUP -->


                                        <!-- Record awal group baru -->
                                        <tr>
                                            <td align="left"><?php echo ++$start; ?></td>
                                            <td align="left">
                                                <?php
                                                // echo "Action"; 

                                                echo anchor(site_url('Tbl_penyusutan/update_list_data/' . $list_data->uuid_penyusutan), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-sm"');
                                                echo '  ';
                                                echo anchor(site_url('Tbl_penyusutan/delete/' . $list_data->id), '<i class="fa fa-trash-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');


                                                ?>
                                            </td>
                                            <td align="left">
                                                <?php
                                                echo $list_data->kelompok_harta;
                                                $GET_GroupName = $list_data->group_kelompok_harta;
                                                ?>
                                            </td>
                                            <td align="left">
                                                <?php
                                                if ($list_data->tanggal_perolehan) {
                                                    // echo $list_data->tanggal_perolehan; 
                                                    echo date("d-M-Y", strtotime($list_data->tanggal_perolehan));
                                                }
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                // echo $list_data->harga_perolehan;
                                                echo number_format($list_data->harga_perolehan, 2, ',', '.');
                                                $Total_Harga_Perolehan = $Total_Harga_Perolehan + $list_data->harga_perolehan;
                                                ?>
                                            </td>
                                            <td align="center"><?php echo $list_data->user; ?></td>
                                            <td align="right">
                                                <?php
                                                // echo $list_data->armorst_penyusutan_thn_lalu; 
                                                echo number_format($list_data->armorst_penyusutan_thn_lalu, 2, ',', '.');
                                                $Total_Armost_Penyusutan_tahun_lalu = $Total_Armost_Penyusutan_tahun_lalu + $list_data->armorst_penyusutan_thn_lalu;
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                // echo $list_data->nilai_buku_thn_lalu; 
                                                echo number_format($list_data->nilai_buku_thn_lalu, 2, ',', '.');
                                                $TOTAL_Nilai_buku_tahun_lalu = $TOTAL_Nilai_buku_tahun_lalu + $list_data->nilai_buku_thn_lalu;
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                // echo $list_data->penyusutan_bulan_ini; 
                                                echo number_format($list_data->penyusutan_bulan_ini, 2, ',', '.');
                                                $TOTAL_penyusutan_bulan_ini = $TOTAL_penyusutan_bulan_ini + $list_data->penyusutan_bulan_ini;
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                // echo $list_data->armorst_penyusutan_bulan_ini; 
                                                echo number_format($list_data->armorst_penyusutan_bulan_ini, 2, ',', '.');
                                                $Total_armost_penyusutan_bulan_ini = $Total_armost_penyusutan_bulan_ini + $list_data->armorst_penyusutan_bulan_ini;
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                // echo $list_data->nilai_buku_bulan_ini; 
                                                echo number_format($list_data->nilai_buku_bulan_ini, 2, ',', '.');
                                                $TOTAL_Nilai_buku_bulan_ini = $TOTAL_Nilai_buku_bulan_ini + $list_data->nilai_buku_bulan_ini;
                                                ?>
                                            </td>
                                        </tr>

                                <?php
                                    }
                                }

                                ?>


                            <?php
                            }
                            ?>





                        </tbody>


                    </table>
                </div>
                <!-- /.card-body -->


                <div class="row">
                    <div class="col-12">
                        <div class="card-body">
                            <div class="card-body">

                                <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center" width="10px">REKAP <?php echo date('Y'); ?></th>
                                            <th style="text-align:center">HARGA PEROLEHAN / <?php echo date('Y'); ?></th>
                                            <th style="text-align:center">PENYUSUTAN</th>
                                            <th style="text-align:center">AMORTISASI</th>
                                            <th style="text-align:center">NILAI BUKU</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // foreach ($Tbl_pembelian_data as $list_data) {
                                        ?>
                                            <tr>
                                                <td>BANGUNAN TETAP</td>
                                                <td align="right">
                                                    <?php 
                                                    // echo $TOTAL_BANGUNAN_TETAP_HARGA_PEROLEHAN 
                                                    echo number_format($TOTAL_BANGUNAN_TETAP_HARGA_PEROLEHAN, 2, ',', '.');
                                                    ?>
                                                    </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>

                                            </tr>
                                            <tr>
                                                <td>BANGUNAN TIDAK TETAP</td>
                                                <td align="right">
                                                    <?php 
                                                    // echo $TOTAL_BANGUNAN_TIDAK_TETAP_HARGA_PEROLEHAN; 
                                                    echo number_format($TOTAL_BANGUNAN_TIDAK_TETAP_HARGA_PEROLEHAN, 2, ',', '.');
                                                    ?>
                                                    </td>



                                                <td></td>
                                                <td></td>
                                                <td></td>

                                            </tr>
                                            <tr>
                                                <td>INVENTARIS GOL.1</td>
                                                <td align="right">
                                                    <?php 
                                                    // echo $TOTAL_INVENTARIS_GOL_1_HARGA_PEROLEHAN; 
                                                    echo number_format($TOTAL_INVENTARIS_GOL_1_HARGA_PEROLEHAN, 2, ',', '.');
                                                    ?>
                                                    </td>




                                                <td></td>
                                                <td></td>
                                                <td></td>

                                            </tr>
                                            <tr>
                                                <td>INVENTARIS GOL.2</td>
                                                <td align="right">
                                                    <?php 
                                                    // echo $TOTAL_INVENTARIS_GOL_2_HARGA_PEROLEHAN; 
                                                    echo number_format($TOTAL_INVENTARIS_GOL_2_HARGA_PEROLEHAN, 2, ',', '.');
                                                    ?>
                                                    </td>




                                                <td></td>
                                                <td></td>
                                                <td></td>

                                            </tr>
                                            <tr>
                                                <td>INVENTARIS GOL.3</td>
                                                <td align="right">
                                                    <?php 
                                                    // echo $TOTAL_INVENTARIS_GOL_3_HARGA_PEROLEHAN; 
                                                    echo number_format($TOTAL_INVENTARIS_GOL_3_HARGA_PEROLEHAN, 2, ',', '.');
                                                    ?>
                                                    </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>

                                            </tr>
                                        <?php
                                        // }
                                        ?>



                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>

                                    </tfoot>

                                </table>
                            </div>
                        </div>
                    </div>
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
            "scrollY": 650,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 700,
            "scrollX": true
        });
    });
</script>