<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }

    .nav-tabs.jurnal-penjualan-tabs {
        border-bottom: 2px solid #ffc107;
        margin-bottom: 15px;
    }

    .nav-tabs.jurnal-penjualan-tabs .nav-item {
        margin-bottom: -2px;
    }

    .nav-tabs.jurnal-penjualan-tabs .nav-link {
        background-color: #ffffff;
        border: 2px solid #ffc107;
        border-bottom: none;
        color: #888888;
        font-weight: normal;
        margin-right: 4px;
        border-radius: 4px 4px 0 0;
        opacity: 0.75;
    }

    .nav-tabs.jurnal-penjualan-tabs .nav-link:hover {
        background-color: #f8f9fa;
        color: #666666;
        opacity: 0.9;
    }

    .nav-tabs.jurnal-penjualan-tabs .nav-link.active {
        background-color: #007bff;
        border-color: #ffc107;
        color: #000000;
        font-weight: bold;
        opacity: 1;
    }

    .jurnal-penjualan-unit-block {
        margin-bottom: 40px;
        padding-bottom: 0;
        border-bottom: none;
    }

    .jurnal-penjualan-unit-block:last-child {
        margin-bottom: 0;
    }

    .jurnal-penjualan-unit-title {
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 12px;
        color: #5c4033;
        letter-spacing: 0.5px;
        width: 100%;
    }

    .jurnal-penjualan-unit-actions {
        margin-bottom: 10px;
    }

    .jurnal-penjualan-unit-table-wrap {
        border: 1px solid #ffc107;
        border-radius: 8px;
        padding: 10px;
        background: #fffdf8;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.12);
    }

    .jurnal-penjualan-unit-table-wrap .dataTables_wrapper {
        width: 100%;
        margin: 0;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit {
        border-collapse: collapse;
        width: 100% !important;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit thead th {
        background-color: #e8d4b8 !important;
        color: #4a3728 !important;
        font-weight: 600;
        border: 1px solid #d4b896 !important;
        padding: 10px 8px;
        white-space: nowrap;
        vertical-align: middle;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit tbody td {
        border: 1px solid #e8e0d5;
        padding: 8px;
        background-color: #ffffff;
        vertical-align: middle;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit tbody tr:nth-child(even) td {
        background-color: #faf7f2;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit tbody tr:hover td {
        background-color: #f5ebe0;
    }

    .jurnal-penjualan-unit-table-wrap table.tbl-jurnal-penjualan-per-unit tfoot th {
        background-color: #f0e4d4 !important;
        color: #4a3728 !important;
        border: 1px solid #d4b896 !important;
        font-weight: bold;
        padding: 10px 8px;
    }

    .jurnal-penjualan-unit-table-wrap .dataTables_scroll {
        border: 1px solid #e8d4b8;
        border-radius: 4px;
        overflow: hidden;
        background: #ffffff;
    }

    .jurnal-penjualan-unit-table-wrap .dataTables_scrollHead {
        background: #e8d4b8;
    }

    .jurnal-penjualan-unit-table-wrap .dataTables_scrollHeadInner,
    .jurnal-penjualan-unit-table-wrap .dataTables_scrollHeadInner table {
        width: 100% !important;
    }

    .jurnal-penjualan-unit-table-wrap .dataTables_scrollBody {
        border-top: 1px solid #d4b896;
    }

    .jurnal-penjualan-unit-table-wrap .dataTables_scrollBody::-webkit-scrollbar,
    .jurnal-penjualan-unit-table-wrap .dataTables_scrollHead::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }

    .jurnal-penjualan-unit-table-wrap .dataTables_scrollBody::-webkit-scrollbar-thumb,
    .jurnal-penjualan-unit-table-wrap .dataTables_scrollHead::-webkit-scrollbar-thumb {
        background: #d4b896;
        border-radius: 6px;
    }

    .jurnal-penjualan-unit-table-wrap .dataTables_scrollBody::-webkit-scrollbar-track,
    .jurnal-penjualan-unit-table-wrap .dataTables_scrollHead::-webkit-scrollbar-track {
        background: #f5f0e8;
    }

    .jurnal-penjualan-tab-table-wrap {
        border: 1px solid #ffc107;
        border-radius: 8px;
        padding: 10px;
        background: #f8fff8;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.12);
    }

    .jurnal-penjualan-tab-table-wrap .dataTables_wrapper {
        width: 100%;
        margin: 0;
    }

    .jurnal-penjualan-tab-table-wrap table.display {
        border-collapse: collapse;
        width: 100% !important;
    }

    .jurnal-penjualan-tab-table-wrap table.display thead th {
        background-color: #d4edda !important;
        color: #2d5a3d !important;
        font-weight: 600;
        border: 1px solid #b8dfc4 !important;
        padding: 10px 8px;
        white-space: nowrap;
        vertical-align: middle;
    }

    .jurnal-penjualan-tab-table-wrap table.display tbody td {
        border: 1px solid #dceee2;
        padding: 8px;
        background-color: #ffffff;
        vertical-align: middle;
    }

    .jurnal-penjualan-tab-table-wrap table.display tbody tr:nth-child(even) td {
        background-color: #f4fbf6;
    }

    .jurnal-penjualan-tab-table-wrap table.display tbody tr:hover td {
        background-color: #e8f5ec;
    }

    .jurnal-penjualan-tab-table-wrap table.display tfoot th {
        background-color: #c3e6cb !important;
        color: #2d5a3d !important;
        border: 1px solid #b8dfc4 !important;
        font-weight: bold;
        padding: 10px 8px;
    }

    .jurnal-penjualan-tab-table-wrap .dataTables_scroll {
        border: 1px solid #b8dfc4;
        border-radius: 4px;
        overflow: hidden;
        background: #ffffff;
    }

    .jurnal-penjualan-tab-table-wrap .dataTables_scrollHead {
        background: #d4edda;
    }

    .jurnal-penjualan-tab-table-wrap .dataTables_scrollHeadInner,
    .jurnal-penjualan-tab-table-wrap .dataTables_scrollHeadInner table {
        width: 100% !important;
    }

    .jurnal-penjualan-tab-table-wrap .dataTables_scrollBody {
        border-top: 1px solid #b8dfc4;
    }

    .jurnal-penjualan-tab-table-wrap .dataTables_scrollBody::-webkit-scrollbar,
    .jurnal-penjualan-tab-table-wrap .dataTables_scrollHead::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }

    .jurnal-penjualan-tab-table-wrap .dataTables_scrollBody::-webkit-scrollbar-thumb,
    .jurnal-penjualan-tab-table-wrap .dataTables_scrollHead::-webkit-scrollbar-thumb {
        background: #b8dfc4;
        border-radius: 6px;
    }

    .jurnal-penjualan-tab-table-wrap .dataTables_scrollBody::-webkit-scrollbar-track,
    .jurnal-penjualan-tab-table-wrap .dataTables_scrollHead::-webkit-scrollbar-track {
        background: #eef8f0;
    }
</style>

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
        // echo $date_awal; 
        // echo "<br/>";

        $Get_month_from_date = $month_selected;
        $Get_year_Tahun_ini = $year_selected;
        $Get_year_Setahun_lalu = date("Y", strtotime('-1 year'));




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
                                    <div class="col-4" align="left">
                                        <div class="col-12" text-align="center"> <strong>JURNAL PENJUALAN</strong> <?php echo bulan_teks($Get_month_from_date) . " " . $Get_year_Tahun_ini ?></div>
                                    </div>

                                    <div class="col-6" align="left">




                                        <?php
                                        $action_cari_between_date = site_url('Tbl_penjualan/jurnal_penjualan2');
                                        ?>

                                        <form id="formFilterBulanJurnalPenjualan" action="<?php echo $action_cari_between_date; ?>" method="post">
                                            <div class="row">
                                                <div class="col-md-4" text-align="right" align="right">
                                                    <input type="month" id="bulan_ns" name="bulan_ns" value="<?php echo isset($bulan_ns_selected) ? $bulan_ns_selected : date('Y-m'); ?>">
                                                </div>
                                                <div class="col-md-2" text-align="left" align="left">
                                                    <button type="submit" id="btnCariBulanJurnalPenjualan" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in" aria-hidden="true"></i> Cari</button>
                                                </div>

                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>



                    <div class="card-body">

                        <ul class="nav nav-tabs jurnal-penjualan-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tabJurnalPenjualanBarisTab" data-toggle="tab" href="#tabJurnalPenjualanBaris" role="tab" aria-controls="tabJurnalPenjualanBaris" aria-selected="true">
                                    Jurnal penjualan (baris)
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tabJurnalPenjualanKolomTab" data-toggle="tab" href="#tabJurnalPenjualanKolom" role="tab" aria-controls="tabJurnalPenjualanKolom" aria-selected="false">
                                    Jurnal penjualan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tabJurnalPenjualanPerUnitTab" data-toggle="tab" href="#tabJurnalPenjualanPerUnit" role="tab" aria-controls="tabJurnalPenjualanPerUnit" aria-selected="false">
                                    Jurnal penjualan per unit
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tabJurnalPenjualanBaris" role="tabpanel" aria-labelledby="tabJurnalPenjualanBarisTab">
                                <div class="row mb-2">
                                    <div class="col-12 text-right">
                                        <?php
                                        $url_excel_jurnal_penjualan_baris = site_url('Tbl_penjualan/excel_jurnal_penjualan2_baris?bulan_ns=' . rawurlencode(isset($bulan_ns_selected) ? $bulan_ns_selected : date('Y-m')));
                                        echo anchor($url_excel_jurnal_penjualan_baris, '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Cetak ke Excel', 'class="btn btn-success btn-flat"');
                                        ?>
                                    </div>
                                </div>

                                <div class="jurnal-penjualan-tab-table-wrap">
                                <table id="tblJurnalPenjualanBaris" class="display nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" style="text-align:center">No</th>
                                            <th rowspan="2" style="text-align:center">TANGGAL</th>
                                            <th rowspan="2" style="text-align:center">Bukti</th>
                                            <th colspan="3" style="text-align:center">KODE</th>
                                            <th rowspan="2" style="text-align:center">Keterangan</th>
                                            <th rowspan="2" style="text-align:center">debet</th>
                                            <th rowspan="2" style="text-align:center">Kredit</th>
                                        </tr>
                                        <tr>
                                            <th style="text-align:center">PL</th>
                                            <th style="text-align:center">Ref</th>
                                            <th style="text-align:center">Rek</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $startBaris = 0;
                                        $TOTAL_debet_baris = 0;
                                        $TOTAL_kredit_baris = 0;
                                        $baris_data = isset($Buku_besar_DATA_baris) ? $Buku_besar_DATA_baris : array();
                                        foreach ($baris_data as $list_data) {
                                            $debet_val = isset($list_data->debet) ? (float) $list_data->debet : 0;
                                            $kredit_val = isset($list_data->kredit) ? (float) $list_data->kredit : 0;
                                            if ($debet_val == 0 && $kredit_val == 0) {
                                                continue;
                                            }

                                            $Get_date = isset($list_data->tanggal) ? date("Y-m-d", strtotime($list_data->tanggal)) : '';
                                            $bukti = isset($list_data->nokirim) ? (string) $list_data->nokirim : '';
                                            $pl = isset($list_data->pl) ? (string) $list_data->pl : '';
                                            $ref = isset($list_data->ref) ? (string) $list_data->ref : (isset($list_data->kode) ? (string) $list_data->kode : '');
                                            $rek = isset($list_data->rek_display) ? trim((string) $list_data->rek_display) : trim((string) (isset($list_data->kode_akun) ? $list_data->kode_akun : ''));
                                            $keterangan = isset($list_data->keterangan_display) ? trim((string) $list_data->keterangan_display) : '';
                                            $keterangan_style = ($kredit_val > 0 && $debet_val == 0) ? 'padding-left:4ch;' : '';

                                            $TOTAL_debet_baris += $debet_val;
                                            $TOTAL_kredit_baris += $kredit_val;
                                        ?>
                                            <tr>
                                                <td align="left"><?php echo ++$startBaris; ?></td>
                                                <td align="left"><?php echo $Get_date; ?></td>
                                                <td align="left"><?php echo htmlspecialchars($bukti, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td align="left"><?php echo htmlspecialchars($pl, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td align="left"><?php echo htmlspecialchars($ref, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td align="left"><?php echo htmlspecialchars($rek, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td align="left" style="<?php echo $keterangan_style; ?>"><?php echo htmlspecialchars($keterangan, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td align="right"><?php echo $debet_val != 0 ? number_format($debet_val, 2, ',', '.') : ''; ?></td>
                                                <td align="right"><?php echo $kredit_val != 0 ? number_format($kredit_val, 2, ',', '.') : ''; ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="7" style="text-align:right"></th>
                                            <th style="text-align:right">
                                                <strong><?php echo number_format($TOTAL_debet_baris, 2, ',', '.'); ?></strong>
                                            </th>
                                            <th style="text-align:right">
                                                <strong><?php echo number_format($TOTAL_kredit_baris, 2, ',', '.'); ?></strong>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tabJurnalPenjualanKolom" role="tabpanel" aria-labelledby="tabJurnalPenjualanKolomTab">
                                <div class="row mb-2">
                                    <div class="col-12 text-right">
                                        <?php
                                        $url_excel_jurnal_penjualan = site_url('Tbl_penjualan/excel_jurnal_penjualan2?bulan_ns=' . rawurlencode(isset($bulan_ns_selected) ? $bulan_ns_selected : date('Y-m')));
                                        echo anchor($url_excel_jurnal_penjualan, '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Cetak ke Excel', 'class="btn btn-success btn-flat"');
                                        ?>
                                    </div>
                                </div>

                                <div class="jurnal-penjualan-tab-table-wrap">
                                <!-- <table id="tglSPOPFreeze" class="table table-striped dt-responsive w-100 table-bordered display nowrap table-hover mb-0" style="width:100%"> -->
                                <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <!-- <tr>

                                    <th rowspan="3" style="text-align:left" width="10px">Tanggal</th>
                                    <th rowspan="3" style="text-align:center">Kode Akun</th>
                                    <th rowspan="3" style="text-align:center">No. Bukti BKM</th>
                                    <th rowspan="3" style="text-align:center">PL</th>
                                    <th rowspan="3" style="text-align:center">KETERANGAN</th>

                                    <th colspan="3" style="text-align:center">Debet</th>


                                    <th colspan="1" style="text-align:center">Kredit</th>
                                </tr>
                                <tr>
                                    <th rowspan="2" style="text-align:center">11301-UU Dagang</th>
                                    <th colspan="2" style="text-align:center">Serba-Serbi</th>
                                    <th rowspan="2" style="text-align:right">11101-Kas Besar</th>
                                </tr>


                                <tr>
                                    <th rowspan="2" style="text-align:left">No. Rek</th>
                                    <th rowspan="2" style="text-align:right">Jumlah</th>

                                </tr>
                                 -->
                                <!-- ============================================ -->

                                <tr>

                                    <th rowspan="3" style="text-align:left" width="10px">No</th>
                                    <th rowspan="3" style="text-align:left" width="10px">TANGGAL</th>
                                    <th rowspan="3" style="text-align:center">No. INVOICE</th>
                                    <th rowspan="3" style="text-align:center">No. PESAN</th>
                                    <th rowspan="3" style="text-align:center">No. KIRIM</th>
                                    <th rowspan="3" style="text-align:center">KONSUMEN</th>

                                    <th colspan="1" style="text-align:center">DEBET</th>
                                    <th colspan="2" style="text-align:center">KREDIT</th>

                                </tr>
                                <tr>
                                    <th style="text-align:right">11301</th>
                                    <th style="text-align:right">41101</th>
                                    <th style="text-align:right">21201</th>
                                    <!-- <th style="text-align:center">11301-UU</th> -->
                                </tr>
                                <tr>
                                    <th style="text-align:right">Piutang</th>
                                    <th style="text-align:right">Penjualan DPP</th>
                                    <th style="text-align:right">Utang PPN</th>
                                    <!-- <th style="text-align:center">11301-UU</th> -->
                                </tr>
                                <!-- <tr>
                                    <th style="text-align:left">11301-UU</th>
                                    <th style="text-align:right">Jumlah</th>
                                </tr> -->

                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                $TOTAL_debet_11301 = 0;
                                $TOTAL_kredit_41101 = 0;
                                $TOTAL_kredit_21201 = 0;

                                $TOTAL_saldo = 0;
                                $GET_KODE_PL = "";

                                foreach ($Buku_besar_DATA_data as $list_data) {




                                    // BARIS KE 1 ----- PERTAMA

                                    if ($list_data->kode_akun == 11301) {

                                        // CEK dengan SPOP yang sama
                                        // // $Get_kode_akun = 11301;
                                        // $this->db->where('kode_akun', $list_data->kode_akun);
                                        // // $this->db->where('uuid_spop', $list_data->uuid_spop);
                                        // $GET_debet_buku_besar_by_kode_akun_by_spop = $this->db->get('sys_kode_akun')->row()->debet;


                                        $Get_date = date("Y-m-d", strtotime($list_data->tanggal));


                                        $Get_kode_akun = 41101;
                                        $this->db->where('kode_akun', $Get_kode_akun);
                                        $this->db->where('nokirim', $list_data->nokirim);
                                        $this->db->where('tanggal', $Get_date);
                                        $GET_DATA_41101_kredit = $this->db->get('buku_besar')->row()->kredit;

                                        $Get_kode_akun = 21201;
                                        $this->db->where('kode_akun', $Get_kode_akun);
                                        $this->db->where('nokirim', $list_data->nokirim);
                                        $this->db->where('tanggal', $Get_date);
                                        $GET_DATA_21201_kredit = $this->db->get('buku_besar')->row()->kredit;



                                ?>

                                        <tr>
                                            <td align="left">
                                                <?php
                                                echo ++$start;
                                                ?>
                                            </td>

                                            <td align="left">
                                                <?php
                                                // echo "TOTAL";
                                                echo $Get_date;
                                                ?>
                                            </td>

                                            <!-- Nomor Invoice -->
                                            <td align="left">
                                                <?php
                                                // echo $list_data->spop;
                                                // echo "Nomor Invoice";
                                                ?>
                                            </td>

                                            <!-- Nomor Pesan -->
                                            <td align="left">
                                                <?php
                                                // echo $list_data->pl;
                                                // echo $list_data->nokirim;
                                                ?>
                                            </td>

                                            <!-- SUPPLIER -->
                                            <td align="left">
                                                <?php
                                                // echo $list_data->supplier;
                                                // $this->db->where('kode_pl', $GET_KODE_PL);
                                                // $GET_DATA_nama_PL = $this->db->get('sys_kode_pl')->row()->keterangan;

                                                echo $list_data->nokirim;
                                                ?>
                                            </td>

                                            <!-- Konsumen -->
                                            <td align="left">
                                                <?php
                                                // echo $list_data->kode_akun;
                                                echo $list_data->konsumen_nama;
                                                ?>
                                            </td>

                                            <!-- debet 11301 -->
                                            <td align="right">
                                                <?php
                                                echo "<font color='black'><strong>" . number_format($list_data->debet, 2, ',', '.') . "</strong></font>";
                                                $TOTAL_debet_11301 = $TOTAL_debet_11301+$list_data->debet;
                
                                                ?>
                                            </td>

                                            <!-- Jumlah kredit 41101 -->
                                            <td align="right">

                                                <?php
                                                // echo $TOTAL_debet_jumlah;
                                                // $TOTAL_debet_jumlah = $TOTAL_debet_jumlah + $list_data->debet;
                                                echo "<font color='black'><strong>" . number_format($GET_DATA_41101_kredit, 2, ',', '.') . "</strong></font>";

                                                $TOTAL_kredit_41101 = $TOTAL_kredit_41101+$GET_DATA_41101_kredit;
                                                

                                                ?>

                                            </td>


                                            <!-- Jumlah Kredit -->
                                            <td align="right">

                                                <?php
                                                // echo $TOTAL_kredit_11301;
                                                // $TOTAL_kredit_11301 = $TOTAL_kredit_11301 + $GET_DATA_kode_akun_kredit;
                                                echo "<font color='black'><strong>" . number_format($GET_DATA_21201_kredit, 2, ',', '.') . "</strong></font>";

                                                $TOTAL_kredit_21201 = $TOTAL_kredit_21201+$GET_DATA_21201_kredit;

                                                ?>

                                            </td>



                                        </tr>

                                    <?php


                                    


                                    }
                                }
                                ?>



                            </tbody>


                            <tfoot>
                                <tr>

                                    <th style="text-align:left" width="10px"></th>

                                    <!-- tanggal -->
                                    <th style="text-align:center"></th>

                                    <!-- no invoice -->
                                    <th style="text-align:center"></th>

                                    <!-- no pesan -->
                                    <th style="text-align:center"></th>

                                    <!-- no kirim -->
                                    <th style="text-align:center"></th>

                                    <!-- konsumen -->
                                    <th style="text-align:right">
                                        <?php
                                        //echo "<font color='blue'><strong>" . number_format($TOTAL_11301_SEMUA + $TOTAL_debet_jumlah_SEMUA, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </th>

                                    <!-- TOTAL DEBET 11301-->
                                    <th style="text-align:right">
                                        <?php
                                        echo "<font color='blue'><strong>" . number_format($TOTAL_debet_11301, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </th>

                                    <!-- TOTAL kredit 41101 -->
                                    <th style="text-align:right">
                                        <?php
                                        echo "<font color='blue'><strong>" . number_format($TOTAL_kredit_41101, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </th>

                                    <!-- kredit 21201 -->
                                    <th style="text-align:right">
                                        <?php
                                        echo "<font color='blue'><strong>" . number_format($TOTAL_kredit_21201, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </th> <!-- TOTAL JUMLAH -->

                                </tr>

                            </tfoot>







                        </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tabJurnalPenjualanPerUnit" role="tabpanel" aria-labelledby="tabJurnalPenjualanPerUnitTab">
                                <?php
                                $jurnal_penjualan_per_unit_data = isset($jurnal_penjualan_per_unit_data) ? $jurnal_penjualan_per_unit_data : array();
                                $unit_index = 0;
                                foreach ($jurnal_penjualan_per_unit_data as $unit_block) {
                                    $unit_row = isset($unit_block['unit']) ? $unit_block['unit'] : null;
                                    $unit_rows = isset($unit_block['rows']) ? $unit_block['rows'] : array();
                                    $uuid_unit = ($unit_row && isset($unit_row->uuid_unit)) ? (string) $unit_row->uuid_unit : '';
                                    $unit_label = '';
                                    if ($unit_row) {
                                        $unit_label = trim((string) (isset($unit_row->nama_unit) ? $unit_row->nama_unit : ''));
                                        if ($unit_label === '') {
                                            $unit_label = trim((string) (isset($unit_row->kode_unit) ? $unit_row->kode_unit : 'Unit'));
                                        }
                                    } else {
                                        $unit_label = 'Unit';
                                    }
                                    $table_id = 'tblJurnalPenjualanPerUnit' . $unit_index;
                                    $url_excel_per_unit = site_url('Tbl_penjualan/excel_jurnal_penjualan2_per_unit?bulan_ns=' . rawurlencode(isset($bulan_ns_selected) ? $bulan_ns_selected : date('Y-m')) . '&uuid_unit=' . rawurlencode($uuid_unit));

                                    $TOTAL_piutang_unit = 0;
                                    $TOTAL_penjualan_unit = 0;
                                    $TOTAL_utang_ppn_unit = 0;
                                    $TOTAL_jumlah_unit = 0;
                                    $TOTAL_selisih_unit = 0;
                                ?>
                                    <div class="jurnal-penjualan-unit-block">
                                        <div class="jurnal-penjualan-unit-title"><?php echo htmlspecialchars($unit_label, ENT_QUOTES, 'UTF-8'); ?></div>
                                        <div class="jurnal-penjualan-unit-actions text-right">
                                            <?php echo anchor($url_excel_per_unit, '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Cetak ke Excel', 'class="btn btn-success btn-flat"'); ?>
                                        </div>

                                        <div class="jurnal-penjualan-unit-table-wrap">
                                        <table id="<?php echo $table_id; ?>" class="display nowrap tbl-jurnal-penjualan-per-unit" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center">No</th>
                                                    <th style="text-align:center">Tanggal</th>
                                                    <th style="text-align:center">NO INVOICE</th>
                                                    <th style="text-align:center">Nomor Pesan</th>
                                                    <th style="text-align:center">Nomor Kirim</th>
                                                    <th style="text-align:center">KONSUMEN</th>
                                                    <th style="text-align:right">Piutang</th>
                                                    <th style="text-align:right">Penjualan</th>
                                                    <th style="text-align:right">Utang PPN</th>
                                                    <th style="text-align:center">Tanggal Bayar</th>
                                                    <th style="text-align:right">Jumlah</th>
                                                    <th style="text-align:right">Selisih</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $start_per_unit = 0;
                                                foreach ($unit_rows as $list_data) {
                                                    $piutang_val = isset($list_data->piutang) ? (float) $list_data->piutang : 0;
                                                    $penjualan_val = isset($list_data->penjualan) ? (float) $list_data->penjualan : 0;
                                                    $utang_ppn_val = isset($list_data->utang_ppn) ? (float) $list_data->utang_ppn : 0;
                                                    $jumlah_bayar_val = isset($list_data->jumlah_bayar) ? (float) $list_data->jumlah_bayar : 0;
                                                    $selisih_val = isset($list_data->selisih) ? (float) $list_data->selisih : 0;

                                                    $TOTAL_piutang_unit += $piutang_val;
                                                    $TOTAL_penjualan_unit += $penjualan_val;
                                                    $TOTAL_utang_ppn_unit += $utang_ppn_val;
                                                    $TOTAL_jumlah_unit += $jumlah_bayar_val;
                                                    $TOTAL_selisih_unit += $selisih_val;
                                                ?>
                                                    <tr>
                                                        <td align="left"><?php echo ++$start_per_unit; ?></td>
                                                        <td align="left"><?php echo htmlspecialchars(isset($list_data->tgl_jual_display) ? $list_data->tgl_jual_display : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td align="left"><?php echo htmlspecialchars(isset($list_data->no_invoice) ? $list_data->no_invoice : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td align="left"><?php echo htmlspecialchars(isset($list_data->nmrpesan) ? $list_data->nmrpesan : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td align="left"><?php echo htmlspecialchars(isset($list_data->nmrkirim) ? $list_data->nmrkirim : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td align="left"><?php echo htmlspecialchars(isset($list_data->unit) ? $list_data->unit : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td align="right"><?php echo number_format($piutang_val, 2, ',', '.'); ?></td>
                                                        <td align="right"><?php echo number_format($penjualan_val, 2, ',', '.'); ?></td>
                                                        <td align="right"><?php echo number_format($utang_ppn_val, 2, ',', '.'); ?></td>
                                                        <td align="left"><?php echo htmlspecialchars(isset($list_data->tgl_bayar_display) ? $list_data->tgl_bayar_display : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td align="right"><?php echo $jumlah_bayar_val != 0 ? number_format($jumlah_bayar_val, 2, ',', '.') : ''; ?></td>
                                                        <td align="right"><?php echo $selisih_val != 0 ? number_format($selisih_val, 2, ',', '.') : ''; ?></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="6" style="text-align:right">TOTAL</th>
                                                    <th style="text-align:right"><strong><?php echo number_format($TOTAL_piutang_unit, 2, ',', '.'); ?></strong></th>
                                                    <th style="text-align:right"><strong><?php echo number_format($TOTAL_penjualan_unit, 2, ',', '.'); ?></strong></th>
                                                    <th style="text-align:right"><strong><?php echo number_format($TOTAL_utang_ppn_unit, 2, ',', '.'); ?></strong></th>
                                                    <th style="text-align:center"></th>
                                                    <th style="text-align:right"><strong><?php echo number_format($TOTAL_jumlah_unit, 2, ',', '.'); ?></strong></th>
                                                    <th style="text-align:right"><strong><?php echo number_format($TOTAL_selisih_unit, 2, ',', '.'); ?></strong></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        </div>
                                    </div>
                                <?php
                                    $unit_index++;
                                }
                                ?>
                            </div>
                        </div>
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
        function parseNominal(value) {
            if (typeof value === 'number') {
                return value;
            }

            var text = $('<div>').html(value).text();
            text = text.replace(/\./g, '').replace(',', '.').replace(/[^0-9.-]/g, '');
            var number = parseFloat(text);
            return isNaN(number) ? 0 : number;
        }

        function formatNominal(value) {
            return value.toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function updateFooterTotalsBaris(api) {
            var totalDebet = 0;
            var totalKredit = 0;

            api.rows({
                search: 'applied'
            }).every(function() {
                var rowData = this.data();
                totalDebet += parseNominal(rowData[7]);
                totalKredit += parseNominal(rowData[8]);
            });

            $(api.column(7).footer()).html("<strong>" + formatNominal(totalDebet) + "</strong>");
            $(api.column(8).footer()).html("<strong>" + formatNominal(totalKredit) + "</strong>");
        }

        function updateFooterTotals(api) {
            var totalDebet = 0;
            var totalKredit41101 = 0;
            var totalKredit21201 = 0;

            api.rows({
                search: 'applied'
            }).every(function() {
                var rowData = this.data();
                totalDebet += parseNominal(rowData[6]);
                totalKredit41101 += parseNominal(rowData[7]);
                totalKredit21201 += parseNominal(rowData[8]);
            });

            $(api.column(6).footer()).html("<font color='blue'><strong>" + formatNominal(totalDebet) + "</strong></font>");
            $(api.column(7).footer()).html("<font color='blue'><strong>" + formatNominal(totalKredit41101) + "</strong></font>");
            $(api.column(8).footer()).html("<font color='blue'><strong>" + formatNominal(totalKredit21201) + "</strong></font>");
        }

        function updateFooterTotalsPerUnit(api) {
            var totalPiutang = 0;
            var totalPenjualan = 0;
            var totalUtangPpn = 0;
            var totalJumlah = 0;
            var totalSelisih = 0;

            api.rows({
                search: 'applied'
            }).every(function() {
                var rowData = this.data();
                totalPiutang += parseNominal(rowData[6]);
                totalPenjualan += parseNominal(rowData[7]);
                totalUtangPpn += parseNominal(rowData[8]);
                totalJumlah += parseNominal(rowData[10]);
                totalSelisih += parseNominal(rowData[11]);
            });

            $(api.column(6).footer()).html("<strong>" + formatNominal(totalPiutang) + "</strong>");
            $(api.column(7).footer()).html("<strong>" + formatNominal(totalPenjualan) + "</strong>");
            $(api.column(8).footer()).html("<strong>" + formatNominal(totalUtangPpn) + "</strong>");
            $(api.column(10).footer()).html("<strong>" + formatNominal(totalJumlah) + "</strong>");
            $(api.column(11).footer()).html("<strong>" + formatNominal(totalSelisih) + "</strong>");
        }

        function initJurnalPenjualanPerUnitTables() {
            $('.tbl-jurnal-penjualan-per-unit').each(function() {
                var $table = $(this);
                if ($.fn.DataTable.isDataTable($table)) {
                    return;
                }

                var tablePerUnit = $table.DataTable({
                    "scrollY": "420px",
                    "scrollX": true,
                    "scrollCollapse": true,
                    "paging": false,
                    "info": false,
                    "order": [],
                    "autoWidth": false,
                    "footerCallback": function() {
                        updateFooterTotalsPerUnit(this.api());
                    }
                });

                updateFooterTotalsPerUnit(tablePerUnit);
            });
        }

        var tableJurnalPenjualanBaris;
        if ($.fn.DataTable.isDataTable('#tblJurnalPenjualanBaris')) {
            tableJurnalPenjualanBaris = $('#tblJurnalPenjualanBaris').DataTable();
        } else {
            tableJurnalPenjualanBaris = $('#tblJurnalPenjualanBaris').DataTable({
                "scrollY": 900,
                "scrollX": true,
                "paging": false,
                "info": false,
                "order": [],
                "footerCallback": function() {
                    var api = this.api();
                    updateFooterTotalsBaris(api);
                }
            });
        }

        updateFooterTotalsBaris(tableJurnalPenjualanBaris);

        var tableJurnalPenjualan;
        if ($.fn.DataTable.isDataTable('#tglSPOPFreeze')) {
            tableJurnalPenjualan = $('#tglSPOPFreeze').DataTable();
        } else {
            tableJurnalPenjualan = $('#tglSPOPFreeze').DataTable({
                "scrollY": 900,
                "scrollX": true,
                "paging": false,
                "info": false,
                "order": [],
                "footerCallback": function() {
                    var api = this.api();
                    updateFooterTotals(api);
                }
            });
        }

        updateFooterTotals(tableJurnalPenjualan);

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            if (tableJurnalPenjualanBaris) {
                tableJurnalPenjualanBaris.columns.adjust();
                updateFooterTotalsBaris(tableJurnalPenjualanBaris);
            }
            if (tableJurnalPenjualan) {
                tableJurnalPenjualan.columns.adjust();
                updateFooterTotals(tableJurnalPenjualan);
            }
            if ($(e.target).attr('href') === '#tabJurnalPenjualanPerUnit') {
                initJurnalPenjualanPerUnitTables();
                $('.tbl-jurnal-penjualan-per-unit').each(function() {
                    if ($.fn.DataTable.isDataTable(this)) {
                        $(this).DataTable().columns.adjust();
                    }
                });
            }
        });

        $('#bulan_ns').on('change', function() {
            if ($(this).val()) {
                $('#formFilterBulanJurnalPenjualan').trigger('submit');
            }
        });
    });
</script>