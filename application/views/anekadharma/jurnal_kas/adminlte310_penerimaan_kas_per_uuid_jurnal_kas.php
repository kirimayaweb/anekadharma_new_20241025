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
                                        <div class="col-12" text-align="center"> <strong>INPUT KODE AKUN JURNAL PENERIMAAN</strong></div>
                                    </div>
                                    <div class="col-2" align="left">
                                        <?php //echo anchor(site_url('jurnal_kas/pemasukan_kas'), 'Pemasukan Kas', 'class="btn btn-danger"');
                                        ?>

                                    </div>
                                    <div class="col-2" align="left">

                                        <?php //echo anchor(site_url('jurnal_kas/pengeluaran_kas'), 'Pengeluaran Kas', 'class="btn btn-success"');
                                        ?>
                                    </div>
                                    <div class="col-4" align="right">

                                        <?php //echo anchor(site_url('jurnal_kas/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); 
                                        ?>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>



                    <div class="card-body">

                        <form action="<?php echo $action; ?>" method="post">
                            <div class="row">

                                <div class="col-3">
                                    <label for="supplier_nama">Atur Kode Akun : <strong><?php echo "SPOP:" . $spop; ?> </strong></label>
                                    <!-- <textarea class="form-control" rows="3" name="supplier_nama" id="supplier_nama" placeholder="Supplier Nama"><?php //echo $supplier_nama; 
                                                                                                                                                        ?></textarea> -->


                                    <select name="kode_akun" id="kode_akun" class="form-control select2" style="width: 100%; height: 40px;" required>

                                        <?php

                                        if ($get_kode_akun) {
                                            // Get Nama akun dari kode akun

                                            $sql = "SELECT * FROM `sys_kode_akun` WHERE `kode_akun`='$get_kode_akun'";
                                            $Get_nama_akun = $this->db->query($sql)->row()->nama_akun

                                        ?>
                                            <option value="<?php echo $get_kode_akun; ?>"><?php echo $get_kode_akun . " ==> " . $Get_nama_akun; ?></option>
                                        <?php
                                        } else {
                                        ?>
                                            <option value="">Pilih Kode Akun</option>
                                        <?php
                                        }
                                        ?>


                                        <?php

                                        $sql = "select * from sys_kode_akun order by kode_akun ASC";


                                        foreach ($this->db->query($sql)->result() as $m) {
                                            // foreach ($data_produk as $m) {
                                            echo "<option value='$m->kode_akun' ";
                                            echo ">  " . strtoupper($m->kode_akun)  . " ==> " . strtoupper($m->nama_akun)  . "</option>";
                                        }
                                        ?>
                                    </select>


                                </div>

                                <div class="col-4">

                                </div>

                                <div class="col-2">
                                    <?php //echo anchor(site_url('tbl_pembelian/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); 
                                    ?>



                                </div>

                            </div>

                            <div class="row">

                                <div class="col-3">

                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                        <!-- <button type="button" class="btn btn-primary">Simpan</button> -->
                                        <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                    </div>
                                </div>
                            </div>

                        </form>
                        <hr>

                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <!-- <th style="text-align:left" width="10px">No</th> -->
                                    <th rowspan="3" style="text-align:left" width="10px">Tanggal</th>
                                    <th rowspan="3" style="text-align:center">Kode Akun</th>
                                    <th rowspan="3" style="text-align:center">No. Bukti BKM</th>
                                    <th rowspan="3" style="text-align:center">PL</th>
                                    <th rowspan="3" style="text-align:center">KETERANGAN</th>
                                    <th colspan="1" style="text-align:right">Debit</th>


                                    <th colspan="3" style="text-align:center">KREDIT</th>
                                <tr>
                                    <th rowspan="2" style="text-align:right">11101-Kas Besar</th>
                                    <th rowspan="2" style="text-align:center">11301-PU <br />Non Angsuran</th>
                                    <th colspan="2" style="text-align:center">Serba-Serbi</th>
                                </tr>
                                <tr>
                                    <th rowspan="3" style="text-align:center">Rek</th>
                                    <th style="text-align:center">Jumlah</th>
                                </tr>

                                <!-- <th style="text-align:center">Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                $TOTAL_debet = 0;
                                $TOTAL_kredit = 0;
                                $TOTAL_saldo = 0;
                                foreach ($Data_kas as $list_data) {
                                    // [0] => stdClass Object ( [nomor] => 4280 [tanggal] => 30/09/2024 [bukti] => BKK [keterangan] => Biaya PU/ATK : Putro Bengkel (Pembayaran SPOP No 558 Tgl 30/09/2024) [kode_rekening] => 4 [debet] => [kredit] => 1.750.000,00 )
                                ?>

                                    <tr>
                                        <!-- <td><?php
                                                    //echo ++$start;
                                                    ?>
                                            </td>-->

                                        <td>
                                            <?php
                                            echo date("d-m-Y", strtotime($list_data->tanggal));
                                            // echo "<br/>";

                                            // if ($list_data->debet > 0) {
                                            //     // Ubah debet
                                            //     echo anchor(site_url('Jurnal_kas/pemasukan_kas_update/' . $list_data->id), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));
                                            // } else {
                                            //     // Ubah Kredit
                                            //     echo anchor(site_url('Jurnal_kas/pengeluaran_kas_update/' . $list_data->id), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));
                                            // }

                                            // echo ' ';
                                            // echo anchor(site_url('jurnal_kas/delete/' . $list_data->id), '<i class="fa fa-trash-o">Hapus</i>', 'title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Anda Yakin akan menghapus data ini ?\')"');


                                            // `, `uuid_jurnal_kas`, `tanggal`, `bukti`, `keterangan`, `kode_rekening`, `debet`, `kredit`
                                            ?>
                                        </td>

                                        <!-- Kode Akun -->
                                        <td  align="center"><?php
                                            // echo "Kode Akun";
                                            if ($list_data->kode_akun) {
                                                echo $list_data->kode_akun;
                                                // echo "<br/>";
                                                // echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                            } else {
                                                // echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                            }
                                            ?>
                                        </td>

                                        <td><?php
                                            echo $list_data->bukti;
                                            ?>
                                        </td>
                                        <td align="left">
                                            <?php
                                            echo $list_data->keterangan;
                                            ?>
                                        </td>
                                        <td align="left">
                                            <?php
                                            echo $list_data->kode_rekening;
                                            ?>
                                        </td>

                                        <!-- Debet -->
                                        <td style="text-align:right">
                                            <?php
                                            if ($list_data->debet > 0) {
                                                echo number_format($list_data->debet, 2, ',', '.');
                                                $TOTAL_debet = $TOTAL_debet + $list_data->debet;
                                            } else {
                                                echo "";
                                            }
                                            ?>
                                        </td>

                                        <!-- Kredit -->
                                        <td style="text-align:right">
                                            <?php
                                            // if ($list_data->kredit > 0) {
                                            //     echo number_format($list_data->kredit, 2, ',', '.');
                                            //     $TOTAL_kredit = $TOTAL_kredit + $list_data->kredit;
                                            // } else {
                                            //     echo "";
                                            // }

                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            // echo number_format($TOTAL_debet - $TOTAL_kredit, 2, ',', '.');
                                            ?>
                                        </td>
                                        <td style="text-align:right">
                                            <?php
                                            if ($list_data->debet > 0) {
                                                echo number_format($list_data->debet, 2, ',', '.');
                                                $TOTAL_debet = $TOTAL_debet + $list_data->debet;
                                            } else {
                                                echo "";
                                            }
                                            ?>
                                        </td>

                                    </tr>

                                <?php
                                }
                                ?>

                            </tbody>

                            <!-- tfoot -->

                            <!-- <tfoot>
                                <tr>

                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:right">

                                    </th>
                                    <th style="text-align:right">

                                    </th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>

                                </tr>

                            </tfoot> -->



                            <!-- end of tfoot -->


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