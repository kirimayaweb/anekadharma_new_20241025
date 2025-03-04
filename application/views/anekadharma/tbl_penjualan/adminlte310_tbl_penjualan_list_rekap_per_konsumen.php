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
                                    <div class="col-12" text-align="center"> <strong>REKAP PENJUALAN PER KONSUMEN</strong></div>
                                </div>


                            </div>


                            <div class="col-6">
                                <?php echo anchor(site_url('tbl_penjualan/RekapPenjualanPerBarang'), 'Rekap Penjualan Per Barang', 'class="btn btn-success"'); ?>
                            </div>


                            <div class="col-2">
                                <?php //echo anchor(site_url('tbl_penjualan/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); 
                                ?>
                            </div>



                        </div>




                    </div>




                    <div class="card-body">

                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>

                                <tr>
                                    <th>No</th>

                                    <th>Konsumen</th>
                                    <th>SPOP</th>
                                    <th>Tanggal<br /> Jual</th>

                                    <th>Nomor<br /> Kirim</th>
                                    <th>Konsumen</th>
                                    <th>Nama Barang <br /> Penjualan</th>
                                    <th>Jumlah</th>
                                    <th>Harga<br /> Satuan</th>
                                    <th>TOTAL</th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                foreach ($Tbl_penjualan_data as $list_data_PERSEDIAAN) {

                                    // get data penjualan filter uuid_barang dan spop
                                    if ($start == 0) {

                                ?>
                                        <!-- BARIS KE 1 HANYA MENAMPILKAN DATA NAMA KONSUMEN -->
                                        <tr>
                                            <td><?php echo ++$start; ?></td>
                                            <td><?php echo $list_data_PERSEDIAAN->konsumen_nama_penjualan; ?></td>
                                            <td></td><!-- SPOP list nama barang persediaan-->

                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>





                                        </tr>

                                        <!-- CEK DATA GROUPING DATA BERDASARKAN NAMA UUID KONSUMEN -->
                                        <!-- BARIS KE 2 DATA PENJUALAN BERDASARKAN NAMA PENJUALAN -->

                                        <?php

                                        $sql_PENJUALAN_by_UUID_PERSEDIAAN = "SELECT 
                                                        tbl_penjualan.tgl_jual as tgl_jual_penjualan,
                                                        tbl_penjualan.id_persediaan_barang as id_persediaan_barang,
                                                        tbl_penjualan.nmrkirim as nmrkirim_penjualan,
                                                        tbl_penjualan.uuid_konsumen as uuid_konsumen_penjualan,
                                                        tbl_penjualan.konsumen_nama as konsumen_nama_penjualan,
                                                        tbl_penjualan.nama_barang as nama_barang_penjualan,
                                                        tbl_penjualan.jumlah as jumlah_penjualan,
                                                        tbl_penjualan.harga_satuan as harga_satuan_penjualan,
                                                        tbl_penjualan.uuid_persediaan as uuid_persediaan_penjualan
                                                            FROM tbl_penjualan
                                                            --  right JOIN  tbl_penjualan ON persediaan.uuid_persediaan= tbl_penjualan.uuid_persediaan
                                                            WHERE tbl_penjualan.uuid_konsumen =  '$list_data_PERSEDIAAN->uuid_konsumen_penjualan'
                                                            ORDER BY tbl_penjualan.tgl_jual ASC, tbl_penjualan.konsumen_nama ASC, tbl_penjualan.nmrkirim DESC;";

                                        $Total_jumlah_Barang = 0;
                                        $Total_Harga = 0;
                                        foreach ($this->db->query($sql_PENJUALAN_by_UUID_PERSEDIAAN)->result() as $list_data_PENJUALAN) {
                                            //LOOPING PENJUALAN BERDASARKAN UUID_PENJUALAN
                                        ?>

                                            <tr>
                                                <td><?php echo ++$start; ?></td>
                                                <td></td>
                                                <td>
                                                    <?php

                                                    $this->db->where('id', $list_data_PENJUALAN->id_persediaan_barang);
                                                    $Query_data_persediaan_barang = $this->db->get('persediaan');
                                                    $Get_data_persediaan_barang = $Query_data_persediaan_barang->row();

                                                    // echo "nomor spop : "; 
                                                    // if ($Get_data_persediaan_barang->spop) {
                                                    //     echo "SPOP: ";
                                                    // }
                                                    echo $Get_data_persediaan_barang->spop;
                                                    ?>
                                                </td> <!-- SPOP X data isi nomor spop dari nama barang pertama -->

                                                <td>
                                                    <?php
                                                    echo date("d-m-Y", strtotime($list_data_PENJUALAN->tgl_jual_penjualan));
                                                    ?>
                                                </td>
                                                <td><?php echo $list_data_PENJUALAN->nmrkirim_penjualan; ?></td>
                                                <td>
                                                    <?php
                                                    echo $list_data_PENJUALAN->konsumen_nama_penjualan;
                                                    $Nama_Konsumen = $list_data_PENJUALAN->konsumen_nama_penjualan;
                                                    ?>
                                                </td>
                                                <td><?php echo $list_data_PENJUALAN->nama_barang_penjualan; ?></td>
                                                <td align="right">
                                                    <?php
                                                    echo $list_data_PENJUALAN->jumlah_penjualan;
                                                    $Total_jumlah_Barang = $Total_jumlah_Barang + $list_data_PENJUALAN->jumlah_penjualan;
                                                    ?>
                                                </td>
                                                <td align="right">
                                                    <?php
                                                    echo number_format($list_data_PENJUALAN->harga_satuan_penjualan, 2, ',', '.');
                                                    ?>
                                                </td>
                                                <td align="right">

                                                    <?php
                                                    echo number_format($list_data_PENJUALAN->jumlah_penjualan * $list_data_PENJUALAN->harga_satuan_penjualan, 2, ',', '.');

                                                    $Total_Harga = $Total_Harga + ($list_data_PENJUALAN->jumlah_penjualan * $list_data_PENJUALAN->harga_satuan_penjualan);
                                                    ?>
                                                </td>





                                            </tr>

                                        <?php
                                        } //END OF SELESAI LOOPING PENJUALAN BERDASARKAN UUID_PENJUALAN
                                        ?>

                                        <!-- TOTAL PER NAMA BARANG -->
                                        <tr>
                                            <td><?php echo ++$start; ?></td>
                                            <td><?php //echo $list_data_PERSEDIAAN->namabarang_persediaan; 
                                                ?></td>
                                            <td></td><!-- SPOP Y kolom spop total -->

                                            <td></td>

                                            <td></td>
                                            <td>
                                                <?php
                                                echo $Nama_Konsumen;
                                                ?>
                                            </td>
                                            <td style="background-color:yellow;" align="right">TOTAL</td>
                                            <td style="background-color:yellow;" align="right"><?php echo "<font color='red'><strong>" . number_format($Total_jumlah_Barang, 0, ',', '.') . "<strong>"; ?></td>
                                            <td style="background-color:yellow;"></td>
                                            <td style="background-color:yellow;" align="right"><?php echo "<font color='red'><strong>" . number_format($Total_Harga, 2, ',', '.') . "<strong>"; ?></td>
                                        </tr>


                                    <?php
                                    } else {
                                        // -------------- BARIS KE 2 NAMA BARANG PERSEDIAAN DAN SETERUSNYA ------------
                                    ?>

                                        <!-- BARIS KE 1 HANYA MENAMPILKAN DATA NAMA PERSEDIAAN -->
                                        <tr>
                                            <td><?php echo ++$start; ?></td>
                                            <td><?php echo $list_data_PERSEDIAAN->konsumen_nama_penjualan; ?></td>
                                            <td></td> <!-- SPOP C kolom spop nama barang persediaan setelah nama barang pertama -->

                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td> <!-- total 2 -->






                                        </tr>

                                        <!-- CEK DATA GROUPING DATA BERDASARKAN NAMA BARANG DI PERSEDIAAN -->
                                        <!-- BARIS KE 2 DATA PENJUALAN BERDASARKAN NAMA PENJUALAN -->

                                        <?php

                                        $sql_PENJUALAN_by_UUID_PERSEDIAAN = "SELECT 
                                                        tbl_penjualan.tgl_jual as tgl_jual_penjualan,
                                                        tbl_penjualan.id_persediaan_barang as id_persediaan_barang,
                                                        tbl_penjualan.nmrkirim as nmrkirim_penjualan,
                                                        tbl_penjualan.uuid_konsumen as uuid_konsumen_penjualan,
                                                        tbl_penjualan.konsumen_nama as konsumen_nama_penjualan,
                                                        tbl_penjualan.nama_barang as nama_barang_penjualan,
                                                        tbl_penjualan.jumlah as jumlah_penjualan,
                                                        tbl_penjualan.harga_satuan as harga_satuan_penjualan,
                                                        tbl_penjualan.uuid_persediaan as uuid_persediaan_penjualan
                                                            FROM tbl_penjualan
                                                            --  right JOIN  tbl_penjualan ON persediaan.uuid_persediaan= tbl_penjualan.uuid_persediaan
                                                            WHERE tbl_penjualan.uuid_konsumen =  '$list_data_PERSEDIAAN->uuid_konsumen_penjualan'
                                                            ORDER BY tbl_penjualan.tgl_jual ASC, tbl_penjualan.konsumen_nama ASC, tbl_penjualan.nmrkirim DESC;";

                                        $Total_jumlah_Barang = 0;
                                        $Total_Harga = 0;

                                        foreach ($this->db->query($sql_PENJUALAN_by_UUID_PERSEDIAAN)->result() as $list_data_PENJUALAN) {
                                            //LOOPING PENJUALAN BERDASARKAN UUID_PENJUALAN
                                        ?>

                                            <tr>
                                                <td><?php echo ++$start; ?></td>
                                                <td></td>
                                                <td align="left">
                                                    <?php

                                                    $this->db->where('id', $list_data_PENJUALAN->id_persediaan_barang);
                                                    $Query_data_persediaan_barang = $this->db->get('persediaan');
                                                    $Get_data_persediaan_barang = $Query_data_persediaan_barang->row();

                                                    // echo "nomor spop : "; 


                                                    // if ($Get_data_persediaan_barang->spop) {
                                                    //     echo "SPOP: ";
                                                    // }
                                                    echo $Get_data_persediaan_barang->spop;

                                                    ?>
                                                </td> <!-- SPOP V : isi nomor spop spop nama barang ke 2 dst-->

                                                <td>
                                                    <?php
                                                    echo date("d-m-Y", strtotime($list_data_PENJUALAN->tgl_jual_penjualan));
                                                    ?>
                                                </td>
                                                <td><?php echo $list_data_PENJUALAN->nmrkirim_penjualan; ?></td>
                                                <td>
                                                    <?php
                                                    echo $list_data_PENJUALAN->konsumen_nama_penjualan;
                                                    $Nama_Konsumen = $list_data_PENJUALAN->konsumen_nama_penjualan;
                                                    ?>
                                                </td>
                                                <td><?php echo $list_data_PENJUALAN->nama_barang_penjualan; ?></td>
                                                <td align="right">
                                                    <?php
                                                    echo $list_data_PENJUALAN->jumlah_penjualan;
                                                    $Total_jumlah_Barang = $Total_jumlah_Barang + $list_data_PENJUALAN->jumlah_penjualan;
                                                    ?>
                                                </td>
                                                <td align="right">
                                                    <?php
                                                    echo number_format($list_data_PENJUALAN->harga_satuan_penjualan, 2, ',', '.');
                                                    ?>
                                                </td>
                                                <td align="right">
                                                    <?php
                                                    // echo "total 3";
                                                    echo number_format($list_data_PENJUALAN->jumlah_penjualan * $list_data_PENJUALAN->harga_satuan_penjualan, 2, ',', '.');

                                                    $Total_Harga = $Total_Harga + ($list_data_PENJUALAN->jumlah_penjualan * $list_data_PENJUALAN->harga_satuan_penjualan);
                                                    ?>
                                                </td>
                                            </tr>

                                        <?php
                                        } //END OF SELESAI LOOPING PENJUALAN BERDASARKAN UUID_PENJUALAN
                                        ?>


                                        <!-- // -------------- BARIS KE 2 NAMA BARANG PERSEDIAAN DAN SETERUSNYA ------------ -->

                                        <!-- TOTAL PER NAMA BARANG -->
                                        <tr>
                                            <td><?php echo ++$start; ?></td>
                                            <td><?php //echo $list_data_PERSEDIAAN->namabarang_persediaan; 
                                                ?></td>
                                            <td></td> <!-- SPOP B total nama barang ke 2 dst -->

                                            <td></td>
                                            <td></td>
                                            <td>
                                                <?php
                                                echo $Nama_Konsumen;
                                                ?>
                                            </td>
                                            <td style="background-color:yellow;" align="right">TOTAL</td>
                                            <td style="background-color:yellow;" align="right"><?php echo "<font color='red'><strong>" . number_format($Total_jumlah_Barang, 0, ',', '.') . "</strong>"; ?></td>
                                            <td style="background-color:yellow;"></td>
                                            <td style="background-color:yellow;" align="right">
                                                <?php
                                                // echo "Total 4";
                                                echo "<font color='red'><strong>" . number_format($Total_Harga, 2, ',', '.') . "</strong>"; ?></td>
                                        </tr>




                                <?php

                                    }
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