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
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-12"> DATA TAGIHAN PER SALES </div>
                                </div>
                            </div>
                            <div class="col-2">

                                <!-- <select name="level_sekolah" id="level_sekolah" class="form-control select2" style="width: 200px; height: 10px;">
                                        <option value="">Pilih Tingkat</option>
                                        <option value="MA">MA</option>
                                        <option value="MTS">MTS</option>
                                        <option value="MI">MI</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SD">SD</option>
                                    </select>
                                    <button type="submit" class="btn btn-danger"> Cari</button> -->
                            </div>
                            <!-- <div class="col-md-2  card-title"></div> -->
                            <div class="col-2">

                            </div>


                        </div>

                    </div>
                    



                    <div class="card-body">

                        <!-- <table id="rekapbukujadi" class="table table-bordered table-striped" style="width:100%"> -->
                        <table id="exampleFreeze" class="table table-bordered table-striped" style="width:100%">
                            <!-- <thead>

                                <tr>
                                    <th style="text-align:center;font-size:1vw" width="10px">No</th>


                                    <th style="text-align:center;font-size:1vw" width="110px">MAPEL</th>

                                    <?php
                                    // foreach ($data_tgl_list as $data_tgl_list_list) {
                                    //     echo "<th style='text-align:center;font-size:1vw'> " . $data_tgl_list_list['date_input_gudang'] . "</th>";
                                    // }
                                    ?>
                                    <th style="text-align:center;font-size:1vw">TOTAL <br />BUKU JADI</th>

                                </tr>
                            </thead> -->
                            <thead>

                                <tr>
                                    <th style="text-align:center;font-size:1vw" width="10px">No</th>
                                    <th style="text-align:center" width="200px">Cek Tagihan</th>
                                    <th style="text-align:center" width="200px">Tagihan Gabungan</th>
                                   

                                    <th>Kode Sales<br />Nama Sales</th>

                                    
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $start = 0;
                                $x_total_ALL = 0;
                                foreach ($tbl_sales_data as $tbl_sales) {
                                ?>
                                    <tr>
                                        <td><?php echo ++$start ?></td>

                                        <td width="200px">
                                            <?php
                                            $uuid_stock = "kosong";
                                            // echo anchor(site_url('tbl_sales/pemesanan_by_sales/' . $tbl_sales->uuid_sales), '<i class="fa fa-eye" aria-hidden="true">PEMESANAN</i>', 'class="btn btn-warning btn-sm"');

                                            // if(){
                                            // if ($this->session->userdata('sess_username') == "admin" or $this->session->userdata('sess_username') == "administrator") {
                                            //     echo anchor(site_url('Trans_bayar/bayar_by_sales/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">PEMBAYARAN</i>', 'class="btn btn-success btn-sm" target="_blank"');
                                            //     echo "<br/>";

                                            //     echo anchor(site_url('tbl_sales/lap_penjualan_dynamic/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">CEK TAGIHAN Dy</i>', 'class="btn btn-warning btn-sm" target="_blank"');

                                            // }


                                            $get_id = $this->session->userdata('sess_iduser');
                                            $sql = "select * from tbl_user where id_users=$get_id";
                                            $user = $this->db->query($sql)->row_array();    
                                            if($user['status_tagihan'] == 1)                                                 
                                            {
                                                echo anchor(site_url('Trans_bayar/bayar_by_sales/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">PEMBAYARAN</i>', 'class="btn btn-success btn-sm" target="_blank"');
                                                echo "<br/>";
                                                // echo anchor(site_url('tbl_sales/lap_penjualan/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">CEK TAGIHAN</i>', 'class="btn btn-danger btn-sm" target="_blank"');
                                                // echo "<br/>";
                                                echo anchor(site_url('tbl_sales/lap_penjualan_dynamic/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">CEK TAGIHAN Dy</i>', 'class="btn btn-warning btn-sm" target="_blank"');
                                                //echo "<br/>";
                                                //echo anchor(site_url('tbl_sales/lap_penjualan_dynamic_gabungan/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">CEK TAGIHAN GABUNGAN</i>', 'class="btn btn-danger btn-sm" target="_blank"');
                                            }

                                            ?>
                                        </td>
                                        <td width="200px">
                                            <?php
                                            $uuid_stock = "kosong";
                                            // echo anchor(site_url('tbl_sales/pemesanan_by_sales/' . $tbl_sales->uuid_sales), '<i class="fa fa-eye" aria-hidden="true">PEMESANAN</i>', 'class="btn btn-warning btn-sm"');

                                            // if(){
                                            // if ($this->session->userdata('sess_username') == "admin" or $this->session->userdata('sess_username') == "administrator") {
                                            //     $this->db->where('uuid_sales_rekomendasi', $tbl_sales->uuid_sales);
                                            //     $data_downline = $this->db->get('tbl_sales');
                                            //     if ($data_downline->num_rows() > 0) {
                                            //         echo anchor(site_url('tbl_sales/lap_penjualan_dynamic_gabungan/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">CEK TAGIHAN GABUNGAN</i>', 'class="btn btn-danger btn-sm" target="_blank"');  
                                            //     }
                                                
                                            // }

                                            if($user['status_tagihan'] == 1)                                                 
                                            {
                                                $this->db->where('uuid_sales_rekomendasi', $tbl_sales->uuid_sales);
                                                $data_downline = $this->db->get('tbl_sales');
                                                if ($data_downline->num_rows() > 0) {
                                                    echo anchor(site_url('tbl_sales/lap_penjualan_dynamic_gabungan/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">CEK TAGIHAN GABUNGAN</i>', 'class="btn btn-danger btn-sm" target="_blank"');  
                                                }

                                            }


                                            ?>
                                        </td>

                                        <td>
                                            <?php
                                            echo $tbl_sales->kode_sales;
                                            echo "<br/>";
                                            echo "<strong>" . $tbl_sales->nama_sales . "</strong>";
                                            echo "<br/>";
                                            echo " [Alamat: ] <i>" . $tbl_sales->alamat_sales . "</i>";
                                            echo "<br/>";
                                            echo " [no. telp: ] <i>" . $tbl_sales->notelp_sales . "</i>";
                                            ?>
                                        </td>

                                    </tr>
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


<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#rekapbukujadi').DataTable({
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>