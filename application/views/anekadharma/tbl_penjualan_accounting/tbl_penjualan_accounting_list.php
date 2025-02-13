<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <link rel="stylesheet" href="<?php echo base_url('assets/datatables/dataTables.bootstrap.css') ?>"/>
        <link rel="stylesheet" href="<?php echo base_url('assets/datatables/dataTables.bootstrap.css') ?>"/>
        <style>
            .dataTables_wrapper {
                min-height: 500px
            }
            
            .dataTables_processing {
                position: absolute;
                top: 50%;
                left: 50%;
                width: 100%;
                margin-left: -50%;
                margin-top: -25px;
                padding-top: 20px;
                text-align: center;
                font-size: 1.2em;
                color:grey;
            }
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4">
                <h2 style="margin-top:0px">Tbl_penjualan_accounting List</h2>
            </div>
            <div class="col-md-4 text-center">
                <div style="margin-top: 4px"  id="message">
                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class="col-md-4 text-right">
                <?php echo anchor(site_url('tbl_penjualan_accounting/create'), 'Create', 'class="btn btn-primary"'); ?>
		<?php echo anchor(site_url('tbl_penjualan_accounting/excel'), 'Excel', 'class="btn btn-primary"'); ?>
	    </div>
        </div>
        <table class="table table-bordered table-striped" id="mytable">
            <thead>
                <tr>
                    <th width="80px">No</th>
		    <th>Uuid Penjualan Proses</th>
		    <th>Uuid Penjualan</th>
		    <th>Uuid Persediaan</th>
		    <th>Id Persediaan Barang</th>
		    <th>Uuid Barang</th>
		    <th>Tgl Input</th>
		    <th>Tgl Jual</th>
		    <th>Nmrpesan</th>
		    <th>Nmrkirim</th>
		    <th>Uuid Konsumen</th>
		    <th>Konsumen Id</th>
		    <th>Konsumen Nama</th>
		    <th>Kode Barang</th>
		    <th>Nama Barang</th>
		    <th>Uuid Unit</th>
		    <th>Unit</th>
		    <th>Satuan</th>
		    <th>Harga Satuan</th>
		    <th>Jumlah</th>
		    <th>Total Nominal</th>
		    <th>Umpphpsl22</th>
		    <th>Piutang</th>
		    <th>Penjualandpp</th>
		    <th>Utangppn</th>
		    <th>Cetak Bukti Penjualan</th>
		    <th>Id Usr</th>
		    <th>Proses Bayar</th>
		    <th>Tgl Bayar Input</th>
		    <th>Tgl Bayar</th>
		    <th>Nmr Bukti Bayar</th>
		    <th>Kode Akun</th>
		    <th>Proses Transaksi</th>
		    <th width="200px">Action</th>
                </tr>
            </thead>
	    
        </table>
        <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
                {
                    return {
                        "iStart": oSettings._iDisplayStart,
                        "iEnd": oSettings.fnDisplayEnd(),
                        "iLength": oSettings._iDisplayLength,
                        "iTotal": oSettings.fnRecordsTotal(),
                        "iFilteredTotal": oSettings.fnRecordsDisplay(),
                        "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                        "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                    };
                };

                var t = $("#mytable").dataTable({
                    initComplete: function() {
                        var api = this.api();
                        $('#mytable_filter input')
                                .off('.DT')
                                .on('keyup.DT', function(e) {
                                    if (e.keyCode == 13) {
                                        api.search(this.value).draw();
                            }
                        });
                    },
                    oLanguage: {
                        sProcessing: "loading..."
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {"url": "tbl_penjualan_accounting/json", "type": "POST"},
                    columns: [
                        {
                            "data": "id",
                            "orderable": false
                        },{"data": "uuid_penjualan_proses"},{"data": "uuid_penjualan"},{"data": "uuid_persediaan"},{"data": "id_persediaan_barang"},{"data": "uuid_barang"},{"data": "tgl_input"},{"data": "tgl_jual"},{"data": "nmrpesan"},{"data": "nmrkirim"},{"data": "uuid_konsumen"},{"data": "konsumen_id"},{"data": "konsumen_nama"},{"data": "kode_barang"},{"data": "nama_barang"},{"data": "uuid_unit"},{"data": "unit"},{"data": "satuan"},{"data": "harga_satuan"},{"data": "jumlah"},{"data": "total_nominal"},{"data": "umpphpsl22"},{"data": "piutang"},{"data": "penjualandpp"},{"data": "utangppn"},{"data": "cetak_bukti_penjualan"},{"data": "id_usr"},{"data": "proses_bayar"},{"data": "tgl_bayar_input"},{"data": "tgl_bayar"},{"data": "nmr_bukti_bayar"},{"data": "kode_akun"},{"data": "proses_transaksi"},
                        {
                            "data" : "action",
                            "orderable": false,
                            "className" : "text-center"
                        }
                    ],
                    order: [[0, 'desc']],
                    rowCallback: function(row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    }
                });
            });
        </script>
    </body>
</html>