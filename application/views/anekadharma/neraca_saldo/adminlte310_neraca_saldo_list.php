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

        if (!isset($bulan_ns_value) || $bulan_ns_value === '') {
            $bulan_ns_value = sprintf('%04d-%02d', (int) $Get_year_Tahun_ini, (int) $Get_month_from_date);
        }

        // $futureDate=date('Y', strtotime('-1 year'));

        // if (date("Y", strtotime($date_awal)) < 2020) {
        //     $Get_date_awal = date("d-m-Y");
        // } else {
        //     $Get_date_awal = date("d-m-Y", strtotime($date_awal));
        // }

        // if (date("Y", strtotime($date_akhir)) < 2020) {
        //     $Get_date_akhir = date("d-m-Y");
        // } else {
        //     $Get_date_akhir = date("d-m-Y", strtotime($date_akhir));
        // }

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
                            <div class="col-3">
                                <div class="row">
                                    <strong id="ns-periode-label">NERACA SALDO <?php echo bulan_teks($Get_month_from_date) . " " . $Get_year_Tahun_ini ?></strong>




                                </div>
                            </div>

                            <div class="col-md-6">


                                <?php
                                $action_cari_between_date = isset($url_cari_bulan) ? $url_cari_bulan : site_url('Neraca_saldo/Cari_bulan_data');
                                ?>

                                <form id="form-cari-neraca-saldo" action="<?php echo $action_cari_between_date; ?>" method="post">
                                    <div class="row">
                                        <div class="col-md-4" text-align="right" align="right">
                                            <input type="month" class="form-control form-control-sm" id="bulan_ns" name="bulan_ns" value="<?php echo htmlspecialchars($bulan_ns_value, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off">
                                        </div>
                                        <div class="col-md-2" text-align="left" align="left">
                                            <button type="submit" id="btn-cari-neraca-saldo" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in" aria-hidden="true"></i> Cari</button>
                                        </div>

                                    </div>
                                </form>




                            </div>



                        </div>

                        <!-- </form> -->



                    </div>




                    <div class="card-body">

                        <?php
                        $url_excel = isset($url_neraca_saldo_excel) ? $url_neraca_saldo_excel : site_url('Neraca_saldo/excel_list');
                        ?>
                        <div class="d-flex flex-wrap justify-content-end mb-2">
                            <button type="button" class="btn btn-success btn-sm" id="btn-neraca-saldo-excel">
                                <i class="fa fa-file-excel-o"></i> Cetak ke Excel
                            </button>
                        </div>

                        <div class="ns-datatable-wrap" id="ns-datatable-wrap">
                        <table id="tglSPOPFreeze" class="display nowrap ns-datatable-green" style="width:100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="text-align:center" width="10px">No</th>
                                    <th rowspan="2" style="text-align:center" width="10px">Tanggal</th>
                                    <th rowspan="2" style="text-align:center">Kode Rek.</th>
                                    <th rowspan="2" style="text-align:center">Uraian</th>
                                    <th colspan="2" style="text-align:center" id="ns-th-tahun-lalu">NERACA SALDO 31 Desember <?php echo $Get_year_Setahun_lalu; ?></th>
                                    <th colspan="2" style="text-align:center">PENYESUAIAN</th>
                                    <th colspan="2" style="text-align:center">NS SETELAH PENYESUAIAN</th>
                                    <th colspan="2" style="text-align:center">LABA/ RUGI</th>
                                </tr>
                                <tr>
                                    <th>debet</th>
                                    <th>kredit</th>
                                    <th>debet</th>
                                    <th>kredit</th>
                                    <th>debet</th>
                                    <th>kredit</th>
                                    <th>debet</th>
                                    <th>kredit</th>
                                </tr>


                            </thead>
                            <tbody id="ns-tbody">
                                <?php
                                $this->load->view('anekadharma/neraca_saldo/adminlte310_neraca_saldo_tbody', array(
                                    'Data_Kode_Akun' => $Data_Kode_Akun,
                                    'Get_month_from_date' => (int) $Get_month_from_date,
                                    'Get_year_Tahun_ini' => (int) $Get_year_Tahun_ini,
                                    'month_selected' => $month_selected,
                                    'year_selected' => $year_selected,
                                ));
                                ?>
                            </tbody>


                        </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </section>
</div>






<!-- MODAL -->

<!-- MODAL EXTRA LARGE UPDATE PER ID -->
<?php $action_simpan = "Simpan_input_data" ?>
<form action="<?php echo $action_simpan; ?>" method="post">
    <div class="modal fade" id="modal_input_jurnal_penyesuaian<?php //echo $list_data->id 
                                                                ?>">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">INPUT DATA PENYESUAIAN <?php //echo $list_data->id
                                                                    ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">



                        <form action="<?php echo $action; ?>" method="post">
                            <div class="row">
                                <!-- <div class="col-6"> -->
                                <div class="form-group">
                                    <label for="datetime">Tanggal <?php echo form_error('tgl_po') ?></label>
                                    <div class="col-3">
                                        <div class="input-group date" id="tgl_po" name="tgl_po" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#tgl_po" id="tgl_po" name="tgl_po" value="<?php echo $date_po_X; ?>" required />
                                            <div class="input-group-append" data-target="#tgl_po" data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <!-- </dsiv> -->
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="kode_pl">Kode Rekening:</label>
                                        <div class="col-12">
                                            <input type="text" name="kode_rekening" id="kode_rekening" placeholder="kode_rekening" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-4">
                                    <label for="supplier_nama">Kode Akun : </strong></label>

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

                                    <label for="kode_pl">Debet / Kredit</label>
                                    <select name="status_proses" id="status_proses" class="form-control select2" style="width: 100%; height: 80px;" required>

                                        <option value=""></option>
                                        <option value="debet">Debet</option>
                                        <option value="kredit">Kredit</option>


                                    </select>


                                </div>

                                <div class="col-4">
                                    <label for="kode_pl">Nominal </label>
                                    <input type="number" name="nominal_penyesuaian" id="nominal_penyesuaian" placeholder="nominal penyesuaian" class="form-control" required>
                                </div>


                            </div>
                            <div class="row">
                                <label for="kode_pl">Keterangan:</label>
                                <div class="col-12">
                                    <input type="text" name="keterangan" id="keterangan" placeholder="keterangan" class="form-control" required>
                                </div>
                            </div>




                        </form>


                    </div>


                </div>


                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <!-- <button type="button" class="btn btn-primary">Simpan</button> -->
                    <button type="submit" class="btn btn-primary">SIMPAN</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
<!-- END OF MODAL EXTRA LARGE -->

<!-- END OF MODAL -->












<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }

    .ns-datatable-wrap {
        border: 2px solid #a8e6cf;
        border-radius: 12px;
        padding: 14px 12px 10px;
        background: linear-gradient(180deg, #f3fbf6 0%, #ffffff 55%);
        box-shadow: 0 4px 18px rgba(76, 175, 80, 0.10);
        position: relative;
        overflow: hidden;
    }

    .ns-datatable-wrap::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #81c784, #a5d6a7, #c8e6c9);
    }

    .ns-datatable-wrap.ns-loading {
        opacity: 0.72;
        pointer-events: none;
    }

    .ns-datatable-wrap.ns-loading::after {
        content: 'Memuat data...';
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(243, 251, 246, 0.65);
        color: #2e7d32;
        font-weight: 600;
        letter-spacing: 0.3px;
        z-index: 5;
    }

    .ns-datatable-wrap table.dataTable {
        border-collapse: separate !important;
        border-spacing: 0;
    }

    .ns-datatable-wrap table.dataTable thead th,
    .ns-datatable-wrap table.dataTable tbody td {
        border: 1px solid #b9dfc4 !important;
    }

    .ns-datatable-wrap table.dataTable thead th {
        background: linear-gradient(180deg, #e8f5e9 0%, #f1f8f4 100%) !important;
        color: #2e7d32;
        font-weight: 600;
        vertical-align: middle;
    }

    .ns-datatable-wrap table.dataTable tbody tr:nth-child(even) td {
        background-color: #f9fdfb;
    }

    .ns-datatable-wrap table.dataTable tbody tr:hover td {
        background-color: #edf7ef !important;
    }

    .ns-datatable-wrap .dataTables_scrollHead table.dataTable thead th {
        border-bottom: 2px solid #a5d6a7 !important;
    }

    .ns-datatable-wrap .dataTables_filter input,
    .ns-datatable-wrap #bulan_ns {
        border-color: #a5d6a7;
    }

    .ns-datatable-wrap .dataTables_filter input:focus {
        border-color: #66bb6a;
        box-shadow: 0 0 0 0.15rem rgba(102, 187, 106, 0.25);
    }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
(function() {
    var LS_BULAN_NS = 'ns_bulan_ns';
    var urlAjaxRefresh = <?php echo json_encode(isset($url_ajax_refresh) ? $url_ajax_refresh : site_url('Neraca_saldo/ajax_refresh_datatable')); ?>;
    var urlExcel = <?php echo json_encode($url_excel); ?>;
    var serverBulanNs = <?php echo json_encode($bulan_ns_value); ?>;
    var nsRefreshing = false;
    var nsMainDt = null;

    function saveNsLocalStorage() {
        try {
            var val = jQuery('#bulan_ns').val() || '';
            if (/^\d{4}-\d{2}$/.test(val)) {
                localStorage.setItem(LS_BULAN_NS, val);
            }
        } catch (eLs) {}
    }

    function getDefaultBulanNs() {
        var now = new Date();
        return now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0');
    }

    function ensureBulanNsValue() {
        var $input = jQuery('#bulan_ns');
        var val = ($input.val() || '').trim();
        if (!/^\d{4}-\d{2}$/.test(val)) {
            val = serverBulanNs || getDefaultBulanNs();
            $input.val(val);
        }
        return val;
    }

    function destroyNeracaSaldoDatatable() {
        if (window.jQuery && jQuery.fn.DataTable && jQuery.fn.DataTable.isDataTable('#tglSPOPFreeze')) {
            jQuery('#tglSPOPFreeze').DataTable().destroy();
            nsMainDt = null;
        }
    }

    function initNeracaSaldoDatatable() {
        if (!window.jQuery || !jQuery.fn.DataTable) {
            return null;
        }
        var $table = jQuery('#tglSPOPFreeze');
        if (!$table.length) {
            return null;
        }
        destroyNeracaSaldoDatatable();
        nsMainDt = $table.DataTable({
            scrollY: 600,
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            searching: true,
            ordering: true,
            info: false,
            order: [],
            language: {
                search: 'Cari:',
                zeroRecords: 'Data tidak ditemukan',
                emptyTable: 'Belum ada data'
            }
        });
        return nsMainDt;
    }

    function updateNeracaSaldoHeader(res) {
        if (res.periode_label) {
            jQuery('#ns-periode-label').text('NERACA SALDO ' + res.periode_label);
        }
        if (res.tahun_lalu) {
            jQuery('#ns-th-tahun-lalu').text('NERACA SALDO 31 Desember ' + res.tahun_lalu);
        }
    }

    function refreshNeracaSaldoDatatable() {
        if (nsRefreshing) {
            return;
        }
        var val = ensureBulanNsValue();
        if (!val) {
            return;
        }

        nsRefreshing = true;
        saveNsLocalStorage();
        jQuery('#ns-datatable-wrap').addClass('ns-loading');

        jQuery.ajax({
            url: urlAjaxRefresh,
            type: 'POST',
            dataType: 'json',
            data: { bulan_ns: val }
        }).done(function(res) {
            if (!res || !res.ok) {
                return;
            }

            serverBulanNs = res.bulan_ns_value || val;
            updateNeracaSaldoHeader(res);

            destroyNeracaSaldoDatatable();
            jQuery('#ns-tbody').html(res.tbody_html || '');
            initNeracaSaldoDatatable();
        }).fail(function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memuat data neraca saldo.' });
            }
        }).always(function() {
            nsRefreshing = false;
            jQuery('#ns-datatable-wrap').removeClass('ns-loading');
        });
    }

    window.addEventListener('load', function() {
        if (!window.jQuery) {
            return;
        }

        var lsBulan = null;
        try {
            lsBulan = localStorage.getItem(LS_BULAN_NS);
        } catch (eLs) {}

        if (lsBulan && /^\d{4}-\d{2}$/.test(lsBulan)) {
            jQuery('#bulan_ns').val(lsBulan);
        } else {
            ensureBulanNsValue();
            saveNsLocalStorage();
        }

        initNeracaSaldoDatatable();

        if (lsBulan && /^\d{4}-\d{2}$/.test(lsBulan) && lsBulan !== serverBulanNs) {
            refreshNeracaSaldoDatatable();
        }

        jQuery('#bulan_ns').on('change', function() {
            if (!this.value) {
                return;
            }
            refreshNeracaSaldoDatatable();
        });

        jQuery('#form-cari-neraca-saldo').on('submit', function(e) {
            e.preventDefault();
            refreshNeracaSaldoDatatable();
        });

        jQuery('#btn-neraca-saldo-excel').on('click', function() {
            var val = ensureBulanNsValue();
            var f = jQuery('<form method="post" target="_blank"></form>');
            f.attr('action', urlExcel);
            f.append(jQuery('<input type="hidden" name="bulan_ns">').val(val || ''));
            jQuery('body').append(f);
            f.submit();
            f.remove();
        });
    });
})();
</script>