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
                                        <div class="col-12" text-align="center"> <strong>JURNAL UMUM</strong></div>
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

                        <table id="example" class="table table-striped dt-responsive w-100 table-bordered display nowrap table-hover mb-0" style="width:100%">
                            <thead>

                                <!-- <tr>
                                    <th rowspan="2" style="text-align:left" width="10px">No</th>
                                    <th rowspan="2" style="text-align:left" width="10px">Tanggal</th>
                                    <th rowspan="2" style="text-align:center">Kode Akun</th>
                                    <th rowspan="2" style="text-align:center">No. Bukti BKM</th>
                                    <th rowspan="2" style="text-align:center">PL</th>
                                    <th rowspan="2" style="text-align:center">KETERANGAN</th>

                                    <th colspan="1" style="text-align:center">Debit</th>


                                    <th colspan="3" style="text-align:center">KREDIT</th>
                                <tr>
                                    <th rowspan="2" style="text-align:right">11101-Kas Besar</th>
                                    <th rowspan="2" style="text-align:center">11301-PU <br />Non Angsuran</th>
                                    <th colspan="2" style="text-align:center">Serba-Serbi</th>
                                </tr>
                                <tr>
                                    <th rowspan="2" style="text-align:left">Rek</th>
                                    <th style="text-align:center">Jumlah</th>
                                </tr> -->



                                <tr>

                                    <th rowspan="2" style="text-align:left" width="10px">No</th>
                                    <th rowspan="2" style="text-align:left" width="10px">Tanggal</th>

                                    <th rowspan="2" style="text-align:center">Bukti</th>
                                    <th rowspan="2" style="text-align:center">PL</th>
                                    <th rowspan="2" style="text-align:center">Ref</th>
                                    <th colspan="2" style="text-align:center">Uraian</th>


                                    <th rowspan="2" style="text-align:center">DEBET</th>
                                    <th rowspan="2" style="text-align:center">KREDIT</th>

                                </tr>

                                <tr>
                                    <th style="text-align:center">Kode Rek.</th>
                                    <th style="text-align:center">Rek.</th>
                                </tr>





                            </thead>
                            <tbody>
                                <?php

                                $Total_debet = 0;
                                $Total_kredit = 0;
                                $start = 0;
                                foreach ($Data_Jurnal_Umum as $list_data) {

                                ?>


                                    <tr>
                                        <td>
                                            <?php
                                            echo ++$start;
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo date("d-m-Y", strtotime($list_data->tanggal));
                                            ?>
                                        </td>


                                        <td><?php
                                            echo $list_data->bukti;
                                            ?>
                                        </td>
                                        <td><?php
                                            echo $list_data->pl;
                                            ?>
                                        </td>
                                        <!-- Ref -->
                                        <td align="left">
                                            <?php
                                            // echo $list_data->keterangan;
                                            ?>
                                        </td>

                                        <!-- Kode rek -->
                                        <td style="text-align:left">
                                            <?php
                                            echo $list_data->kode_rekening;
                                            ?>
                                        </td>

                                        <!-- Rek -->
                                        <td style="text-align:left">
                                            <?php
                                            if ($list_data->rekening == "") {

                                                $this->db->where('kode_akun', $list_data->kode_rekening);
                                                $data_akun = $this->db->get('sys_kode_akun');

                                                if ($data_akun->num_rows() > 0) {

                                                    $Get_data_akun = $data_akun->row_array();
                                                    echo $Get_data_akun['nama_akun'];
                                                }
                                            } else {
                                                echo $list_data->rekening;
                                            }

                                            // echo $list_data->keterangan;                                                
                                            ?>
                                        </td>



                                        <!-- /DEBET -->
                                        <td style="text-align:right">
                                            <?php
                                            echo number_format($list_data->debet, 2, ',', '.');
                                            $Total_debet = $Total_debet + $list_data->debet;

                                            ?>
                                        </td>

                                        <!-- /KREDIT -->
                                        <td style="text-align:right">
                                            <?php

                                            echo number_format($list_data->kredit, 2, ',', '.');
                                            $Total_kredit = $Total_kredit + $list_data->kredit;
                                            ?>
                                        </td>

                                    </tr>

                                <?php
                                    // END OF FOR EACH $Data_kas 
                                }
                                ?>

                            </tbody>

                            <tfoot>
                                <tr>

                                    <th style="text-align:left" width="10px"></th>
                                    <th style="text-align:left" width="10px"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:right"><?php echo number_format($Total_debet, 2, ',', '.'); ?></th>
                                    <th style="text-align:right"><?php echo number_format($Total_kredit, 2, ',', '.'); ?></th>

                                </tr>

                            </tfoot>
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
            "scrollY": 600,
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