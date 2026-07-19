<?php
if (empty($Tbl_penjualan_data) || !is_array($Tbl_penjualan_data)) {
    return;
}

$action_ubah = isset($action_ubah_per_id) ? $action_ubah_per_id : site_url('tbl_penjualan/create_action_nmrkirim_update_per_id_penjualan/');
$tgl_awal_hidden = isset($tgl_awal_param) ? $tgl_awal_param : '';
$tgl_akhir_hidden = isset($tgl_akhir_param) ? $tgl_akhir_param : '';
$field_rekap_hidden = isset($field_rekap) ? $field_rekap : 'unit';

foreach ($Tbl_penjualan_data as $list_data) {
    if (empty($list_data->id)) {
        continue;
    }

    $Get_stock_di_persediaan = penjualan_hitung_jumlah_maks_ubah_barang($this, $list_data);
    $harga_awal_angka = penjualan_parse_harga_satuan_input($list_data->harga_satuan);
    $harga_awal_tampil = number_format($list_data->harga_satuan, 2, ',', '.');
    ?>
    <form action="<?php echo $action_ubah . (int) $list_data->id; ?>" method="post" class="penjualan-form-ubah-barang">
        <input type="hidden" name="konfirmasi_ubah_harga" value="0">
        <input type="hidden" name="ajax" value="1">
        <div class="modal fade" id="modal-rekap-ubah-barang_<?php echo (int) $list_data->id; ?>">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update Barang <?php echo (int) $list_data->id; ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="redirect_rekap" value="1">
                        <input type="hidden" name="field_rekap" value="<?php echo htmlspecialchars($field_rekap_hidden, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="tgl_awal" value="<?php echo htmlspecialchars($tgl_awal_hidden, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="tgl_akhir" value="<?php echo htmlspecialchars($tgl_akhir_hidden, ENT_QUOTES, 'UTF-8'); ?>">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-12">
                                    <label for="nama_barang">Barang</label>
                                    <input type="text" class="form-control" name="nama_barang" value="<?php echo htmlspecialchars($list_data->nama_barang, ENT_QUOTES, 'UTF-8'); ?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label for="harga_satuan">Harga Satuan</label>
                                </div>
                                <div class="col-4">
                                    <label style="color:red" for="jumlah">Jumlah Maks= <?php echo (int) $Get_stock_di_persediaan; ?></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <input type="text"
                                        class="form-control input-harga-satuan-ubah"
                                        name="harga_satuan"
                                        value="<?php echo $harga_awal_tampil; ?>"
                                        data-harga-awal="<?php echo htmlspecialchars((string) $harga_awal_angka, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-harga-awal-tampil="<?php echo htmlspecialchars($harga_awal_tampil, ENT_QUOTES, 'UTF-8'); ?>"
                                        placeholder="<?php echo $harga_awal_tampil; ?>">
                                </div>
                                <div class="col-4">
                                    <input type="number" class="form-control input-jumlah-ubah" name="jumlah" value="<?php echo (int) $list_data->jumlah; ?>" min="1" max="<?php echo (int) $Get_stock_di_persediaan; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-simpan-ubah-barang">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php
}

$this->load->view('anekadharma/tbl_penjualan/_penjualan_form_ubah_barang_js');
