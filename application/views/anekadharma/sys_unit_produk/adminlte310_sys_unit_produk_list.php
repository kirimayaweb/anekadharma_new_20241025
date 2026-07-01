<?php
$bulan_tampil = isset($bulan_produksi_selected) && $bulan_produksi_selected !== ''
    ? $bulan_produksi_selected
    : date('Y-m');
$url_ajax_list_by_bulan = isset($url_ajax_list_by_bulan) ? $url_ajax_list_by_bulan : site_url('Sys_unit_produk/ajax_list_by_bulan');
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
                        </div>
                        <div class="row align-items-center flex-wrap">
                            <div class="col-auto">
                                <strong>PRODUKSI</strong>
                            </div>
                            <div class="col-auto">
                                <?php
                                $url_create_produksi_bulan = isset($url_create_produksi)
                                    ? $url_create_produksi . '?bulan=' . urlencode($bulan_tampil)
                                    : site_url('Sys_unit_produk/create_produksi?bulan=' . urlencode($bulan_tampil));
                                echo anchor($url_create_produksi_bulan, 'Input Produksi', 'class="btn btn-danger" id="btn-input-produksi"');
                                $url_create_produksi_tanpa_bahan_bulan = isset($url_create_produksi_tanpa_bahan)
                                    ? $url_create_produksi_tanpa_bahan . '?bulan=' . urlencode($bulan_tampil)
                                    : site_url('Sys_unit_produk/create_produksi_tanpa_bahan?bulan=' . urlencode($bulan_tampil));
                                echo ' ';
                                echo anchor($url_create_produksi_tanpa_bahan_bulan, 'Input Produksi Tanpa Bahan', 'class="btn btn-input-produksi-tanpa-bahan" id="btn-input-produksi-tanpa-bahan"');
                                ?>
                            </div>
                            <div class="col-auto d-flex align-items-center flex-wrap">
                                <label for="bulan_produksi" class="mb-0 mr-2">Bulan:</label>
                                <input type="month" id="bulan_produksi" name="bulan_produksi" class="form-control d-inline-block" style="width:auto;" value="<?php echo htmlspecialchars($bulan_tampil, ENT_QUOTES, 'UTF-8'); ?>">
                                <span class="ml-2 small" id="info-jumlah-produksi-bulan">
                                    Menampilkan <?php echo count($Sys_unit_produk_data); ?> data — bulan <?php echo htmlspecialchars(date('m/Y', strtotime($bulan_tampil . '-01')), ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </div>
                        </div>




                    </div>
                    <!-- <br /> -->



                    <div class="card-body">

                        <?php if ($this->session->flashdata('message')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($this->session->flashdata('message'), ENT_QUOTES, 'UTF-8'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-12 col-sm-12">
                                <div class="card card-primary card-tabs">

                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-one-tabContent">
                                            <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">

                                                <table id="example" class="display nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="80px">No</th>
                                                            <th width="200px">Action</th>
                                                            <!-- <th>Uuid Unit</th> -->
                                                            <!-- <th>Kode Unit</th> -->

                                                            <th>Tgl Transaksi<br /><strong>SPOP</strong></th>
                                                            <!-- <th>SPOP</th> -->
                                                            <th>Nama Unit</th>

                                                            <!-- <th>Uuid Barang</th> -->
                                                            <!-- <th>Kode Barang</th> -->
                                                            <th>Nama Barang</th>
                                                            <th>Jumlah Produksi</th>
                                                            <th>Satuan</th>
                                                            <th>Harga Satuan</th>


                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $start = 0;
                                                        $get_saldo = 0;
                                                        foreach ($Sys_unit_produk_data as $list_data) {

                                                        ?>
                                                            <tr>
                                                                <td style="text-align:center"><?php echo ++$start ?></td>
                                                                <td style="text-align:left">
                                                                    <?php

                                                                    $this->db->where('uuid_persediaan', $list_data->uuid_persediaan);
                                                                    $persediaan_nama_barang = $this->db->get('persediaan');



                                                                    echo anchor(site_url('Sys_unit_produk/update_produksi/' . $persediaan_nama_barang->row()->id), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));
                                                                    // echo ' ';
                                                                    // echo anchor(site_url('Sys_unit_produk/delete/' . $list_data->id), '<i class="fa fa-trash-o">Hapus</i>', 'title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                                                    ?>
                                                                </td>


                                                                <td style="text-align:left">
                                                                    <?php
                                                                    echo date("d-M-Y", strtotime($list_data->tgl_transaksi));
                                                                    echo "<br/>";
                                                                    echo "<strong>".$persediaan_nama_barang->row()->spop."</strong>";
                                                                    ?>

                                                                </td>
                                                                <!-- 
                                                                <td style="text-align:left">
                                                                    <?php
                                                                    // echo $persediaan_nama_barang->row()->spop; 
                                                                    ?>

                                                                </td> -->
                                                                <td style="text-align:left"><?php echo $list_data->nama_unit; ?> </td>
                                                                <td style="text-align:left"><?php echo $list_data->nama_barang; ?> </td>
                                                                <td style="text-align:right"><?php echo $list_data->jumlah_produksi; ?> </td>
                                                                <td style="text-align:left"><?php echo $list_data->satuan; ?> </td>
                                                                <td style="text-align:right"><?php echo number_format($list_data->harga_satuan, 2, ',', '.'); ?> </td>


                                                                <!-- `id`, `uuid_unit`, `kode_unit`, `nama_unit`, ``, `uuid_produk`, `kode_barang`, `nama_barang`, `jumlah_produksi`, `satuan`, `harga_satuan` -->

                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>


                                                    </tbody>



                                                </table>


                                            </div>

                                        </div>
                                    </div>
                                    <!-- /.card -->
                                </div>
                            </div>

                        </div>



                    </div>
                    <!-- /.card-body -->






                </div>
            </div>





        </div>




    </section>
</div>




<!-- MODAL EXTRA LARGE -->

<div class="modal fade" id="modal-xl-select-unit">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Produk Barang</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <form action="<?php echo $action; ?>" method="post">



                        <div class="form-group">
                            <div class="row">
                                <div class="col-12">
                                    Silahkan isi nama produk baru jika belum nama produk di aplikasi, Kemudian klik Proses:
                                    <br />
                                </div>

                            </div>
                        </div>


                        <div class="form-group">
                            <div class="row">
                                <div class="col-4">
                                    <label for="keterangan">Tanggal Produksi <?php echo form_error('tgl_transaksi') ?></label>

                                    <div class="input-group date" id="tgl_transaksi" name="tgl_transaksi" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_transaksi" id="tgl_transaksi" name="tgl_transaksi" required />
                                        <div class="input-group-append" data-target="#tgl_transaksi" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>

                                        </div>

                                    </div>
                                </div>



                            </div>
                        </div>



                        <div class="form-group">
                            <div class="row">

                                <div class="col-4">
                                    <label for="keterangan">Nama Produk Baru </label>

                                    <input class="form-control" rows="3" name="nama_barang" id="nama_barang" placeholder="nama_barang">

                                </div>

                                <div class="col-4">
                                    <label for="keterangan">Satuan <?php //echo form_error('satuan') 
                                                                    ?></label>
                                    <input class="form-control" rows="3" name="satuan" id="satuan" placeholder="satuan" required>

                                </div>
                                <div class="col-4">
                                    <label for="keterangan">Harga Satuan <?php //echo form_error('harga_satuan') 
                                                                            ?></label>
                                    <input class="form-control uang" rows="3" name="harga_satuan" id="harga_satuan" placeholder="harga_satuan" required>

                                </div>

                            </div>
                        </div>



                        <div class="form-group">
                            <div class="row">
                                <div class="col-4">
                                    <label for="konsumen_nama">Unit <?php echo form_error('konsumen_nama') ?></label>
                                    <select name="uuid_unit" id="uuid_unit" class="form-control select2" style="width: 100%; height: 40px;" required>
                                        <?php
                                        // if ($uuid_unit) {
                                        ?>
                                        <!-- <option value="<?php echo $uuid_unit; ?>"><?php echo $nama_unit; ?> </option> -->
                                        <?php
                                        // } else {
                                        ?>
                                        <option value="">Pilih Unit </option>
                                        <?php
                                        // }
                                        ?>

                                        <?php

                                        // $sql = "select * from sys_konsumen order by nama_konsumen ASC ";
                                        $sql = "select * from sys_unit order by nama_unit ASC ";
                                        foreach ($this->db->query($sql)->result() as $m) {
                                            echo "<option value='$m->uuid_unit' ";
                                            echo ">  " . strtoupper($m->nama_unit) .  "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label for="keterangan">Jumlah Produksi <?php echo form_error('jumlah_produksi') ?></label>
                                    <input class="form-control uang" rows="3" name="jumlah_produksi" id="jumlah_produksi" placeholder="jumlah_produksi" required>
                                </div>

                            </div>
                        </div>


                        <div class="form-group">
                            <div class="row">
                                <div class="col-4">

                                </div>
                                <div class="col-4" align="center">

                                    <button type="submit" class="btn btn-primary">Simpan dan lanjut mengisi bahan-bahan produksi</button>

                                </div>
                                <div class="col-4">

                                </div>
                            </div>
                        </div>





                    </form>
                </div>


            </div>

            <div class="modal-footer">

                <!-- <button type="submit" class="btn btn-primary">Proses</button> -->

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<!-- END OF MODAL EXTRA LARGE -->





<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    #info-jumlah-produksi-bulan {
        color: #ffeb3b;
        font-weight: 700;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.35);
    }
    .btn-input-produksi-tanpa-bahan {
        background-color: #00e676 !important;
        color: #0d47a1 !important;
        border: 2px solid #ffeb3b !important;
        box-shadow: 0 0 8px rgba(255, 235, 59, 0.85);
        font-weight: 700;
    }
    .btn-input-produksi-tanpa-bahan:hover,
    .btn-input-produksi-tanpa-bahan:focus {
        background-color: #00c853 !important;
        color: #0a3d91 !important;
        border-color: #fff176 !important;
        box-shadow: 0 0 12px rgba(255, 235, 59, 1);
    }
</style>

<script>
window.addEventListener('load', function() {
    if (!window.jQuery || !jQuery.fn || !jQuery.fn.dataTable) {
        console.error('Produksi: jQuery/DataTables belum dimuat. Muat ulang halaman.');
        return;
    }
    var $ = window.jQuery;
    var urlAjaxListByBulan = <?php echo json_encode($url_ajax_list_by_bulan); ?>;
    var urlCreateProduksiBase = <?php echo json_encode(isset($url_create_produksi) ? $url_create_produksi : site_url('Sys_unit_produk/create_produksi')); ?>;
    var urlCreateProduksiTanpaBahanBase = <?php echo json_encode(isset($url_create_produksi_tanpa_bahan) ? $url_create_produksi_tanpa_bahan : site_url('Sys_unit_produk/create_produksi_tanpa_bahan')); ?>;
    var bulanProduksiAktif = <?php echo json_encode($bulan_tampil); ?>;
    var dtProduksi = null;

    function updateLinkInputProduksi(bulanYm) {
        var href = urlCreateProduksiBase + (urlCreateProduksiBase.indexOf('?') >= 0 ? '&' : '?') + 'bulan=' + encodeURIComponent(bulanYm);
        $('#btn-input-produksi').attr('href', href);
        var hrefTanpaBahan = urlCreateProduksiTanpaBahanBase + (urlCreateProduksiTanpaBahanBase.indexOf('?') >= 0 ? '&' : '?') + 'bulan=' + encodeURIComponent(bulanYm);
        $('#btn-input-produksi-tanpa-bahan').attr('href', hrefTanpaBahan);
    }

    function escapeHtml(text) {
        return String(text === null || text === undefined ? '' : text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function buildProduksiRowHtml(row) {
        return '<tr>'
            + '<td style="text-align:center">' + escapeHtml(row.no) + '</td>'
            + '<td style="text-align:left">'
            + '<a href="' + escapeHtml(row.edit_url) + '" title="edit" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o">Ubah</i></a>'
            + '</td>'
            + '<td style="text-align:left">' + escapeHtml(row.tgl_transaksi) + '<br/><strong>' + escapeHtml(row.spop) + '</strong></td>'
            + '<td style="text-align:left">' + escapeHtml(row.nama_unit) + '</td>'
            + '<td style="text-align:left">' + escapeHtml(row.nama_barang) + '</td>'
            + '<td style="text-align:right">' + escapeHtml(row.jumlah_produksi) + '</td>'
            + '<td style="text-align:left">' + escapeHtml(row.satuan) + '</td>'
            + '<td style="text-align:right">' + escapeHtml(row.harga_satuan) + '</td>'
            + '</tr>';
    }

    function destroyDataTableProduksi() {
        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy();
            dtProduksi = null;
        }
    }

    function initDataTableProduksi() {
        destroyDataTableProduksi();
        dtProduksi = $('#example').DataTable({
            scrollY: 700,
            scrollX: true,
            scrollCollapse: true,
            pageLength: 25,
            language: {
                emptyTable: 'Belum ada data produksi pada bulan ini',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
                infoFiltered: '(disaring dari _MAX_ total data)',
                lengthMenu: 'Tampilkan _MENU_ data',
                search: 'Cari:',
                zeroRecords: 'Tidak ada data yang cocok',
                paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' }
            }
        });
    }

    function updateInfoJumlah(rows, bulanLabel) {
        var jumlah = Array.isArray(rows) ? rows.length : 0;
        $('#info-jumlah-produksi-bulan').text('Menampilkan ' + jumlah + ' data — bulan ' + bulanLabel);
    }

    function renderProduksiRows(rows, bulanLabel) {
        var html = '';
        if (Array.isArray(rows)) {
            for (var i = 0; i < rows.length; i++) {
                html += buildProduksiRowHtml(rows[i]);
            }
        }
        $('#example tbody').html(html);
        updateInfoJumlah(rows, bulanLabel);
        initDataTableProduksi();
    }

    function loadProduksiByBulan(bulanYm) {
        if (!bulanYm) {
            return;
        }
        destroyDataTableProduksi();
        $('#example tbody').html('<tr><td colspan="8" class="text-center text-muted">Memuat data...</td></tr>');
        $.ajax({
            url: urlAjaxListByBulan,
            type: 'GET',
            data: { bulan: bulanYm },
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function(res) {
            if (!res || !res.ok) {
                var msg = res && res.message ? res.message : 'Gagal memuat data produksi.';
                $('#example tbody').html('<tr><td colspan="8" class="text-center text-danger">' + escapeHtml(msg) + '</td></tr>');
                updateInfoJumlah([], bulanYm.replace(/^(\d{4})-(\d{2})$/, '$2/$1'));
                initDataTableProduksi();
                return;
            }
            renderProduksiRows(res.rows, res.bulan_label);
        }).fail(function() {
            $('#example tbody').html('<tr><td colspan="8" class="text-center text-danger">Gagal memuat data produksi.</td></tr>');
            updateInfoJumlah([], bulanYm.replace(/^(\d{4})-(\d{2})$/, '$2/$1'));
            initDataTableProduksi();
        });
    }

    $('#bulan_produksi').on('change', function() {
        var bulan = $(this).val() || '';
        if (!bulan || bulan === bulanProduksiAktif) {
            return;
        }
        bulanProduksiAktif = bulan;
        updateLinkInputProduksi(bulan);
        loadProduksiByBulan(bulan);
    });

    updateLinkInputProduksi(bulanProduksiAktif);
    initDataTableProduksi();
});
</script>