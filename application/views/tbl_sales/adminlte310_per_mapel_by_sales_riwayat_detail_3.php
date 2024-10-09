<?php


// $this->db->where('tahun_pesanan', $_SESSION['thn_selected']);
// $this->db->where('semester_pesanan', $_SESSION['semester_selected']);
// $this->db->where('uuid_sales', $uuid_sales_PROCESS);
// $this->db->where('tingkat_pesanan', $data_tingkat);
// $data_PIVOT = $this->db->get('trans_pesan_jual_retur_pivot')->result();


// DATA RIWAYAT
$this->db->where('tahun_pesanan', $_SESSION['thn_selected']);
$this->db->where('semester_pesanan', $_SESSION['semester_selected']);
$this->db->where('uuid_sales', $uuid_sales_PROCESS);
$this->db->where('tingkat_pesanan', $tingkat_selected_PROCESS);
$data_RIWAYAT = $this->db->get('trans_pesan_jual_retur_riwayat')->result();

?>



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
                            <?php
                            if (!empty($_SESSION['jenis_data_selected'])) {
                                if ($_SESSION['jenis_data_selected'] == "BARU") {
                                    $action = site_url('Tbl_sales/riwayat_per_sales_baru/');
                                    $action_kirim = $action . $uuid_sales_PROCESS;
                                } else {
                                    $action = site_url('Tbl_sales/riwayat_per_sales_lama/');
                                    $action_kirim = $action . $uuid_sales_PROCESS;
                                }
                            } else {
                                $action = site_url('Tbl_sales/riwayat_per_sales/');
                                $action_kirim = $action . $uuid_sales_PROCESS;
                            }


                            // $action_jenis = $action_data . $uuid_sales_PROCESS;

                            // print_r("VIEW, action_jenis " . $action_jenis);
                            // print_r("<br/>");
                            // print_r("<br/>");


                            ?>


                            <div class="col-12 card-title">
                                <div class="row">

                                    <div class="col-sm-4" align="left">
                                        RIWAYAT TRANSAKSI :
                                        <?php

                                        echo "<br/>";
                                        if (!is_null($uuid_sales_PROCESS)) {
                                            $this->db->where('uuid_sales', $uuid_sales_PROCESS);
                                            $data_sales = $this->db->get('tbl_sales');
                                            echo $data_sales->row()->nama_sales;
                                            echo " - ";
                                        }
                                        echo "<strong>";

                                        if (is_null($data_tingkat)) {
                                            echo "ALL [ ";
                                        } else {
                                            echo $data_tingkat;
                                        }

                                        echo "  [" . $_SESSION['thn_selected'] . " / " . $_SESSION['semester_selected'] . " ]";

                                        if (!empty($_SESSION['jenis_data_selected'])) {
                                            echo " " . $_SESSION['jenis_data_selected'];
                                        }

                                        echo "</strong>";

                                        // $sql = "select nama_sales from tbl_sales where uuid_sales=$uuid_sales_PROCESS   ";
                                        // echo $this->db->query($sql)->row()->nama_sales;

                                        ?>

                                        <?php
                                        $action_refresh_data = site_url('Trans_pesan_jual_retur_pivot/ReCALCULATING_data_sales_PER_transaksi/' . $uuid_sales_PROCESS);
                                        // Trans_pesan_jual_retur_pivot/insert_pesanjualretur_pivot/731f5119dd3f11ebab780242ac120004/SD

                                        ?>

                                        <form action="<?php echo $action_refresh_data; ?>" method="post">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa fa-floppy-o"></i> <?php echo "REFRESH DATA" ?>
                                            </button>
                                        </form>

                                    </div>

                                    <div class="col-sm-7" align="left">
                                        <?php
                                        $action_kode_sales = site_url('tbl_sales/riwayat_per_sales3view/' . $uuid_sales_PROCESS . '/' . $tingkat_selected_X);

                                        ?>

                                        <form action="<?php echo $action_kode_sales; ?>" method="post">

                                            <div class="col-sm-5 card-title">
                                                <select name="uuid_sales" id="uuid_sales" class="form-control select2" style="width: 100%; height: 40px;">

                                                    <option value="<?php echo $uuid_sales_PROCESS ?>"><?php if ($uuid_sales_PROCESS) {
                                                                                                            echo $nama_sales_selected;
                                                                                                        } else {
                                                                                                            echo "Pilih Sales / Customer";
                                                                                                        } ?></option> <?php $sql = "select * from tbl_sales order by  nama_sales ";
                                                                                                                        foreach ($this->db->query($sql)->result() as $m) {
                                                                                                                            echo "<option value='$m->uuid_sales' ";
                                                                                                                            echo "> " . strtoupper($m->nama_sales) . "</option>";
                                                                                                                        }
                                                                                                                        ?>
                                                </select>
                                            </div>

                                            <div class="col-sm-2 card-title">

                                                <select name="level_sekolah" id="level_sekolah" class="form-control select2" style="width: 100%; height: 40px;">
                                                    <?php
                                                    // if (!empty($_SESSION['level_sekolah_selected'])) {
                                                    if (!is_null($data_tingkat)) {
                                                    ?>
                                                        <!-- <option value="<?php //echo $_SESSION['level_sekolah_selected']; 
                                                                            ?>"><?php //echo $_SESSION['level_sekolah_selected']; 
                                                                                ?></option> -->
                                                        <option value="<?php echo $data_tingkat; ?>"><?php echo $data_tingkat; ?></option>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <option value="">Pilih Tingkat</option>
                                                    <?php
                                                    }

                                                    // $sql = "select tingkat from  tbl_produk_mapel_referensi group by tingkat order by  tingkat asc";
                                                    // foreach ($this->db->query($sql)->result() as $m) {
                                                    //     echo "<option value='$m->tingkat' ";
                                                    //     echo ">  " . strtoupper($m->tingkat) . "</option>";
                                                    // }

                                                    foreach ($this->Auto_load_model->cek_tingkat_by_user() as $m) {
                                                        echo "<option value='$m->tingkat' ";
                                                        echo ">  " . strtoupper($m->tingkat) . "</option>";
                                                    }

                                                    ?>

                                                </select>

                                            </div>

                                            <div class="col-sm-2 card-title">

                                                <select name="jenis_data" id="jenis_data" class="form-control select2" style="width: 100%; height: 40px;">
                                                    <?php
                                                    // print_r($tingkat_selected_PROCESS);
                                                    // if (!empty($_SESSION['jenis_data_selected'])) {
                                                    if (!is_null($jenis_data_selected_PROCESS)) {
                                                    ?>
                                                        <option value="<?php echo $jenis_data_selected_PROCESS; ?>"><?php echo $jenis_data_selected_PROCESS; ?></option>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <option value="">Pilih Data</option>
                                                    <?php
                                                    }
                                                    ?>
                                                    <option value="BARU">BARU</option>
                                                    <option value="LAMA">LAMA</option>
                                                </select>

                                            </div>



                                            <div class="col-sm-2 card-title">
                                                <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo "CARI DATA" ?></button>
                                            </div>

                                        </form>

                                    </div>


                                    <div class="col-sm-1 card-title">
                                        <!-- <div class="col-12" align="left"><?php //echo anchor(site_url('tbl_sales/excel_riwayat_persales/' . $uuid_sales_PROCESS . '/' . $tingkat_selected_X . '/' . $jenis_data_selected_PROCESS), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Cetak Excel', 'class="btn btn-success btn-sm"'); 
                                                                                ?></div> -->

                                        <?php
                                        $action_model_lama = site_url('tbl_sales/riwayat_per_sales/' . $uuid_sales_PROCESS . '/' . $tingkat_selected_X);
                                        // tbl_sales/riwayat_per_sales2/731f5119dd3f11ebab780242ac120004/SD

                                        ?>

                                        <form action="<?php echo $action_model_lama; ?>" method="post" target="_blank">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="fa fa-floppy-o"></i> <?php echo "MODEL LAMA" ?>
                                            </button>
                                        </form>


                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                    <br />





                    <div class="card-body">

                        <!-- TABLE CONTENT -->

                        <!-- <table id="example" class="display nowrap" style="width:100%"> -->
                        <!-- <table id="example9" class="display nowrap" style="width:100%"> -->
                        <table id="exampleFreeze" class="display nowrap" style="width:100%">
                            <thead>

                                <tr>
                                    <th style="font-size:0.7vw" width="10px">No</th>

                                    <th style="font-size:0.7vw" width="110px">MAPEL</th>

                                    <!-- RIWAYAT PEMESANAN -->
                                    <?php



                                    $get_thn_selected = $_SESSION['thn_selected'];
                                    $get_semester_selected = $_SESSION['semester_selected'];
                                    $keterangan_status_buku = "buku_lama";

                                    $proses_data = "pesan";


                                    // if ($data_PIVOT) {

                                    // print_r("jenis_data_selected_PROCESS");
                                    // print_r("<br/>");
                                    // print_r($jenis_data_selected_PROCESS);
                                    // print_r("<br/>");

                                    if (!is_null($jenis_data_selected_PROCESS)) {

                                        if ($jenis_data_selected_PROCESS == "LAMA") {
                                            //    LAMA

                                            $sql = "SELECT trans_pesan_jual_retur_pivot_a.*,
                                                    sys_cover_b.nama_cover as nama_cover,                            
                                                    sys_cover_b.keterangan as keterangan_cover                            
                                                    FROM trans_pesan_jual_retur_pivot trans_pesan_jual_retur_pivot_a 
                                                    left join  sys_cover sys_cover_b ON sys_cover_b.uuid_cover = trans_pesan_jual_retur_pivot_a.uuid_cover
                                            
                                                    WHERE trans_pesan_jual_retur_pivot_a.uuid_sales = '$uuid_sales_PROCESS' 
                                                    AND trans_pesan_jual_retur_pivot_a.tahun_pesanan = '$get_thn_selected'
                                                    AND trans_pesan_jual_retur_pivot_a.semester_pesanan = '$get_semester_selected' 
                                                    AND trans_pesan_jual_retur_pivot_a.tingkat_pesanan = '$data_tingkat' 
                                                    AND trans_pesan_jual_retur_pivot_a.nama_process = '$proses_data' 
                                                    -- AND sys_cover_b.keterangan = '$keterangan_status_buku'
                
                                                    ORDER BY trans_pesan_jual_retur_pivot_a.date_input ASC ";

                                            // print_r("sql LAMA");
                                            // print_r("<br/>");

                                            // print_r($sql);
                                            // print_r("<br/>");
                                        } else {

                                            // BARU

                                            $sql = "SELECT trans_pesan_jual_retur_pivot_a.*,
                                                sys_cover_b.nama_cover as nama_cover,
                                                sys_cover_b.keterangan as keterangan_cover                            
                                                
                                                FROM trans_pesan_jual_retur_pivot trans_pesan_jual_retur_pivot_a 
                                                left join  sys_cover sys_cover_b ON sys_cover_b.uuid_cover = trans_pesan_jual_retur_pivot_a.uuid_cover
                                        
                                                WHERE trans_pesan_jual_retur_pivot_a.uuid_sales = '$uuid_sales_PROCESS' 
                                                AND trans_pesan_jual_retur_pivot_a.tahun_pesanan = '$get_thn_selected'
                                                AND trans_pesan_jual_retur_pivot_a.semester_pesanan = '$get_semester_selected' 
                                                AND trans_pesan_jual_retur_pivot_a.tingkat_pesanan = '$data_tingkat' 
                                                AND trans_pesan_jual_retur_pivot_a.nama_process = '$proses_data' 
                                                -- AND sys_cover_b.keterangan <> '$keterangan_status_buku'
            
                                                ORDER BY trans_pesan_jual_retur_pivot_a.date_input ASC ";

                                            // print_r("sql BARU 1");
                                            // print_r("<br/>");

                                            // print_r($sql);
                                            // print_r("<br/>");
                                        }
                                    } else {

                                        // BARU

                                        $sql = "SELECT trans_pesan_jual_retur_pivot_a.*,
                                            sys_cover_b.nama_cover as nama_cover,
                                            sys_cover_b.keterangan as keterangan_cover                            
                                            
                                            FROM trans_pesan_jual_retur_pivot trans_pesan_jual_retur_pivot_a 
                                            left join  sys_cover sys_cover_b ON sys_cover_b.uuid_cover = trans_pesan_jual_retur_pivot_a.uuid_cover
                                    
                                            WHERE trans_pesan_jual_retur_pivot_a.uuid_sales = '$uuid_sales_PROCESS' 
                                            AND trans_pesan_jual_retur_pivot_a.tahun_pesanan = '$get_thn_selected'
                                            AND trans_pesan_jual_retur_pivot_a.semester_pesanan = '$get_semester_selected' 
                                            AND trans_pesan_jual_retur_pivot_a.tingkat_pesanan = '$data_tingkat' 
                                            AND trans_pesan_jual_retur_pivot_a.nama_process = '$proses_data' 
                                            -- AND sys_cover_b.keterangan <> '$keterangan_status_buku'
        
                                            ORDER BY trans_pesan_jual_retur_pivot_a.date_input ASC ";

                                        // print_r("sql BARU 2");
                                        // print_r("<br/>");

                                        // print_r($sql);
                                        // print_r("<br/>");
                                    }

                                    // print_r("JOIN PEMESANAN BUKU LAMA");

                                    $data_PIVOT_pesan_per_sales_per_tingkat = $this->db->query($sql);

                                    // print_r("PEMESANAN ");
                                    // print_r("<br/>");
                                    // print_r($data_PIVOT_pesan_per_sales_per_tingkat->result());
                                    // print_r("<br/>");
                                    // print_r("<br/>");
                                    // print_r("<br/>");

                                    if ($data_PIVOT_pesan_per_sales_per_tingkat->num_rows() > 0) {

                                        if ($data_PIVOT_pesan_per_sales_per_tingkat->result()) {
                                            foreach ($data_PIVOT_pesan_per_sales_per_tingkat->result() as $RIWAYAT_PEMESANAN_list) {

                                                $this->db->where('uuid_cover', $RIWAYAT_PEMESANAN_list->uuid_cover);
                                                $get_sys_cover = $this->db->get('sys_cover')->row_array();

                                                if ($jenis_data_selected_PROCESS == "BARU" and $get_sys_cover['keterangan'] <> "buku_lama") {

                                                    if (!empty($RIWAYAT_PEMESANAN_list->date_input) or !empty($RIWAYAT_PEMESANAN_list->date_input)) {

                                                        $DATE_DATA = date('Y-m-d', strtotime('0 day', strtotime($RIWAYAT_PEMESANAN_list->date_input)));

                                                        echo "<th style='text-align:right;font-size:0.7vw'> PEMESANAN: " . $RIWAYAT_PEMESANAN_list->nama_cover  . "<br/>" . $DATE_DATA . "<br/> " .
                                                            anchor(site_url('Trans_pemesanan/update_pemesanan_2024/' . $uuid_sales_PROCESS . "/" . $RIWAYAT_PEMESANAN_list->uuid_process), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Ubah Data Pesanan', 'class="btn btn-primary btn-sm"')  . "</th>";
                                                    }
                                                } elseif ($jenis_data_selected_PROCESS == "LAMA" and $get_sys_cover['keterangan'] == "buku_lama") {

                                                    if (!empty($RIWAYAT_PEMESANAN_list->date_input) or !empty($RIWAYAT_PEMESANAN_list->date_input)) {
                                                        $DATE_DATA = date('Y-m-d', strtotime('0 day', strtotime($RIWAYAT_PEMESANAN_list->date_input)));

                                                        echo "<th style='text-align:right;font-size:0.7vw'> PEMESANAN: " . $RIWAYAT_PEMESANAN_list->nama_cover  . "<br/>" . $DATE_DATA . "<br/> " .
                                                            anchor(site_url('Trans_pemesanan/update_pemesanan_2024/' . $uuid_sales_PROCESS . "/" . $RIWAYAT_PEMESANAN_list->uuid_process), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Ubah Data Pesanan', 'class="btn btn-primary btn-sm"')  . "</th>";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    // }
                                    ?>

                                    <th style="font-size:0.7vw" align="right">TOTAL PESANAN</th>

                                    <!-- PENJUALAN -->
                                    <?php
                                    // if ($data_PIVOT) {

                                    $proses_data = "penjualan";

                                    if (!is_null($jenis_data_selected_PROCESS)) {

                                        if ($jenis_data_selected_PROCESS == "LAMA") {
                                            //    LAMA

                                            $sql = "SELECT trans_pesan_jual_retur_pivot_a.*,
                                                        sys_cover_b.nama_cover as nama_cover,                            
                                                        sys_cover_b.keterangan as keterangan                            
                                                        FROM trans_pesan_jual_retur_pivot trans_pesan_jual_retur_pivot_a 
                                                        left join  sys_cover sys_cover_b ON sys_cover_b.uuid_cover = trans_pesan_jual_retur_pivot_a.uuid_cover
                                                
                                                        WHERE trans_pesan_jual_retur_pivot_a.uuid_sales = '$uuid_sales_PROCESS' 
                                                        AND trans_pesan_jual_retur_pivot_a.tahun_pesanan = '$get_thn_selected'
                                                        AND trans_pesan_jual_retur_pivot_a.semester_pesanan = '$get_semester_selected' 
                                                        AND trans_pesan_jual_retur_pivot_a.tingkat_pesanan = '$data_tingkat' 
                                                        AND trans_pesan_jual_retur_pivot_a.nama_process = '$proses_data' 
                                                        -- AND sys_cover_b.keterangan = '$keterangan_status_buku'
                    
                                                        ORDER BY trans_pesan_jual_retur_pivot_a.date_input ASC ";
                                        } else {

                                            // BARU

                                            $sql = "SELECT trans_pesan_jual_retur_pivot_a.*,
                                                    sys_cover_b.nama_cover as nama_cover,
                                                    sys_cover_b.keterangan as keterangan                            
                                                    
                                                    FROM trans_pesan_jual_retur_pivot trans_pesan_jual_retur_pivot_a 
                                                    left join  sys_cover sys_cover_b ON sys_cover_b.uuid_cover = trans_pesan_jual_retur_pivot_a.uuid_cover
                                            
                                                    WHERE trans_pesan_jual_retur_pivot_a.uuid_sales = '$uuid_sales_PROCESS' 
                                                    AND trans_pesan_jual_retur_pivot_a.tahun_pesanan = '$get_thn_selected'
                                                    AND trans_pesan_jual_retur_pivot_a.semester_pesanan = '$get_semester_selected' 
                                                    AND trans_pesan_jual_retur_pivot_a.tingkat_pesanan = '$data_tingkat' 
                                                    AND trans_pesan_jual_retur_pivot_a.nama_process = '$proses_data' 
                                                    -- AND sys_cover_b.keterangan <> '$keterangan_status_buku'
                
                                                    ORDER BY trans_pesan_jual_retur_pivot_a.date_input ASC ";
                                        }
                                    } else {

                                        // BARU

                                        $sql = "SELECT trans_pesan_jual_retur_pivot_a.*,
                                                sys_cover_b.nama_cover as nama_cover,
                                                sys_cover_b.keterangan as keterangan                            
                                                
                                                FROM trans_pesan_jual_retur_pivot trans_pesan_jual_retur_pivot_a 
                                                left join  sys_cover sys_cover_b ON sys_cover_b.uuid_cover = trans_pesan_jual_retur_pivot_a.uuid_cover
                                        
                                                WHERE trans_pesan_jual_retur_pivot_a.uuid_sales = '$uuid_sales_PROCESS' 
                                                AND trans_pesan_jual_retur_pivot_a.tahun_pesanan = '$get_thn_selected'
                                                AND trans_pesan_jual_retur_pivot_a.semester_pesanan = '$get_semester_selected' 
                                                AND trans_pesan_jual_retur_pivot_a.tingkat_pesanan = '$data_tingkat' 
                                                AND trans_pesan_jual_retur_pivot_a.nama_process = '$proses_data' 
                                                -- AND sys_cover_b.keterangan <> '$keterangan_status_buku'
            
                                                ORDER BY trans_pesan_jual_retur_pivot_a.date_input ASC ";
                                    }

                                    // print_r("JOIN PENJUALAN BUKU LAMA");

                                    $data_PIVOT_penjualan_per_sales_per_tingkat = $this->db->query($sql);

                                    // print_r("PENJUALAN ");
                                    // print_r("<br/>");
                                    // print_r($data_PIVOT_penjualan_per_sales_per_tingkat->result());
                                    // print_r("<br/>");
                                    // print_r("<br/>");
                                    // print_r("<br/>");


                                    if ($data_PIVOT_penjualan_per_sales_per_tingkat->result()) {
                                        foreach ($data_PIVOT_penjualan_per_sales_per_tingkat->result() as $RIWAYAT_PENJUALAN_list) {

                                            $this->db->where('uuid_cover', $RIWAYAT_PENJUALAN_list->uuid_cover);
                                            $get_sys_cover = $this->db->get('sys_cover')->row_array();

                                            if ($jenis_data_selected_PROCESS == "BARU" and $get_sys_cover['keterangan'] <> "buku_lama") {

                                                if (!empty($RIWAYAT_PENJUALAN_list->date_input) or !empty($RIWAYAT_PENJUALAN_list->date_input)) {

                                                    $DATE_DATA = date('Y-m-d H:m:d', strtotime('0 day', strtotime($RIWAYAT_PENJUALAN_list->date_input)));

                                                    echo "<th style='text-align:right;font-size:0.7vw'> PENJUALAN: " . $RIWAYAT_PENJUALAN_list->nama_cover . anchor(site_url('trans_penjualan/hapus_penjualan_2024/' . $uuid_sales_selected . "/" . $RIWAYAT_PENJUALAN_list->uuid_process), '<i class="fa fa-file-word-o" aria-hidden="true"></i> HAPUS', 'class="btn btn-danger btn-sm" ') . "<br/>" . $DATE_DATA . "<br/> " .
                                                        anchor(site_url('trans_penjualan/update_penjualan_2024/' . $uuid_sales_PROCESS . "/" . $RIWAYAT_PENJUALAN_list->uuid_process . "/" . $RIWAYAT_PENJUALAN_list->date_input), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Ubah Data Penjualan', 'class="btn btn-primary btn-sm"')   . "<br/>" . anchor(site_url('trans_penjualan/cetak_pdf_DYNAMIC/' . $RIWAYAT_PENJUALAN_list->uuid_process), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Cetak Nota (Pdf) ', 'class="btn btn-success btn-sm" target="_blank"') . "</th>";
                                                }
                                            } elseif ($jenis_data_selected_PROCESS == "LAMA" and $get_sys_cover['keterangan'] == "buku_lama") {
                                                if (!empty($RIWAYAT_PENJUALAN_list->date_input) or !empty($RIWAYAT_PENJUALAN_list->date_input)) {
                                                    $DATE_DATA = date('Y-m-d', strtotime('0 day', strtotime($RIWAYAT_PENJUALAN_list->date_input)));

                                                    echo "<th style='text-align:right;font-size:0.7vw'> PENJUALAN: " . $RIWAYAT_PENJUALAN_list->nama_cover . anchor(site_url('trans_penjualan/hapus_penjualan_2024/' . $uuid_sales_selected . "/" . $RIWAYAT_PENJUALAN_list->uuid_process), '<i class="fa fa-file-word-o" aria-hidden="true"></i> HAPUS', 'class="btn btn-danger btn-sm" ') . "<br/>" . $DATE_DATA . "<br/> " .
                                                        anchor(site_url('trans_penjualan/update_penjualan_2024/' . $uuid_sales_PROCESS . "/" . $RIWAYAT_PENJUALAN_list->uuid_process . "/" . $RIWAYAT_PENJUALAN_list->date_input), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Ubah Data Penjualan', 'class="btn btn-primary btn-sm"')   . "<br/>" . anchor(site_url('trans_penjualan/cetak_pdf_DYNAMIC/' . $RIWAYAT_PENJUALAN_list->uuid_process), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Cetak Nota (Pdf) ', 'class="btn btn-success btn-sm" target="_blank"') . "</th>";
                                                }
                                            }
                                        }
                                    }

                                    // }
                                    ?>




                                    <!-- PENJUALAN TUNAI -->
                                    <?php
                                    // if ($data_PIVOT) {

                                    $proses_data = "tunai";

                                    if (!is_null($jenis_data_selected_PROCESS)) {

                                        if ($jenis_data_selected_PROCESS == "LAMA") {
                                            //    LAMA

                                            $sql = "SELECT trans_pesan_jual_retur_pivot_a.*,
                                                            sys_cover_b.nama_cover as nama_cover,                            
                                                            sys_cover_b.keterangan as keterangan                            
                                                            FROM trans_pesan_jual_retur_pivot trans_pesan_jual_retur_pivot_a 
                                                            left join  sys_cover sys_cover_b ON sys_cover_b.uuid_cover = trans_pesan_jual_retur_pivot_a.uuid_cover
                                                    
                                                            WHERE trans_pesan_jual_retur_pivot_a.uuid_sales = '$uuid_sales_PROCESS' 
                                                            AND trans_pesan_jual_retur_pivot_a.tahun_pesanan = '$get_thn_selected'
                                                            AND trans_pesan_jual_retur_pivot_a.semester_pesanan = '$get_semester_selected' 
                                                            AND trans_pesan_jual_retur_pivot_a.tingkat_pesanan = '$data_tingkat' 
                                                            AND trans_pesan_jual_retur_pivot_a.nama_process = '$proses_data' 
                                                            -- AND sys_cover_b.keterangan = '$keterangan_status_buku'
                        
                                                            ORDER BY trans_pesan_jual_retur_pivot_a.date_input ASC ";
                                        } else {

                                            // BARU

                                            $sql = "SELECT trans_pesan_jual_retur_pivot_a.*,
                                                        sys_cover_b.nama_cover as nama_cover,
                                                        sys_cover_b.keterangan as keterangan                            
                                                        
                                                        FROM trans_pesan_jual_retur_pivot trans_pesan_jual_retur_pivot_a 
                                                        left join  sys_cover sys_cover_b ON sys_cover_b.uuid_cover = trans_pesan_jual_retur_pivot_a.uuid_cover
                                                
                                                        WHERE trans_pesan_jual_retur_pivot_a.uuid_sales = '$uuid_sales_PROCESS' 
                                                        AND trans_pesan_jual_retur_pivot_a.tahun_pesanan = '$get_thn_selected'
                                                        AND trans_pesan_jual_retur_pivot_a.semester_pesanan = '$get_semester_selected' 
                                                        AND trans_pesan_jual_retur_pivot_a.tingkat_pesanan = '$data_tingkat' 
                                                        AND trans_pesan_jual_retur_pivot_a.nama_process = '$proses_data' 
                                                        -- AND sys_cover_b.keterangan <> '$keterangan_status_buku'
                    
                                                        ORDER BY trans_pesan_jual_retur_pivot_a.date_input ASC ";
                                        }
                                    } else {

                                        // BARU

                                        $sql = "SELECT trans_pesan_jual_retur_pivot_a.*,
                                                    sys_cover_b.nama_cover as nama_cover,
                                                    sys_cover_b.keterangan as keterangan                            
                                                    
                                                    FROM trans_pesan_jual_retur_pivot trans_pesan_jual_retur_pivot_a 
                                                    left join  sys_cover sys_cover_b ON sys_cover_b.uuid_cover = trans_pesan_jual_retur_pivot_a.uuid_cover
                                            
                                                    WHERE trans_pesan_jual_retur_pivot_a.uuid_sales = '$uuid_sales_PROCESS' 
                                                    AND trans_pesan_jual_retur_pivot_a.tahun_pesanan = '$get_thn_selected'
                                                    AND trans_pesan_jual_retur_pivot_a.semester_pesanan = '$get_semester_selected' 
                                                    AND trans_pesan_jual_retur_pivot_a.tingkat_pesanan = '$data_tingkat' 
                                                    AND trans_pesan_jual_retur_pivot_a.nama_process = '$proses_data' 
                                                    -- AND sys_cover_b.keterangan <> '$keterangan_status_buku'
                
                                                    ORDER BY trans_pesan_jual_retur_pivot_a.date_input ASC ";
                                    }

                                    // print_r("JOIN TUNAI BUKU LAMA");

                                    $data_PIVOT_tunai_per_sales_per_tingkat = $this->db->query($sql);

                                    // print_r("TUNAI ");
                                    // print_r("<br/>");
                                    // print_r($data_PIVOT_tunai_per_sales_per_tingkat->result());
                                    // print_r("<br/>");
                                    // print_r("<br/>");
                                    // print_r("<br/>");

                                    if ($data_PIVOT_tunai_per_sales_per_tingkat->result()) {
                                        foreach ($data_PIVOT_tunai_per_sales_per_tingkat->result() as $RIWAYAT_PENJUALAN_TUNAI_list) {

                                            $this->db->where('uuid_cover', $RIWAYAT_PENJUALAN_TUNAI_list->uuid_cover);
                                            $get_sys_cover = $this->db->get('sys_cover')->row_array();

                                            if ($jenis_data_selected_PROCESS == "BARU" and $get_sys_cover['keterangan'] <> "buku_lama") {

                                                if (!empty($RIWAYAT_PENJUALAN_TUNAI_list->date_input) or !empty($RIWAYAT_PENJUALAN_TUNAI_list->date_input)) {

                                                    $DATE_DATA = date('Y-m-d', strtotime('0 day', strtotime($RIWAYAT_PENJUALAN_TUNAI_list->date_input)));

                                                    echo "<th style='text-align:right;font-size:0.7vw'> PENJUALAN TUNAI: " . $RIWAYAT_PENJUALAN_TUNAI_list->nama_cover . "<br/>" . $DATE_DATA . "<br/> " . anchor(site_url('trans_penjualan_tunai/update_penjualan_2024/' . $uuid_sales_PROCESS . '/' . $RIWAYAT_PENJUALAN_TUNAI_list->uuid_process), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Ubah Data Penjualan Tunai', 'class="btn btn-primary btn-sm"')  . "<br/>" . anchor(site_url('trans_penjualan_tunai/cetak_pdf_DYNAMIC/' . $RIWAYAT_PENJUALAN_TUNAI_list->uuid_process), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Cetak Nota (Pdf) ', 'class="btn btn-success btn-sm" target="_blank"') . "</th>";
                                                }
                                            } elseif ($jenis_data_selected_PROCESS == "LAMA" and $get_sys_cover['keterangan'] == "buku_lama") {

                                                if (!empty($RIWAYAT_PENJUALAN_TUNAI_list->date_input) or !empty($RIWAYAT_PENJUALAN_TUNAI_list->date_input)) {
                                                    $DATE_DATA = date('Y-m-d', strtotime('0 day', strtotime($RIWAYAT_PENJUALAN_TUNAI_list->date_input)));

                                                    echo "<th style='text-align:right;font-size:0.7vw'> PENJUALAN TUNAI: " . $RIWAYAT_PENJUALAN_TUNAI_list->nama_cover . "<br/>" . $DATE_DATA . "<br/> " . anchor(site_url('trans_penjualan_tunai/update_penjualan_2024/' . $uuid_sales_PROCESS . '/' . $RIWAYAT_PENJUALAN_TUNAI_list->uuid_process), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Ubah Data Penjualan Tunai', 'class="btn btn-primary btn-sm"')  . "<br/>" . anchor(site_url('trans_penjualan_tunai/cetak_pdf_DYNAMIC/' . $RIWAYAT_PENJUALAN_TUNAI_list->uuid_process), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Cetak Nota (Pdf) ', 'class="btn btn-success btn-sm" target="_blank"') . "</th>";
                                                }
                                            }
                                        }
                                    }
                                    // }
                                    ?>


                                    <!-- PENJUALAN TUNAI -->

                                    <th style="font-size:0.7vw" align="right">TOTAL PENJUALAN TUNAI</th>
                                    <th style="font-size:0.7vw" align="right">TOTAL PENJUALAN</th>
                                    <th style="font-size:0.7vw" align="right">KEKURANGAN PENGIRIMAN</th>

                                    <!-- RETUR -->
                                    <?php
                                    // if ($data_PIVOT) {




                                    $proses_data = "retur";

                                    if (!is_null($jenis_data_selected_PROCESS)) {

                                        if ($jenis_data_selected_PROCESS == "LAMA") {
                                            //    LAMA

                                            $sql = "SELECT trans_pesan_jual_retur_pivot_a.*,
                                                            sys_cover_b.nama_cover as nama_cover,                            
                                                            sys_cover_b.keterangan as keterangan                            
                                                            FROM trans_pesan_jual_retur_pivot trans_pesan_jual_retur_pivot_a 
                                                            left join  sys_cover sys_cover_b ON sys_cover_b.uuid_cover = trans_pesan_jual_retur_pivot_a.uuid_cover
                                                    
                                                            WHERE trans_pesan_jual_retur_pivot_a.uuid_sales = '$uuid_sales_PROCESS' 
                                                            AND trans_pesan_jual_retur_pivot_a.tahun_pesanan = '$get_thn_selected'
                                                            AND trans_pesan_jual_retur_pivot_a.semester_pesanan = '$get_semester_selected' 
                                                            AND trans_pesan_jual_retur_pivot_a.tingkat_pesanan = '$data_tingkat' 
                                                            AND trans_pesan_jual_retur_pivot_a.nama_process = '$proses_data' 
                                                            -- AND sys_cover_b.keterangan = '$keterangan_status_buku'
                        
                                                            ORDER BY trans_pesan_jual_retur_pivot_a.date_input ASC ";
                                        } else {

                                            // BARU

                                            $sql = "SELECT trans_pesan_jual_retur_pivot_a.*,
                                                        sys_cover_b.nama_cover as nama_cover,
                                                        sys_cover_b.keterangan as keterangan                            
                                                        
                                                        FROM trans_pesan_jual_retur_pivot trans_pesan_jual_retur_pivot_a 
                                                        left join  sys_cover sys_cover_b ON sys_cover_b.uuid_cover = trans_pesan_jual_retur_pivot_a.uuid_cover
                                                
                                                        WHERE trans_pesan_jual_retur_pivot_a.uuid_sales = '$uuid_sales_PROCESS' 
                                                        AND trans_pesan_jual_retur_pivot_a.tahun_pesanan = '$get_thn_selected'
                                                        AND trans_pesan_jual_retur_pivot_a.semester_pesanan = '$get_semester_selected' 
                                                        AND trans_pesan_jual_retur_pivot_a.tingkat_pesanan = '$data_tingkat' 
                                                        AND trans_pesan_jual_retur_pivot_a.nama_process = '$proses_data' 
                                                        -- AND sys_cover_b.keterangan <> '$keterangan_status_buku'
                    
                                                        ORDER BY trans_pesan_jual_retur_pivot_a.date_input ASC ";
                                        }
                                    } else {

                                        // BARU

                                        $sql = "SELECT trans_pesan_jual_retur_pivot_a.*,
                                                    sys_cover_b.nama_cover as nama_cover,
                                                    sys_cover_b.keterangan as keterangan                            
                                                    
                                                    FROM trans_pesan_jual_retur_pivot trans_pesan_jual_retur_pivot_a 
                                                    left join  sys_cover sys_cover_b ON sys_cover_b.uuid_cover = trans_pesan_jual_retur_pivot_a.uuid_cover
                                            
                                                    WHERE trans_pesan_jual_retur_pivot_a.uuid_sales = '$uuid_sales_PROCESS' 
                                                    AND trans_pesan_jual_retur_pivot_a.tahun_pesanan = '$get_thn_selected'
                                                    AND trans_pesan_jual_retur_pivot_a.semester_pesanan = '$get_semester_selected' 
                                                    AND trans_pesan_jual_retur_pivot_a.tingkat_pesanan = '$data_tingkat' 
                                                    AND trans_pesan_jual_retur_pivot_a.nama_process = '$proses_data' 
                                                    -- AND sys_cover_b.keterangan <> '$keterangan_status_buku'
                
                                                    ORDER BY trans_pesan_jual_retur_pivot_a.date_input ASC ";
                                    }

                                    // print_r("JOIN TUNAI BUKU LAMA");

                                    $data_PIVOT_retur_per_sales_per_tingkat = $this->db->query($sql);

                                    // print_r("TUNAI ");
                                    // print_r("<br/>");
                                    // print_r($data_PIVOT_retur_per_sales_per_tingkat->result());
                                    // print_r("<br/>");
                                    // print_r("<br/>");
                                    // print_r("<br/>");

                                    $data_PIVOT_retur_per_sales_per_tingkat = $this->db->query($sql);

                                    if ($data_PIVOT_retur_per_sales_per_tingkat->result()) {
                                        foreach ($data_PIVOT_retur_per_sales_per_tingkat->result() as $RIWAYAT_RETUR_list) {

                                            $this->db->where('uuid_cover', $RIWAYAT_RETUR_list->uuid_cover);
                                            $get_sys_cover = $this->db->get('sys_cover')->row_array();

                                            if ($jenis_data_selected_PROCESS == "BARU" and $get_sys_cover['keterangan'] <> "buku_lama") {

                                                if (!empty($RIWAYAT_RETUR_list->date_input) or !empty($RIWAYAT_RETUR_list->date_input)) {

                                                    $DATE_DATA = date('Y-m-d', strtotime('0 day', strtotime($RIWAYAT_RETUR_list->date_input)));

                                                    echo "<th style='text-align:right;font-size:0.7vw'> RETUR: " . $RIWAYAT_PENJUALAN_TUNAI_list->nama_cover . "<br/>" . $DATE_DATA . "<br/> " .
                                                        anchor(site_url('trans_retur/update_retur_DYNAMIC_2024/' . $uuid_sales_PROCESS . "/" . $RIWAYAT_RETUR_list->uuid_process), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Ubah Data Retur', 'class="btn btn-primary btn-sm"')  . "</th>";
                                                }
                                            } elseif ($jenis_data_selected_PROCESS == "LAMA" and $get_sys_cover['keterangan'] == "buku_lama") {

                                                if (!empty($RIWAYAT_RETUR_list->date_input) or !empty($RIWAYAT_RETUR_list->date_input)) {
                                                    $DATE_DATA = date('Y-m-d', strtotime('0 day', strtotime($RIWAYAT_RETUR_list->date_input)));

                                                    echo "<th style='text-align:right;font-size:0.7vw'> RETUR: " . $RIWAYAT_PENJUALAN_TUNAI_list->nama_cover . "<br/>" . $DATE_DATA . "<br/> " .
                                                        anchor(site_url('trans_retur/update_retur_DYNAMIC_2024/' . $uuid_sales_PROCESS . "/" . $RIWAYAT_RETUR_list->uuid_process), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Ubah Data Retur', 'class="btn btn-primary btn-sm"')  . "</th>";
                                                }
                                            }
                                        }
                                    }
                                    // }
                                    ?>

                                    <th style="font-size:0.7vw" align="right">TOTAL RETUR</th>

                                    <th style="font-size:0.7vw" align="right">TOTAL PENJUALAN BERSIH</th>


                                </tr>
                            </thead>

                            <!-- body -->

                            <tbody>
                                <?php
                                $start = 0;
                                $total_mapel = 0;
                                $total_PENJUALAN_ALL = 0;


                                $TOTAL_PENJUALAN_64 = 0;
                                $TOTAL_PENJUALAN_96 = 0;

                                $TOTAL_PENJUALAN_TUNAI_64 = 0;
                                $TOTAL_PENJUALAN_TUNAI_96 = 0;

                                $TOTAL_PESANAN_64 = 0;
                                $TOTAL_PESANAN_96 = 0;

                                $TOTAL_RETUR_64 = 0;
                                $TOTAL_RETUR_96 = 0;

                                $TOTAL_PENJUALAN_BERSIH_64 = 0;
                                $TOTAL_PENJUALAN_BERSIH_96 = 0;
                                foreach ($data_RIWAYAT as $data_RIWAYAT_list) {
                                ?>
                                    <tr>
                                        <td style="font-size:0.7vw" width="10px"><?php echo ++$start ?></td>
                                        <td style="font-size:0.7vw" width="10px"> <?php echo $data_RIWAYAT_list->mapel; ?> </td>

                                        <?php
                                        // if ($data_PIVOT) {

                                        if ($data_PIVOT_pesan_per_sales_per_tingkat->num_rows() > 0) {
                                            // PEMESANAN
                                            $total_PERMAPEL_PESANAN_64 = 0;
                                            $total_PERMAPEL_PESANAN_96 = 0;
                                            $Total_Pesanan_perMapel = 0;
                                            foreach ($data_PIVOT_pesan_per_sales_per_tingkat->result() as $RIWAYAT_PEMESANAN_list) {

                                                $this->db->where('uuid_cover', $RIWAYAT_PEMESANAN_list->uuid_cover);
                                                $get_sys_cover = $this->db->get('sys_cover')->row_array();

                                                if ($jenis_data_selected_PROCESS == "BARU" and $get_sys_cover['keterangan'] <> "buku_lama") {
                                                    $x_pemesanan_64 = $RIWAYAT_PEMESANAN_list->date_input . "_PESANAN_64";
                                                    $x_pemesanan_96 = $RIWAYAT_PEMESANAN_list->date_input . "_PESANAN_96";
                                                    $xfield = $RIWAYAT_PEMESANAN_list->field_name;
                                                    if (!empty($xfield)) {
                                                        echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($data_RIWAYAT_list->$xfield) . "</td>";
                                                        $Total_Pesanan_perMapel = $Total_Pesanan_perMapel + $data_RIWAYAT_list->$xfield;
                                                    } else {
                                                        echo "<td style='text-align:right;font-size:0.7vw'>" . 0 . "</td>";
                                                    }
                                                } elseif ($jenis_data_selected_PROCESS == "LAMA" and $get_sys_cover['keterangan'] == "buku_lama") {
                                                    $x_pemesanan_64 = $RIWAYAT_PEMESANAN_list->date_input . "_PESANAN_64";
                                                    $x_pemesanan_96 = $RIWAYAT_PEMESANAN_list->date_input . "_PESANAN_96";
                                                    $xfield = $RIWAYAT_PEMESANAN_list->field_name;
                                                    if (!empty($RIWAYAT_PEMESANAN_list->date_input)  or !empty($RIWAYAT_PEMESANAN_list->date_input)) {
                                                        echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($data_RIWAYAT_list->$xfield) . "</td>";
                                                        $Total_Pesanan_perMapel = $Total_Pesanan_perMapel + $data_RIWAYAT_list->$xfield;
                                                    } else {
                                                        echo "<td style='text-align:right;font-size:0.7vw'>" . 0 . "</td>";
                                                    }
                                                }
                                            }
                                        }
                                        // }
                                        ?>

                                        <!-- TOTAL PESANAN -->
                                        <td style="text-align:right;font-size:0.7vw" width="10px"> <?php
                                                                                                    echo "<strong>" . nominal($Total_Pesanan_perMapel) . "</strong>";
                                                                                                    // $TOTAL_PESANAN_64 = $TOTAL_PESANAN_64 + $total_PERMAPEL_PESANAN_64;
                                                                                                    // $TOTAL_PESANAN_96 = $TOTAL_PESANAN_96 + $total_PERMAPEL_PESANAN_96;                                                                                                
                                                                                                    ?> </td>

                                        <!-- PENJUALAN PER TANGGAL INPUT -->
                                        <?php
                                        $total_PERMAPEL_PENJUALAN_64 = 0;
                                        $total_PERMAPEL_PENJUALAN_96 = 0;
                                        $Total_Penjualan_perMapel = 0;
                                        // if ($data_PIVOT) {
                                        if ($data_PIVOT_penjualan_per_sales_per_tingkat->result()) {
                                            foreach ($data_PIVOT_penjualan_per_sales_per_tingkat->result() as $RIWAYAT_PENJUALAN_list) {


                                                $this->db->where('uuid_cover', $RIWAYAT_PENJUALAN_list->uuid_cover);
                                                $get_sys_cover = $this->db->get('sys_cover')->row_array();

                                                if ($jenis_data_selected_PROCESS == "BARU" and $get_sys_cover['keterangan'] <> "buku_lama") {

                                                    $x_penjualan_64 = $RIWAYAT_PENJUALAN_list->date_input . "_PENJUALAN_64";
                                                    $x_penjualan_96 = $RIWAYAT_PENJUALAN_list->date_input . "_PENJUALAN_96";

                                                    if (!empty($RIWAYAT_PENJUALAN_list->date_input) or !empty($RIWAYAT_PENJUALAN_list->date_input)) {

                                                        // echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($data_RIWAYAT_list->$x_penjualan_64 + $data_RIWAYAT_list->$x_penjualan_96) . "</td>";
                                                        $xfield = $RIWAYAT_PENJUALAN_list->field_name;
                                                        echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($data_RIWAYAT_list->$xfield) . "</td>";

                                                        //$total_PERMAPEL_PENJUALAN_64 = $total_PERMAPEL_PENJUALAN_64 + ($data_RIWAYAT_list->$x_penjualan_64);
                                                        //$total_PERMAPEL_PENJUALAN_96 = $total_PERMAPEL_PENJUALAN_96 + ($data_RIWAYAT_list->$x_penjualan_96);

                                                        $Total_Penjualan_perMapel = $Total_Penjualan_perMapel + $data_RIWAYAT_list->$xfield;
                                                    } else {
                                                        echo "<td style='text-align:right;font-size:0.7vw'> 0 </td>";
                                                    }
                                                } elseif ($jenis_data_selected_PROCESS == "LAMA" and $get_sys_cover['keterangan'] == "buku_lama") {

                                                    $x_penjualan_64 = $RIWAYAT_PENJUALAN_list->date_input . "_PENJUALAN_64";
                                                    $x_penjualan_96 = $RIWAYAT_PENJUALAN_list->date_input . "_PENJUALAN_96";
                                                    if (!empty($RIWAYAT_PENJUALAN_list->date_input) or !empty($RIWAYAT_PENJUALAN_list->date_input)) {
                                                        // $x_penjualan_64_96 = $data_RIWAYAT_list->$x_penjualan_64 + $data_RIWAYAT_list->$x_penjualan_96;

                                                        // echo "<td style='text-align:right;font-size:0.7vw'> " . nominal($data_RIWAYAT_list->$x_penjualan_64 + $data_RIWAYAT_list->$x_penjualan_96) . "</td>";
                                                        $xfield = $RIWAYAT_PENJUALAN_list->field_name;
                                                        echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($data_RIWAYAT_list->$xfield) . "</td>";

                                                        //$total_PERMAPEL_PENJUALAN_64 = $total_PERMAPEL_PENJUALAN_64 + ($data_RIWAYAT_list->$x_penjualan_64);
                                                        //$total_PERMAPEL_PENJUALAN_96 = $total_PERMAPEL_PENJUALAN_96 + ($data_RIWAYAT_list->$x_penjualan_96);
                                                        $Total_Penjualan_perMapel = $Total_Penjualan_perMapel + $data_RIWAYAT_list->$xfield;
                                                    } else {
                                                        echo "<td style='text-align:right;font-size:0.7vw'> 0 </td>";
                                                    }
                                                }
                                            }
                                        }
                                        // }
                                        // else {
                                        //     echo "<th style='text-align:right;font-size:0.7vw'> X0  </th>";
                                        // }
                                        ?>



                                        <!-- PENJUALAN TUNAI PER TANGGAL INPUT -->
                                        <?php
                                        $total_PERMAPEL_PENJUALAN_TUNAI_64 = 0;
                                        $total_PERMAPEL_PENJUALAN_TUNAI_96 = 0;
                                        $Total_Tunai_perMapel = 0;
                                        // if ($data_PIVOT) {
                                        if ($data_PIVOT_tunai_per_sales_per_tingkat->result()) {
                                            foreach ($data_PIVOT_tunai_per_sales_per_tingkat->result() as $RIWAYAT_PENJUALAN_TUNAI_list) {


                                                $this->db->where('uuid_cover', $RIWAYAT_PENJUALAN_TUNAI_list->uuid_cover);
                                                $get_sys_cover = $this->db->get('sys_cover')->row_array();

                                                if ($jenis_data_selected_PROCESS == "BARU" and $get_sys_cover['keterangan'] <> "buku_lama") {

                                                    $x_penjualan_64 = $RIWAYAT_PENJUALAN_TUNAI_list->date_input . "_PENJUALAN_TUNAI_64";
                                                    $x_penjualan_96 = $RIWAYAT_PENJUALAN_TUNAI_list->date_input . "_PENJUALAN_TUNAI_96";

                                                    if (!empty($RIWAYAT_PENJUALAN_TUNAI_list->date_input) or !empty($RIWAYAT_PENJUALAN_TUNAI_list->date_input)) {
                                                        // $x_penjualan_64_96 = $data_RIWAYAT_list->$x_penjualan_64 + $data_RIWAYAT_list->$x_penjualan_96;

                                                        //echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($data_RIWAYAT_list->$x_penjualan_64 + $data_RIWAYAT_list->$x_penjualan_96) . "</td>";
                                                        $xfield = $RIWAYAT_PENJUALAN_TUNAI_list->field_name;
                                                        echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($data_RIWAYAT_list->$xfield) . "</td>";

                                                        //$total_PERMAPEL_PENJUALAN_TUNAI_64 = $total_PERMAPEL_PENJUALAN_TUNAI_64 + ($data_RIWAYAT_list->$x_penjualan_64);
                                                        //$total_PERMAPEL_PENJUALAN_TUNAI_96 = $total_PERMAPEL_PENJUALAN_TUNAI_96 + ($data_RIWAYAT_list->$x_penjualan_96);
                                                        $Total_Tunai_perMapel = $Total_Tunai_perMapel + $data_RIWAYAT_list->$xfield;
                                                    } else {
                                                        echo "<td style='text-align:right;font-size:0.7vw'> 0 </td>";
                                                    }
                                                } elseif ($jenis_data_selected_PROCESS == "LAMA" and $get_sys_cover['keterangan'] == "buku_lama") {

                                                    $x_penjualan_64 = $RIWAYAT_PENJUALAN_TUNAI_list->date_input . "_PENJUALAN_64";
                                                    $x_penjualan_96 = $RIWAYAT_PENJUALAN_TUNAI_list->date_input . "_PENJUALAN_96";

                                                    if (!empty($RIWAYAT_PENJUALAN_TUNAI_list->date_input) or !empty($RIWAYAT_PENJUALAN_TUNAI_list->date_input)) {
                                                        // $x_penjualan_64_96 = $data_RIWAYAT_list->$x_penjualan_64 + $data_RIWAYAT_list->$x_penjualan_96;

                                                        //echo "<td style='text-align:right;font-size:0.7vw'> " . nominal($data_RIWAYAT_list->$x_penjualan_64 + $data_RIWAYAT_list->$x_penjualan_96) . "</td>";
                                                        $xfield = $RIWAYAT_PENJUALAN_TUNAI_list->field_name;
                                                        echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($data_RIWAYAT_list->$xfield) . "</td>";

                                                        //$total_PERMAPEL_PENJUALAN_TUNAI_64 = $total_PERMAPEL_PENJUALAN_TUNAI_64 + ($data_RIWAYAT_list->$x_penjualan_64);
                                                        //$total_PERMAPEL_PENJUALAN_TUNAI_96 = $total_PERMAPEL_PENJUALAN_TUNAI_96 + ($data_RIWAYAT_list->$x_penjualan_96);
                                                        $Total_Tunai_perMapel = $Total_Tunai_perMapel + $data_RIWAYAT_list->$xfield;
                                                    } else {
                                                        echo "<td style='text-align:right;font-size:0.7vw'> 0 </td>";
                                                    }
                                                }
                                            }
                                        }
                                        // }
                                        // else {
                                        //     echo "<th style='text-align:right;font-size:0.7vw'> X0  </th>";
                                        // }
                                        ?>

                                        <!-- PENJUALAN TUNAI TOTAL -->
                                        <td style="text-align:right;font-size:0.7vw" width="10px"> <?php
                                                                                                    //echo nominal($total_PERMAPEL_PENJUALAN_TUNAI_64 + $total_PERMAPEL_PENJUALAN_TUNAI_96);
                                                                                                    echo nominal($Total_Tunai_perMapel);

                                                                                                    //$TOTAL_PENJUALAN_TUNAI_64 = $TOTAL_PENJUALAN_TUNAI_64 + $total_PERMAPEL_PENJUALAN_TUNAI_64;

                                                                                                    //$TOTAL_PENJUALAN_TUNAI_96 = $TOTAL_PENJUALAN_TUNAI_96 + $total_PERMAPEL_PENJUALAN_TUNAI_96;
                                                                                                    ?>
                                        </td>


                                        <!-- PENJUALAN TOTAL -->
                                        <td style="text-align:right;font-size:0.7vw" width="10px"> <?php
                                                                                                    // echo "<strong>" . nominal($total_PERMAPEL_PENJUALAN_64 + $total_PERMAPEL_PENJUALAN_96 + $total_PERMAPEL_PENJUALAN_TUNAI_64 + $total_PERMAPEL_PENJUALAN_TUNAI_96) . "</strong>";
                                                                                                    echo "<strong>" . nominal($Total_Penjualan_perMapel + $Total_Tunai_perMapel) . "</strong>";

                                                                                                    //$TOTAL_PENJUALAN_64 = $TOTAL_PENJUALAN_64 + $total_PERMAPEL_PENJUALAN_64;
                                                                                                    //$TOTAL_PENJUALAN_96 = $TOTAL_PENJUALAN_96 + $total_PERMAPEL_PENJUALAN_96;
                                                                                                    ?>
                                        </td>

                                        <!-- KEKURANGAN PENGIRIMAN TOTAL -->
                                        <td style="text-align:right;font-size:0.7vw;color:red" width="10px"> <?php
                                                                                                                // echo nominal(($total_PERMAPEL_PESANAN_64 + $total_PERMAPEL_PESANAN_96) - ($total_PERMAPEL_PENJUALAN_64 + $total_PERMAPEL_PENJUALAN_96)); 

                                                                                                                if (($Total_Pesanan_perMapel - ($Total_Penjualan_perMapel + $Total_Tunai_perMapel)) > 0) {
                                                                                                                    echo "- " . nominal($Total_Pesanan_perMapel - ($Total_Penjualan_perMapel + $Total_Tunai_perMapel));
                                                                                                                } else {
                                                                                                                    echo 0;
                                                                                                                }
                                                                                                                ?> </td>

                                        <!-- RETUR PER TANGGAL INPUT-->
                                        <?php
                                        $total_permapel_RETUR_64 = 0;
                                        $total_permapel_RETUR_96 = 0;
                                        $Total_Retur_perMapel = 0;
                                        // if ($data_PIVOT) {
                                        if ($data_PIVOT_retur_per_sales_per_tingkat->result()) {
                                            foreach ($data_PIVOT_retur_per_sales_per_tingkat->result() as $RIWAYAT_RETUR_list) {


                                                $this->db->where('uuid_cover', $RIWAYAT_RETUR_list->uuid_cover);
                                                $get_sys_cover = $this->db->get('sys_cover')->row_array();

                                                if ($jenis_data_selected_PROCESS == "BARU" and $get_sys_cover['keterangan'] <> "buku_lama") {

                                                    $x_retur_64 = $RIWAYAT_RETUR_list->date_input . "_RETUR_64";
                                                    $x_retur_96 = $RIWAYAT_RETUR_list->date_input . "_RETUR_96";
                                                    if (!empty($RIWAYAT_RETUR_list->date_input) or !empty($RIWAYAT_RETUR_list->date_input)) {
                                                        // $x_retur_64_96 = $data_RIWAYAT_list->$x_retur_64 + $data_RIWAYAT_list->$x_retur_96;

                                                        //echo "<th style='text-align:right;font-size:0.7vw'>" . nominal($data_RIWAYAT_list->$x_retur_64 + $data_RIWAYAT_list->$x_retur_96) . "</th>";
                                                        $xfield = $RIWAYAT_RETUR_list->field_name;
                                                        echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($data_RIWAYAT_list->$xfield) . "</td>";

                                                        //$total_permapel_RETUR_64 = $total_permapel_RETUR_64 + ($data_RIWAYAT_list->$x_retur_64);
                                                        //$total_permapel_RETUR_96 = $total_permapel_RETUR_96 + ($data_RIWAYAT_list->$x_retur_96);
                                                        $Total_Retur_perMapel = $Total_Retur_perMapel + $data_RIWAYAT_list->$xfield;
                                                    } else {
                                                        echo "<th style='text-align:right;font-size:0.7vw'>" . 0 . "</th>";
                                                    }
                                                } elseif ($jenis_data_selected_PROCESS == "LAMA" and $get_sys_cover['keterangan'] == "buku_lama") {

                                                    $x_retur_64 = $RIWAYAT_RETUR_list->date_input . "_RETUR_64";
                                                    $x_retur_96 = $RIWAYAT_RETUR_list->date_input . "_RETUR_96";
                                                    if (!empty($RIWAYAT_RETUR_list->date_input) or !empty($RIWAYAT_RETUR_list->date_input)) {
                                                        // $x_retur_64_96 = $data_RIWAYAT_list->$x_retur_64 + $data_RIWAYAT_list->$x_retur_96;

                                                        //echo "<th style='text-align:right;font-size:0.7vw'>" . nominal($data_RIWAYAT_list->$x_retur_64 + $data_RIWAYAT_list->$x_retur_96) . "</th>";
                                                        $xfield = $RIWAYAT_RETUR_list->field_name;
                                                        echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($data_RIWAYAT_list->$xfield) . "</td>";

                                                        //$total_permapel_RETUR_64 = $total_permapel_RETUR_64 + ($data_RIWAYAT_list->$x_retur_64);
                                                        //$total_permapel_RETUR_96 = $total_permapel_RETUR_96 + ($data_RIWAYAT_list->$x_retur_96);
                                                        $Total_Retur_perMapel = $Total_Retur_perMapel + $data_RIWAYAT_list->$xfield;
                                                    } else {
                                                        echo "<th style='text-align:right;font-size:0.7vw'>" . 0 . "</th>";
                                                    }
                                                }
                                            }
                                        }
                                        // } else {
                                        //     // echo "<th style='text-align:right;font-size:0.7vw'>" . 0 . "</th>";
                                        //     $total_RETUR = 0;
                                        // }
                                        ?>

                                        <!-- RETUR TOTAL -->
                                        <td style="text-align:right;font-size:0.7vw;color:blue" width="10px"> <?php
                                                                                                                //echo nominal($total_permapel_RETUR_64 + $total_permapel_RETUR_96);
                                                                                                                echo "<strong>" . nominal($Total_Retur_perMapel) . "</strong>";
                                                                                                                //$TOTAL_RETUR_64 = $TOTAL_RETUR_64 + $total_permapel_RETUR_64;
                                                                                                                //$TOTAL_RETUR_96 = $TOTAL_RETUR_96 + $total_permapel_RETUR_96;
                                                                                                                ?> </td>




                                        <!-- TOTAL PENJUALAN BERSIH -->
                                        <td style="text-align:right;font-size:0.7vw" width="10px"> <?php
                                                                                                    //echo nominal(($total_PERMAPEL_PENJUALAN_64 + $total_PERMAPEL_PENJUALAN_96) - ($total_permapel_RETUR_64 + $total_permapel_RETUR_96) + ($total_PERMAPEL_PENJUALAN_TUNAI_64 + $total_PERMAPEL_PENJUALAN_TUNAI_96));
                                                                                                    echo "<strong>" . nominal(($Total_Penjualan_perMapel + $Total_Tunai_perMapel) - $Total_Retur_perMapel) . "</strong>";

                                                                                                    //$TOTAL_PENJUALAN_BERSIH_64 = $TOTAL_PENJUALAN_BERSIH_64 + ($total_PERMAPEL_PENJUALAN_64 - $total_permapel_RETUR_64 + $TOTAL_PENJUALAN_TUNAI_64);

                                                                                                    //$TOTAL_PENJUALAN_BERSIH_96 = $TOTAL_PENJUALAN_BERSIH_96 + ($total_PERMAPEL_PENJUALAN_96 - $total_permapel_RETUR_96 + $TOTAL_PENJUALAN_TUNAI_96);
                                                                                                    ?> </td>

                                    </tr>
                                <?php
                                    // }
                                }
                                ?>


                            </tbody>


                            <!-- body -->


                            <!-- TFOOT -->


                            <tfoot>

                                <?php
                                $sql = "SELECT `jumlah_halaman` as jumlah_halaman FROM `trans_pesan_jual_retur_riwayat`GROUP BY `jumlah_halaman` order BY `jumlah_halaman` ASC";
                                foreach ($this->db->query($sql)->result() as $JUMLAH_HALAMAN_list) {
                                ?>


                                    <tr>
                                        <?php
                                        $X_jmlh_halaman = $JUMLAH_HALAMAN_list->jumlah_halaman;
                                        // $X_jmlh_halaman = "64";
                                        ?>
                                        <th style="font-size:0.7vw" width="10px"></th>

                                        <th style="font-size:0.7vw" width="110px">TOTAL <?php echo $X_jmlh_halaman; ?></th>

                                        <!-- RIWAYAT PEMESANAN -->
                                        <?php





                                        if ($data_PIVOT_pesan_per_sales_per_tingkat->num_rows() > 0) {

                                            if ($data_PIVOT_pesan_per_sales_per_tingkat->result()) {
                                                $Total_pemesanan = 0;
                                                foreach ($data_PIVOT_pesan_per_sales_per_tingkat->result() as $RIWAYAT_PEMESANAN_list) {

                                                    $this->db->where('uuid_cover', $RIWAYAT_PEMESANAN_list->uuid_cover);
                                                    $get_sys_cover = $this->db->get('sys_cover')->row_array();

                                                    if ($jenis_data_selected_PROCESS == "BARU" and $get_sys_cover['keterangan'] <> "buku_lama") {

                                                        if (!empty($RIWAYAT_PEMESANAN_list->date_input) or !empty($RIWAYAT_PEMESANAN_list->date_input)) {

                                                            $sql = "SELECT SUM($RIWAYAT_PEMESANAN_list->field_name) AS data_field FROM `trans_pesan_jual_retur_riwayat` WHERE `uuid_sales`='$uuid_sales_PROCESS' AND `tahun_pesanan` = '$get_thn_selected' AND `semester_pesanan` = '$get_semester_selected' AND `tingkat_pesanan` = '$data_tingkat' AND `jumlah_halaman`='$X_jmlh_halaman' GROUP BY `jumlah_halaman`";

                                                            if (isset($this->db->query($sql)->row()->data_field)) {
                                                                echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($this->db->query($sql)->row()->data_field) . "</td>";
                                                                $Total_pemesanan = $Total_pemesanan + $this->db->query($sql)->row()->data_field;
                                                            } else {
                                                                echo "<td style='text-align:right;font-size:0.7vw'>0</td>";
                                                            }
                                                        }
                                                    } elseif ($jenis_data_selected_PROCESS == "LAMA" and $get_sys_cover['keterangan'] == "buku_lama") {

                                                        if (!empty($RIWAYAT_PEMESANAN_list->date_input) or !empty($RIWAYAT_PEMESANAN_list->date_input)) {
                                                            $sql = "SELECT SUM($RIWAYAT_PEMESANAN_list->field_name) AS data_field FROM `trans_pesan_jual_retur_riwayat` WHERE `uuid_sales`='$uuid_sales_PROCESS' AND `tahun_pesanan` = '$get_thn_selected' AND `semester_pesanan` = '$get_semester_selected' AND `tingkat_pesanan` = '$data_tingkat' AND `jumlah_halaman`='$X_jmlh_halaman' GROUP BY `jumlah_halaman`";

                                                            if (isset($this->db->query($sql)->row()->data_field)) {
                                                                echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($this->db->query($sql)->row()->data_field) . "</td>";
                                                                $Total_pemesanan = $Total_pemesanan + $this->db->query($sql)->row()->data_field;
                                                            } else {
                                                                echo "<td style='text-align:right;font-size:0.7vw'>0</td>";
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }




                                        ?>

                                        <th style="text-align:right;font-size:0.7vw"><?php echo nominal($Total_pemesanan); ?></th>

                                        <!-- PENJUALAN -->
                                        <?php







                                        if ($data_PIVOT_penjualan_per_sales_per_tingkat->result()) {
                                            $Total_penjualan = 0;
                                            foreach ($data_PIVOT_penjualan_per_sales_per_tingkat->result() as $RIWAYAT_PENJUALAN_list) {

                                                $this->db->where('uuid_cover', $RIWAYAT_PENJUALAN_list->uuid_cover);
                                                $get_sys_cover = $this->db->get('sys_cover')->row_array();

                                                if ($jenis_data_selected_PROCESS == "BARU" and $get_sys_cover['keterangan'] <> "buku_lama") {

                                                    if (!empty($RIWAYAT_PENJUALAN_list->date_input) or !empty($RIWAYAT_PENJUALAN_list->date_input)) {

                                                        $sql = "SELECT SUM(`$RIWAYAT_PENJUALAN_list->field_name`) AS data_field FROM `trans_pesan_jual_retur_riwayat` WHERE `uuid_sales`='$uuid_sales_PROCESS' AND `tahun_pesanan` = '$get_thn_selected' AND `semester_pesanan` = '$get_semester_selected' AND `tingkat_pesanan` = '$data_tingkat' AND `jumlah_halaman`='$X_jmlh_halaman' GROUP BY `jumlah_halaman`";

                                                        // echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($this->db->query($sql)->row()->data_field) . "</td>";

                                                        if (isset($this->db->query($sql)->row()->data_field)) {
                                                            echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($this->db->query($sql)->row()->data_field) . "</td>";
                                                            $Total_penjualan = $Total_penjualan + $this->db->query($sql)->row()->data_field;
                                                        } else {
                                                            echo "<td style='text-align:right;font-size:0.7vw'>0</td>";
                                                        }
                                                    }
                                                } elseif ($jenis_data_selected_PROCESS == "LAMA" and $get_sys_cover['keterangan'] == "buku_lama") {
                                                    if (!empty($RIWAYAT_PENJUALAN_list->date_input) or !empty($RIWAYAT_PENJUALAN_list->date_input)) {
                                                        $sql = "SELECT SUM(`$RIWAYAT_PENJUALAN_list->field_name`) AS data_field FROM `trans_pesan_jual_retur_riwayat` WHERE `uuid_sales`='$uuid_sales_PROCESS' AND `tahun_pesanan` = '$get_thn_selected' AND `semester_pesanan` = '$get_semester_selected' AND `tingkat_pesanan` = '$data_tingkat' AND `jumlah_halaman`='$X_jmlh_halaman' GROUP BY `jumlah_halaman`";

                                                        // echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($this->db->query($sql)->row()->data_field) . "</td>";

                                                        if (isset($this->db->query($sql)->row()->data_field)) {
                                                            echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($this->db->query($sql)->row()->data_field) . "</td>";
                                                            $Total_penjualan = $Total_penjualan + $this->db->query($sql)->row()->data_field;
                                                        } else {
                                                            echo "<td style='text-align:right;font-size:0.7vw'>0</td>";
                                                        }
                                                    }
                                                }
                                            }
                                        }




                                        ?>




                                        <!-- PENJUALAN TUNAI -->
                                        <?php

                                        if ($data_PIVOT_tunai_per_sales_per_tingkat->result()) {
                                            $Total_penjualan_tunai = 0;
                                            foreach ($data_PIVOT_tunai_per_sales_per_tingkat->result() as $RIWAYAT_PENJUALAN_TUNAI_list) {

                                                $this->db->where('uuid_cover', $RIWAYAT_PENJUALAN_TUNAI_list->uuid_cover);
                                                $get_sys_cover = $this->db->get('sys_cover')->row_array();

                                                if ($jenis_data_selected_PROCESS == "BARU" and $get_sys_cover['keterangan'] <> "buku_lama") {

                                                    if (!empty($RIWAYAT_PENJUALAN_TUNAI_list->date_input) or !empty($RIWAYAT_PENJUALAN_TUNAI_list->date_input)) {

                                                        $sql = "SELECT SUM(`$RIWAYAT_PENJUALAN_TUNAI_list->field_name`) AS data_field FROM `trans_pesan_jual_retur_riwayat` WHERE `uuid_sales`='$uuid_sales_PROCESS' AND `tahun_pesanan` = '$get_thn_selected' AND `semester_pesanan` = '$get_semester_selected' AND `tingkat_pesanan` = '$data_tingkat' AND `jumlah_halaman`='$X_jmlh_halaman' GROUP BY `jumlah_halaman`";

                                                        if (isset($this->db->query($sql)->row()->data_field)) {
                                                            echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($this->db->query($sql)->row()->data_field) . "</td>";
                                                            $Total_penjualan_tunai = $Total_penjualan_tunai + $this->db->query($sql)->row()->data_field;
                                                        } else {
                                                            echo "<td style='text-align:right;font-size:0.7vw'>0</td>";
                                                        }
                                                    }
                                                } elseif ($jenis_data_selected_PROCESS == "LAMA" and $get_sys_cover['keterangan'] == "buku_lama") {

                                                    if (!empty($RIWAYAT_PENJUALAN_TUNAI_list->date_input) or !empty($RIWAYAT_PENJUALAN_TUNAI_list->date_input)) {
                                                        $sql = "SELECT SUM(`$RIWAYAT_PENJUALAN_TUNAI_list->field_name`) AS data_field FROM `trans_pesan_jual_retur_riwayat` WHERE `uuid_sales`='$uuid_sales_PROCESS' AND `tahun_pesanan` = '$get_thn_selected' AND `semester_pesanan` = '$get_semester_selected' AND `tingkat_pesanan` = '$data_tingkat' AND `jumlah_halaman`='$X_jmlh_halaman' GROUP BY `jumlah_halaman`";

                                                        if (isset($this->db->query($sql)->row()->data_field)) {
                                                            echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($this->db->query($sql)->row()->data_field) . "</td>";
                                                            $Total_penjualan_tunai = $Total_penjualan_tunai + $this->db->query($sql)->row()->data_field;
                                                        } else {
                                                            echo "<td style='text-align:right;font-size:0.7vw'>0</td>";
                                                        }
                                                    }
                                                }
                                            }
                                        }



                                        ?>


                                        <!-- PENJUALAN TUNAI -->

                                        <th style="font-size:0.7vw;text-align:right"><?php echo nominal($Total_penjualan_tunai); ?></th>
                                        <th style="font-size:0.7vw;text-align:right"><?php echo nominal($Total_penjualan + $Total_penjualan_tunai); ?></th>

                                        <!-- KEKURANGAN PENGIRIMAN -->
                                        <!-- <th style="font-size:0.7vw;text-align:right;color:red"> -->
                                        <?php
                                        $x_total = $Total_pemesanan - ($Total_penjualan + $Total_penjualan_tunai);
                                        if (($x_total) > 0) {
                                            // echo nominal($x_total);
                                            echo "<td style='text-align:right;font-size:0.7vw;color:red'>" . nominal($x_total) . "</td>";
                                        } else {
                                            // echo 0;
                                            echo "<td style='text-align:right;font-size:0.7vw'>0</td>";
                                        }

                                        ?>
                                        <!-- </th> -->

                                        <!-- RETUR -->
                                        <?php



                                        if ($data_PIVOT_retur_per_sales_per_tingkat->result()) {
                                            $Total_retur = 0;
                                            foreach ($data_PIVOT_retur_per_sales_per_tingkat->result() as $RIWAYAT_RETUR_list) {

                                                $this->db->where('uuid_cover', $RIWAYAT_RETUR_list->uuid_cover);
                                                $get_sys_cover = $this->db->get('sys_cover')->row_array();

                                                if ($jenis_data_selected_PROCESS == "BARU" and $get_sys_cover['keterangan'] <> "buku_lama") {

                                                    if (!empty($RIWAYAT_RETUR_list->date_input) or !empty($RIWAYAT_RETUR_list->date_input)) {

                                                        $sql = "SELECT SUM(`$RIWAYAT_RETUR_list->field_name`) AS data_field FROM `trans_pesan_jual_retur_riwayat` WHERE `uuid_sales`='$uuid_sales_PROCESS' AND `tahun_pesanan` = '$get_thn_selected' AND `semester_pesanan` = '$get_semester_selected' AND `tingkat_pesanan` = '$data_tingkat' AND `jumlah_halaman`='$X_jmlh_halaman' GROUP BY `jumlah_halaman`";

                                                        if (isset($this->db->query($sql)->row()->data_field)) {
                                                            echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($this->db->query($sql)->row()->data_field) . "</td>";
                                                            $Total_retur = $Total_retur + $this->db->query($sql)->row()->data_field;
                                                        } else {
                                                            echo "<td style='text-align:right;font-size:0.7vw'>0</td>";
                                                        }
                                                    }
                                                } elseif ($jenis_data_selected_PROCESS == "LAMA" and $get_sys_cover['keterangan'] == "buku_lama") {

                                                    if (!empty($RIWAYAT_RETUR_list->date_input) or !empty($RIWAYAT_RETUR_list->date_input)) {
                                                        $sql = "SELECT SUM(`$RIWAYAT_RETUR_list->field_name`) AS data_field FROM `trans_pesan_jual_retur_riwayat` WHERE `uuid_sales`='$uuid_sales_PROCESS' AND `tahun_pesanan` = '$get_thn_selected' AND `semester_pesanan` = '$get_semester_selected' AND `tingkat_pesanan` = '$data_tingkat' AND `jumlah_halaman`='$X_jmlh_halaman' GROUP BY `jumlah_halaman`";

                                                        if (isset($this->db->query($sql)->row()->data_field)) {
                                                            echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($this->db->query($sql)->row()->data_field) . "</td>";
                                                            $Total_retur = $Total_retur + $this->db->query($sql)->row()->data_field;
                                                        } else {
                                                            echo "<td style='text-align:right;font-size:0.7vw'>0</td>";
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        ?>

                                        <th style="font-size:0.7vw;text-align:right"><?php echo nominal($Total_retur); ?></th>

                                        <th style="font-size:0.7vw;text-align:right"><?php echo nominal(($Total_penjualan + $Total_penjualan_tunai) - $Total_retur); ?></th>


                                    </tr>
                                <?php
                                }
                                ?>

                            </tfoot>


                            <!-- TFOOT -->

                        </table>

                        <?php
                        // print_r($waktu_buka);
                        // print_r("<br/>");
                        // print_r("TABEL TERBUKA = ");
                        // print_r(date("H:i:s"));
                        // print_r("<br/>");
                        ?>

                        <!-- END OF TABLE CONTENT -->

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
            "scrollY": 400,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 400,
            "scrollX": true
        });
    });
</script>