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
            "scrollY": 1100,
            "scrollX": true
        });
    });
</script>