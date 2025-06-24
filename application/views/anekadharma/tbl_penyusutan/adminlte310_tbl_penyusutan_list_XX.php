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


        <?php



        $Get_month_from_date = $month_selected;
        $Get_year_Tahun_ini = $year_selected;
        $Get_year_Setahun_lalu = date("Y", strtotime('-1 year'));


        // echo $date_awal; 
        // echo "<br/>";

        if (date("Y", strtotime($date_awal)) < 2020) {
            $Get_date_awal = date("d-m-Y");
        } else {
            $Get_date_awal = date("d-m-Y", strtotime($date_awal));
        }

        // echo $Get_date_awal;
        // echo "<br/>";
        // echo "<br/>";


        // echo $date_akhir; 
        // echo "<br/>";

        if (date("Y", strtotime($date_akhir)) < 2020) {
            $Get_date_akhir = date("d-m-Y");
        } else {
            $Get_date_akhir = date("d-m-Y", strtotime($date_akhir));
        }

        // echo $Get_date_akhir;
        // echo "<br/>";
        // echo "<br/>";

        function bulan_teks($angka_bulan)
        {
            if ($angka_bulan == 1) {
                $bulan_teks = "Januari";
            } elseif ($angka_bulan == 2) {
                $bulan_teks = "Februari";
            } elseif ($angka_bulan == 3) {
                $bulan_teks = "Maret";
            } elseif ($angka_bulan == 4) {
                $bulan_teks = "April";
            } elseif ($angka_bulan == 5) {
                $bulan_teks = "Mei";
            } elseif ($angka_bulan == 6) {
                $bulan_teks = "Juni";
            } elseif ($angka_bulan == 7) {
                $bulan_teks = "Juli";
            } elseif ($angka_bulan == 8) {
                $bulan_teks = "Agustus";
            } elseif ($angka_bulan == 9) {
                $bulan_teks = "September";
            } elseif ($angka_bulan == 10) {
                $bulan_teks = "Oktober";
            } elseif ($angka_bulan == 11) {
                $bulan_teks = "November";
            } elseif ($angka_bulan == 12) {
                $bulan_teks = "Desember";
            } else {
                $bulan_teks = "";
            }
            return $bulan_teks;
        }

        ?>



        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-12">
                                <div class="row">

                                    <div class="col-3" align="left">
                                        <div class="col-12" text-align="center"> <strong>JURNAL KAS</strong> <?php echo bulan_teks($Get_month_from_date) . " " . $Get_year_Tahun_ini ?> </div>
                                    </div>

                                    <div class="col-2" align="left">



                                        <?php
                                        if ($this->session->userdata('id_user_level') == 1 or $this->session->userdata('id_user_level') == 2 or $this->session->userdata('id_user_level') == 9) {
                                            echo anchor(site_url('jurnal_kas/pemasukan_kas'), 'INPUT DATA', 'class="btn btn-danger"');
                                        }

                                        ?>

                                        <?php //echo anchor(site_url('jurnal_kas/pengeluaran_kas'), 'Kredit ( BKK )', 'class="btn btn-success"');
                                        ?>

                                    </div>
                                    <!-- <div class="col-2" align="left">

                                        <?php //echo anchor(site_url('jurnal_kas/pengeluaran_kas'), 'Pengeluaran Kas', 'class="btn btn-success"');
                                        ?>
                                    </div> -->

                                    <div class="col-6" align="right">

                                        <!-- <?php
                                                // $action_cari_between_date = site_url('Jurnal_kas/cari_between_date');
                                                ?>

                                        <form action="<?php //echo $action_cari_between_date; 
                                                        ?>" method="post">
                                            <div class="row">

                                                <div class="col-md-1" text-align="right" align="right"></div>

                                                <div class="col-md-3" text-align="right">
                                                    <div class="input-group date" id="tgl_awal" name="tgl_awal" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_awal" id="tgl_awal" name="tgl_awal" value="<?php echo $Get_date_awal; ?>" required />
                                                        <div class="input-group-append" data-target="#tgl_awal" data-toggle="datetimepicker">
                                                            <div class="input-group-text">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-1" text-align="center" align="center">s/d</div>

                                                <div class="col-md-3" text-align="left" align="left">
                                                    <div class="input-group date" id="tgl_akhir" name="tgl_akhir" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_akhir" id="tgl_akhir" name="tgl_akhir" value="<?php echo $Get_date_akhir; ?>" required />
                                                        <div class="input-group-append" data-target="#tgl_akhir" data-toggle="datetimepicker">
                                                            <div class="input-group-text">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-2" text-align="left" align="left">
                                                    <strong>
                                                        <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in" aria-hidden="true"></i> Cari</button>
                                                    </strong>
                                                </div>

                                            </div>
                                        </form>


                                        <br /> -->


                                        <?php
                                        $action_cari_between_date = site_url('Jurnal_kas/cari_between_date');
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

                                    <div class="col-1" align="right">

                                        <?php echo anchor(site_url('jurnal_kas/excel'), 'Cetak', 'class="btn btn-success"'); ?>
                                    </div>


                                </div>
                            </div>

                        </div>

                    </div>



                    <div class="card-body">

                         <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
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

                            foreach ($Data_penyusutan as $list_data) {

                                if ($start == 0) {
                                    // GROUP MASIH SAMA
                            ?>

                                    <!-- NAMA GROUP -->

                                    <tr>
                                        <td align="left"><?php echo ++$start; ?></td>
                                        <td align="left"><?php echo "Action"; ?></td>
                                        <td align="left" colspan="3">
                                            <?php

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
                                        <td align="left"><?php //echo $list_data->nilai_buku_bulan_ini; 
                                                            ?></td>
                                    </tr>

                                    <!-- END OF NAMA GROUP -->





                                    <!-- Record awal group baru -->
                                    <tr>
                                        <td align="left"><?php echo ++$start; ?></td>
                                        <td align="left"><?php echo "Action"; ?></td>
                                        <td align="left">
                                            <?php
                                            echo $list_data->kelompok_harta;
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
                                        <td align="left"><?php echo $list_data->harga_perolehan; ?></td>
                                        <td align="left"><?php echo $list_data->user; ?></td>
                                        <td align="left"><?php echo $list_data->armorst_penyusutan_thn_lalu; ?></td>
                                        <td align="left"><?php echo $list_data->nilai_buku_thn_lalu; ?></td>
                                        <td align="left"><?php echo $list_data->penyusutan_bulan_ini; ?></td>
                                        <td align="left"><?php echo $list_data->armorst_penyusutan_bulan_ini; ?></td>
                                        <td align="left"><?php echo $list_data->nilai_buku_bulan_ini; ?></td>
                                    </tr>






                                <?php
                                } else {
                                    // GROUP BERBEDA
                                ?>


                                    <?php
                                    if ($GET_GroupName == $list_data->group_kelompok_harta) {
                                    ?>

                                        <!-- Record awal group baru -->
                                        <tr>
                                            <td align="left"><?php echo ++$start; ?></td>
                                            <td align="left"><?php echo "Action"; ?></td>
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
                                            <td align="left"><?php echo $list_data->harga_perolehan; ?></td>
                                            <td align="left"><?php echo $list_data->user; ?></td>
                                            <td align="left"><?php echo $list_data->armorst_penyusutan_thn_lalu; ?></td>
                                            <td align="left"><?php echo $list_data->nilai_buku_thn_lalu; ?></td>
                                            <td align="left"><?php echo $list_data->penyusutan_bulan_ini; ?></td>
                                            <td align="left"><?php echo $list_data->armorst_penyusutan_bulan_ini; ?></td>
                                            <td align="left"><?php echo $list_data->nilai_buku_bulan_ini; ?></td>
                                        </tr>


                                    <?php
                                    } else {


                                    ?>


                                        <!-- NAMA GROUP -->
                                        <tr>
                                            <td align="left"><?php echo ++$start; ?></td>
                                            <td align="left"><?php echo "Action"; ?></td>
                                            <td align="left"  colspan="3">
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
                                            <td align="left"><?php //echo $list_data->nilai_buku_bulan_ini; 
                                                                ?></td>
                                        </tr>

                                        <!-- END OF NAMA GROUP -->


                                        <!-- Record awal group baru -->
                                        <tr>
                                            <td align="left"><?php echo ++$start; ?></td>
                                            <td align="left"><?php echo "Action"; ?></td>
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
                                            <td align="left"><?php echo $list_data->harga_perolehan; ?></td>
                                            <td align="left"><?php echo $list_data->user; ?></td>
                                            <td align="left"><?php echo $list_data->armorst_penyusutan_thn_lalu; ?></td>
                                            <td align="left"><?php echo $list_data->nilai_buku_thn_lalu; ?></td>
                                            <td align="left"><?php echo $list_data->penyusutan_bulan_ini; ?></td>
                                            <td align="left"><?php echo $list_data->armorst_penyusutan_bulan_ini; ?></td>
                                            <td align="left"><?php echo $list_data->nilai_buku_bulan_ini; ?></td>
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
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>