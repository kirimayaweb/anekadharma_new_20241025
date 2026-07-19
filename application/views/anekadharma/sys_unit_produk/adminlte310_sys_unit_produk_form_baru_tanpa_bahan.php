<?php
$bulan_produksi_selected = isset($bulan_produksi_selected) && $bulan_produksi_selected !== ''
    ? $bulan_produksi_selected
    : date('Y-m');
$bulan_produksi_ym = isset($bulan_produksi_ym) && $bulan_produksi_ym !== ''
    ? $bulan_produksi_ym
    : $bulan_produksi_selected;
$bulan_produksi_label = isset($bulan_produksi_label) && $bulan_produksi_label !== ''
    ? $bulan_produksi_label
    : date('F Y', strtotime($bulan_produksi_selected . '-01'));
$url_ajax_stock_persediaan = isset($url_ajax_stock_persediaan)
    ? $url_ajax_stock_persediaan
    : site_url('Sys_unit_produk/ajax_stock_persediaan_by_bulan');
$tgl_transaksi_bahan = isset($tgl_transaksi_bahan) ? $tgl_transaksi_bahan : ($bulan_produksi_selected . '-01');
$jumlah_bahan_count = isset($jumlah_bahan_count) ? (int) $jumlah_bahan_count : 0;
$produk_sudah_ada = isset($produk_sudah_ada) ? (bool) $produk_sudah_ada : false;
$label_btn_produk = isset($label_btn_produk) ? $label_btn_produk : 'Simpan';
$action_simpan_produk_form = isset($action_simpan_produk_form) ? $action_simpan_produk_form : '';
$data_bahan_list = isset($data_bahan_produk_unit) && is_array($data_bahan_produk_unit) ? $data_bahan_produk_unit : array();
$TOTAL_HARGA = 0;
foreach ($data_bahan_list as $_bahan_row) {
    $TOTAL_HARGA += $_bahan_row->jumlah_bahan * $_bahan_row->harga_satuan_bahan;
}
if (!empty($tgl_transaksi)) {
    if (preg_match('/^\d{2}-\d{2}-\d{4}(\s+\d{2}:\d{2}:\d{2})?$/', trim($tgl_transaksi))) {
        $tgl_transaksi_produk_X = trim($tgl_transaksi);
        if (strlen($tgl_transaksi_produk_X) === 10) {
            $tgl_transaksi_produk_X .= ' 00:00:00';
        }
    } elseif (date('Y', strtotime($tgl_transaksi)) < 2020) {
        $tgl_transaksi_produk_X = date('d-m-Y H:i:s');
    } else {
        $tgl_transaksi_produk_X = date('d-m-Y H:i:s', strtotime($tgl_transaksi));
    }
} else {
    $tgl_transaksi_produk_X = date('d-m-Y H:i:s', strtotime($bulan_produksi_selected . '-01'));
}
$harga_satuan_tampil = isset($harga_satuan) && $harga_satuan !== '' ? number_format((float) preg_replace('/[^0-9.]/', '', $harga_satuan), 0, ',', '.') : '';
$mode_produksi_tanpa_bahan = isset($mode_produksi_tanpa_bahan) ? (bool) $mode_produksi_tanpa_bahan : false;
$btn_produk_siap = false;
$produk_draft = isset($produk_draft) && is_array($produk_draft) ? $produk_draft : array();
$hapus_produk_draft_client = isset($hapus_produk_draft_client) ? (bool) $hapus_produk_draft_client : false;
$data_penjualan_per_uuid_penjualan = isset($data_penjualan_per_uuid_penjualan) && is_array($data_penjualan_per_uuid_penjualan)
    ? $data_penjualan_per_uuid_penjualan
    : array();
$mode_update_produksi = isset($mode_update_produksi) ? (bool) $mode_update_produksi : false;
$bulan_produksi_terkunci = isset($bulan_produksi_terkunci) ? $bulan_produksi_terkunci : '';
$bulan_produksi_terkunci_label = isset($bulan_produksi_terkunci_label) ? $bulan_produksi_terkunci_label : '';
$tgl_transaksi_awal = isset($tgl_transaksi_awal) ? $tgl_transaksi_awal : '';
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


        <!-- <div class="col-md-1"></div> -->
        <div class="col-md-12">
            <div class="box box-warning box-solid">


                <div class="card card-primary">
                    <div class="card-body">

                        <?php if ($this->session->flashdata('message')): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($this->session->flashdata('message'), ENT_QUOTES, 'UTF-8'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php endif; ?>

                        <div class="card card-info mb-3">
                            <div class="card-header">
                                <strong>Produk Baru (Tanpa Bahan)</strong>
                            </div>
                            <div class="card-body">
                                <form id="form-produk-baru" method="post" action="<?php echo htmlspecialchars($action_simpan_produk_form, ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="id_persediaan_barang" value="<?php echo isset($id_persediaan_barang) ? (int) $id_persediaan_barang : ''; ?>">
                                    <input type="hidden" name="uuid_barang" value="<?php echo isset($uuid_barang) ? htmlspecialchars($uuid_barang, ENT_QUOTES, 'UTF-8') : ''; ?>">
                                    <input type="hidden" name="bulan_produksi" value="<?php echo htmlspecialchars($bulan_produksi_selected, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php if ($mode_update_produksi && $bulan_produksi_terkunci !== '') { ?>
                                    <input type="hidden" name="bulan_produksi_terkunci" id="bulan_produksi_terkunci" value="<?php echo htmlspecialchars($bulan_produksi_terkunci, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php } ?>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="tgl_transaksi_produk">Tanggal Produksi</label>
                                            <div class="input-group date" id="tgl_transaksi_produk_wrap" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input field-produk-baru" data-target="#tgl_transaksi_produk_wrap" id="tgl_transaksi_produk" name="tgl_transaksi" value="<?php echo htmlspecialchars($tgl_transaksi_produk_X, ENT_QUOTES, 'UTF-8'); ?>" required>
                                                <div class="input-group-append" data-target="#tgl_transaksi_produk_wrap" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                            <?php if ($mode_update_produksi && $bulan_produksi_terkunci_label !== '') { ?>
                                            <small class="text-muted d-block mt-1">Hanya boleh diubah dalam bulan <strong><?php echo htmlspecialchars($bulan_produksi_terkunci_label, ENT_QUOTES, 'UTF-8'); ?></strong></small>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="uuid_unit_produk">Unit</label>
                                            <select name="uuid_unit" id="uuid_unit_produk" class="form-control select2 field-produk-baru" style="width:100%;" required>
                                                <option value="">Pilih Unit</option>
                                                <?php
                                                $sql_unit = 'SELECT * FROM sys_unit ORDER BY nama_unit ASC';
                                                foreach ($this->db->query($sql_unit)->result() as $m) {
                                                    $sel = (isset($uuid_unit) && $uuid_unit === $m->uuid_unit) ? ' selected' : '';
                                                    echo '<option value="' . htmlspecialchars($m->uuid_unit, ENT_QUOTES, 'UTF-8') . '"' . $sel . '>' . strtoupper($m->nama_unit) . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="jumlah_produksi_produk">Jumlah Produksi</label>
                                            <input class="form-control uang field-produk-baru" name="jumlah_produksi" id="jumlah_produksi_produk" value="<?php echo isset($jumlah_produksi) ? htmlspecialchars($jumlah_produksi, ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="nama_barang_produk">Nama Produk</label>
                                            <input class="form-control field-produk-baru" name="nama_barang" id="nama_barang_produk" value="<?php echo isset($nama_barang) ? htmlspecialchars($nama_barang, ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-2">
                                            <label for="satuan_produk">Satuan</label>
                                            <input class="form-control field-produk-baru" name="satuan" id="satuan_produk" value="<?php echo isset($satuan) ? htmlspecialchars($satuan, ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="harga_satuan_produk">Harga Satuan</label>
                                            <input class="form-control uang field-produk-baru" name="harga_satuan" id="harga_satuan_produk" value="<?php echo htmlspecialchars($harga_satuan_tampil, ENT_QUOTES, 'UTF-8'); ?>" required>
                                        </div>
                                        <div class="col-md-7">
                                            <label for="keterangan_produk">Keterangan <small class="text-muted">(opsional)</small></label>
                                            <input type="text" class="form-control field-produk-baru" name="keterangan" id="keterangan_produk" value="<?php echo isset($keterangan) ? htmlspecialchars($keterangan, ENT_QUOTES, 'UTF-8') : ''; ?>">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-md-8">
                                <small class="text-muted d-block" id="info-btn-produk-baru">
                                    Lengkapi tanggal, unit, jumlah produksi, nama produk, satuan, dan harga satuan untuk mengaktifkan tombol simpan. Keterangan bersifat opsional.
                                </small>
                            </div>
                            <div class="col-md-4 text-md-right mt-2 mt-md-0">
                                <button type="submit" form="form-produk-baru" class="btn btn-primary btn-lg" id="btn-simpan-produk-baru" disabled>
                                    <?php echo htmlspecialchars($label_btn_produk, ENT_QUOTES, 'UTF-8'); ?>
                                </button>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="row" align="center">
                                <div class="col-12">
                                    <a href="<?php echo site_url('Sys_unit_produk') . '?bulan=' . urlencode($bulan_produksi_selected); ?>" class="btn btn-success">Kembali ke data produk</a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>

            </div>
        </div>
        <!-- <div class="col-md-1"></div> -->
    </section>

</div>

<script>
window.addEventListener('load', function() {
    if (!window.jQuery) {
        console.error('Produksi tanpa bahan: jQuery belum dimuat.');
        return;
    }
    var $ = window.jQuery;
    var actionSimpanProdukForm = <?php echo json_encode($action_simpan_produk_form); ?>;
    var labelBtnProdukSimpan = <?php echo json_encode($label_btn_produk); ?>;
    var modeUpdateProduksi = <?php echo $mode_update_produksi ? 'true' : 'false'; ?>;
    var bulanProduksiTerkunci = <?php echo json_encode($bulan_produksi_terkunci); ?>;
    var bulanProduksiTerkunciLabel = <?php echo json_encode($bulan_produksi_terkunci_label); ?>;
    var tglTransaksiAwal = <?php echo json_encode($tgl_transaksi_awal); ?>;
    var tglTransaksiTerakhirValid = tglTransaksiAwal || ($('#tgl_transaksi_produk').val() || '');
    var sedangSimpan = false;

    function getNilaiFieldProduk($el) {
        if (!$el || !$el.length) {
            return '';
        }
        var val = $el.val();
        if (val === null || val === undefined) {
            return '';
        }
        val = String(val).trim();
        if ($el.hasClass('uang')) {
            return val.replace(/\./g, '');
        }
        return val;
    }

    function parseMomentTglProduk(val) {
        if (typeof moment === 'undefined') {
            return null;
        }
        val = String(val || '').trim();
        if (!val) {
            return null;
        }
        var m = moment(val, 'DD-MM-YYYY HH:mm:ss', true);
        if (!m.isValid()) {
            m = moment(val, 'DD-MM-YYYY', true);
        }
        return m.isValid() ? m : null;
    }

    function ambilBagianWaktuTglProduk(val) {
        var match = String(val || '').match(/(\d{2}:\d{2}:\d{2})/);
        return match ? match[1] : '00:00:00';
    }

    function tglProdukValid() {
        return !!parseMomentTglProduk($('#tgl_transaksi_produk').val());
    }

    function parseBulanYmDariTglProduk(tglStr) {
        tglStr = String(tglStr || '').trim();
        if (!tglStr) {
            return '';
        }
        var m = tglStr.match(/^(\d{2})-(\d{2})-(\d{4})/);
        if (m) {
            return m[3] + '-' + m[2];
        }
        var d = new Date(tglStr);
        if (!isNaN(d.getTime())) {
            var bulan = String(d.getMonth() + 1);
            if (bulan.length < 2) {
                bulan = '0' + bulan;
            }
            return d.getFullYear() + '-' + bulan;
        }
        return '';
    }

    function syncBulanDariTglProduk() {
        var bulanYm = parseBulanYmDariTglProduk($('#tgl_transaksi_produk').val() || '');
        if (bulanYm) {
            $('#form-produk-baru input[name="bulan_produksi"]').val(bulanYm);
        }
        return bulanYm;
    }

    function daftarFieldKosong() {
        var kosong = [];
        if (!tglProdukValid()) {
            kosong.push('Tanggal produksi');
        }
        if (getNilaiFieldProduk($('#uuid_unit_produk')) === '') {
            kosong.push('Unit');
        }
        var jml = getNilaiFieldProduk($('#jumlah_produksi_produk'));
        if (jml === '' || parseFloat(jml) <= 0) {
            kosong.push('Jumlah produksi');
        }
        if (getNilaiFieldProduk($('#nama_barang_produk')) === '') {
            kosong.push('Nama produk');
        }
        if (getNilaiFieldProduk($('#satuan_produk')) === '') {
            kosong.push('Satuan');
        }
        var harga = getNilaiFieldProduk($('#harga_satuan_produk'));
        if (harga === '' || parseFloat(harga) <= 0) {
            kosong.push('Harga satuan');
        }
        return kosong;
    }

    function fieldWajibTerisi() {
        return daftarFieldKosong().length === 0;
    }

    function tampilkanNotifikasiFieldKosong(kosong) {
        kosong = kosong || daftarFieldKosong();
        var pesan = 'Data belum lengkap. Harap isi: ' + kosong.join(', ') + '.';
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Belum Bisa Disimpan',
                text: pesan,
                confirmButtonText: 'OK'
            });
        } else {
            alert(pesan);
        }
    }

    function refreshTombolProdukBaru() {
        var siap = fieldWajibTerisi() && !!actionSimpanProdukForm && !sedangSimpan;
        $('#btn-simpan-produk-baru').prop('disabled', !siap).text(labelBtnProdukSimpan);
        var $info = $('#info-btn-produk-baru');
        var kosong = daftarFieldKosong();
        if (!actionSimpanProdukForm) {
            $info.text('URL simpan tidak tersedia.');
        } else if (kosong.length) {
            $info.text('Lengkapi: ' + kosong.join(', ') + '. Keterangan bersifat opsional.');
        } else {
            $info.text('Semua field wajib sudah terisi. Klik Simpan untuk menyimpan data produksi tanpa bahan.');
        }
    }

    function resetFieldSetelahSimpan() {
        $('#nama_barang_produk').val('');
        $('#jumlah_produksi_produk').val('').trigger('input');
        refreshTombolProdukBaru();
    }

    function tampilkanAlertBulanTglProdukTerkunci() {
        var label = bulanProduksiTerkunciLabel || bulanProduksiTerkunci;
        var pesan = 'Tanggal produksi tidak boleh diubah ke bulan/tahun berbeda';
        if (label) {
            pesan += ' (' + label + ')';
        }
        pesan += ' karena akan berdampak pada kesalahan data persediaan.';
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'warning', title: 'Tidak Diizinkan', text: pesan, confirmButtonText: 'OK' });
        } else {
            alert(pesan);
        }
    }

    function validasiBulanTglProdukUpdate(momentDate) {
        if (!modeUpdateProduksi || !bulanProduksiTerkunci) {
            return true;
        }
        if (!momentDate || typeof moment === 'undefined' || !moment.isMoment(momentDate) || !momentDate.isValid()) {
            return false;
        }
        return momentDate.format('YYYY-MM') === bulanProduksiTerkunci;
    }

    var syncingTglProdukPicker = false;

    function kembalikanTglProdukAwal() {
        var mAwal = parseMomentTglProduk(tglTransaksiTerakhirValid || tglTransaksiAwal || $('#tgl_transaksi_produk').val());
        if (!mAwal) {
            return;
        }
        syncingTglProdukPicker = true;
        var $wrap = $('#tgl_transaksi_produk_wrap');
        var $input = $('#tgl_transaksi_produk');
        var waktu = ambilBagianWaktuTglProduk(tglTransaksiTerakhirValid || tglTransaksiAwal || $input.val());
        var formatted = mAwal.format('DD-MM-YYYY') + ' ' + waktu;
        $input.val(formatted);
        if ($wrap.length && $.fn.datetimepicker) {
            $wrap.datetimepicker('date', mAwal);
            $wrap.datetimepicker('hide');
        }
        syncingTglProdukPicker = false;
    }

    function setTglProdukDariPicker(momentDate) {
        if (syncingTglProdukPicker) {
            return;
        }
        if (!momentDate || typeof moment === 'undefined' || !moment.isMoment(momentDate) || !momentDate.isValid()) {
            return;
        }
        if (!validasiBulanTglProdukUpdate(momentDate)) {
            tampilkanAlertBulanTglProdukTerkunci();
            kembalikanTglProdukAwal();
            return;
        }
        syncingTglProdukPicker = true;
        var $wrap = $('#tgl_transaksi_produk_wrap');
        var $input = $('#tgl_transaksi_produk');
        var waktu = ambilBagianWaktuTglProduk($input.val());
        var formatted = momentDate.format('DD-MM-YYYY') + ' ' + waktu;
        $input.val(formatted);
        tglTransaksiTerakhirValid = formatted;
        $wrap.datetimepicker('date', momentDate);
        $wrap.datetimepicker('hide');
        syncingTglProdukPicker = false;
        syncBulanDariTglProduk();
        refreshTombolProdukBaru();
    }

    if ($('#tgl_transaksi_produk_wrap').length && $.fn.datetimepicker) {
        var $tglProdukWrap = $('#tgl_transaksi_produk_wrap');
        var pickerOpts = {
            format: 'DD-MM-YYYY',
            useCurrent: false,
            allowInputToggle: true,
            icons: {
                time: 'far fa-clock',
                date: 'far fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'far fa-calendar-check',
                clear: 'far fa-trash-alt',
                close: 'fas fa-times'
            }
        };
        if (modeUpdateProduksi && bulanProduksiTerkunci && /^\d{4}-\d{2}$/.test(bulanProduksiTerkunci) && typeof moment !== 'undefined') {
            var minTgl = moment(bulanProduksiTerkunci + '-01', 'YYYY-MM-DD');
            pickerOpts.minDate = minTgl;
            pickerOpts.maxDate = moment(minTgl).endOf('month');
        }
        $tglProdukWrap.datetimepicker(pickerOpts);
        var tglProdukAwal = parseMomentTglProduk($('#tgl_transaksi_produk').val());
        if (tglProdukAwal) {
            $tglProdukWrap.datetimepicker('date', tglProdukAwal);
        }
        $tglProdukWrap.on('change.datetimepicker', function(e) {
            if (!syncingTglProdukPicker && e.date) {
                setTglProdukDariPicker(e.date);
            }
        });
    }

    $('#tgl_transaksi_produk').on('change blur', function() {
        var m = parseMomentTglProduk($(this).val());
        if (m && !validasiBulanTglProdukUpdate(m)) {
            tampilkanAlertBulanTglProdukTerkunci();
            kembalikanTglProdukAwal();
            return;
        }
        if (m && $('#tgl_transaksi_produk_wrap').length && $.fn.datetimepicker) {
            $('#tgl_transaksi_produk_wrap').datetimepicker('date', m);
        }
        tglTransaksiTerakhirValid = $(this).val();
        syncBulanDariTglProduk();
        refreshTombolProdukBaru();
    });

    if ($('#uuid_unit_produk').length && $.fn.select2) {
        $('#uuid_unit_produk').select2({ width: '100%' });
    }

    $(document).on('input change', '#form-produk-baru .field-produk-baru', function() {
        refreshTombolProdukBaru();
    });

    $('#form-produk-baru').on('submit', function(e) {
        e.preventDefault();
        var kosong = daftarFieldKosong();
        if (kosong.length) {
            tampilkanNotifikasiFieldKosong(kosong);
            return false;
        }
        if (!actionSimpanProdukForm) {
            tampilkanNotifikasiFieldKosong(['URL simpan']);
            return false;
        }
        if (modeUpdateProduksi && bulanProduksiTerkunci) {
            var mSubmit = parseMomentTglProduk($('#tgl_transaksi_produk').val());
            if (mSubmit && !validasiBulanTglProdukUpdate(mSubmit)) {
                tampilkanAlertBulanTglProdukTerkunci();
                kembalikanTglProdukAwal();
                return false;
            }
        }

        var $btn = $('#btn-simpan-produk-baru');
        sedangSimpan = true;
        $btn.prop('disabled', true).text('Menyimpan...');

        $.ajax({
            url: actionSimpanProdukForm,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function(res) {
            sedangSimpan = false;
            if (!res || !res.ok) {
                var msg = res && res.message ? res.message : 'Gagal menyimpan data.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: msg, confirmButtonText: 'OK' });
                } else {
                    alert(msg);
                }
                refreshTombolProdukBaru();
                return;
            }
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.message || 'Data berhasil tersimpan.',
                    confirmButtonText: 'OK'
                }).then(function() {
                    resetFieldSetelahSimpan();
                });
            } else {
                alert(res.message || 'Data berhasil tersimpan.');
                resetFieldSetelahSimpan();
            }
        }).fail(function() {
            sedangSimpan = false;
            var msg = 'Gagal menyimpan data. Periksa koneksi atau coba lagi.';
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Gagal', text: msg, confirmButtonText: 'OK' });
            } else {
                alert(msg);
            }
            refreshTombolProdukBaru();
        });

        return false;
    });

    syncBulanDariTglProduk();
    refreshTombolProdukBaru();
});
</script>
