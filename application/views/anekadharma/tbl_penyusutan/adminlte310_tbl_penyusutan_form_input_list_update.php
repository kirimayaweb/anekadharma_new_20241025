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
                <div class="card card-warning">
                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>
                                            <?php

                                            if ($button == "Simpan") {
                                            ?>
                                                Input List Baru data
                                            <?php
                                            } else {
                                            ?>
                                                Update data
                                            <?php
                                            }
                                            ?>

                                        </strong></div>

                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>



                    </div>
                    <br />



                    <div class="card-body">


                        <!-- <form action="<?php //echo $action; 
                                            ?>" method="post"> -->



                        <form action="<?php echo $action; ?>" method="post">



                            <div class="form-group">

                                <div class="row">


                                    <div class="col-4"></div>
                                    <div class="col-4"></div>

                                </div>

                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-4">

                                        <label for="konsumen_nama">Tahun - Bulan</label>

                                        <?php
                                        // $action_cari_bulan_data = site_url('Tbl_penyusutan/Simpan_Input_list_data_baru_action');
                                        ?>

                                        <!-- <form action="<?php //echo $action_cari_bulan_data; 
                                                            ?>" method="post"> -->
                                        <div class="row">
                                            <div class="col-md-4" text-align="right" align="right">
                                                <input type="month" id="bulan_ns" name="bulan_ns">
                                            </div>
                                            <!-- <div class="col-md-2" text-align="left" align="left">
                                                <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in" aria-hidden="true"></i> Cari</button>
                                            </div> -->

                                        </div>
                                        <!-- </form> -->


                                    </div>

                                    <div class="col-4">
                                        <label for="konsumen_nama">Group</label>
                                        <select name="group_kelompok_harta" id="group_kelompok_harta" class="form-control select2" style="width: 100%; height: 40px;" required>

                                            <?php
                                            if ($group_kelompok_harta) {
                                                $sql = "select * from sys_group_penyusutan where kode_group_penyusutan='$group_kelompok_harta' ";
                                                // $detail_group_penyusutn =$this->db->query($sql)->row()->group_penyusutan;    

                                            ?>

                                                <option value="<?php echo $group_kelompok_harta; ?>"><?php echo $this->db->query($sql)->row()->group_penyusutan; ?></option>

                                            <?php
                                            } else {
                                            ?>
                                                <option value="">Pilih Group</option>
                                            <?php

                                            }
                                            $sql = "select * from sys_group_penyusutan order by kode_group_penyusutan ASC ";
                                            foreach ($this->db->query($sql)->result() as $m) {
                                                echo "<option value='$m->kode_group_penyusutan' ";
                                                echo ">  " . strtoupper($m->group_penyusutan)  .  "</option>";
                                            }

                                            ?>
                                        </select>


                                    </div>

                                    <div class="col-4">
                                        <label for="KlmpkJenisHarta">Kelompok / Jenis Harta </label>
                                        <input type="text" class="form-control" rows="3" name="KlmpkJenisHarta" id="KlmpkJenisHarta" placeholder="Kelompok Jenis Harta" value="<?php echo $kelompok_harta; ?>" required>
                                    </div>
                                    <!-- 
                                    <div class="col-4">
                                        <label for="HargaPerolehan">Harga Perolehan <?php //echo form_error('nmrkirim') 
                                                                                    ?></label>
                                        <input type="text" class="form-control" rows="3" name="HargaPerolehan" id="HargaPerolehan" placeholder="Harga Perolehan" required>
                                    </div>
 -->


                                </div>

                            </div>

                            <div class="form-group">
                                <div class="row">

                                    <div class="col-4">

                                        <label for="date">Tanggal Perolehan <?php echo form_error('tanggal_perolehan') ?></label>

                                        <?php

                                        if ($tanggal_perolehan) {
                                            $get_tanggal = date("d-m-Y", strtotime($tanggal_perolehan));
                                        } else {
                                            $get_tanggal = date("Y-m-d H:i:s");
                                        }

                                        ?>

                                        <div class="input-group date" id="tanggal" name="tanggal" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#tgl_po" id="tgl_po" name="tanggal" value="<?php echo date("d-m-Y", strtotime($get_tanggal)); ?>" required />
                                            <div class="input-group-append" data-target="#tgl_po" data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>

                                            </div>


                                        </div>

                                    </div>

                                    <div class="col-4">
                                        <label for="HargaPerolehan">Harga Perolehan <?php //echo form_error('nmrkirim') 
                                                                                    ?></label>
                                        <input type="text" class="form-control" rows="3" name="HargaPerolehan" id="HargaPerolehan" placeholder="Harga Perolehan" value="<?php 
                                        // echo $harga_perolehan; 
                                        echo number_format($harga_perolehan, 2, ',', '.');
                                        ?>" required>
                                    </div>

                                    <div class="col-4">
                                        <label for="User">User <?php //echo form_error('nmrkirim') 
                                                                ?></label>
                                        <input type="text" class="form-control" rows="3" name="User" id="User" placeholder="User" value="<?php echo $user; ?>" required>
                                    </div>


                                </div>

                            </div>

                            <div class="form-group">
                                <div class="row">

                                    <div class="col-4">
                                        <label for="AmortisasiPenyusutanTahunLalu">Amortisasi Penyusutan tahun lalu</label>
                                        <input type="text" class="form-control" rows="3" name="AmortisasiPenyusutanTahunLalu" id="AmortisasiPenyusutanTahunLalu" placeholder="Amortisasi Penyusutan Tahun Lalu" value="
                                        <?php 
                                        // echo $armorst_penyusutan_thn_lalu; 
                                        echo number_format($armorst_penyusutan_thn_lalu, 2, ',', '.');
                                        ?>" required>
                                    </div>

                                    <div class="col-4">
                                        <label for="NilaiBukuTahunLalu">Nilai Buku Bulan lalu<?php //echo form_error('nmrkirim') 
                                                                                                ?></label>
                                        <input type="text" class="form-control" rows="3" name="NilaiBukuTahunLalu" id="NilaiBukuTahunLalu" placeholder="Nilai Buku Tahun Lalu" value="
                                        <?php 
                                        // echo $nilai_buku_thn_lalu; 
                                        echo number_format($nilai_buku_thn_lalu, 2, ',', '.');
                                        ?>" required>
                                    </div>


                                </div>

                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-4">
                                        <label for="Penyusutan">Penyusutan <?php //echo form_error('nmrkirim') 
                                                                            ?></label>
                                        <input type="text" class="form-control" rows="3" name="Penyusutan" id="Penyusutan" placeholder="Penyusutan" value="
                                        <?php 
                                        // echo $penyusutan_bulan_ini; 
                                        echo number_format($penyusutan_bulan_ini, 2, ',', '.');
                                        ?>" required>
                                    </div>

                                    <div class="col-4">
                                        <label for="AmortisasiPenyusutanTahunIni">Amortisasi Penyusutan Tahun Ini</label>
                                        <input type="text" class="form-control" rows="3" name="AmortisasiPenyusutanTahunIni" id="AmortisasiPenyusutanTahunIni" placeholder="Amortisasi Penyusutan Tahun Ini" value="
                                        <?php 
                                        // echo $armorst_penyusutan_bulan_ini; 
                                        echo number_format($armorst_penyusutan_bulan_ini, 2, ',', '.');
                                        ?>" required>
                                    </div>

                                    <div class="col-4">
                                        <label for="nilaibukubulanini">Nilai Buku <?php //echo form_error('nmrkirim') 
                                                                                    ?></label>
                                        <input type="text" class="form-control" rows="3" name="nilaibukubulanini" id="nilaibukubulanini" placeholder="Nilai Buku Bulan Ini" value="
                                        <?php 
                                        // echo $nilai_buku_bulan_ini; 
                                        echo number_format($nilai_buku_bulan_ini, 2, ',', '.');
                                        ?>" required>
                                    </div>


                                </div>

                            </div>



                            <!-- <div class="form-group">
                                <div class="row">
                                    <div class="col-12" align="center">
                                        <button type="submit" class="btn btn-success" data-toggle="modal" data-target="#modal-xl">
                                            Simpan Data Baru
                                        </button>
                                    </div>
                                </div>
                            </div> -->


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12" align="center">
                                        <!-- <input type="hidden" name="id" value="<?php //echo $id; 
                                                                                    ?>" /> -->
                                        <button type="submit" class="btn btn-primary"><?php echo $button; ?></button> 
                                        <a href="<?php echo site_url('Tbl_penyusutan') ?>" class="btn btn-default">Cancel</a>
                                    </div>
                                </div>
                            </div>


                        </form>


                    </div>

                    <!-- /.card-body -->
                </div>

            </div>

        </div>
    </section>
</div>





<!-- ============== -->




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